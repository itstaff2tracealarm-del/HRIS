<?php

class DashboardController
{
    protected $mysql;
    protected $mssql;

    public function __construct($mysql_conn, $mssql_conn = null)
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        $this->mysql = $mysql_conn;
        $this->mssql = $mssql_conn;

        if (empty($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    /* =========================
       GENERIC MSSQL COUNT
    ========================= */

    protected function mssqlCount($sql)
    {
        if(!$this->mssql) return 0;

        $stmt = sqlsrv_query($this->mssql, $sql);
        if(!$stmt) return 0;

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

        return $row ? (int)$row[0] : 0;
    }

    /* =========================
       DASHBOARD DATA FUNCTIONS
    ========================= */

    protected function getTotalEmployees()
    {
        return $this->mssqlCount("SELECT COUNT(*) FROM CardDB");
    }

    protected function getTodayAttendance($today)
    {
        return $this->mssqlCount("
            SELECT COUNT(DISTINCT CardNo)
            FROM tblTransactionLive
            WHERE CAST(TrDateTime AS DATE) = '$today'
        ");
    }

    protected function getEmployeesInside($today)
    {
        return $this->mssqlCount("
            SELECT COUNT(DISTINCT CardNo)
            FROM tblTransactionLive
            WHERE CAST(TrDateTime AS DATE) = '$today'
            AND [Transaction]='Valid Entry Access'
        ");
    }

    protected function getTotalPayroll()
    {
        if(!$this->mysql) return 0;

        $result = $this->mysql->query("SELECT COUNT(*) FROM employees");

        if($result){
            $row = $result->fetch_row();
            return $row[0] ?? 0;
        }

        return 0;
    }

    protected function getRecentLogs()
    {
        if(!$this->mssql) return null;

        $sql = "
            SELECT TOP 10
            c.Name,
            t.TrDateTime,
            t.TrController
            FROM tblTransactionLive t
            INNER JOIN CardDB c ON t.CardNo=c.CardNo
            ORDER BY TrDateTime DESC
        ";

        return sqlsrv_query($this->mssql, $sql);
    }

    /* =========================
       MAIN DASHBOARD
    ========================= */

    public function index()
    {
        $today = date('Y-m-d');

        // 🔥 CLEAN DATA CALLS
        $totalEmployees   = $this->getTotalEmployees();
        $todayAttendance  = $this->getTodayAttendance($today);
        $employeesInside  = $this->getEmployeesInside($today);
        $totalPayroll     = $this->getTotalPayroll();
        $logs             = $this->getRecentLogs();

        include __DIR__ . '/../views/dashboard/index.php';
    }
}