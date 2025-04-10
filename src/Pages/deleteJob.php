<?php

use App\Managers\ErrorManager;
use App\Managers\SuccessManager;
use App\Controllers\JobController;

require_once __DIR__ . '/../../vendor/autoload.php';

$id = $_GET['id'];

if (!$id) {
    ErrorManager::redirectToErrorPage('bad-job-id');
}

$jobController = new JobController();
$deleteStatus = $jobController->deleteJob($id);

if ($deleteStatus) {
    SuccessManager::redirectToSuccessPage('delete-success');
}
else {
    ErrorManager::redirectToErrorPage('delete-error');
}