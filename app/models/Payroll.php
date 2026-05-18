<?php

class Payroll
{
    protected $conn; // Ito ang gagamitin ng MySQLi (all, find, create, etc.)
    protected $table = 'phphr_payroll';
    private $mysql;  // Para sa computeAttendance
    private $mssql;  // Para sa MSSQL query

    public function __construct($mysql_conn, $mssql_conn) {
        $this->mysql = $mysql_conn;
        $this->mssql = $mssql_conn;
        // SOLUTION: I-assign ang mysql_conn sa conn property 
        // para gumana ang $this->conn->query() at prepare()
        $this->conn = $mysql_conn; 
    }

    // ALL PAYROLLS - Kasama ang Employee Details
    public function all() {
        $sql = "SELECT p.*, e.CardID as card_no, e.first_name, e.last_name, e.middle_name, e.employee_code, e.department, e.phone 
                FROM {$this->table} p
                LEFT JOIN phphr_employees e ON p.employee_id = e.id
                ORDER BY p.id DESC";
        
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // FETCH ACTIVE EMPLOYEES
    public function allActive() {
        $sql = "SELECT id, CardID as card_no, employee_code, first_name, last_name, middle_name, 
                       department, designation, status, date_of_joining, salary, hmo, ef 
                FROM phphr_employees 
                WHERE status = 1 
                ORDER BY last_name ASC";
                
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // FIND - Ginagamit sa Edit/View/Payslip
    public function find($id)
    {
        $sql = "SELECT p.*, e.CardID as card_no, e.phone, e.first_name, e.last_name, e.employee_code, e.department, e.designation
                FROM {$this->table} p 
                LEFT JOIN phphr_employees e ON p.employee_id = e.id 
                WHERE p.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // CREATE - Inayos ang mismatch sa placeholders at bind_param
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (employee_id, card_no, basic_rate, allowances, deductions, net_salary, 
                 period_start, period_end, payment_date, frequency, regular_days, 
                 status, payslip_path)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            'isddddssssds', 
            $data['employee_id'],   
            $data['card_no'],      
            $data['basic_rate'],    
            $data['allowances'],    
            $data['deductions'],    
            $data['net_salary'],    
            $data['period_start'],  
            $data['period_end'],    
            $data['payment_date'],  
            $data['frequency'],     
            $data['regular_days'],  
            $data['payslip_path']   
        );

        return $stmt->execute();
    }

    // UPDATE
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table}
                SET employee_id=?, card_no=?, period_start=?, period_end=?, payment_date=?, frequency=?, 
                    basic_rate=?, regular_days=?, allowances=?, deductions=?, net_salary=?
                WHERE id=?";

        $stmt = $this->conn->prepare($sql);
        
        $stmt->bind_param(
            'isssssdddddi', 
            $data['employee_id'],
            $data['card_no'],
            $data['period_start'],
            $data['period_end'],
            $data['payment_date'],
            $data['frequency'],
            $data['basic_rate'],
            $data['regular_days'],
            $data['allowances'],
            $data['deductions'],
            $data['net_salary'],
            $id
        );

        return $stmt->execute();
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }


    public function computeAttendance($card_no, $startDate, $endDate) {
        $uniqueDates = [];

        // --- HAKBANG 1: Kumuha sa MySQL (Field Logs) ---
        $sql_mysql = "SELECT DISTINCT DATE(time_in) as log_date 
                      FROM field_attendance 
                      WHERE card_no = ? AND DATE(time_in) BETWEEN ? AND ?";
        
        $stmt = mysqli_prepare($this->mysql, $sql_mysql);
        mysqli_stmt_bind_param($stmt, "sss", $card_no, $startDate, $endDate);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $uniqueDates[$row['log_date']] = true;
        }

        // --- HAKBANG 2: Kumuha sa MSSQL (Office Logs) ---
        $sql_mssql = "SELECT DISTINCT CAST(TrDateTime AS DATE) as log_date 
                      FROM tblTransactionLive 
                      WHERE CardNo = ? AND CAST(TrDateTime AS DATE) BETWEEN ? AND ?";
        
        $params = array($card_no, $startDate, $endDate);
        $stmt_mssql = sqlsrv_query($this->mssql, $sql_mssql, $params);

        if ($stmt_mssql !== false) {
            while ($row = sqlsrv_fetch_array($stmt_mssql, SQLSRV_FETCH_ASSOC)) {
                $date_str = $row['log_date'] instanceof DateTime ? 
                            $row['log_date']->format('Y-m-d') : $row['log_date'];
                $uniqueDates[$date_str] = true;
            }
        }

        return count($uniqueDates);
    }
}

/** * AJAX Handler para sa Image Saving 
 */
if (isset($_GET['a']) && $_GET['a'] == 'save_image') {
    header('Content-Type: application/json');
    if (isset($_POST['image']) && isset($_POST['filename'])) {
        $data = $_POST['image'];
        $filename = $_POST['filename'];

        $image_parts = explode(";base64,", $data);
        if (isset($image_parts[1])) {
            $image_base64 = base64_decode($image_parts[1]);
            $folderPath = "C:/xampp/htdocs/phphr-main/phphr-main/public/uploads/payslips/";
            
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $file = $folderPath . $filename;
            
            if (file_put_contents($file, $image_base64)) {
                echo json_encode(['success' => true, 'filename' => $filename]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to write file to disk.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid image data.']);
        }
    }
    exit;
}