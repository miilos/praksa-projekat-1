<?php

namespace App\Models;
use App\Core\Db;
use App\Managers\ErrorManager;
use Ramsey\Uuid\Uuid;

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

    public function getValidationErrors(): array
    {
        return $this->errors;
    }

    private function getEmailsInDB(): array
    {
        try {
            $dbh = (new Db())->getConnection();

            $stmt = $dbh->prepare('SELECT email FROM users');
            $stmt->execute();

            $emails = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
            return $emails;
        }
        catch (\PDOException $e) {
            $msg = ErrorManager::getErrors()['db-error'];
            ErrorManager::redirectToErrorPage($msg);
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
        }

        return [];
    }

    public function createUser(): array
    {
        $dbh = (new Db())->getConnection();
        $uuid = Uuid::uuid4();
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        try {
            $query = "INSERT INTO users(userId, firstname, lastname, email, password, field) VALUES (:id, :firstName, :lastName, :email, :password, :field)";

            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':firstName', $this->firstName);
            $stmt->bindParam(':lastName', $this->lastName);
            $stmt->bindParam(':email',$this->email);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':field', $this->field);

            $stmt->execute();

            // builds an array that looks like what fetching the new user from the database would look like,
            // so that there's no need for another query to add user data to the session variable
            unset($this->passwordConfirm);
            return [
                'userId' => $uuid,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'email' => $this->email,
                'password' => $this->password,
                'field' => $this->field
            ];
        }
        catch (\PDOException $e) {
            ErrorManager::redirectToErrorPage('db-error');
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
        }

        return [];
    }

    public static function getUserByEmail(string $email): array|bool
    {
        try {
            $dbh = (new Db())->getConnection();

            $query = "SELECT * FROM users WHERE email=:email";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':email', $email);

            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
        catch (\PDOException $e) {
            ErrorManager::redirectToErrorPage('db-error');
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
        }

        return [];
    }
}