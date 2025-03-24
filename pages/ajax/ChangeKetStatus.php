<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$time = date('Y-m-d H:i:s');

$dataMain = mysqli_query($con,"UPDATE 
                                    tbl_status_matching 
                                SET
                                    kt_status = '$_POST[newStatus]'
                                WHERE
                                    id = '$_POST[id_status]'");

$ip_num = $_SERVER['REMOTE_ADDR'];
mysqli_query($con,"INSERT INTO log_status_matching SET
                    `ids` = '$_POST[idm]', 
                    `status` = 'Change ket status to', 
                    `info` = '$_POST[newStatus]', 
                    `do_by` = '$_SESSION[userLAB]', 
                    `do_at` = '$time', 
                    `ip_address` = '$ip_num'");

$response = array(
    'session' => 'LIB_SUCCSS',
    'exp' => 'updated'
);
echo json_encode($response);
