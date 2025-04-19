<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Core\QueryBuilder;
use App\Models\FavouritesModel;

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
        $qb->select('*');
        $qb->table('jobs');
        $qb->join('INNER JOIN', 'employers', 'employerId', 'employerId');

        if ($filter) {
            foreach ($filter as $field => $value) {
                $qb->where([$field => $value]);
            }
        }

        return $qb->execute();
    }

    public static function filterJobs(array $filters): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
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

        return $qb->execute();
    }

    private static function checkFiltersEmpty(array $filters): bool
    {
        return count(array_filter($filters)) > 0;
    }

    public static function getJobById(string $id): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('jobs');
        $qb->join('INNER JOIN', 'employers', 'employerId', 'employerId');
        $qb->where(['jobId' => $id]);
        return $qb->execute('one');
    }

    public static function getJobNames(): array
    {
        $qb = new QueryBuilder();
        $qb->select('jobId', 'jobName');
        $qb->table('jobs');
        return $qb->execute();
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

        if ($this->field === '-') {
            $this->errors['field'][] = 'Morate izabrati polje rada';
        }

        if (!$this->startSalary) {
            $this->errors['startSalary'][] = 'Morate uneti pocetnu platu';
        }

        if ($this->location === '-') {
            $this->errors['location'][] = 'Morate izabrati lokaciju posla';
        }

        return empty($this->errors);
    }

    public function createJob(): void
    {
        $jobId = Uuid::uuid4();
        $qb = new QueryBuilder();
        $qb->insert();
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
            'flexibleHours' => isset($this->flexibleHours) ? 1 : 0,
            'workFromHome' => isset($this->workFromHome) ? 1 : 0
        ]);
        $qb->execute();
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
        $qb->update();
        $qb->table('jobs');
        $qb->values($data);
        $qb->where(['jobId' => $id]);
        return $qb->execute();
    }

    public static function deleteJob(string $id): bool
    {
        // delete any entries in the favourites table because jobId is a foreign key there
        FavouritesModel::deleteJobFromFavourites($id);

        $qb = new QueryBuilder();
        $qb->delete();
        $qb->table('jobs');
        $qb->where(['jobId' => $id]);
        return $qb->execute();
    }
}