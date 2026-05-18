<?php

session_start();

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/mssql.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';

$controller = new DashboardController($mysql_conn, $mssql_conn);
$controller->index();