<?php

namespace App\Models;

use App\Core\Db;
use App\Managers\ErrorManager;

class JobModel
{
    // meant for getting all jobs or filtering jobs based on the field
    // for additional filtering, filterJobs() is used
    public function getJobs(array $filter = []): array
    {
        try {
            $dbh = (new Db())->getConnection();

            $query = "SELECT * FROM jobs j INNER JOIN employers e ON j.employerId = e.employerId";

            if ($filter) {
                $query .= " WHERE";
                foreach ($filter as $field => $value) {
                    $query .= " $field=:$field AND";
                }

                // remove trailing AND from SQL query
                $query = substr($query, 0, -3);
            }

            $stmt = $dbh->prepare($query);

            foreach ($filter as $field => $value) {
                $stmt->bindParam(":$field", $value);
            }

            $stmt->execute();
            $jobs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $jobs;
        }
        catch (\PDOException $e) {
            ErrorManager::redirectToErrorPage('db-error');
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
        }

        return [];
    }

    public function filterJobs(array $filters): array
    {
        try {
            $dbh = (new Db())->getConnection();

            $query = "SELECT * FROM jobs j INNER JOIN employers e ON j.employerId = e.employerId";

            // if the filters are specified, build the sql query by chaining conditions
            // of the filters that are set
            // if not, skip the query building and execute it as is

            $filtersNotEmpty = $this->checkFiltersEmpty($filters);
            if ($filtersNotEmpty) {
                $query .= " WHERE";

                foreach ($filters as $field => $value) {
                    if (!$value) {
                        continue;
                    }

                    switch ($field) {
                        case 'name':
                            $query .= " j.jobName LIKE :$field";
                            break;
                        case 'location':
                            $query .= " j.location = :$field";
                            break;
                        case 'flexibleHours':
                            $query .= " j.flexibleHours = 1";
                            break;
                        case 'workFromHome':
                            $query .= " j.workFromHome = 1";
                            break;
                    }
                    $query .= " AND";
                }
                $query = substr($query, 0, -3);
            }

            $stmt = $dbh->prepare($query);

            if ($filtersNotEmpty && $filters['name']) {
                $stmt->bindValue(':name', '%'.$filters['name'].'%', \PDO::PARAM_STR);
            }

            if ($filtersNotEmpty && $filters['location']) {
                $stmt->bindValue(':location', $filters['location'], \PDO::PARAM_STR);
            }

            $stmt->execute();
            $jobs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $jobs;
        }
        catch (\PDOException $e) {
            ErrorManager::redirectToErrorPage('db-error');
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
        }

        return [];
    }

    private function checkFiltersEmpty(array $filters): bool
    {
        return count(array_filter($filters)) > 0;
    }

    public function getJobById(string $id): array | bool
    {
        try {
            $dbh = (new Db())->getConnection();

            $query = "SELECT * FROM jobs j INNER JOIN employers e ON j.employerId = e.employerId WHERE jobId=:jobId";

            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':jobId', $id);

            $stmt->execute();
            $job = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $job;
        }
        catch (\PDOException $e) {
            ErrorManager::redirectToErrorPage('db-error');
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
        }

        return [];
    }
}