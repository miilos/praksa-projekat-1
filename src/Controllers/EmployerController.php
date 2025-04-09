<?php

namespace App\Controllers;

use App\Models\EmployerModel;

class EmployerController
{
    public static function getAllEmployers(): array
    {
        return EmployerModel::getAllEmployers();
    }
}