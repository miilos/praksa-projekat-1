<?php

namespace App\Controllers;

use App\Managers\EmailManager;
use App\Managers\ErrorManager;
use App\Managers\SessionManager;
use App\Models\UserModel;

class AuthController
{
    public function signup(array $data): bool|array
    {
        $userModel = new UserModel(
            $data['firstName'],
            $data['lastName'],
            $data['password'],
            $data['passwordConfirm'],
            $data['email'],
            $data['field']
        );

        if($userModel->validate()) {
            $user = $userModel->createUser();
            SessionManager::startSession('user', $user);

            $mailManager = new EmailManager();
            $mailManager->sendMail(
                $data['email'],
                $data['firstName'],
                'Dobrodosli!',
                'Vas nalog je uspesno kreiran. Hvala sto koristite nasu platformu!'
            );

            header('Location: ../Pages/home.php');
            return true;
        }
        else {
            return $userModel->getValidationErrors();
        }
    }

    public function logIn(array $data): bool
    {
        $user = UserModel::getUserByEmail($data['email']);

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

    private function checkPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}