<?php

namespace App\Models;

use App\Controllers\ErrorController;
use App\Core\Db;

class JobModel
{
    public function getAllJobs(): array
    {
        try {
            $dbh = (new Db())->getHandler();

            $query = "SELECT * FROM jobs j INNER JOIN employers e ON j.employerId = e.employerId";
            $stmt = $dbh->prepare($query);

            $stmt->execute();
            $jobs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $jobs;
        }
        catch (\PDOException $e) {
            $msg = ErrorController::getErrors()['db-error'];
            ErrorController::redirectToErrorPage($msg);
        }

        return [];
    }
}