<?php

use App\Managers\SessionManager;
use App\Managers\ErrorManager;
use App\Controllers\JobController;

require_once __DIR__ . '/../../vendor/autoload.php';

$user = SessionManager::getSessionData('user');

if (!$user || $user['role'] !== 'admin') {
    ErrorManager::redirectToErrorPage('not-authorized');
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style/style.css">
    <title>Izmenite oglas</title>
</head>
<body>
    <?php
        include './_navbar.php';

        $jobController = new JobController();
        $jobController->getJobsAdmin('Update', 'updateJob.php');
    ?>
</body>
</html>
