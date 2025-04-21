<?php

namespace App\Models;

use App\Core\QueryBuilder;

class CommentModel
{
    public static function createComment($data): bool
    {
        $qb = new QueryBuilder();
        $qb->insert();
        $qb->table('comments');
        $qb->fields('user_id', 'job_id', 'text');
        $qb->values($data);
        return $qb->execute();
    }

    public static function getCommentsForJob(string $jobId): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('comments');
        $qb->join('INNER JOIN', 'users', 'user_id', 'userId');
        $qb->where(['job_id' => $jobId]);
        return $qb->execute();
    }

    public static function deleteCommentsForJob(string $jobId): bool
    {
        $qb = new QueryBuilder();
        $qb->delete();
        $qb->table('comments');
        $qb->where(['job_id' => $jobId]);
        return $qb->execute();
    }
}