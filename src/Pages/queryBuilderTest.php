<?php

use App\Core\QueryBuilder;

require_once __DIR__ . '/../../vendor/autoload.php';

$qb = new QueryBuilder();

$qb->operation('INSERT');
$qb->table('test');
$qb->fields('f1', 'f2', 'f3');
$qb->data(['f1'=>1, 'f2'=>2, 'f3'=>3]);
$qb->build();
var_dump($qb->execute());