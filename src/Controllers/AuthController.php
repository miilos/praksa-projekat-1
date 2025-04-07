<?php

namespace App\Controllers;

use App\Core\Db;
use Ramsey\Uuid\Uuid;

class AuthController
{
    public function signup($data): void
    {
        $user = $this->createUser($data);

        $session = new SessionController();
        $session->startSession('user', $user);
        header('Location: ../Pages/home.php');
    }

    private function createUser($data): array
    {
        $dbh = (new Db())->getHandler();
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
//            if(str_contains($e->getMessage(), 'Duplicate entry')) {
//                ErrorController::redirectToErrorPage('email-taken');
//            }
//            else {
            ErrorController::redirectToErrorPage('db-error');
//            }
        }

        return [];
    }

    public function logIn($data): bool
    {
        try {
            $dbh = (new Db())->getHandler();

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

            $session = new SessionController();
            $session->startSession('user', $user);
            header("Location: ../Pages/home.php");

            return true;
        }
        catch (\PDOException $e) {
            ErrorController::redirectToErrorPage('db-error');
        }

        return false;
    }

    private function checkPassword($password, $hash): bool
    {
        return password_verify($password, $hash);
    }
}