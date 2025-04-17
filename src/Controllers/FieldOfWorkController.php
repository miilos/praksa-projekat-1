<?php

namespace App\Controllers;

use App\Models\FieldOfWorkModel;

class FieldOfWorkController
{
    public static function getAllFields(): array
    {
        return FieldOfWorkModel::getAllFields();
    }
}