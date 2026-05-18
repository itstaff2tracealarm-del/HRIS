<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Employee.php';

class EmployeesController extends Controller
{
    protected $employee;
    protected $mssql;

    public function __construct($mysql_conn, $mssql_conn = null) {
        parent::__construct($mysql_conn);

        $this->employee = new Employee($mysql_conn);
        $this->mssql = $mssql_conn;

        if (empty($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    public function index() {
        $employees = $this->employee->all();

        $falcoEmployees = [];

        if($this->mssql){
            $sql = "SELECT TOP 100 CardNo, StaffNo, Name, Department FROM CardDB";
            $stmt = sqlsrv_query($this->mssql, $sql);

            if($stmt){
                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                    $falcoEmployees[] = $row;
                }
            }
        }

        $this->view('employees/index', [
            'employees' => $employees,
            'falcoEmployees' => $falcoEmployees
        ]);
    }

    public function archived() {
        $employees = $this->employee->getDeleted();

        $this->view('employees/delete', [
            'employees' => $employees
        ]);
    }

    // ======================================================
    // CREATE & STORE & AJAX VALIDATION
    // ======================================================
    
    /*** AJAX Method para sa Live Duplicate Checking***/
    public function check_duplicate() {
        // I-set ang header para sa JSON response
        header('Content-Type: application/json');

        $fname = trim($_POST['first_name'] ?? '');
        $lname = trim($_POST['last_name'] ?? '');
        $bdate = $_POST['date_of_birth'] ?? null;

        $exists = false;
        if (!empty($fname) && !empty($lname) && !empty($bdate)) {
            // Tatawagin nito yung 'exists' method sa Employee.php model mo
            $exists = $this->employee->exists($fname, $lname, $bdate);
        }

        echo json_encode(['exists' => $exists]);
        exit; // Mahalaga ito para hindi mag-render ang view
    }

    public function create() {
        $errorMsg   = $_SESSION['add_employee_error'] ?? '';
        $successMsg = $_SESSION['add_employee_success'] ?? '';
        $old        = $_SESSION['add_employee_old'] ?? [];

        unset($_SESSION['add_employee_error'], $_SESSION['add_employee_success'], $_SESSION['add_employee_old']);

        $this->view('employees/create', [
            'errorMsg'   => $errorMsg,
            'successMsg' => $successMsg,
            'old'        => $old
        ]);
    }

    public function store() {
        // var_dump($_POST); die();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('employees.php?a=create');
        }
        $cardID = $_POST['CardID'] ?? null;
        $fname = trim($_POST['first_name'] ?? '');
        $lname = trim($_POST['last_name'] ?? '');
        $bdate = $_POST['date_of_birth'] ?? null;
        $tassiID = trim($_POST['employee_code'] ?? '');

        // Double check sa server-side bago i-save
        if ($this->employee->exists($fname, $lname, $bdate)) {
            $_SESSION['add_employee_error'] = 'USER ALREADY EXISTS! (Name and Birthdate match found)';
            $_SESSION['add_employee_old'] = $_POST;
            return $this->redirect('employees.php?a=create');
        }

        $fileName = null;
        if (isset($_FILES['ID_filename']) && $_FILES['ID_filename']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/profile/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }

            $fileExtension = pathinfo($_FILES['ID_filename']['name'], PATHINFO_EXTENSION);
            $fileName = str_replace(' ', '', $lname) . "_" . str_replace(' ', '', $fname) . "_" . time() . "." . $fileExtension;
            move_uploaded_file($_FILES['ID_filename']['tmp_name'], $uploadDir . $fileName);
        }

        $data = [
            'CardID'                 =>$_POST['CardID']?? null,
            'user_id'                => $_SESSION['user_id'],
            'employee_code'          => $tassiID,
            'first_name'             => $fname,
            'middle_name'            => trim($_POST['middle_name'] ?? ''),
            'last_name'              => $lname,
            'date_of_birth'          => $bdate,
            'gender'                 => $_POST['gender'] ?? null,
            'civil_status'           => $civil_status,
            'nationality'            => $_POST['nationality'] ?? null,
            'religion'               => $religion,
            'phone'                  => trim($_POST['phone'] ?? ''),
            'email_address'          => trim($_POST['email_address'] ?? ''),
            'current_address'        => trim($_POST['current_address'] ?? ''),
            'emergency_name'         => trim($_POST['emergency_name'] ?? ''),
            'emergency_number'       => trim($_POST['emergency_number'] ?? ''),
            'emergency_relationship' => $_POST['emergency_relationship'] ?? null,
            'department'             => trim($_POST['department'] ?? ''),
            'designation'            => trim($_POST['designation'] ?? ''),
            'date_of_joining'        => $_POST['date_of_joining'] ?? null,
            'probation_end_date'     => $_POST['probation_end_date'] ?? null,
            'employment_type'        => $_POST['employment_type'] ?? null,
            'reporting_manager'      => trim($_POST['reporting_manager'] ?? ''),
            'work_location'          => $_POST['work_location'] ?? null,
            'salary'                 => $_POST['salary'] ?? 0,
            'hmo'                    => $_POST['hmo'] ?? 0,
            'ef'                     => $_POST['ef'] ?? 0,
            'status'                 => (int)($_POST['status'] ?? 1),
            'time_in'                => $_POST['time_in'],
            'time_out'               => $_POST['time_out'],
            'ID_filename'            => $fileName
    
        ];

try {
    if ($this->employee->create($data)) {
        // I-set ang success message na may pangalan ng employee
        $_SESSION['success_message'] = strtoupper("Employee record for " . $fname . " " . $lname . " has been added successfully!");
        
        // Panatilihin ang legacy session key kung kailangan sa ibang parts ng system
        $_SESSION['add_employee_success'] = 'Employee added successfully.';

        /**
         * REDIRECT TO CREATE: 
         * Binago natin mula 'index' patungong 'create' para pagka-save, 
         * lalabas ang success toast sa itaas ng malinis na form.
         */
        return $this->redirect('employees.php?a=create'); 
        
    } else {
        throw new Exception("Model failed to save data.");
    }
} catch (mysqli_sql_exception $e) {
    // Kapag duplicate ang TASSI ID (Duplicate entry error code: 1062)
    $_SESSION['add_employee_error'] = ($e->getCode() == 1062) 
        ? "TASSI ID NUMBER ALREADY EXISTS! ($tassiID)" 
        : "Database Error: " . $e->getMessage();
    
    // Ibalik ang lumang input para hindi na i-type lahat ulit ng HR
    $_SESSION['add_employee_old'] = $_POST;
    return $this->redirect('employees.php?a=create');

} catch (Exception $e) {
    $_SESSION['add_employee_error'] = "Error: " . $e->getMessage();
    $_SESSION['add_employee_old'] = $_POST;
    return $this->redirect('employees.php?a=create');
}
    }

