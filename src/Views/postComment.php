<?php

use App\Controllers\CommentController;

require_once __DIR__ . '../../../vendor/autoload.php';

$data = json_decode(file_get_contents('php://input'), true);

echo CommentController::createComment([
    'user_id' => $data['user_id'],
    'job_id' => $data['job_id'],
    'text' => $data['text'],
]);