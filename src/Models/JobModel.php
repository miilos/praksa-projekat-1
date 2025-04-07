<?php

namespace App\Models;

use App\Controllers\ErrorController;
use App\Core\Db;

class JobModel
{
    public function getJobs($filter = []): array
    {
        try {
            $dbh = (new Db())->getHandler();

            $query = "SELECT * FROM jobs j INNER JOIN employers e ON j.employerId = e.employerId";

            if ($filter) {
                $query .= " WHERE";
                foreach ($filter as $field => $value) {
                    $query .= " $field=:$field";
                    $query .= " AND";
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
            ErrorController::redirectToErrorPage('db-error');
        }

        return [];
    }
}