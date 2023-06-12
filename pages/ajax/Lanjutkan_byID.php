<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$time = date('Y-m-d H:i:s');
$ip_num = $_SERVER['REMOTE_ADDR'];
mysqli_query($con,"UPDATE `tbl_status_matching` SET
            `status`='hold'
            where `id` = '$_POST[id_status]'");
$sqlNoResep = mysqli_query($con,"SELECT idm from tbl_status_matching where id = '$_POST[id_status]'");
$NoResep = mysqli_fetch_array($sqlNoResep);
mysqli_query($con,"INSERT INTO log_status_matching SET
            `ids` = '$NoResep[idm]', 
            `status` = 'hold', 
            `info` = 'lanjutkan after wait', 
            `do_by` = '$_SESSION[userLAB]', 
            `do_at` = '$time', 
            `ip_address` = '$ip_num'");

$response = array(
    'session' => 'LIB_SUCCSS',
    'exp' => 'updated'
);
echo json_encode($response);
