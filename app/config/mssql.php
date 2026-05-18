<?php


$serverName = "FILESERVER-2\\SQLEXPRESS";

$connectionOptions = [
    "Database"=>"DataDBENT",
    "Uid"=>"python",
    "PWD"=>"python"
];

$mssql_conn = sqlsrv_connect($serverName,$connectionOptions);

if($mssql_conn === false){
    die(print_r(sqlsrv_errors(),true));
}
?>
