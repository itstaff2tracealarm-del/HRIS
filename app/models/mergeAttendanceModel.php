<?php

class mergeAttendanceModel {
    private $mysql;
    private $mssql;

    /**
     * @param mysqli $mysql_conn  - Connection para sa MySQL (Field Database)
     * @param resource $mssql_conn - Connection para sa MSSQL (Office Database)
     */
    public function __construct($mysql_conn, $mssql_conn) {
        $this->mysql = $mysql_conn;
        $this->mssql = $mssql_conn;
    }

    /**
     * Kukuha ng attendance mula sa MySQL (Field) 
     * Kasama ang FirstName at LastName mula sa phphr_employees table
     */
public function getMySQLData() {
    $data = [];
    // Palitan ang FirstName/LastName ng first_name/last_name
    $sql = "SELECT 
                a.card_no, 
                a.time_in, 
                a.time_out, 
                e.first_name, 
                e.last_name 
            FROM field_attendance a
            LEFT JOIN phphr_employees e ON a.card_no = e.CardID"; 
    
    $res = mysqli_query($this->mysql, $sql);
    
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    }
    return $data;
}

    /**
     * Kukuha ng attendance mula sa MSSQL (Office)
     * Kukunin ang pinaka-unang tapik (FirstIn) at huling tapik (LastOut) kada araw
     */
    public function getMSSQLData() {
        $data = [];
        
        // Puna: Nilagyan natin ng [ ] ang [Transaction] dahil reserved keyword ito sa MSSQL
        $sql = "SELECT 
                    CardNo, 
                    CAST(TrDateTime AS DATE) as LogDate,
                    MIN(CASE WHEN [Transaction] = 'Valid Entry Access' THEN TrDateTime END) as FirstIn,
                    MAX(CASE WHEN [Transaction] = 'Valid Exit Access' THEN TrDateTime END) as LastOut
                FROM tblTransactionLive 
                WHERE [Transaction] IN ('Valid Entry Access', 'Valid Exit Access')
                GROUP BY CardNo, CAST(TrDateTime AS DATE)
                ORDER BY LogDate DESC";

        $stmt = sqlsrv_query($this->mssql, $sql);
        
        if ($stmt === false) {
            // Debugging: I-uncomment ito kung may error sa query execution
            // die(print_r(sqlsrv_errors(), true));
            return [];
        }
        
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Kinukuha ang lahat ng employees para magamit sa pag-match 
     * ng pangalan para sa MSSQL data sa loob ng Controller
     */
    public function getAllEmployees() {
        $employees = [];
        $sql = "SELECT CardID, first_name, last_name FROM phphr_employees";
        $res = mysqli_query($this->mysql, $sql);
        
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                // Ginagawa nating key ang CardID para madaling hanapin sa Controller
                $employees[$row['CardID']] = $row['first_name'] . ' ' . $row['last_name'];
            }
        }
        return $employees;
    }
public function findByCardNo($card_no) {
    // Dahil mysqli ang gamit sa model na ito ($this->mysql), 
    // gagamit tayo ng mysqli_prepare para safe sa SQL Injection.
    $sql = "SELECT * FROM phphr_employees WHERE CardID = ? LIMIT 1";
    
    $stmt = mysqli_prepare($this->mysql, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $card_no); // "s" means string
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $data;
    }
    
    return null;
}
public function get_individual_merge($card_id, $start, $end) {
    $combined = [];

    // 1. KUNIN ANG LOGS MULA SA MYSQL (Field)
    $sql_mysql = "SELECT 
                    time_in as full_datetime, 
                    time_in, 
                    time_out, 
                    'FIELD/MANUAL' as source 
                  FROM field_attendance 
                  WHERE card_no = ? 
                  AND DATE(time_in) BETWEEN ? AND ?";
    
    $stmt_mysql = mysqli_prepare($this->mysql, $sql_mysql);
    if ($stmt_mysql) {
        mysqli_stmt_bind_param($stmt_mysql, "sss", $card_id, $start, $end);
        mysqli_stmt_execute($stmt_mysql);
        $res_mysql = mysqli_stmt_get_result($stmt_mysql);
        while ($row = mysqli_fetch_assoc($res_mysql)) {
            $combined[] = [
                'date'     => date('Y-m-d', strtotime($row['time_in'])),
                'time_in'  => date('H:i:s', strtotime($row['time_in'])),
                'time_out' => (!empty($row['time_out']) && $row['time_out'] != '0000-00-00 00:00:00') ? date('H:i:s', strtotime($row['time_out'])) : '--:--',
                'source'   => $row['source']
            ];
        }
        mysqli_stmt_close($stmt_mysql);
    }

    // 2. KUNIN ANG LOGS MULA SA MSSQL (Office)
    $sql_mssql = "SELECT 
                    CAST(TrDateTime AS DATE) as LogDate,
                    MIN(CASE WHEN [Transaction] = 'Valid Entry Access' THEN TrDateTime END) as FirstIn,
                    MAX(CASE WHEN [Transaction] = 'Valid Exit Access' THEN TrDateTime END) as LastOut,
                    'OFFICE/BIOMETRIC' as source
                  FROM tblTransactionLive 
                  WHERE CardNo = ? 
                  AND CAST(TrDateTime AS DATE) BETWEEN ? AND ?
                  AND [Transaction] IN ('Valid Entry Access', 'Valid Exit Access')
                  GROUP BY CardNo, CAST(TrDateTime AS DATE)";

    $params = [$card_id, $start, $end];
    $stmt_mssql = sqlsrv_query($this->mssql, $sql_mssql, $params);

    if ($stmt_mssql !== false) {
        while ($row = sqlsrv_fetch_array($stmt_mssql, SQLSRV_FETCH_ASSOC)) {
            $combined[] = [
                'date'     => $row['LogDate'] instanceof DateTime ? $row['LogDate']->format('Y-m-d') : $row['LogDate'],
                'time_in'  => ($row['FirstIn'] instanceof DateTime) ? $row['FirstIn']->format('H:i:s') : '--:--',
                'time_out' => ($row['LastOut'] instanceof DateTime) ? $row['LastOut']->format('H:i:s') : '--:--',
                'source'   => $row['source']
            ];
        }
    }

    // 3. SORT: Pinakabago sa taas
    usort($combined, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    return $combined;
}

}