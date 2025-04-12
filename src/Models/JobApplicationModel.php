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
        $qb->operation('INSERT');
        $qb->table('applications');
        $qb->fields('applicationId', 'userId', 'jobId');
        $qb->values([
            'applicationId' => $applicationId,
            'userId' => $data['userId'],
            'jobId' => $data['jobId']
        ]);
        $qb->build();
        $status = $qb->execute();
        $qb->close();
        return $status;
    }

    public static function getJobsAppliedToByUser(string $userId, bool $onlyIds = false): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');

        if ($onlyIds) {
            $qb->fields(
                [ 'field' => 'jobId', 'table' => 'jobs' ],
                [ 'field' => 'submittedAt', 'table' => 'applications' ]
            );
            $qb->table('applications');
            $qb->join('INNER JOIN', 'jobs', 'jobId', 'jobId');
        }
        else {
            $qb->fields(
                ['field' => 'jobId', 'table' => 'jobs'],
                ['field' => 'employerId', 'table' => 'jobs'],
                ['field' => 'jobName', 'table' => 'jobs'],
                ['field' => 'description', 'table' => 'jobs'],
                ['field' => 'field', 'table' => 'jobs'],
                ['field' => 'startSalary', 'table' => 'jobs'],
                ['field' => 'location', 'table' => 'jobs'],
                ['field' => 'createdAt', 'table' => 'jobs'],
                ['field' => 'flexibleHours', 'table' => 'jobs'],
                ['field' => 'workFromHome', 'table' => 'jobs'],
                ['field' => 'submittedAt', 'table' => 'applications'],
                ['field' => 'userId', 'table' => 'applications'],
                ['field' => 'employerName', 'table' => 'employers']
            );
            $qb->table('applications');
            $qb->join('INNER JOIN', 'jobs', 'jobId', 'jobId');
            $qb->join('INNER JOIN', 'employers', 'employerId', 'employerId');
        }

        $qb->where([ 'userId' => $userId ], table: 'applications');
        $qb->build();
        $applications = $qb->execute();
        $qb->close();
        return $applications;
    }
}