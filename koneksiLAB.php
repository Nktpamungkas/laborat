<?php
$host = "10.0.4.7\SQLEXPRESS";
// $host = "DIT\MSSQLSERVER08";
$username = "sa";
$password = "123";
$db_name = "TICKET";
//--

function db_connect()
{
    global $host, $username, $password, $db_name;
    set_time_limit(600);
    //mssql_connect($host, $username, $password) or die("Tidak bisa terkoneksi dengan server Database Laborat !");
    //mssql_select_db($db_name) or die("Under maintenance");
    $connInfo = array( "Database"=>$db_name, "UID"=>$username, "PWD"=>$password);
    sqlsrv_connect( $host, $connInfo);
}