<?php

class FalcoController
{
    private $mysql;
    private $mssql;

    public function __construct($mysql, $mssql)
    {
        $this->mysql = $mysql;
        $this->mssql = $mssql;
    }

    public function index()
    {
        require __DIR__ . '/../views/Falco/index.php';
    }

    // =========================
    // 🔥 GET FALCO DATA (DIRECT)
    // =========================
    public function getFalcoData()
    {
      $sql = "
    SELECT 
        CardNo,
        MAX(TrName) as employee_name, -- 🔥 FIX
        MIN(CASE WHEN [Transaction] = 'Valid Entry Access' THEN TrDateTime END) as time_in,
        MAX(CASE WHEN [Transaction] = 'Valid Exit Access' THEN TrDateTime END) as time_out
    FROM tblTransactionLive
    WHERE TrDateTime >= DATEADD(HOUR, -12, GETDATE())
    AND [Transaction] IN ('Valid Entry Access','Valid Exit Access')
    GROUP BY CardNo
    ORDER BY MAX(TrDateTime) DESC
    ";

        $stmt = sqlsrv_query($this->mssql, $sql);

        $data = [];

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

        $data[] = [
    "card_no" => $row['CardNo'],
    "name" => $row['employee_name'],
    "time_in" => $row['time_in'] ? $row['time_in']->format('Y-m-d H:i:s') : null,
    "time_out" => $row['time_out'] ? $row['time_out']->format('Y-m-d H:i:s') : null
];
        }

        echo json_encode($data);
    }

    // =========================
    // OPTIONAL: SYNC TO LOCAL
    // =========================
    public function sync()
    {
        $sql = "
    SELECT 
        CardNo,
        MAX(TrName) as employee_name, -- 🔥 FIX
        MIN(CASE WHEN [Transaction] = 'Valid Entry Access' THEN TrDateTime END) as time_in,
        MAX(CASE WHEN [Transaction] = 'Valid Exit Access' THEN TrDateTime END) as time_out
    FROM tblTransactionLive
    WHERE TrDateTime >= DATEADD(HOUR, -12, GETDATE())
    AND [Transaction] IN ('Valid Entry Access','Valid Exit Access')
    GROUP BY CardNo
    ORDER BY MAX(TrDateTime) DESC
        ";

        $stmt = sqlsrv_query($this->mssql, $sql);

        $count = 0;

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

            $card = $row['CardNo'];
            $name = $row['employee_name'];
            $time_in = $row['time_in'];
            $time_out = $row['time_out'];

            if (!$time_in) continue;

            $time_in = $time_in->format('Y-m-d H:i:s');
            $time_out = $time_out ? $time_out->format('Y-m-d H:i:s') : null;

            $this->mysql->query("
                INSERT INTO field_attendance (card_no, time_in, time_out, updated_at)
                VALUES ('$card', '$time_in', " . ($time_out ? "'$time_out'" : "NULL") . ", NOW())
                ON DUPLICATE KEY UPDATE
                time_out = IFNULL('$time_out', time_out),
                updated_at = NOW()
            ");

            $count++;
        }

        echo json_encode([
            "status" => "success",
            "count" => $count
        ]);
    }
}