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

    // check if the user is logged in to know whether to render a job application link or a login message
    $user = SessionManager::getSessionData('user');

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
            <button class="favourites-btn" data-user="<?= $user['userId'] ?>" data-job="<?= $job['jobId'] ?>">
                <span class="material-symbols-outlined">
                    <?= $isFavourite ? 'favorite' : 'heart_plus' ?>
                </span>
            </button>
        <?php endif; ?>

        <h1 class="job-header-title"><?= $job['jobName'] ?></h1>
        <h3 class="job-header-employer"><?= $job['employerName'] ?></h3>
        <div class="job-header-info">
            <div>
                <span class="material-symbols-outlined">
                    location_on
                </span>
                <?= $job['location'] ?>
            </div>
            <div>
                <span class="material-symbols-outlined">
                    schedule
                </span>
                <?= date('j.n.Y.', strtotime($job['createdAt'])) ?>
            </div>
            <div>
                <span class="material-symbols-outlined">
                    update
                </span>
                <?= $job['shifts'] === 1 ? $job['shifts'] . ' smena' : $job['shifts'] . ' smene' ?>
            </div>
        </div>
        <div class="job-header-extra-info">
            <div class="job-details-detail job-details-detail--field">
                <?= $job['field'] ?>
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
            <p class="job-description-desc"><?= $job['description'] ?></p>
        </div>

        <div class="employer-info">
            <h1 class="employer-info-title">O poslodavcu:</h1>
            <p class="employer-info-desc"><?= $job['employerDescription'] ?></p>
            <p class="employer-info-based-in"><b>Baziran u: </b><?= $job['basedIn'] ?></p>
        </div>

        <div class="job-comments">
            <h1 class="job-comments-title">Komentari:</h1>
            <a href="./createComment.php?jobId=<?= $job['jobId'] ?>" class="btn btn--primary">Ostavite komentar</a>
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

    <script src="./scripts/addFavourites.js"></script>
</body>
</html>
