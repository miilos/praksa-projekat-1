<?php

use App\Controllers\AuthController;
use App\Controllers\ErrorController;
use App\Controllers\JobController;
use App\Controllers\JobApplicationController;
use App\Controllers\SuccessController;
use App\Core\Request;
use App\Core\Router;

require_once __DIR__ . '/vendor/autoload.php';

Dotenv\Dotenv::createImmutable('./')->load();

const ROOT_PATH = __DIR__;

$router = new Router(new Request());
$router
    ->get('/', [JobController::class, 'index'])
    ->get('/job/{id}', [JobController::class, 'jobDetails'])
    ->get('/login', [AuthController::class, 'login'])
    ->get('/signup', [AuthController::class, 'signup'])
    ->get('/home', [JobController::class, 'home'])
    ->get('/apply/{id}', [JobApplicationController::class, 'apply'])
    ->get('/adminSelection', [JobController::class, 'adminSelection'])
    ->get('/job/create', [JobController::class, 'create'])
    ->get('/job/update/{id}', [JobController::class, 'update'])
    ->get('/job/delete/{id}', [JobController::class, 'delete'])
    ->get('/success/{msg}', [SuccessController::class, 'success'])
    ->get('/error/{msg}', [ErrorController::class, 'error'])
    ->post('/', [JobController::class, 'index'])
    ->post('/login', [AuthController::class, 'login'])
    ->post('/signup', [AuthController::class, 'signup'])
    ->post('/apply/{id}', [JobApplicationController::class, 'apply'])
    ->post('/job/create', [JobController::class, 'create'])
    ->post('/executeUpdate/{id}', [JobController::class, 'executeUpdate']);

$router->resolve();