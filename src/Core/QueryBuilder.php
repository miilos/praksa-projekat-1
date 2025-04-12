<?php

namespace App\Core;

use App\Core\Db;
use App\Managers\ErrorManager;
use App\Managers\SuccessManager;

class QueryBuilder
{
    private string $query;
    private string $operation;
    private string $table;
    private string $join = '';
    private string $fields = '';
    private array $data;
    private array $bindings = [];
    private string $conditionsString = '';

    public function operation(string $operation): void
    {
        $this->operation = $operation;
    }

    public function table(string $table): void
    {
        $this->table = $table;
    }

    public function join(string $joinType, string $joinTable, string $baseTableField, string $joinTableField, string $operation = '='): void
    {
        $baseTableQualifier = $this->table[0];
        $joinTableQualifier = $joinTable[0];
        $previousJoinTableQualifier = '';

        if ($this->join) {
            $lastQualifierIndex = (strrpos($this->join, '.')) - 1;
            $previousJoinTableQualifier = $this->join[$lastQualifierIndex];

            // if a join already exists, $baseTableField refers to the field from the last table joined
            $this->join .= " $joinType $joinTable $joinTableQualifier
                           ON $previousJoinTableQualifier.$baseTableField $operation $joinTableQualifier.$joinTableField";
        }
        else {
            $this->join .= " $baseTableQualifier $joinType $joinTable $joinTableQualifier 
                           ON $baseTableQualifier.$baseTableField $operation $joinTableQualifier.$joinTableField";
        }
    }

    public function fields(mixed ...$fields): void
    {
        foreach ($fields as $field) {
            if (is_string($field)) {
                $this->fields .= "$field, ";
            }

            if (is_array($field)) {
                $this->fields .= (substr($field['table'], 0, 1) . '.' . $field['field'] . ', ');
            }
        }

        $this->fields = substr($this->fields, 0, -2);
    }

    public function data(array $data): void
    {
        $this->data = $data;
    }

    public function where(array $condition, string $operation = '=', string $separator = 'AND', string $table = ''): void
    {
        $this->bindings[] = $condition;

        if ($table) {
            $this->conditionsString .= ($table[0] . '.' . key($condition) . " $operation :" . key($condition) . " $separator ");
        }
        else {
            $this->conditionsString .= (key($condition) . " $operation :" . key($condition) . " $separator ");
        }
    }

    public function build(): void
    {
        switch ($this->operation) {
            case 'SELECT':
                $this->buildSelect();
                break;
            case 'INSERT':
                $this->buildInsert();
                break;
            case 'UPDATE':
                $this->buildUpdate();
                break;
            case 'DELETE':
                $this->buildDelete();
                break;
        }
    }

    private function buildSelect(): void
    {
        // remove trailing condition from $conditionsString
        $this->conditionsString = substr($this->conditionsString, 0, -4);

        $this->query = "$this->operation $this->fields FROM $this->table $this->join";

        if ($this->conditionsString) {
            $this->query .= " WHERE $this->conditionsString";
        }
    }

    private function buildInsert(): void
    {

    }

    private function buildUpdate(): void
    {

    }

    private function buildDelete(): void
    {

    }

    public function execute(string $fetch = 'all', int $fetchMode = \PDO::FETCH_ASSOC): array|bool
    {
        try {
            $dbh = (new Db())->getConnection();

            $stmt = $dbh->prepare($this->query);

            if ($this->bindings) {
                foreach ($this->bindings as $binding) {
                    $stmt->bindValue(':' . key($binding), $binding[key($binding)]);
                }
            }

            $stmt->execute();

            if ($fetch === 'all') {
                if ($fetchMode === \PDO::FETCH_COLUMN) {
                    return $stmt->fetchAll($fetchMode, 0);
                }
                return $stmt->fetchAll($fetchMode);
            }
            else {
               return $stmt->fetch($fetchMode);
            }
        }
        catch (\PDOException $e) {
            echo $e->getMessage();
            //ErrorManager::redirectToErrorPage('db-error');
            return [];
        }
        catch (\Throwable $t) {
            echo $t->getMessage();
            //ErrorManager::redirectToErrorPage('unknown-error');
            return [];
        }
    }

    // INSERT INTO table (fields) VALUES(values)
    // SELECT fields FROM table WHERE condition
    // UPDATE table SET field=value WHERE condition
    // DELETE FROM table WHERE condition
}