<?php

namespace App\Controllers;

use App\Models\CommentModel;

class CommentController
{
    //#[Route(method:'get', name:'get-comments', path:'/comments/{id}', requirements: [])]
    public static function createComment(array $data): bool
    {
        return CommentModel::createComment($data);
    }

    public static function getCommentsForJob(string $jobId): array
    {
        return CommentModel::getCommentsForJob($jobId);
    }
}