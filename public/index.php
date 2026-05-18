<?php

session_start();

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/mssql.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$action = $_GET['a'] ?? 'login';   // default: show login form

$controller = new AuthController($mysql_conn);

if (!method_exists($controller, $action)) {
    $action = 'login';
}

$controller->$action();