<?php

use App\Controllers\JobController;
use App\Managers\SuccessManager;
use App\Models\JobModel;
use App\Managers\ErrorManager;
use App\Core\Request;

require_once __DIR__ . '/../../vendor/autoload.php';

if (!$_GET['id']) {
    ErrorManager::redirectToErrorPage('bad-job-id');
}

$job = JobModel::getJobById($_GET['id']);

$request = new Request();
$data = $request->getBody();

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

$jobController = new JobController();
$updateStatus = $jobController->updateJob($_GET['id'], $data);

if ($updateStatus) {
    SuccessManager::redirectToSuccessPage('update-success');
}
else {
    ErrorManager::redirectToErrorPage('update-error');
}