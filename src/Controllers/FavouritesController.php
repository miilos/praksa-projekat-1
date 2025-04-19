<?php

namespace App\Controllers;

use App\Models\FavouritesModel;

class FavouritesController
{
    public static function addFavourite(array $data): bool
    {
        $removeId = null;
        $favourites = FavouritesModel::getAllFavourites();

        // if a user already set a job posting as favourite and clicked the favourite button again, remove it from favourites
        foreach ($favourites as $favourite) {
            if (
                ($favourite['userId'] === $data['userId']) &&
                ($favourite['jobId'] === $data['jobId'])
            ) {
                $removeId = $favourite['favouriteId'];
            }
        }

        if (!$removeId) {
            return FavouritesModel::addFavourite($data);
        }
        else {
            return FavouritesModel::removeFavourite($removeId);
        }
    }

    public static function getUsersFavourites(string $userId): array
    {
        return FavouritesModel::getUsersFavourites($userId);
    }

    public static function getFullUserFavouritesData(string $userId): array
    {
        return FavouritesModel::getFullUserFavouritesData($userId);
    }
}