<?php
// app/config/database.php - Database connection using MySQLi
$host     = 'localhost';
$user     = 'root';
$pass     = '';
$db       = 'attendance_system';

$mysql_conn = mysqli_connect($host, $user, $pass, $db);

if (!$mysql_conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($mysql_conn, "utf8mb4");
?>