<?php

use App\Controllers\JobController;
use App\Core\Request;

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
        ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="form search" autocomplete="off">
        <h1 class="search-title">Filteri za pretragu</h1>

        <input type="text" id="name" name="name" class="input" placeholder="Naziv oglasa">
        <input type="text" id="location" name="location" class="input" placeholder="Lokacija">

        <input type="checkbox" id="flexibleHours" name="flexibleHours">
        <label for="flexibleHours">Klizno radno vreme</label>

        <input type="checkbox" id="workFromHome" name="workFromHome">
        <label for="flexibleHours">Rad od kuce</label>

        <input type="submit" id="submit" name="submit" class="form-btn" value="Primeni filtere">
    </form>

    <?php
        $jobController = new JobController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new Request();
            $body = $request->getBody();
            unset($body['submit']);

            $jobController->getFilteredJobs('Filtrirani oglasi', $body);
        }
        else {
            $jobController->getJobs('Svi oglasi');
        }
    ?>
</body>
</html>