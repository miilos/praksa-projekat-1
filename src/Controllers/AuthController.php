<?php

namespace App\Controllers;

use App\Core\Db;
use App\Managers\ErrorManager;
use App\Managers\SessionManager;
use Ramsey\Uuid\Uuid;

class AuthController
{
    public function signup(array $data): void
    {
        $user = $this->createUser($data);
        SessionManager::startSession('user', $user);
        header('Location: ../Pages/home.php');
    }

    private function createUser(array $data): array
    {
        $dbh = (new Db())->getConnection();
        $uuid = Uuid::uuid4();
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        try {
            $query = "INSERT INTO users(userId, firstname, lastname, email, password, field) VALUES (:id, :firstName, :lastName, :email, :password, :field)";

            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':firstName', $data['firstName']);
            $stmt->bindParam(':lastName', $data['lastName']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $data['password']);
            $stmt->bindParam(':field', $data['field']);

            $stmt->execute();

            // builds an array that looks like what fetching the new user from the database would look like,
            // so that there's no need for another query to add user data to the session variable
            unset($data['passwordConfirm']);
            return ['userId' => $uuid, ...$data];
        }
        catch (\PDOException $e) {
            ErrorManager::redirectToErrorPage('db-error');
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
        }

        return [];
    }

    public function logIn(array $data): bool
    {
        try {
            $dbh = (new Db())->getConnection();

            $query = "SELECT * FROM users WHERE email=:email";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':email', $data['email']);

            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (
                !$user ||
                !$this->checkPassword($data['password'], $user['password'])
            ) {
                return false;
            }

            SessionManager::startSession('user', $user);
            header("Location: ../Pages/home.php");

            return true;
        }
        catch (\PDOException $e) {
            ErrorManager::redirectToErrorPage('db-error');
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
        }

        return false;
    }

    private function checkPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}