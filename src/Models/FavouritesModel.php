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
        $qb->insert();
        $qb->table('favourites');
        $qb->fields('favouriteId', 'userId', 'jobId');
        $qb->values([
            'favouriteId' => $favouriteId,
            'userId' => $data['userId'],
            'jobId' => $data['jobId']
        ]);
        return $qb->execute();
    }

    public static function getAllFavourites(): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('favourites');
        return $qb->execute();
    }

    public static function getUsersFavourites(string $userId): array
    {
        $qb = new QueryBuilder();
        $qb->select('jobId');
        $qb->table('favourites');
        $qb->where(['userId' => $userId]);
        return $qb->execute();
    }

    public static function getFullUserFavouritesData(string $userId): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('favourites');
        $qb->join('INNER JOIN', 'jobs', 'jobId', 'jobId');
        $qb->join('INNER JOIN', 'employers', 'employerId', 'employerId');
        $qb->where(['userId' => $userId]);
        return $qb->execute();
    }

    public static function checkIfFavourite(string $userId, string $jobId): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('favourites');
        $qb->where(['userId' => $userId]);
        $qb->where(['jobId' => $jobId]);
        return $qb->execute();
    }

    public static function removeFavourite(string $favouriteId): bool
    {
        $qb = new QueryBuilder();
        $qb->delete();
        $qb->table('favourites');
        $qb->where(['favouriteId' => $favouriteId]);
        return $qb->execute();
    }

    public static function deleteJobFromFavourites(string $jobId): bool
    {
        $qb = new QueryBuilder();
        $qb->delete();
        $qb->table('favourites');
        $qb->where(['jobId' => $jobId]);
        return $qb->execute();
    }
}