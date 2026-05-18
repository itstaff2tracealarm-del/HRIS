<?php

// session_start();

// require_once __DIR__ . '/../app/config/config.php';
// require_once __DIR__ . '/../app/config/database.php';
// require_once __DIR__ . '/../app/controllers/EmployeesController.php';

// $action = $_GET['a'] ?? 'index';

// $controller = new EmployeesController($conn);

// if (!method_exists($controller, $action)) {
//     $action = 'index';
// }

// $controller->$action();



session_start();

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/mssql.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/controllers/EmployeesController.php';

$action = $_GET['a'] ?? 'index';

$controller = new EmployeesController($mysql_conn, $mssql_conn);

// I-check kung valid ang action, kung hindi, default sa index
if (!method_exists($controller, $action)) {
    $action = 'index';
}

// Dito na tatakbo ang kahit anong action, kasama na ang check_duplicate
$controller->$action();