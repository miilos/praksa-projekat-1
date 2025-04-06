<?php

namespace App\Core;

use App\Controllers\ErrorController;
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
        catch(PDOException $e) {
            $msg = ErrorController::getErrors()['db-error'];
            ErrorController::redirectToErrorPage($msg);
        }
    }

    public function getHandler()
    {
        return $this->dbh;
    }
}