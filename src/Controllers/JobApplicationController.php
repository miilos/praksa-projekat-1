<?php

namespace App\Controllers;

use App\Managers\EmailManager;
use App\Managers\ErrorManager;
use App\Managers\SuccessManager;
use App\Models\JobApplicationModel;
use App\Pages\JobRenderer;

class JobApplicationController
{
    public static function apply(array $data, array $emailData): void
    {
        $applicationSuccess = JobApplicationModel::createJobApplication($data);

        if ($applicationSuccess) {
            $mailManager = new EmailManager();
            $mailManager->sendMail(
                $emailData['email'],
                $emailData['name'],
                'Uspesna prijava!',
                'Vasa prijava za posao je uspesno poslata.'
            );

            SuccessManager::redirectToSuccessPage('sent-application');
        }
        else {
            ErrorManager::redirectToErrorPage('failed-application');
        }
    }

    public static function getApplicationsByUser($userId): array
    {
        return JobApplicationModel::getJobsAppliedToByUser($userId);
    }
}