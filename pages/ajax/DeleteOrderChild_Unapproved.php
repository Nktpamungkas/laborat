<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$time = date('Y-m-d H:i:s');
$ip_num = $_SERVER['REMOTE_ADDR'];
$id = $_POST['id'];

mysqli_query($con,"DELETE FROM tbl_orderchild WHERE id = '$id'");
$LIB_SUCCSS = "LIB_SUCCSS";

$sqlNoResep = mysqli_query($con,"SELECT no_resep from tbl_matching where id = '$_POST[id_matching]'");
$NoResep = mysqli_fetch_array($sqlNoResep);
mysqli_query($con,"INSERT into log_status_matching set 
                `ids` = '$NoResep[no_resep]',
                `status` = 'insert order child',
                `info` = 'Delete Order Child',
                `do_by` = '$_SESSION[userLAB]', 
                `do_at` = '$time', 
                `ip_address` = '$ip_num'");

$response = array(
    'session' => $LIB_SUCCSS,
    'exp' => 'inserted'
);








echo json_encode($response);
