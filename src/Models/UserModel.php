<?php

namespace App\Models;
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
        if(!$this->firstName) {
            $this->errors['firstName'][] = "Morate uneti ime";
        }

        if(!$this->lastName) {
            $this->errors['lastName'][] = "Morate uneti prezime";
        }

        if(!$this->password) {
            $this->errors['password'][] = "Morate uneti password";
        }

        if(strlen($this->password) < self::PASS_LENGTH) {
            $this->errors['password'][] = "Lozinka mora biti najmanje " . self::PASS_LENGTH . " karaktera";
        }

        if($this->password !== $this->passwordConfirm) {
            $this->errors['passwordConfirm'][] = "Password i confirm password nisu isti";
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'][] = "Niste uneli validnu email adresu";
        }

        if($this->field === '-') {
            $this->errors['field'][] = "Morate izabrati jedno polje rada";
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}