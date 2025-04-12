<?php

namespace App\Models;
use App\Core\QueryBuilder;
use Ramsey\Uuid\Uuid;

class UserModel extends Model
{
    private const int PASS_LENGTH = 8;

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

    private function getEmailsInDB(): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('email');
        $qb->table('users');
        $qb->build();
        $emails = $qb->execute(fetchMode: \PDO::FETCH_COLUMN);
        $qb->close();
        return $emails;
    }

    public function createUser(): array
    {
        $userId = Uuid::uuid4();
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $qb = new QueryBuilder();
        $qb->operation('INSERT');
        $qb->table('users');
        $qb->fields('userId', 'firstName', 'lastName', 'email', 'password', 'field');
        $qb->values([
            'userId' => $userId,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
            'field' => $this->field
        ]);
        $qb->build();
        $qb->execute();
        $qb->close();

        // builds an array that looks like what fetching the new user from the database would look like,
        // so that there's no need for another query to add user data to the session variable
        unset($this->passwordConfirm);
        return [
            'userId' => $userId,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
            'field' => $this->field,
            'role' => 'user'
        ];
    }

    // return array with user if user is found, bool if there's no user because execute() uses fetch()
    public static function getUserByEmail(string $email): array|bool
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('*');
        $qb->table('users');
        $qb->where(['email' => $email]);
        $qb->build();
        $user = $qb->execute('one');
        $qb->close();
        return $user;
    }
}