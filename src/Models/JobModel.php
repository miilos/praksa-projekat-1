<?php

namespace App\Models;

use App\Core\Db;
use App\Managers\ErrorManager;
use App\Managers\SuccessManager;
use Ramsey\Uuid\Uuid;
use App\Core\QueryBuilder;

class JobModel extends Model
{
    public function __construct(
        protected string $employerId,
        protected string $jobName,
        protected string $description,
        protected string $field,
        protected int $startSalary,
        protected int $shifts,
        protected string $location,
        protected bool $flexibleHours,
        protected bool $workFromHome
    ) {}

    // meant for getting all jobs or filtering jobs based on the field
    // for additional filtering, filterJobs() is used
    public static function getJobs(array $filter = []): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('*');
        $qb->table('jobs');
        $qb->join('INNER JOIN', 'employers', 'employerId', 'employerId');

        if ($filter) {
            foreach ($filter as $field => $value) {
                $qb->where([$field => $value]);
            }
        }

        $qb->build();
        $jobs = $qb->execute();
        $qb->close();
        return $jobs;
    }

    public static function filterJobs(array $filters): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('*');
        $qb->table('jobs');
        $qb->join('INNER JOIN', 'employers', 'employerId', 'employerId');

        $filtersNotEmpty = self::checkFiltersEmpty($filters);
        if ($filtersNotEmpty) {
            foreach ($filters as $field => $value) {
                if (!$value) continue;

                switch ($field) {
                    case 'jobName':
                        $qb->where([$field => "%$value%"], 'LIKE');
                        break;
                    case 'location':
                        $qb->where([$field => $value]);
                        break;
                    case 'flexibleHours':
                    case 'workFromHome':
                        $qb->where([$field => 1]);
                        break;
                }
            }
        }

        $qb->build();
        $jobs = $qb->execute();
        $qb->close();
        return $jobs;
    }

    private static function checkFiltersEmpty(array $filters): bool
    {
        return count(array_filter($filters)) > 0;
    }

    public static function getJobById(string $id): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('*');
        $qb->table('jobs');
        $qb->join('INNER JOIN', 'employers', 'employerId', 'employerId');
        $qb->where(['jobId' => $id]);
        $qb->build();
        $job = $qb->execute('one');
        $qb->close();
        return $job;
    }

    public static function getJobNames(): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('jobId', 'jobName');
        $qb->table('jobs');
        $qb->build();
        $jobs = $qb->execute();
        $qb->close();
        return $jobs;
    }

    public function validate(): bool
    {
        if ($this->employerId === '-') {
            $this->errors['employerId'][] = 'Morate izabrati poslodavca';
        }

        if (!$this->jobName) {
            $this->errors['jobName'][] = 'Morate uneti naziv oglasa';
        }

        if (!$this->description) {
            $this->errors['description'][] = 'Morate unesti opis oglasa';
        }

        if (!$this->field) {
            $this->errors['field'][] = 'Morate izabrati polje rada';
        }

        if (!$this->startSalary) {
            $this->errors['startSalary'][] = 'Morate uneti pocetnu platu';
        }

        if (!$this->location) {
            $this->errors['location'][] = 'Morate uneti lokaciju posla';
        }

        return empty($this->errors);
    }

    public function createJob(): void
    {
        $jobId = Uuid::uuid4();
        $qb = new QueryBuilder();
        $qb->operation('INSERT');
        $qb->table('jobs');
        $qb->fields('jobId', 'employerId', 'jobName', 'description', 'field', 'startSalary', 'shifts', 'location', 'flexibleHours', 'workFromHome');
        $qb->values([
            'jobId' => $jobId,
            'employerId' => $this->employerId,
            'jobName' => $this->jobName,
            'description' => $this->description,
            'field' => $this->field,
            'startSalary' => $this->startSalary,
            'shifts' => $this->shifts,
            'location' => $this->location,
            'flexibleHours' => $this->flexibleHours,
            'workFromHome' => $this->workFromHome
        ]);
        $qb->build();
        $qb->execute();
        $qb->close();
    }

    public static function updateJob(string $id, array $data): bool
    {
        if (isset($data['flexibleHours'])) {
            $data['flexibleHours'] = ($data['flexibleHours'] === 'on') ? 1 : 0;
        }

        if (isset($data['workFromHome'])) {
            $data['workFromHome'] = ($data['workFromHome'] === 'on') ? 1 : 0;
        }

        $qb = new QueryBuilder();
        $qb->operation('UPDATE');
        $qb->table('jobs');
        $qb->values($data);
        $qb->where(['jobId' => $id]);
        $qb->build();
        $status = $qb->execute();
        $qb->close();
        return $status;
    }

    public static function deleteJob(string $id): bool
    {
        $qb = new QueryBuilder();
        $qb->operation("DELETE");
        $qb->table('jobs');
        $qb->where(['jobId' => $id]);
        $qb->build();
        $status = $qb->execute();
        $qb->close();
        return $status;
    }
}