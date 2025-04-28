<?php

use App\Controllers\FavouritesController;

require_once __DIR__ . '../../../vendor/autoload.php';

$data = json_decode(file_get_contents('php://input'), true);

echo FavouritesController::addFavourite([
    'userId' => $data['userId'],
    'jobId' => $data['jobId'],
]);