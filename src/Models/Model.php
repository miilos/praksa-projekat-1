<?php

namespace App\Models;

abstract class Model
{
    protected $errors = [];

    public function getValidationErrors(): array
    {
        return $this->errors;
    }
}