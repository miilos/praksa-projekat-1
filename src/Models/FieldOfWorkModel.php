<?php

namespace App\Models;

use App\Core\QueryBuilder;

class FieldOfWorkModel
{
    public static function getAllFields(): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('name');
        $qb->table('job_fields');
        $qb->build();
        $fields = $qb->execute(fetchMode: \PDO::FETCH_COLUMN);
        $qb->close();
        return $fields;
    }
}