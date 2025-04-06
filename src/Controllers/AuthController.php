<?php

namespace App\Controllers;

use App\Core\Db;
use Ramsey\Uuid\Uuid;

class AuthController
{
    public function signup($data): void
    {
        $this->createUser($data);
    }

    private function createUser($data): void
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

            echo "signed up!";
        }
        catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
    }

    public function logIn($data): void
    {
        $dbh = (new Db())->getHandler();

        $query = "SELECT * FROM users WHERE email=:email";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':email', $data['email']);

        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(
            !$user ||
            !$this->checkPassword($data['password'], $user['password'])
        ) {
            echo 'incorrect email or password!';
        }
        else {
            echo 'logged in!';
        }
    }

    private function checkPassword($password, $hash): bool
    {
        return password_verify($password, $hash);
    }
}