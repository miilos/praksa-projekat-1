<?php

namespace App\Models;

use App\Core\QueryBuilder;
use Ramsey\Uuid\Uuid;

class FavouritesModel
{
    public static function addFavourite(array $data): bool
    {
        $favouriteId = Uuid::uuid4();
        $qb = new QueryBuilder();
        $qb->operation('INSERT');
        $qb->table('favourites');
        $qb->fields('favouriteId', 'userId', 'jobId');
        $qb->values([
            'favouriteId' => $favouriteId,
            'userId' => $data['userId'],
            'jobId' => $data['jobId']
        ]);
        $qb->build();
        $status = $qb->execute();
        $qb->close();
        return $status;
    }

    public static function getAllFavourites(): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('*');
        $qb->table('favourites');
        $qb->build();
        $favourites = $qb->execute();
        $qb->close();
        return $favourites;
    }

    public static function getUsersFavourites(string $userId): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('jobId');
        $qb->table('favourites');
        $qb->where(['userId' => $userId]);
        $qb->build();
        $favourites = $qb->execute();
        $qb->close();
        return $favourites;
    }

    public static function getFullUserFavouritesData(string $userId): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('*');
        $qb->table('favourites');
        $qb->join('INNER JOIN', 'jobs', 'jobId', 'jobId');
        $qb->join('INNER JOIN', 'employers', 'employerId', 'employerId');
        $qb->where(['userId' => $userId]);
        $qb->build();
        $favourites = $qb->execute();
        $qb->close();
        return $favourites;
    }

    public static function removeFavourite(string $favouriteId): bool
    {
        $qb = new QueryBuilder();
        $qb->operation('DELETE');
        $qb->table('favourites');
        $qb->where(['favouriteId' => $favouriteId]);
        $qb->build();
        $status = $qb->execute();
        $qb->close();
        return $status;
    }
}