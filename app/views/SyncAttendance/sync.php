<?php

session_start();
$conn = new mysqli("localhost", "root", "", "attendance_system");

require_once __DIR__ . '/controllers/SyncController.php';

$controller = new SyncController($conn);

$action = $_GET['a'] ?? 'index';

if (!method_exists($controller, $action)) {
    die("Invalid action");
}

$controller->$action();