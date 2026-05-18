<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php'; 

class RegisteruserController extends Controller {
    protected $userModel;
    protected $db_conn;

    public function __construct($mysql_conn) {
        if(!$mysql_conn){
            die("Database connection failed in Controller");
        }
        parent::__construct($mysql_conn); 
        
        $this->db_conn = $mysql_conn; 
        $this->userModel = new User($this->db_conn); 
       
        // --- RBAC: Updated to allow admin and IT ---
        $sessionRole = $_SESSION['role'] ?? ''; 
        $allowedToManageUsers = ['admin', 'it'];

        if (!in_array($sessionRole, $allowedToManageUsers)) {
            $_SESSION['user_error'] = "Unauthorized access. Only Admins and IT personnel can manage users.";
            $this->redirect('dashboard.php'); 
            exit;
        }
    }

    // 1. LIST USERS
    public function index() {
        $query = "SELECT 
                    u.id, u.full_name, u.username, u.role, u.status, u.last_login, u.login_status,
                    e.ID_filename AS photo,
                    e.department,
                    e.status AS employee_status
                FROM phphr_users u
                LEFT JOIN phphr_employees e 
                    ON TRIM(u.full_name) = CONCAT(TRIM(e.first_name), ' ', TRIM(e.last_name))
                ORDER BY u.id DESC";
        
        $result = mysqli_query($this->db_conn, $query);
        
        if (!$result) {
            die("SQL Error: " . mysqli_error($this->db_conn));
        }

        $rawUsers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $formattedUsers = [];

        foreach ($rawUsers as $u) {
            $nameParts = explode(' ', $u['full_name'], 2);
            $formattedUsers[] = [
                'id'           => $u['id'],
                'first_name'   => $nameParts[0] ?? '',
                'last_name'    => $nameParts[1] ?? '',
                'username'     => $u['username'],
                'role'         => strtoupper($u['role'] ?? 'user'), // Naka-uppercase para sa UI
                'status'       => $u['status'],
                'login_status' => $u['login_status'],
                'photo'        => $u['photo'] ?? '',
                'department'   => $u['department'] ?? 'No Department',
                'last_login'   => $u['last_login'] ?? '' 
            ];
        }

        $this->view('register_user/index', [
            'users'      => $formattedUsers,
            'successMsg' => $_SESSION['user_success'] ?? '',
            'errorMsg'   => $_SESSION['user_error'] ?? ''
        ]);
        unset($_SESSION['user_success'], $_SESSION['user_error']);
    }

    // 2. SHOW CREATE FORM
    public function create() {
        $employees = [];
        $query = "SELECT e.first_name, e.last_name, e.ID_filename 
                  FROM phphr_employees e
                  WHERE e.status = 1 
                  AND NOT EXISTS (
                      SELECT 1 FROM phphr_users u 
                      WHERE TRIM(u.full_name) = CONCAT(TRIM(e.first_name), ' ', TRIM(e.last_name))
                  )
                  ORDER BY e.last_name ASC";

        $result = mysqli_query($this->db_conn, $query); 
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $employees[] = $row;
            }
        }

        $this->view('register_user/create', [
            'employees' => $employees,
            'old' => $_SESSION['add_user_old'] ?? [],
            'errorMsg' => $_SESSION['add_user_error'] ?? ''
        ]);
        unset($_SESSION['add_user_error'], $_SESSION['add_user_old']);
    }

    // 3. STORE NEW USER



// --- RegisteruserController.php ---

