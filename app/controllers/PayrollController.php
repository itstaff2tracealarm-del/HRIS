<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Payroll.php';
require_once __DIR__ . '/../models/Employee.php';
require_once __DIR__ . '/../models/mergeAttendanceModel.php';

class PayrollController extends Controller
{
    protected $payroll;
    protected $employee;
    protected $attendanceModel;

    public function __construct($conn)
    {
        parent::__construct($conn);
        
        // Kunin ang global MSSQL connection mula sa iyong mssql.php config
        global $mssql_conn; 

        // FIX: I-pasa ang dalawang koneksyon sa Payroll Model (MySQL, MSSQL)
        $this->payroll  = new Payroll($conn, $mssql_conn);
        $this->employee = new Employee($conn);

        // I-initialize ang attendance model para sa merging ng data
        $this->attendanceModel = new mergeAttendanceModel($conn, $mssql_conn); 

        if (empty($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    public function index()
    {
        $records = $this->payroll->all();
        $successMsg = $_SESSION['payroll_success'] ?? '';
        $errorMsg   = $_SESSION['payroll_error'] ?? '';
        unset($_SESSION['payroll_success'], $_SESSION['payroll_error']);

        $this->view('payroll/index', [
            'records'    => $records,
            'successMsg' => $successMsg,
            'errorMsg'   => $errorMsg
        ]);
    }

    public function create()
    {
        // 1. Kunin ang parameters mula sa URL (GET data)
        $card_no = $_GET['card_no'] ?? null;
        $start   = $_GET['start'] ?? null;
        $end     = $_GET['end'] ?? null;

        $empDetails = null;
        $totalDays  = 0;

        // 2. Kung may card_no, gamitin ang mabilis na computation
        if ($card_no && $start && $end) {
            $empDetails = $this->attendanceModel->findByCardNo($card_no);
            
            if ($empDetails) {
                // Gamitin ang bagong computeAttendance method para sa mabilis na result
                $totalDays = $this->attendanceModel->computeAttendance($card_no, $start, $end);
            }
        }

        $errorMsg = $_SESSION['add_payroll_error'] ?? '';
        unset($_SESSION['add_payroll_error']);

        // 3. I-pass ang lahat ng data sa view
        $this->view('payroll/create', [
            'errorMsg'     => $errorMsg,
            'employees'    => $this->payroll->allActive(), 
            'emp'          => $empDetails,                
            'computedDays' => $totalDays,                 
            'startDate'    => $start,
            'endDate'      => $end
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('payroll.php?a=create');
        }

        // --- 1. DATA COLLECTION ---
        $data = [
            'employee_id'   => $_POST['employee_id'] ?? '',
            'card_no'       => $_POST['card_no'] ?? '', 
            'basic_rate'    => (float)($_POST['basic_rate'] ?? 0),
            'allowances'    => (float)($_POST['allowances'] ?? 0),
            'deductions'    => (float)($_POST['deductions'] ?? 0),
            'net_salary'    => (float)($_POST['net_salary'] ?? 0),
            'period_start'  => $_POST['period_start'] ?? '',
            'period_end'    => $_POST['period_end'] ?? '',
            'payment_date'  => $_POST['payment_date'] ?? '',
            'frequency'     => $_POST['frequency'] ?? 'SEMI-MONTHLY',
            'regular_days'  => (float)($_POST['regular_days'] ?? 0),
            'payslip_path'  => $_POST['payslip_path'] ?? null
        ];

        // --- 2. VALIDATION ---
        if (!$data['employee_id'] || !$data['period_start'] || !$data['period_end']) {
            $_SESSION['add_payroll_error'] = 'All dates and employee selection are required.';
            return $this->redirect('payroll.php?a=create');
        }

        // --- 3. EXECUTION ---
        if ($this->payroll->create($data)) {
            $_SESSION['payroll_success'] = 'Payroll generated successfully.';
            return $this->redirect('payroll.php?a=index');
        }

        $_SESSION['payroll_error'] = 'Error saving payroll to database.';
        return $this->redirect('payroll.php?a=index');
    }

    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        if (!$id) return $this->redirect('payroll.php?a=index');

        $payroll = $this->payroll->find($id);
        if (!$payroll) {
            $_SESSION['payroll_error'] = 'Record not found.';
            return $this->redirect('payroll.php?a=index');
        }

        $this->view('payroll/edit', [
            'payroll'   => $payroll,
            'employees' => $this->employee->allActive(),
            'errorMsg'  => $_SESSION['edit_payroll_error'] ?? ''
        ]);
        unset($_SESSION['edit_payroll_error']);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('payroll.php?a=index');
        }

        $id = (int)($_POST['id'] ?? 0);
        $data = [
            'card_no'       => $_POST['card_no'] ?? $_POST['CardID'] ?? '',
            'employee_id'   => $_POST['employee_id'],
            'period_start'  => $_POST['period_start'],
            'period_end'    => $_POST['period_end'],
            'payment_date'  => $_POST['payment_date'],
            'frequency'     => $_POST['frequency'],
            'basic_rate'    => (float)$_POST['basic_rate'],
            'regular_days'  => (float)$_POST['regular_days'],
            'allowances'    => (float)$_POST['allowances'],
            'deductions'    => (float)$_POST['deductions'],
            'net_salary'    => (float)$_POST['net_salary']
        ];

        if ($this->payroll->update($id, $data)) {
            $_SESSION['payroll_success'] = 'Payroll updated successfully.';
            return $this->redirect('payroll.php?a=index');
        }
        
        $_SESSION['edit_payroll_error'] = 'Error updating record.';
        return $this->redirect('payroll.php?a=edit&id=' . $id);
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id && $this->payroll->delete($id)) {
            $_SESSION['payroll_success'] = 'Record deleted.';
        } else {
            $_SESSION['payroll_error'] = 'Delete failed.';
        }
        return $this->redirect('payroll.php?a=index');
    }

    public function updateStatus()
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $status = $_GET['status'] ?? $_POST['status'] ?? 'Pending';        
        header('Content-Type: application/json');
        echo json_encode(['success' => $this->payroll->updateStatus($id, $status)]);
        exit; 
    }
}