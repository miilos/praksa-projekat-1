<?php

use App\Controllers\JobController;

require_once __DIR__ . '/vendor/autoload.php';

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="./style/style.css">
    <title>Welcome</title>
</head>
<body>
    <?php
        include_once './src/Pages/_navbar.php';

        $jobController = new JobController();
        $jobController->getAllJobs('Svi poslovi');
    ?>
</body>
</html>