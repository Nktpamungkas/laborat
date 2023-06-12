<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$time = date('Y-m-d H:i:s');

$sqlNoResep = mysqli_query($con,"SELECT idm from tbl_status_matching where id = '$_POST[id_status]'");
$NoResep = mysqli_fetch_array($sqlNoResep);

$ip_num = $_SERVER['REMOTE_ADDR'];
mysqli_query($con,"INSERT INTO log_status_matching SET
            `ids` = '$NoResep[idm]', 
            `status` = 'tunggu', 
            `info` = '$_POST[why]', 
            `do_by` = '$_SESSION[userLAB]', 
            `do_at` = '$time', 
            `ip_address` = '$ip_num'");

mysqli_query($con,"UPDATE tbl_matching set 
            status_bagi = 'tunggu',
            note = '$_POST[why]'
            where no_resep = '$NoResep[idm]'
             ");

mysqli_query($con,"DELETE from `tbl_status_matching` where `id` = '$_POST[id_status]'");
mysqli_query($con,"DELETE from `tbl_matching_detail` where `id_status` = '$_POST[id_status]'");


$response = array(
    'session' => 'LIB_SUCCSS',
    'exp' => 'updated'
);
echo json_encode($response);
