<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$time = date('Y-m-d H:i:s');
$ip_num = $_SERVER['REMOTE_ADDR'];

mysqli_query($con,"INSERT INTO tbl_notestatus SET 
        `flag` = '$flag', 
        `id_matching` = '$_POST[id_matching]', 
        `id_status`= '$_POST[id_status]', 
        `r_code` = '$_POST[Rcode]', 
        `note` = '$_POST[note_status]'");
$LIB_SUCCSS = "LIB_SUCCSS";

$response = array(
    'session' => $LIB_SUCCSS,
    'exp' => 'inserted'
);
echo json_encode($response);
