<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';
$time = date('Y-m-d H:i:s');

mysqli_query($con,"UPDATE master_proses set `is_active`= '$_POST[is_active]' where id = '$_POST[id]'");
$response = "LIB_SUCCSS";
echo json_encode($response);
