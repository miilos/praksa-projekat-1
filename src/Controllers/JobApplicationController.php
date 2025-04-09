<?php

namespace App\Controllers;

use App\Managers\ErrorManager;
use App\Models\JobApplicationModel;
use App\Pages\JobRenderer;

class JobApplicationController
{
    public static function apply(array $data): void
    {
        $applicationSuccess = JobApplicationModel::createJobApplication($data);

        if ($applicationSuccess) {
            header('Location: /praksa-projekat-1/src/Pages/success.php');
        }
        else {
            ErrorManager::redirectToErrorPage('failed-application');
        }
    }

    public static function getApplicationsByUser($userId): void
    {
        $jobsAppliedTo = JobApplicationModel::getJobsAppliedToByUser($userId);

        if($jobsAppliedTo) {
            $jobRenderer = new JobRenderer();
            echo $jobRenderer->renderJobs('Vase prijave', $jobsAppliedTo);
        }
    }
}