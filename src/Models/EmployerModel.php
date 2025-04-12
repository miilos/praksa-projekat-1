<?php

namespace App\Models;

use App\Core\QueryBuilder;

class EmployerModel
{
    public static function getAllEmployers(): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('employerId', 'employerName');
        $qb->table('employers');
        $qb->build();
        $employers = $qb->execute();
        $qb->close();
        return $employers;
    }
}