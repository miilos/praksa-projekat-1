<?php

namespace App\Models;

use App\Core\QueryBuilder;

class LocationModel
{
    public static function getAllLocations(): array
    {
        $qb = new QueryBuilder();
        $qb->select('city');
        $qb->table('locations');
        return $qb->execute(fetchMode: \PDO::FETCH_COLUMN);
    }
}