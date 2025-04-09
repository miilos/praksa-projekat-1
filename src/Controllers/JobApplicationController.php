<?php

namespace App\Controllers;

use App\Managers\ErrorManager;
use App\Managers\SuccessManager;
use App\Models\JobApplicationModel;
use App\Pages\JobRenderer;

class JobApplicationController
{
    public static function apply(array $data): void
    {
        $applicationSuccess = JobApplicationModel::createJobApplication($data);

        if ($applicationSuccess) {
            SuccessManager::redirectToSuccessPage('sent-application');
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