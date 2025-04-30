<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Route;
use App\Managers\EmailManager;
use App\Managers\SessionManager;
use App\Models\JobApplicationModel;
use App\Views\View;

class JobApplicationController
{
    #[Route(method: 'get', path: '/apply/{id}', name: 'applyGet')]
    #[Route(method: 'post', path: '/apply/{id}', name: 'applyPost')]
    public function apply(Request $req, Response $res): string
    {
        $body = $req->getBody();
        $user = SessionManager::getSessionData('user');
        $jobId = $req->getUrlParams()['id'];

        if (!$user) {
            header("location: /login");
            exit();
        }

        if (!$jobId) {
            ErrorController::redirectToErrorPage('bad-job-id');
        }

        if ($body) {
            $data = [
                'userId' => $body['userId'],
                'jobId' =>  $body['jobId'],
            ];

            $emailData = [
                'email' => $body['email'],
                'name' => $body['firstName']
            ];

            $this->createApplication($data, $emailData);
        }

        $view = new View();
        return $view->render('applyToJob', [
            'pageTitle' => 'Prijavite se za posao',
            'user' => $user,
            'jobId' => $jobId
        ]);
    }

    private function createApplication(array $data, array $emailData): void
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

            SuccessController::redirectToSuccessPage('sent-application');
        }
        else {
            ErrorController::redirectToErrorPage('failed-application');
        }
    }

    public static function getApplicationsByUser($userId): array
    {
        return JobApplicationModel::getJobsAppliedToByUser($userId);
    }

    public static function checkIfUserApplied($userId, $jobId): array|bool
    {
        return JobApplicationModel::userAppliedToJob($userId, $jobId);
    }
}