    // ======================================================
    // PROFILE & EDIT & UPDATE
    // ======================================================

    public function profile() {
        $id = $_GET['id'] ?? 0;
        if (!$id) return $this->redirect('employees.php?a=index');

        $employee = $this->employee->find($id);
        if (!$employee) {
            $_SESSION['employee_error'] = 'Employee not found.';
            return $this->redirect('employees.php?a=index');
        }
        return $this->view('employees/view', ['employee' => $employee]);
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        if (!$id) return $this->redirect('employees.php?a=index');

        $employee = $this->employee->find($id);
        if (!$employee) return $this->redirect('employees.php?a=index');

        $errorMsg   = $_SESSION['edit_employee_error'] ?? '';
        $successMsg = $_SESSION['edit_employee_success'] ?? '';
        unset($_SESSION['edit_employee_error'], $_SESSION['edit_employee_success']);

        $this->view('employees/edit', [
            'employee'   => $employee,
            'errorMsg'   => $errorMsg,
            'successMsg' => $successMsg
        ]);
    }


  public function update() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return $this->redirect('employees.php?a=index');

    $id = $_POST['id'] ?? 0;
    if (!$id) return $this->redirect('employees.php?a=index');

// 1. Kunin muna ang lumang filename para kung walang bagong in-upload, hindi mabura
$currentEmployee = $this->employee->find($id);
$fileName = $currentEmployee['ID_filename'] ?? null;

// 2. I-check kung may in-upload na bagong file (dapat 'profile_img' ang gamit)
if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
    // Gamitin ang path na sinabi mo
    $uploadDir = __DIR__ . '/../../public/uploads/profile/'; 
    
