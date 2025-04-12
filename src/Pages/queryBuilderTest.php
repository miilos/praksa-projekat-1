<?php

use App\Core\QueryBuilder;

require_once __DIR__ . '/../../vendor/autoload.php';

$qb = new QueryBuilder();

/*
SELECT j.jobId, j.employerId, j.jobName,
j.description, j.field, j.startSalary, j.location,
j.createdAt, j.flexibleHours, j.workFromHome,
a.submittedAt, a.userId,
e.employerName
FROM applications a
INNER JOIN jobs j
ON a.jobId = j.jobId
INNER JOIN employers e
ON j.employerId = e.employerId
WHERE a.userId = :userId
*/

$qb->operation('SELECT');
$qb->fields(['field' => 'jobId', 'table' => 'jobs'],
    ['field' => 'employerId', 'table' => 'jobs'],
    ['field' => 'jobName', 'table' => 'jobs'],
    ['field' => 'description', 'table' => 'jobs'],
    ['field' => 'field', 'table' => 'jobs'],
    'startSalary', 'location', 'createdAt', 'flexibleHours', 'workFromHome',
    ['field' => 'submittedAt', 'table' => 'applications'], ['field' => 'userId', 'table' => 'applications'],
    ['field' => 'employerName', 'table' => 'employers']);
$qb->table('applications');
$qb->join('INNER JOIN', 'jobs', 'jobId', 'jobId');
$qb->join('INNER JOIN', 'employers', 'employerId', 'employerId');
$qb->where([ 'userId' => '16cba2b1-58a9-438f-aa67-92e8715be11d' ], table: 'applications');
$qb->build();
var_dump($qb->execute());