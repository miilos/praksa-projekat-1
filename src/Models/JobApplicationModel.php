<?php

namespace App\Models;

use App\Core\Db;
use App\Managers\ErrorManager;
use PDO;
use Ramsey\Uuid\Uuid;

class JobApplicationModel
{
    public static function createJobApplication(array $data): bool
    {
        try {
            $dbh = (new Db())->getConnection();

            $query = "INSERT INTO applications(applicationId, userId, jobId) VALUES(:applicationId, :userId, :jobId)";

            $applicationId = Uuid::uuid4();
            $stmt = $dbh->prepare($query);

            $stmt->bindParam(':applicationId', $applicationId);
            $stmt->bindParam(':userId', $data['userId']);
            $stmt->bindParam(':jobId', $data['jobId']);

            $stmt->execute();

            return $stmt->rowCount() === 1;
        }
        catch (\PDOException $e) {
            echo $e->getMessage();
            //ErrorManager::redirectToErrorPage('db-error');
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
        }

        return true;
    }

    public static function getJobsAppliedToByUser(string $userId, bool $onlyIds = false): array
    {
        try {
            $dbh = (new Db())->getConnection();

            $query = "";
            if ($onlyIds) {
                $query = "SELECT j.jobId, a.submittedAt
                            FROM applications a
                            INNER JOIN jobs j
                            ON a.jobId = j.jobId
                            WHERE a.userId = :userId";
            }
            else {
                $query = "SELECT j.jobId, j.employerId, j.jobName,
                        j.description, j.field, j.startSalary, j.location,
                        j.createdAt, j.flexibleHours, j.workFromHome,
                        a.submittedAt, a.userId,
                        e.employerName
                        FROM applications a
                        INNER JOIN jobs j
                        ON a.jobId = j.jobId
                        INNER JOIN employers e
                        ON j.employerId = e.employerId
                        WHERE a.userId = :userId";
            }

            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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