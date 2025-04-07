<?php

namespace App\Models;
use App\Controllers\ErrorController;
use App\Core\Db;

class UserModel
{
    private $errors = [];
    private const PASS_LENGTH = 8;

    public function __construct(
        protected string $firstName,
        protected string $lastName,
        protected string $password,
        protected string $passwordConfirm,
        protected string $email,
        protected string $field
    ) {}

    public function validate(): bool
    {
        if (!$this->firstName) {
            $this->errors['firstName'][] = "Morate uneti ime";
        }

        if (!$this->lastName) {
            $this->errors['lastName'][] = "Morate uneti prezime";
        }

        if (!$this->password) {
            $this->errors['password'][] = "Morate uneti password";
        }

        if (strlen($this->password) < self::PASS_LENGTH) {
            $this->errors['password'][] = "Lozinka mora biti najmanje " . self::PASS_LENGTH . " karaktera";
        }

        if ($this->password !== $this->passwordConfirm) {
            $this->errors['passwordConfirm'][] = "Password i confirm password nisu isti";
        }

        if (!$this->email) {
            $this->errors['email'][] = "Morate uneti email";
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'][] = "Niste uneli validnu email adresu";
        }

        if (in_array($this->email, $this->getEmailsInDB())) {
            $this->errors['email'][] = "Ova email adresa je vec zauzeta";
        }

        if ($this->field === '-') {
            $this->errors['field'][] = "Morate izabrati jedno polje rada";
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function getEmailsInDB(): array
    {
        try {
            $dbh = (new Db())->getHandler();

            $stmt = $dbh->prepare('SELECT email FROM users');
            $stmt->execute();

            $emails = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
            return $emails;
        }
        catch (\PDOException $e) {
            $msg = ErrorController::getErrors()['db-error'];
            ErrorController::redirectToErrorPage($msg);
        }

        return [];
    }
}