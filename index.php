<?php

use App\Controllers\AuthController;
use App\Controllers\ErrorController;
use App\Controllers\JobController;
use App\Controllers\JobApplicationController;
use App\Controllers\SuccessController;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;

require_once __DIR__ . '/vendor/autoload.php';

Dotenv\Dotenv::createImmutable('./')->load();

const ROOT_PATH = __DIR__;

$router = new Router(new Request(), new Response());

$router->registerRouteAttributes([
    AuthController::class,
    JobController::class,
    JobApplicationController::class,
    SuccessController::class,
    ErrorController::class,
]);

$router->resolve();