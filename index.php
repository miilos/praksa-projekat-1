<?php

use App\Controllers\JobController;
use App\Core\Request;
use App\Managers\SessionManager;
use App\Controllers\FavouritesController;

require_once __DIR__ . '/vendor/autoload.php';

$user = SessionManager::getSessionData('user');

$favourites = [];
if ($user) {
    $favourites = FavouritesController::getUsersFavourites($user['userId']);
}

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

        <input type="text" id="jobName" name="jobName" class="input" placeholder="Naziv oglasa" value="<?php echo ($_POST['jobName'] ?? '') ?>" >
        <input type="text" id="location" name="location" class="input" placeholder="Lokacija" value="<?php echo ($_POST['location'] ?? '') ?>">

        <input type="checkbox" id="flexibleHours" name="flexibleHours" <?php echo (isset($_POST['flexibleHours']) ? 'checked' : '') ?>>
        <label for="flexibleHours">Klizno radno vreme</label>

        <input type="checkbox" id="workFromHome" name="workFromHome" <?php echo (isset($_POST['workFromHome']) ? 'checked' : '') ?>>
        <label for="workFromHome">Rad od kuce</label>

        <input type="submit" id="submit" name="submit-filters" class="form-btn" value="Primeni filtere">
    </form>

    <?php
        $jobController = new JobController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new Request();
            $body = $request->getBody();
            unset($body['submit']);

            $jobController->getFilteredJobs('Filtrirani oglasi', $body, $favourites);
        }
        else {
            $jobController->getJobs('Svi oglasi', favourites: $favourites);
        }
    ?>
</body>
</html>