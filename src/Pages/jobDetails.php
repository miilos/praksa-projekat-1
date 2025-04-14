<?php

use App\Managers\ErrorManager;
use App\Managers\SessionManager;
use App\Models\JobModel;
use App\Models\JobApplicationModel;
use App\Controllers\FavouritesController;

require_once __DIR__ . '../../../vendor/autoload.php';

    // check if jobId exists in the url
    if (!$_GET['id']) {
        ErrorManager::redirectToErrorPage('bad-job-id');
    }
    $job = JobModel::getJobById($_GET['id']);

    // check if the user is logged in to know know whether to render a job application link or a login message
    $user = SessionManager::getSessionData('user');
    if (!$job) {
        ErrorManager::redirectToErrorPage('bad-job-id');
    }

    // check if the user already added this job to his favourites
    $isFavourite = false;
    if ($user) {
        $favourites = FavouritesController::getUsersFavourites($user['userId']);

        foreach ($favourites as $favourite) {
            if ($favourite['jobId'] === $job['jobId']) {
                $isFavourite = true;
            }
        }
    }

    // if the heart button was clicked, add this job to the user's favourites
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        FavouritesController::addFavourite([
                'userId' => $user['userId'],
                'jobId' => $job['jobId']
        ]);
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
    <link rel="stylesheet" href="../../style/style.css">
    <title><?php echo 'Oglas - ' . $job['jobName'] ?></title>
</head>
<body>
    <?php include_once '../Pages/_navbar.php' ?>

    <div class="job-header">
        <?php if ($user): ?>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $job['jobId'] ?>" method="post" class="favourites-form">
            <button type="submit" class="favourites-btn">
                <span class="material-symbols-outlined">
                <?= $isFavourite ? 'favorite' : 'heart_plus' ?>
                </span>
            </button>
        </form>
        <?php endif; ?>

        <h1 class="job-header-title"><?php echo $job['jobName'] ?></h1>
        <h3 class="job-header-employer"><?php echo $job['employerName'] ?></h3>
        <div class="job-header-info">
            <div>
                <span class="material-symbols-outlined">
                    location_on
                </span>
                <?php echo $job['location'] ?>
            </div>
            <div>
                <span class="material-symbols-outlined">
                    schedule
                </span>
                <?php echo date('j.n.Y.', strtotime($job['createdAt'])) ?>
            </div>
            <div>
                <span class="material-symbols-outlined">
                    update
                </span>
                <?php echo $job['shifts'] === 1 ? $job['shifts'] . ' smena' : $job['shifts'] . ' smene' ?>
            </div>
        </div>
        <div class="job-header-extra-info">
            <div class="job-details-detail job-details-detail--field">
                <?php echo $job['field'] ?>
            </div>
            <?php
                if ($job['workFromHome']) {
                    echo '
                        <div class="job-details-detail">
                            <span class="material-symbols-outlined">
                                home
                            </span>
                            Rad od kuce
                        </div>
                    ';
                }

                if ($job['flexibleHours']) {
                    echo '
                        <div class="job-details-detail">
                            <span class="material-symbols-outlined">
                                schedule
                            </span>
                            Klizno radno vreme
                        </div>
                    ';
                }
            ?>
        </div>
    </div>

    <div class="job-info">
        <div class="job-description">
            <h1 class="job-description-title">Opis posla:</h1>
            <p class="job-description-desc"><?php echo $job['description'] ?></p>
        </div>

        <div class="employer-info">
            <h1 class="employer-info-title">O poslodavcu:</h1>
            <p class="employer-info-desc"><?php echo $job['employerDescription'] ?></p>
            <p class="employer-info-based-in"><b>Baziran u: </b><?php echo $job['basedIn'] ?></p>
        </div>
    </div>

    <div class="job-application">
        <?php
            $applied = false;

            if ($user) {
                $jobsAppliedTo = JobApplicationModel::getJobsAppliedToByUser($user['userId'], true);

                foreach ($jobsAppliedTo as $jobApplied) {
                    if ($jobApplied['jobId'] === $job['jobId']) {
                        $applied = true;
                    }
                }
            }

            if ($applied) {
                echo '
                    <h1 class="job-application-title">Prijavili ste se na ovaj oglas ' . date('j.n.Y.', strtotime($jobApplied['submittedAt'])) . '</h1>
                ';
            }
            else {
                echo '
                    <h1 class="job-application-title">Prijavi se!</h1>
                    <a href="/praksa-projekat-1/src/Pages/jobApplication.php?jobId=' . $job['jobId'] . '" class="btn btn--secondary">Posalji prijavu</a>
                ';
            }
        ?>
    </div>
</body>
</html>
