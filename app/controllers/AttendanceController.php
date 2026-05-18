<?php
/* =======================================================================
 PHPHR — Optimized Attendance Controller
 Last Update: 2026-03-30
=======================================================================
*/
    
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Employee.php';

class AttendanceController extends Controller
{
    protected $attendance;
    protected $employee;

    public function __construct($conn)
    {
        parent::__construct($conn);
        $this->attendance = new Attendance($conn);
        $this->employee   = new Employee($conn);

        
        // Auth Check
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    /**
     * Display Attendance Registry
     */
    public function index()
    {
        // Kunin ang lahat ng records (with JOINS from Model)
        $records = $this->attendance->all();

        // Flash Messages
        $successMsg = $this->getFlash('attendance_success');
        $errorMsg   = $this->getFlash('attendance_error');

        $this->view('attendance/index', [
            'records'    => $records,
            'successMsg' => $successMsg,
            'errorMsg'   => $errorMsg
        ]);
    }

    /**
     * Store New Attendance
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('attendance.php');
        }

        // Clean and Map Data
        $data = [
            'employee_id'     => filter_input(INPUT_POST, 'employee_id', FILTER_VALIDATE_INT),
            'attendance_date' => $_POST['attendance_date'] ?? date('Y-m-d'),
            'check_in'        => !empty($_POST['check_in']) ? $_POST['check_in'] : null,
            'check_out'       => !empty($_POST['check_out']) ? $_POST['check_out'] : null,
            'status'          => $_POST['status'] ?? 'present'
        ];

        // Validation
        if (!$data['employee_id'] || !$data['attendance_date']) {
            $this->setFlash('attendance_error', 'Invalid Employee or Date.');
            return $this->redirect('attendance.php?a=create');
        }

        if ($this->attendance->create($data)) {
            $this->setFlash('attendance_success', 'Attendance log saved successfully.');
        } else {
            $this->setFlash('attendance_error', 'Database error: Could not save attendance.');
        }

        return $this->redirect('attendance.php');
    }

    /**
     * Delete Attendance Record
     */
    public function delete()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id && $this->attendance->delete($id)) {
            $this->setFlash('attendance_success', 'Record deleted successfully.');
        } else {
            $this->setFlash('attendance_error', 'Failed to delete record.');
        }

        return $this->redirect('attendance.php');
    }

    // Helper functions para sa mas malinis na session handling
    private function setFlash($key, $message) {
        $_SESSION[$key] = $message;
    }

    private function getFlash($key) {
        $message = $_SESSION[$key] ?? '';
        unset($_SESSION[$key]);
        return $message;
    }
}