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
$qb->fields('jobId', 'employerId', 'jobName', 'description', 'field',
    'startSalary', 'location', 'createdAt', 'flexibleHours', 'workFromHome',
    'submittedAt', 'userId', 'employerName');
$qb->table('applications');
$qb->join('INNER JOIN', 'jobs', 'jobId', 'jobId');
$qb->join('INNER JOIN', 'employers', 'employerId', 'employerId');
$qb->build();
var_dump($qb->execute());