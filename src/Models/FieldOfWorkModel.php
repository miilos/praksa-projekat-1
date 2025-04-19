<?php

namespace App\Models;

use App\Core\QueryBuilder;

class FieldOfWorkModel
{
    public static function getAllFields(): array
    {
        $qb = new QueryBuilder();
        $qb->select('name');
        $qb->table('job_fields');
        return $qb->execute(fetchMode: \PDO::FETCH_COLUMN);
    }
}