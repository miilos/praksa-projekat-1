<?php

namespace App\Core;

use App\Managers\ErrorManager;
use PDO;
use PDOException;

class Db
{
    private string $host = "localhost:3307";
    private string $user = "root";
    private string $pass = "";
    private string $database = "praksa-projekat-1";

    private $dbh;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        try {
            $this->dbh = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            ErrorManager::redirectToErrorPage('db-error');
        }
        catch (\Throwable $t) {
            ErrorManager::redirectToErrorPage('unknown-error');
        }
    }

    public function getConnection()
    {
        return $this->dbh;
    }
}