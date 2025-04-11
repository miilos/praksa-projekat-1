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
        return $qb->execute();
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
        return $qb->execute();
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
        return $qb->execute('one');
    }

    public static function getJobNames(): array
    {
        $qb = new QueryBuilder();
        $qb->operation('SELECT');
        $qb->fields('jobId', 'jobName');
        $qb->table('jobs');
        $qb->build();
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
        try {
            $dbh = (new Db())->getConnection();

            $jobId = Uuid::uuid4();
            $query = "INSERT INTO jobs
                        (jobId, employerId, jobName, description, field, startSalary, shifts, location, flexibleHours, workFromHome)
                        VALUES
                        (:jobId, :employerId, :jobName, :description, :field, :startSalary, :shifts, :location, :flexibleHours, :workFromHome)";

            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':jobId', $jobId);
            $stmt->bindParam(':employerId', $this->employerId);
            $stmt->bindParam(':jobName', $this->jobName);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':field', $this->field);
            $stmt->bindParam(':startSalary', $this->startSalary);
            $stmt->bindParam(':shifts', $this->shifts);
            $stmt->bindParam(':location', $this->location);
            $stmt->bindParam(':flexibleHours', $this->flexibleHours);
            $stmt->bindParam(':workFromHome', $this->workFromHome);

            $stmt->execute();
        }
        catch (\PDOException $e) {
            ErrorManager::redirectToErrorPage('db-error');
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
        }
    }

    public static function updateJob(string $id, array $data): bool
    {
        try {
            $dbh = (new Db())->getConnection();

            $query = "UPDATE jobs SET {{newValues}} WHERE jobId=:jobId";

            $newValues = '';
            foreach ($data as $key => $value) {
                $newValues .= "$key=:$key, ";
            }
            $newValues = substr($newValues, 0, -2);

            $query = str_replace("{{newValues}}", $newValues, $query);

            $stmt = $dbh->prepare($query);
            foreach ($data as $key => $value) {
                if ($key === 'flexibleHours' || $key === 'workFromHome') {
                    $value = ($value === 'on') ? 1 : 0;
                }

                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindParam(':jobId', $id);

            $stmt->execute();

            return $stmt->rowCount() > 0;
        }
        catch (\PDOException $e) {
            ErrorManager::redirectToErrorPage('db-error');
            return false;
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
            return false;
        }
    }

    public static function deleteJob(string $id): bool
    {
        try {
            $dbh = (new Db())->getConnection();

            $query = "DELETE FROM jobs WHERE jobId=:jobId";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':jobId', $id);

            $stmt->execute();
            return $stmt->rowCount() > 0;
        }
        catch (\PDOException $e) {
            ErrorManager::redirectToErrorPage('db-error');
            return false;
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
            return false;
        }
    }
}