public function store() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return $this->redirect('register_newuser.php?a=index');

    // 1. Kunin ang input mula sa $_POST
    $username  = trim($_POST['username'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $role      = strtolower(trim($_POST['role'] ?? 'hr'));

    // 2. DITO DAPAT I-DECLARE ANG $data (Bago ang line 116)
    $data = [
        'full_name'     => $firstName . ' ' . $lastName,
        'username'      => $username,
        'password_hash' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
        'role'          => $role,
        'status'        => 1,
        'login_status'  => 0
    ];

    // 3. LINE 116: Dito mo ipapasa ang $data sa Model
    if ($this->userModel->create($data)) { 
        $_SESSION['user_success'] = "Account successfully created!";
        return $this->redirect('register_newuser.php?a=index');
    } else {
        // ... error handling
    // KUKUNIN NATIN ANG TOTOONG ERROR DITO:
    $dbError = mysqli_error($this->db_conn);
    
    // I-save natin sa session para makita mo sa screen
    $_SESSION['add_user_error'] = 'Database Error: ' . $dbError;
    
    // O i-die natin para sigurado
    die("<h1>SQL Debug Info</h1>" . 
        "<b>Error:</b> " . $dbError . "<br>" .
        "<b>Data being sent:</b> <pre>" . print_r($data, true) . "</pre>");

    }
}
    

    // 4. SHOW EDIT FORM
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        $queryUser = "SELECT u.*, e.ID_filename 
                      FROM phphr_users u 
                      LEFT JOIN phphr_employees e ON TRIM(u.full_name) = CONCAT(TRIM(e.first_name), ' ', TRIM(e.last_name))
                      WHERE u.id = $id LIMIT 1";
        
        $resUser = mysqli_query($this->db_conn, $queryUser);
        $userRaw = mysqli_fetch_assoc($resUser);

        if (!$userRaw) { 
            $_SESSION['user_error'] = "User not found.";
            return $this->redirect('register_newuser.php?a=index');
        }

        $nameParts = explode(' ', $userRaw['full_name'], 2);
        $user = [
            'id'           => $userRaw['id'],
            'first_name'   => $nameParts[0] ?? '',
            'last_name'    => $nameParts[1] ?? '',
            'username'     => $userRaw['username'],
            'role'         => $userRaw['role'] ?? 'hr',
            'status'       => $userRaw['status'],
            'login_status' => $userRaw['login_status'],
            'photo'        => $userRaw['ID_filename'] ?? '',
            'last_login'   => $userRaw['last_login'] ?? ''
        ];

        $this->view('register_user/edit', [
            'user' => $user
        ]);
    }

    // 5. UPDATE USER
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return $this->redirect('register_newuser.php?a=index');

        $id = (int)$_POST['id'];
        $existing = $this->userModel->find($id);
        
        if (!$existing) {
            $_SESSION['user_error'] = 'Update failed: Account does not exist.';
            return $this->redirect('register_newuser.php?a=index');
        }

        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? ''; 
        $role = strtolower(trim($_POST['role'] ?? 'hr'));

        // Validation for roles
        $validRoles = ['admin', 'hr', 'it'];
        if (!in_array($role, $validRoles)) {
            $role = $existing['role']; // Fallback sa dating role kung invalid
        }
        
        $data = [
            'full_name' => $existing['full_name'], 
            'username'  => trim($_POST['username']),
            'status'    => (int)$_POST['status'],
            'role'      => $role
        ];

        if (!empty($password)) {
            if ($password !== $confirm_password) {
                $_SESSION['user_error'] = 'Password mismatch. Update cancelled.';
                return $this->redirect("register_newuser.php?a=edit&id=$id");
            }
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $data['password_hash'] = $existing['password_hash'];
        }

        if ($this->userModel->update($id, $data)) {
            $_SESSION['user_success'] = "Account details updated successfully.";
            return $this->redirect('register_newuser.php?a=index');
        }

        $_SESSION['user_error'] = 'System Error: Database update failed.';
        return $this->redirect("register_newuser.php?a=edit&id=$id");
    }

    // 6. DELETE USER
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);

        // Security check: cannot delete self
        if ($id === (int)($_SESSION['user_id'] ?? 0)) {
            $_SESSION['user_error'] = "Action denied: You cannot delete your own account.";
            return $this->redirect('register_newuser.php?a=index');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['user_error'] = "Delete failed: Account not found.";
            return $this->redirect('register_newuser.php?a=index');
        }

        if ($this->userModel->delete($id)) {
            $_SESSION['user_success'] = "Account '{$user['username']}' permanently removed.";
        } else {
            $_SESSION['user_error'] = "Critical: Database deletion failed.";
        }

        return $this->redirect('register_newuser.php?a=index');
    }
}