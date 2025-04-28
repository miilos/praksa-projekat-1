<?php

namespace App\Controllers;

use App\Core\Request;
use App\Managers\EmailManager;
use App\Managers\SessionManager;
use App\Models\UserModel;
use App\Views\View;

class AuthController
{
    public function signup(Request $req): string
    {
        $data = $req->getBody();
        $validationRes = null;
        if ($data) {
            $validationRes = $this->signUserUp($data);
        }

        $errors = [];
        if (is_array($validationRes)) {
            $errors = $validationRes;
        }

        $view = new View();
        return $view->render('signup', [
            'pageTitle' => 'Sign up',
            'errors' => $errors,
        ]);
    }

    private function signUserUp(array $data): bool|array
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

            header('Location: /home');
            return true;
        }
        else {
            return $userModel->getValidationErrors();
        }
    }

    public function login(Request $req): string
    {
        $data = $req->getBody();
        $errors = [];

        if ($data && !$this->logUserIn($data)) {
            $errors['email'][] = 'Username ili email nisu tacni';
            $errors['password'][] = 'Username ili email nisu tacni';
        }

        $view = new View();
        return $view->render('login', [
            'pageTitle' => 'Home',
            'errors' => $errors
        ]);
    }

    private function logUserIn(array $data): bool
    {
        $user = UserModel::getUserByEmail($data['email']);

        if (
            !$user ||
            !$this->checkPassword($data['password'], $user['password'])
        ) {
            return false;
        }

        SessionManager::startSession('user', $user);
        header("Location: /home");
        return true;
    }

    private function checkPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}