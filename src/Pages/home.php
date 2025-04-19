<?php

use App\Controllers\JobApplicationController;
use App\Controllers\JobController;
use App\Managers\SessionManager;
use App\Controllers\FavouritesController;
use App\Pages\JobRenderer;

require_once __DIR__ . '/../../vendor/autoload.php';

$user = SessionManager::getSessionData('user');

if (!$user) {
    header('Location: ./login.php');
    exit();
}

$favourites = FavouritesController::getUsersFavourites($user['userId']);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <title>Home</title>
</head>
<body>
    <?php include_once "./_navbar.php" ?>

    <h1 class="user-hello-msg">hello, <?php echo $user['firstName'] ?></h1>

    <?php
        $jobController = new JobController();
        $jobController->getJobs("Oglasi za vas", [ 'field' => $user['field'] ], $favourites);

        $jobRenderer = new JobRenderer();

        $jobsAppliedTo = JobApplicationController::getApplicationsByUser($user['userId']);
        echo $jobRenderer->renderJobs('Vase prijave', $jobsAppliedTo, $favourites);

        $favouriteJobs = FavouritesController::getFullUserFavouritesData($user['userId']);
        echo $jobRenderer->renderJobs('Vasi omiljeni oglasi', $favouriteJobs, $favourites);
    ?>
</body>
</html>
