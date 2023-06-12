<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$time = date('Y-m-d H:i:s');
$ip_num = $_SERVER['REMOTE_ADDR'];

mysqli_query($con,"DELETE FROM tbl_notestatus WHERE `flag` = '$flag' AND
                                            `id_matching`  = '$_POST[id_matching]' AND 
                                            `id_status`    = '$_POST[id_status]' AND
                                            `r_code`       = '$_POST[Rcode]'");
$LIB_SUCCSS = "LIB_SUCCSS";

$response = array(
    'session' => $LIB_SUCCSS,
    'exp' => 'inserted'
);
echo json_encode($response);
