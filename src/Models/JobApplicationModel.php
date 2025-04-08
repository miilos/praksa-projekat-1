<?php

namespace App\Models;

use App\Controllers\ErrorController;
use App\Core\Db;
use Ramsey\Uuid\Uuid;

class JobApplicationModel
{
    public function createJobApplication($data): bool
    {
        try {
            $dbh = (new Db())->getHandler();

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
            ErrorController::redirectToErrorPage('db-error');
        }

        return true;
    }
}