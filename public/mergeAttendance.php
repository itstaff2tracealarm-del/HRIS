<?php

session_start();

// 1. I-require ang mga kailangang configuration at database connections
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/mssql.php';    // Connection variable: $mssql_conn
require_once __DIR__ . '/../app/config/database.php'; // Connection variable: $mysql_conn

// 2. I-require ang Controller
require_once __DIR__ . '/../app/controllers/MergeAttendanceController.php';

// 3. Kunin ang action mula sa URL (e.g., mergeAttendance.php?a=index)
// Default ay 'login' base sa logic mo, pero kung gusto mong 
// direkta sa listahan, pwede mong gawing 'index'.
$action = $_GET['a'] ?? 'login';

// 4. I-initialize ang Controller at ipasa ang dalawang database connection
$controller = new MergeAttendanceController($mysql_conn, $mssql_conn);

// 5. Siguraduhin na umiiral ang method sa loob ng controller bago tawagin
if (!method_exists($controller, $action)) {
    $action = 'login'; // I-fallback sa login kung walang valid action
}

// 6. Patakbuhin ang action
$controller->$action();