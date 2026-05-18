<?php

session_start();

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/mssql.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/controllers/SyncController.php';

$controller = new SyncController($mysql_conn, $mssql_conn);

// ✅ ROUTING SYSTEM
$action = $_GET['a'] ?? 'index';

if (!method_exists($controller, $action)) {
    die("Invalid action");
}

// ✅ EXECUTE
$controller->$action();