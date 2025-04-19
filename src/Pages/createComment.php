<?php

use App\Managers\ErrorManager;
use App\Managers\SessionManager;

require_once __DIR__ . '../../../vendor/autoload.php';

    if (!$_GET['jobId']) {
        ErrorManager::redirectToErrorPage('bad-job-id');
    }

    $user = SessionManager::getSessionData('user');
    if (!$user) {
        header('Location: ./login.php');
        exit();
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ostavite komentar</title>
</head>
<body>
    <?php include_once './_navbar.php' ?>

    <h1>Ostavite komentar za </h1>
</body>
</html>