    $fileExtension = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);
    $newFileName = str_replace(' ', '', trim($_POST['last_name'])) . "_" . str_replace(' ', '', trim($_POST['first_name'])) . "_" . time() . "." . $fileExtension;
    
    if (move_uploaded_file($_FILES['profile_img']['tmp_name'], $uploadDir . $newFileName)) {
        // Burahin ang lumang file kung meron man para hindi makalat
        if ($fileName && file_exists($uploadDir . $fileName)) { 
            @unlink($uploadDir . $fileName); 
        }
        $fileName = $newFileName; 
    }
}

    // 🔥 IMPORTANT: Dapat TUGMA ang pagkakasunod-sunod dito sa bind_param ng Model mo
    $data = [
        'employee_code'          => trim($_POST['employee_code'] ?? ''),
        'CardID'                 => trim($_POST['CardID'] ?? ''), 
        'first_name'             => trim($_POST['first_name'] ?? ''),
        'middle_name'            => trim($_POST['middle_name'] ?? ''),
        'last_name'              => trim($_POST['last_name'] ?? ''),
        'date_of_birth'          => $_POST['date_of_birth'] ?? null,
        'gender'                 => $_POST['gender'] ?? null,
        'civil_status'           => $_POST['civil_status'] ?? null,
        'nationality'            => $_POST['nationality'] ?? null,
        'religion'               => trim($_POST['religion'] ?? ''),
        'phone'                  => trim($_POST['phone'] ?? ''),
        'email_address'          => trim($_POST['email_address'] ?? ''),
        'current_address'        => trim($_POST['current_address'] ?? ''),
        'emergency_name'         => trim($_POST['emergency_name'] ?? ''),
        'emergency_number'       => trim($_POST['emergency_number'] ?? ''),
        'emergency_relationship' => $_POST['emergency_relationship'] ?? null,
        'department'             => trim($_POST['department'] ?? ''),
        'designation'            => trim($_POST['designation'] ?? ''),
        'date_of_joining'        => $_POST['date_of_joining'] ?? null,
        'probation_end_date'     => $_POST['probation_end_date'] ?? null,
        'employment_type'        => $_POST['employment_type'] ?? null,
        'reporting_manager'      => trim($_POST['reporting_manager'] ?? ''),
        'work_location'          => $_POST['work_location'] ?? null,
        'salary'                 => $_POST['salary'] ?? 0,
        'hmo'                    => $_POST['hmo'] ?? 0,
        'ef'                     => $_POST['ef'] ?? 0,
        'status'                 => (int)($_POST['status'] ?? 1),
        'time_in'                => $_POST['time_in'],
        'time_out'               => $_POST['time_out'],
        'ID_filename'            => $fileName 
    ];

