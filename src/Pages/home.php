<?php

use App\Controllers\JobController;
use App\Controllers\SessionController;

require_once __DIR__ . '/../../vendor/autoload.php';

$session = new SessionController();
$user = $session->getSessionData('user');
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style/style.css">
    <title>Home</title>
</head>
<body>
    <?php include_once "./_navbar.php" ?>

    <h1 class="user-hello-msg">hello, <?php echo $user['firstName'] ?></h1>

    <?php
        $jobController = new JobController();
        $jobController->getJobs("Oglasi za vas", [ 'field' => $user['field'] ]);
    ?>
</body>
</html>
