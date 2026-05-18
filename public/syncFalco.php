<?php

session_start();

require_once __DIR__ . '/../app/config/mssql.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/controllers/FalcoController.php';

$controller = new FalcoController($mysql_conn, $mssql_conn);

$action = $_GET['a'] ?? 'index';

if (!method_exists($controller, $action)) {
    die("Invalid action");
}

$controller->$action();