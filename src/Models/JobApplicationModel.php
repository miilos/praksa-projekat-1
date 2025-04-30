<?php

namespace App\Models;

use App\Core\QueryBuilder;
use Ramsey\Uuid\Uuid;

class JobApplicationModel
{
    public static function createJobApplication(array $data): bool
    {
        $applicationId = Uuid::uuid4();
        $qb = new QueryBuilder();
        $qb->insert();
        $qb->table('applications');
        $qb->fields('applicationId', 'userId', 'jobId');
        $qb->values([
            'applicationId' => $applicationId,
            'userId' => $data['userId'],
            'jobId' => $data['jobId']
        ]);
        return $qb->execute();
    }

    public static function getJobsAppliedToByUser(string $userId, bool $onlyIds = false): array
    {
        $qb = new QueryBuilder();

        if ($onlyIds) {
            $qb->select('j.jobId', 'a.submittedAt');
            $qb->table('applications');
            $qb->join('INNER JOIN', 'jobs', 'a.jobId', 'j.jobId');
        }
        else {
            $qb->select(
                'j.jobId', 'j.employerId', 'j.jobName', 'j.description', 'j.field', 'j.startSalary',
                'j.location', 'j.createdAt', 'j.flexibleHours' ,'j.workFromHome', 'a.submittedAt', 'a.userId', 'e.employerName'
            );
            $qb->table('applications');
            $qb->join('INNER JOIN', 'jobs', 'a.jobId', 'j.jobId');
            $qb->join('INNER JOIN', 'employers', 'j.employerId', 'e.employerId');
        }

        $qb->where([ 'userId' => $userId ], table: 'applications');
        return $qb->execute();
    }

    public static function userAppliedToJob(string $userId, string $jobId): array|bool
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('applications');
        $qb->where([ 'userId' => $userId ]);
        $qb->where([ 'jobId' => $jobId ]);
        return $qb->execute(fetch: 'one');
    }
}