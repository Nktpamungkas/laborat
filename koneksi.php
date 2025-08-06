<?php
date_default_timezone_set('Asia/Jakarta');
// $host="10.0.0.174";
// $username="ditprogram";
// $password="Xou@RUnivV!6";
// $db_name="TM";
// $time = date('Y-m-d H:i:s');
// $connInfo = array( "Database"=>$db_name, "UID"=>$username, "PWD"=>$password);
// $conn     = sqlsrv_connect( $host, $connInfo);
$con=mysqli_connect("10.0.0.10","dit","4dm1n","db_laborat");
$con_db_dyeing=mysqli_connect("10.0.0.10","dit","4dm1n","db_dying");

$hostSVR19     = "10.0.0.221";
$usernameSVR19 = "sa";
$passwordSVR19 = "Ind@taichen2024";
$nowprd        = "nowprd";
$nowprdd       = ["Database" => $nowprd, "UID" => $usernameSVR19, "PWD" => $passwordSVR19];
$con_nowprd = sqlsrv_connect($hostSVR19, $nowprdd);


$cona = mysqli_connect("10.0.0.10","dit","4dm1n","db_adm");

$hostname="10.0.0.21";
// $database = "NOWTEST"; // SERVER NOW 20
$database = "NOWPRD"; // SERVER NOW 22
$user = "db2admin";
$passworddb2 = "Sunkam@24809";
$port="25000";
$conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
$conn1 = db2_pconnect($conn_string,'', '');

if (mysqli_connect_errno()) {
printf("Connect failed: %s\n", mysqli_connect_error());
exit();
}