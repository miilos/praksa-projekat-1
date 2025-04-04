<?php

namespace App\Controllers;

use App\Core\Db;
use App\Core\Request;
use Ramsey\Uuid\Uuid;

class AuthController
{
    public function signup()
    {
        $request = new Request();
        $data = $request->getBody();

        $this->createUser($data);
    }

    private function createUser($data)
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
}