<?php

namespace App\Controllers;

use App\Core\Request;
use App\Managers\SessionManager;
use App\Models\FavouritesModel;
use App\Models\JobModel;
use App\Views\JobRenderer;
use App\Views\View;

class JobController
{
    public function index(Request $req): string
    {
        $user = SessionManager::getSessionData('user');

        $favourites = [];
        if ($user) {
            $favourites = FavouritesController::getUsersFavourites($user['userId']);
        }

        $body = $req->getBody();

        $jobs = [];
        $title = '';
        if ($body) {
            unset($body['submit']);
            $jobs = JobModel::filterJobs($body);
            $title = 'Filtrirani oglasi';
        } else {
            $jobs = JobModel::getJobs();
            $title = 'Svi oglasi';
        }

        $view = new View();
        return $view->render('index', [
            'pageTitle' => 'Welcome',
            'title' => $title,
            'jobs' => $jobs,
            'favourites' => $favourites,
            'jobRenderer' => new JobRenderer(),
        ]);
    }

    public function jobDetails(Request $req): string
    {
        $jobId = $req->getUrlParams()['id'];

        if (!$jobId) {
            ErrorController::redirectToErrorPage('bad-job-id');
        }

        $job = JobModel::getJobById($jobId);
        $comments = CommentController::getCommentsForJob($jobId);
        $user = SessionManager::getSessionData('user');
        $isFavourite = null;
        $userApplication = null;
        if ($user) {
            $isFavourite = FavouritesModel::checkIfFavourite($user['userId'], $jobId);
            $userApplication = JobApplicationController::checkIfUserApplied($user['userId'], $jobId);
        }

        $view = new View();
        return $view->render('job', [
            'pageTitle' => 'Oglas | ' . $job['jobName'],
            'job' => $job,
            'user' => $user,
            'isFavourite' => $isFavourite,
            'userApplication' => $userApplication,
            'comments' => $comments,
        ]);
    }

    public function home(Request $req): string
    {
        $user = SessionManager::getSessionData('user');

        if (!$user) {
            header('Location: /login');
            exit();
        }

        // used to check whether a heart should be rendered
        $favourites = FavouritesController::getUsersFavourites($user['userId']);

        // jobs in the users field of work
        $jobs = JobModel::getJobs(['field' => $user['field']]);

        // jobs for the user's applications section
        $jobsAppliedTo = JobApplicationController::getApplicationsByUser($user['userId']);

        // jobs for the user's favourites section
        $favouriteJobs = FavouritesController::getFullUserFavouritesData($user['userId']);

        // object to render job containers in the view
        $jobRenderer = new JobRenderer();

        $view = new View();
        return $view->render('home', [
            'pageTitle' => 'Home',
            'user' => $user,
            'jobs' => $jobs,
            'jobsAppliedTo' => $jobsAppliedTo,
            'favourites' => $favourites,
            'favouriteJobs' => $favouriteJobs,
            'jobRenderer' => $jobRenderer,
        ]);
    }

    public function adminSelection(Request $req): string
    {
        $jobs = JobModel::getJobNames();
        $jobRenderer = new JobRenderer();

        $view = new View();
        return $view->render('adminSelection', [
            'pageTitle' => 'Admin Selection',
            'jobs' => $jobs,
            'jobRenderer' => $jobRenderer,
        ]);
    }

    public function create(Request $req): string
    {
        $body = $req->getBody();
        $user = SessionManager::getSessionData('user');

        if (!$user || $user['role'] !== 'admin') {
            ErrorController::redirectToErrorPage('not-authorized');
        }

        $errors = [];
        if ($body) {
            $status = $this->createJob($body);

            if (is_array($status)) {
                $errors = $status;
            }
        }

        $employers = EmployerController::getAllEmployers();
        $fields = FieldOfWorkController::getAllFields();
        $locations = LocationController::getAllLocations();

        $view = new View();
        return $view->render('createJob', [
           'pageTitle' => 'Kreirajte oglas',
           'employers' => $employers,
           'fields' => $fields,
           'locations' => $locations,
            'errors' => $errors,
        ]);
    }

    public function createJob(array $data): array|bool
    {
        $jobModel = new JobModel(
            $data['employerId'],
            $data['jobName'],
            $data['description'],
            $data['field'],
            (int)$data['startSalary'],
            $data['shifts'],
            $data['location'],
            isset($data['flexibleHours']),
            isset($data['workFromHome'])
        );

        if ($jobModel->validate()) {
            $jobModel->createJob();
            SuccessController::redirectToSuccessPage('job-created');
            return true;
        }
        else {
           return $jobModel->getValidationErrors();
        }
    }

    public function update(Request $req): string
    {
        $user = SessionManager::getSessionData('user');
        $jobId = $req->getUrlParams()['id'];

        if (!$user || $user['role'] !== 'admin') {
            ErrorController::redirectToErrorPage('not-authorized');
        }

        $job = null;
        if (!$jobId || !($job = JobModel::getJobById($jobId))) {
            ErrorController::redirectToErrorPage('bad-job-id');
        }

        $employers = EmployerController::getAllEmployers();

        $view = new View();
        return $view->render('updateJob', [
            'pageTitle' => 'Azurirajte oglas',
            'job' => $job,
            'employers' => $employers,
        ]);
    }

    public function executeUpdate(Request $req): bool
    {
        $jobId = $req->getUrlParams()['id'];
        $job = null;
        if (!$jobId || !($job = JobModel::getJobById($jobId))) {
            ErrorController::redirectToErrorPage('bad-job-id');
            return false;
        }

        $data = $req->getBody();

        // go through request body and remove all fields that were not changed in the update form

        // flexibleHours and workFromHome checkboxes don't appear in the
        // request body if they're unchecked, so they need to be checked separately
        // if they were unchecked and their value in the db is 1, they were changed in the form
        if (!isset($data['flexibleHours']) && $job['flexibleHours']) {
            $data['flexibleHours'] = 0;
        }

        if (!isset($data['workFromHome']) && $job['workFromHome']) {
            $data['workFromHome'] = 0;
        }

        foreach ($data as $key => $value) {
            // if both checkboxes are set and they are 1 in the db, remove them because they weren't changed
            // if they're 0, they were set before the loop and shouldn't be removed
            if ($key === 'flexibleHours' && $job['flexibleHours'] && $value !== 0) {
                unset($data[$key]);
                continue;
            }

            if ($key === 'workFromHome' && $job['workFromHome'] && $value !== 0) {
                unset($data[$key]);
                continue;
            }

            if ($job[$key] == $value) {
                unset($data[$key]);
            }
        }

        $updateStatus = JobModel::updateJob($job['jobId'], $data);

        if ($updateStatus) {
            SuccessController::redirectToSuccessPage('update-success');
            return true;
        }
        else {
            ErrorController::redirectToErrorPage('update-error');
            return false;
        }
    }

    public function delete(Request $req): bool
    {
        $jobId = $req->getUrlParams()['id'];

        if (!$jobId) {
            ErrorController::redirectToErrorPage('bad-job-id');
        }

        $deleteStatus = JobModel::deleteJob($jobId);

        if ($deleteStatus) {
            SuccessController::redirectToSuccessPage('delete-success');
            return true;
        }
        else {
            ErrorController::redirectToErrorPage('delete-error');
            return false;
        }
    }
}