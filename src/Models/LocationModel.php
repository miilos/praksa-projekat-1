<?php

namespace App\Models;

use App\Core\QueryBuilder;

class LocationModel
{
    public static function getAllLocations(): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('city');
        $qb->table('locations');
        $qb->build();
        $locations = $qb->execute(fetchMode: \PDO::FETCH_COLUMN);
        $qb->close();
        return $locations;
    }
}