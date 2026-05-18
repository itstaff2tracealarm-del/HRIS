<?php

class AuthController
{
    protected $conn;
    protected $userModel;
    
    public function __construct($conn)
    {
        $this->conn = $conn;
        // Gagamit tayo ng User Model para malinis
        require_once __DIR__ . '/../models/User.php';
        $this->userModel = new User($conn);
    }

    public function login()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $error = $_GET['error'] ?? null;
        include __DIR__ . '/../views/auth/login.php';
    }

    public function doLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }

        // CSRF check
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            header('Location: index.php?error=invalid_csrf');
            exit;
        }

        $username = trim($_POST['username'] ?? ''); 
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            header('Location: index.php?error=missing_fields');
            exit;
        }

        // 1. GAMITIN ANG USER MODEL para mahanap ang user
        $user = $this->userModel->findByUsername($username);

        // 2. CHECK KUNG EXISTING ANG USER
        if (!$user) {
            header('Location: index.php?error=invalid_login');
            exit;
        }

        // 3. STATUS CHECK: Heto ang nagpapatupad ng Inactive restriction
        // Kung ang status sa database ay 0, hindi papapasukin kahit tama ang password.
        if ((int)$user['status'] === 0) {
            header('Location: index.php?error=account_disabled');
            exit;
        }

        // 4. PASSWORD VERIFY
        if (!password_verify($password, $user['password_hash'])) {
            header('Location: index.php?error=invalid_login');
            exit;
        }

        // --- MONITORING & TOKEN LOGIC ---
        
        // Generate ng secure token
        $token = bin2hex(random_bytes(32));
        $now = date('Y-m-d H:i:s');

        // I-update ang database gamit ang column names mo
        $update_sql = "UPDATE phphr_users SET 
                       last_login = ?, 
                       login_token = ?, 
                       login_status = 1 
                       WHERE id = ?";
$update_stmt = $this->conn->prepare("
    UPDATE phphr_users 
    SET last_login = ?, 
        login_token = ?, 
        login_status = 1 
    WHERE id = ?
");

$update_stmt->bind_param("ssi", $now, $token, $user['id']);
$update_stmt->execute();
        // --- SESSION SETTING ---
        $_SESSION['user_id']     = $user['id'];
        $_SESSION['full_name']   = $user['full_name'];
        $_SESSION['username']    = $user['username'];
        $_SESSION['role']        = $user['role'];
        $_SESSION['login_token'] = $token; // Itabi ang token sa session para sa validation

        header('Location: dashboard.php');
        exit;
    }

    // Logout action
    public function logout()
    {
        if (isset($_SESSION['user_id'])) {
            // Gamitin ang $this->conn dahil ito ang property ng class
            $stmt = $this->conn->prepare("UPDATE phphr_users SET login_status = 0, login_token = NULL WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
        }
        session_destroy();
        header('Location: index.php');
        exit;
    }

    private function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    private function requireRole($roles = [])
    {
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
            header('Location: index.php?error=unauthorized');
            exit;
        }
    }

    public function payroll()
    {
        $this->requireLogin();
        $this->requireRole(['admin']); // admin lang pwede
        require_once __DIR__ . '/../views/payroll.php';
    }

    public function employees()
    {
        $this->requireLogin();
        $this->requireRole(['admin', 'hr']); // pareho pwede
        require_once __DIR__ . '/../views/employees.php';
    }
}