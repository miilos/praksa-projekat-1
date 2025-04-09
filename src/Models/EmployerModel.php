<?php

namespace App\Models;

use App\Core\Db;
use App\Managers\ErrorManager;

class EmployerModel
{
    public static function getAllEmployers(): array
    {
        try {
            $dbh = (new Db())->getConnection();

            $query = "SELECT employerId, employerName FROM employers";
            $stmt = $dbh->prepare($query);
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