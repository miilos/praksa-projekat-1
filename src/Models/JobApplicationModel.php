<?php

namespace App\Models;

use App\Core\Db;
use App\Managers\ErrorManager;
use Ramsey\Uuid\Uuid;

class JobApplicationModel
{
    public function createJobApplication(array $data): bool
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
            ErrorManager::redirectToErrorPage('db-error');
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
        }

        return true;
    }
}