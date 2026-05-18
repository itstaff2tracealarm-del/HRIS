<?php
// Siguraduhin na walang space bago ang <?php tag
session_start();

header('Content-Type: application/json');

// 1. Siguraduhin ang path ng database.php
// Sinubukan nating gamitin ang absolute path base sa kinalalagyan ng file mo
$db_file = __DIR__ . '/../app/config/database.php';

if (file_exists($db_file)) {
    require_once $db_file;
} else {
    echo json_encode(['status' => 'error', 'debug' => 'File not found at ' . $db_file]);
    exit;
}

// 2. I-check kung may session_id (logged in talaga)
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'expired', 'debug' => 'No active session user_id']);
    exit;
}

// 3. I-check ang $mysql_conn variable mula sa database.php
if (!isset($mysql_conn) || !$mysql_conn) {
    echo json_encode(['status' => 'error', 'debug' => 'Variable $mysql_conn is not defined or null']);
    exit;
}

try {
    // 4. SQL Query - Siguraduhin na 'phphr_users' ang table name mo
    $user_id = (int)$_SESSION['user_id'];
    $sql = "SELECT status, login_token FROM phphr_users WHERE id = $user_id LIMIT 1";
    $result = mysqli_query($mysql_conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        $sessionToken = $_SESSION['login_token'] ?? '';
        $dbToken      = $user['login_token'] ?? '';
        $userStatus   = (int)($user['status'] ?? 0);

        // SIPA LOGIC: Mismatch token o Disabled account
        if ($userStatus === 0 || $sessionToken !== $dbToken) {
            echo json_encode(['status' => 'expired']);
        } else {
            echo json_encode(['status' => 'active']);
        }
    } else {
        echo json_encode(['status' => 'expired', 'debug' => 'User not found in database']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'debug' => $e->getMessage()]);
}