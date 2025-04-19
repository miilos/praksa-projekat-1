<?php

namespace App\Models;

use App\Core\Db;
use App\Core\QueryBuilder;
use App\Managers\ErrorManager;
use PDO;
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
            $qb->join('INNER JOIN', 'jobs', 'jobId', 'jobId');
        }
        else {
            $qb->select(
                'j.jobId', 'j.employerId', 'j.jobName', 'j.description', 'j.field', 'j.startSalary',
                'j.location', 'j.createdAt', 'j.flexibleHours' ,'j.workFromHome', 'a.submittedAt', 'a.userId', 'e.employerName'
            );
            $qb->table('applications');
            $qb->join('INNER JOIN', 'jobs', 'jobId', 'jobId');
            $qb->join('INNER JOIN', 'employers', 'employerId', 'employerId');
        }

        $qb->where([ 'userId' => $userId ], table: 'applications');
        return $qb->execute();
    }
}