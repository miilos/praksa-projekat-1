<?php

namespace App\Controllers;

use App\Core\APIException;
use App\Core\Request;
use App\Core\Response;
use App\Core\Route;
use App\Managers\SessionManager;
use App\Models\CommentModel;
use App\Models\FavouritesModel;
use App\Models\JobModel;
use App\Views\JobRenderer;
use App\Views\View;

class JobController
{
    #[Route(method: 'get', path: '/', name: 'indexGet')]
    #[Route(method: 'post', path: '/', name: 'indexPost')]
    public function index(Request $req, Response $res): string
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

    #[Route(method: 'get', path: '/job/{id}', name: 'jobDetails')]
    public function jobDetails(Request $req, Response $res): string
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

    #[Route(method: 'get', path: '/home', name: 'home')]
    public function home(Request $req, Response $res): string
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

    #[Route(method: 'get', path: '/adminSelection', name: 'adminSelection')]
    public function adminSelection(Request $req, Response $res): string
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

    #[Route(method: 'get', path: '/job/create', name: 'createJobGet')]
    #[Route(method: 'post', path: '/job/create', name: 'createJobPost')]
    public function create(Request $req, Response $res): string
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

    #[Route(method: 'get', path: '/job/update/{id}', name: 'updateJob')]
    public function update(Request $req, Response $res): string
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

    private function prepDataForUpdate(array $data, array $job): array
    {
        // go through request body and remove all fields that were not changed in the update form

        $newData = [...$data];

        // flexibleHours and workFromHome checkboxes don't appear in the
        // request body if they're unchecked, so they need to be checked separately
        // if they were unchecked and their value in the db is 1, they were changed in the form
        if (!isset($newData['flexibleHours']) && $job['flexibleHours']) {
            $data['flexibleHours'] = 0;
        }

        if (!isset($newData['workFromHome']) && $job['workFromHome']) {
            $newData['workFromHome'] = 0;
        }

        foreach ($newData as $key => $value) {
            // if both checkboxes are set and they are 1 in the db, remove them because they weren't changed
            // if they're 0, they were set before the loop and shouldn't be removed
            if ($key === 'flexibleHours' && $job['flexibleHours'] && $value !== 0) {
                unset($newData[$key]);
                continue;
            }

            if ($key === 'workFromHome' && $job['workFromHome'] && $value !== 0) {
                unset($newData[$key]);
                continue;
            }

            if ($job[$key] == $value) {
                unset($newData[$key]);
            }
        }

        return $newData;
    }

    #[Route(method: 'post', path: '/executeUpdate/{id}', name: 'executeJobUpdate')]
    public function executeUpdate(Request $req, Response $res): bool
    {
        $jobId = $req->getUrlParams()['id'];
        $job = null;
        if (!$jobId || !($job = JobModel::getJobById($jobId))) {
            ErrorController::redirectToErrorPage('bad-job-id');
            return false;
        }

        $data = $req->getBody();
        $data = $this->prepDataForUpdate($data, $job);

        $updatedJob = JobModel::updateJob($job['jobId'], $data);

        if ($updatedJob) {
            SuccessController::redirectToSuccessPage('update-success');
            return true;
        }
        else {
            ErrorController::redirectToErrorPage('update-error');
            return false;
        }
    }

    #[Route(method: 'get', path: '/job/delete/{id}', name: 'deleteJob')]
    public function delete(Request $req, Response $res): bool
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

    /* API functions */

    #[Route(method: 'get', path: '/api/v1/jobs', name: 'getAllJobs')]
    public function getAllJobsApi(Request $req, Response $res): string
    {
        try {
            $jobs = JobModel::getJobs();

            return $res->statusCode(200)->sendJSON([
                'status' => 'success',
                'data' => [
                    'jobs' => $jobs
                ]
            ]);
        }
        catch (APIException $e) {
            return ErrorController::handleAPIError($res, $e, $e->statusCode);
        }
        catch (\Throwable $t) {
            return ErrorController::handleAPIError($res, $t);
        }
    }

    #[Route(method: 'get', path: '/api/v1/jobs/{id}', name: 'getJobById')]
    public function getJobByIdApi(Request $req, Response $res): string
    {
        try {
            $id = $req->getUrlParams()['id'];
            $job = JobModel::getJobById($id);

            if (!$job) {
                throw new APIException('posao s tim idjem ne postoji', 400);
            }

            $comments = CommentModel::getCommentsForJob($job['jobId']);
            $job['comments'] = $comments;

            return $res->statusCode(200)->sendJSON([
                'status' => 'success',
                'data' => [
                    'job' => $job
                ]
            ]);
        }
        catch (APIException $e) {
            return ErrorController::handleAPIError($res, $e, $e->statusCode);
        }
        catch (\Throwable $t) {
            return ErrorController::handleAPIError($res, $t);
        }
    }

    #[Route(method: 'post', path: '/api/v1/jobs', name: 'createJob')]
    public function createJobApi(Request $req, Response $res): string
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $jobModel = new JobModel(
                $data['employerId'] ?? '-',
                $data['jobName'] ?? '',
                $data['description'] ?? '',
                $data['field'] ?? '',
                (int)$data['startSalary'] ?? 0,
                $data['shifts'] ?? '',
                $data['location'] ?? '',
                isset($data['flexibleHours']),
                isset($data['workFromHome'])
            );

            if ($jobModel->validate()) {
                $newJob = $jobModel->createJob();
                return $res->statusCode(201)->sendJSON([
                    'status' => 'success',
                    'message' => 'job created!',
                    'data' => [
                        'job' => $newJob
                    ]
                ]);
            }
            else {
                return $res->statusCode(400)->sendJSON([
                    'status' => 'error',
                    'message' => 'validation error',
                    'errors' => $jobModel->getValidationErrors()
                ]);
            }
        }
        catch (APIException $e) {
            return ErrorController::handleAPIError($res, $e, $e->statusCode);
        }
        catch (\Throwable $t) {
            return ErrorController::handleAPIError($res, $t);
        }
    }

    #[Route(method: 'patch', path: '/api/v1/jobs/{id}', name: 'updateJob')]
    public function updateJobApi(Request $req, Response $res): string
    {
        try {
            $data = $req->getBody();
            $jobId = $req->getUrlParams()['id'];
            $job = null;

            if (!$jobId || !($job = JobModel::getJobById($jobId))) {
                throw new APIException('nepostojeci jobId', 400);
            }

            if (!$data) {
                throw new APIException('morate odabrati neka polja za update', 400);
            }

            $data = $this->prepDataForUpdate($data, $job);
            $updatedJob = JobModel::updateJob($jobId, $data);

            if ($updatedJob) {

            }
        }
        catch (APIException $e) {
            ErrorController::handleAPIError($res, $e, $e->statusCode);
        }
        catch (\Throwable $t) {
            return ErrorController::handleAPIError($res, $t);
        }
    }
}