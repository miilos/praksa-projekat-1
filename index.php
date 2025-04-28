<?php

use App\Controllers\AuthController;
use App\Controllers\ErrorController;
use App\Controllers\JobController;
use App\Controllers\JobApplicationController;
use App\Controllers\SuccessController;
use App\Core\Request;
use App\Managers\SessionManager;
use App\Controllers\FavouritesController;
use App\Core\Router;

require_once __DIR__ . '/vendor/autoload.php';

Dotenv\Dotenv::createImmutable('./')->load();

const ROOT_PATH = __DIR__;

$router = new Router(new Request());
$router
    ->get('/', [JobController::class, 'index'])
    ->get('/job', [JobController::class, 'jobDetails']) // needs id
    ->get('/login', [AuthController::class, 'login'])
    ->get('/signup', [AuthController::class, 'signup'])
    ->get('/home', [JobController::class, 'home'])
    ->get('/apply', [JobApplicationController::class, 'apply']) // needs id
    ->get('/adminSelection', [JobController::class, 'adminSelection'])
    ->get('/job/create', [JobController::class, 'create'])
    ->get('/job/update', [JobController::class, 'update'])
    ->get('/job/delete', [JobController::class, 'delete']) // needs id
    ->get('/success', [SuccessController::class, 'success']) // needs msg
    ->get('/error', [ErrorController::class, 'error']) // needs msg
    ->post('/', [JobController::class, 'index'])
    ->post('/login', [AuthController::class, 'login'])
    ->post('/signup', [AuthController::class, 'signup'])
    ->post('/apply', [JobApplicationController::class, 'apply']) // needs id
    ->post('/createJob', [JobController::class, 'create'])
    ->post('/executeUpdate', [JobController::class, 'executeUpdate']); // needs id

$router->resolve();