<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();

mysqli_query($con,"INSERT INTO tbl_note_celup SET 
kk = '$_POST[kk]',
jenis_note = '$_POST[jenis_note]',
note = '$_POST[note]',
created_at = NOW(),
created_by = '$_SESSION[userLAB]'");

$SQL_rcode  = mysqli_query($con,"SELECT idm from tbl_status_matching where id = '$_POST[id_status]' LIMIT 1");
$rcode_ = mysqli_fetch_array($SQL_rcode);
$ip_num = $_SERVER['REMOTE_ADDR'];
mysqli_query($con,"INSERT into log_status_matching set 
                `ids` = '$rcode_[idm]',
                `status` = 'selesai',
                `info` = 'add note $_POST[kk]',
                `do_by` = '$_SESSION[userLAB]', 
                `do_at` = NOW(), 
                `ip_address` = '$ip_num'");
$response = array(
    'session' => 'LIB_SUCCSS',
    'exp' => $rcode_['idm']
);
echo json_encode($response);
