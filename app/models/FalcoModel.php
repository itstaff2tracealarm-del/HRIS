<?php

class FalcoModel
{
    private $mssql;

    public function __construct($mssql)
    {
        $this->mssql = $mssql;
    }

public function getTransactions()
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

    return sqlsrv_query($this->mssql, $sql);
}
}