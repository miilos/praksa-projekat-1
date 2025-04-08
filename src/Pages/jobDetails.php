<?php

use App\Managers\ErrorManager;
use App\Models\JobModel;

require_once __DIR__ . '../../../vendor/autoload.php';

    $jobModel = new JobModel();
    $job = $jobModel->getJobById($_GET['id']);

    if (!$job) {
        ErrorManager::redirectToErrorPage('bad-job-id');
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
        <h1 class="job-application-title">Prijavi se!</h1>
        <a href="/praksa-projekat-1/src/Pages/jobApplication.php?jobId=<?php echo $job['jobId']?>" class="btn btn--secondary">Posalji prijavu</a>
    </div>
</body>
</html>