if ($this->employee->update($id, $data)) {
        // Mag-set ng Session para sa Success message
        $_SESSION['edit_employee_success'] = 'Employee record updated successfully!';
        
        // Mag-redirect pabalik sa edit page na may msg=success sa URL
        return $this->redirect('employees.php?a=edit&id=' . $id . '&msg=success');
    } else {
        $_SESSION['edit_employee_error'] = 'Error updating employee.';
        return $this->redirect('employees.php?a=edit&id=' . $id);
    }
    }

    // ======================================================
    // DELETE & RESTORE ACTIONS
    // ======================================================

    public function delete() {
        $id = $_GET['id'] ?? 0;
        if ($id && $this->employee->delete($id)) {
            $_SESSION['employee_success'] = 'Employee moved to archived list.';
        } else {
            $_SESSION['employee_error'] = 'Error archiving employee.';
        }
        return $this->redirect('employees.php?a=index');
    }

    public function restore() {
        $id = $_GET['id'] ?? 0;
        if ($id && $this->employee->restore($id)) {
            $_SESSION['employee_success'] = 'Employee restored successfully.';
        } else {
            $_SESSION['employee_error'] = 'Error restoring employee.';
        }
        return $this->redirect('employees.php?a=archived');
    }

    public function force_delete() {
        $id = $_GET['id'] ?? 0;
        if ($id && $this->employee->forceDelete($id)) {
            $_SESSION['employee_success'] = 'Employee permanently deleted.';
        } else {
            $_SESSION['employee_error'] = 'Error permanently deleting employee.';
        }
        return $this->redirect('employees.php?a=archived');
    }


    public function link_falco() {
        // Kunin ang ID ng employee at ang bagong Falco Card No mula sa form
        $id = $_POST['id'] ?? 0; 
        $falcoCardNo = $_POST['falco_card_no'] ?? '';

        if(!$id || !$falcoCardNo){
            $_SESSION['employee_error'] = "Missing ID or Falco Card Number.";
            return $this->redirect('employees.php');
        }

        // 1. CHECK DUPLICATE: Siguraduhin na walang ibang employee na gumagamit na ng Falco Card na ito
        // Gagamitin natin ang 'CardID' na column sa database mo
        $check = $this->mysql->prepare("SELECT id FROM employees WHERE CardID = ? AND id != ?");
        $check->bind_param("si", $falcoCardNo, $id);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            $_SESSION['employee_error'] = "This Falco Card is already linked to another employee!";
            return $this->redirect('employees.php?a=edit&id=' . $id);
        }
        $check->close();

        // 2. UPDATE: I-save ang Falco Card Number sa 'CardID' column ng employee
        $stmt = $this->mysql->prepare("
            UPDATE employees 
            SET CardID = ?, sync_status = 'merged'
            WHERE id = ?
        ");

        $stmt->bind_param("si", $falcoCardNo, $id);
        
        if($stmt->execute()){
            $_SESSION['employee_success'] = "Employee CardID updated to $falcoCardNo successfully!";
        } else {
            $_SESSION['employee_error'] = "Update failed: " . $this->mysql->error;
        }

        $stmt->close();
        return $this->redirect('employees.php?a=edit&id=' . $id);
    }

    public function falcobiolist() {
        $falcoEmployees = [];

        if($this->mssql){
            // Kunin ang data mula sa Biometric (MSSQL)
            $sql = "SELECT CardNo, StaffNo, Name, Department FROM CardDB";
            $stmt = sqlsrv_query($this->mssql, $sql);
    
            if($stmt){
                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                    $falcoEmployees[] = $row;
                }
            }
        }

        // Siguraduhin na ang file na ito ay nage-exist:
        // views/employees/falcobiolist.php
        $this->view('employees/falcobiolist', [
            'falcoEmployees' => $falcoEmployees
        ]);
    }


    public function getMergedData() {
        $map = [];
        $merged = [];

        // =========================
        // 🔥 MSSQL (Falco)
        // =========================
        $sql = "
            SELECT 
                CardNo,
                MAX(TrName) as name,
                MIN(CASE WHEN [Transaction] = 'Valid Entry Access' THEN TrDateTime END) as time_in,
                MAX(CASE WHEN [Transaction] = 'Valid Exit Access' THEN TrDateTime END) as time_out
            FROM tblTransactionLive
            GROUP BY CardNo
        ";

        $stmt = sqlsrv_query($this->mssql, $sql);

        if ($stmt === false) {
            echo json_encode(["error" => sqlsrv_errors()]);
            return;
        }

        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

            $map[$r['CardNo']] = [
                "name" => $r['name'] ?? '',
                "time_in" => ($r['time_in'] instanceof DateTime) ? $r['time_in']->format('Y-m-d H:i:s') : null,
                "time_out" => ($r['time_out'] instanceof DateTime) ? $r['time_out']->format('Y-m-d H:i:s') : null
            ];
        }

        // =========================
        // 🔥 MYSQL (Local)
        // =========================
        // $res = $this->conn->query("SELECT * FROM field_attendance");
        $res = $this->mysql->query("SELECT * FROM field_attendance");

        if (!$res) {
            echo json_encode(["error" => $this->mysql->error]);
            return;
        }

        while ($row = $res->fetch_assoc()) {

            $card = $row['card_no']; // ✔ tama na

            $merged[] = [
                "card_no"   => $card,
                "name"      => $map[$card]['name'] ?? 'UNKNOWN',
                "local_in"  => $row['time_in'],
                "local_out" => $row['time_out'],
                "falco_in"  => $map[$card]['time_in'] ?? null,
                "falco_out" => $map[$card]['time_out'] ?? null
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($merged);
    }

    public function falco_compare() {
        $this->view('employees/falco_compare');
    }
}