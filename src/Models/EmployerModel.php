<?php

namespace App\Models;

use App\Core\QueryBuilder;

class EmployerModel
{
    public static function getAllEmployers(): array
    {
        $qb = new QueryBuilder();
        $qb->select('employerId, employerName');
        $qb->table('employers');
        return $qb->execute();
    }
}