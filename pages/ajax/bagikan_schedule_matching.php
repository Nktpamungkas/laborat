<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$time = date('Y-m-d H:i:s');
function get_client_ip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
$ip = get_client_ip();
$ip_num = $_SERVER['REMOTE_ADDR'];
mysqli_query($con,"UPDATE tbl_matching set status_bagi = 'siap bagi' where no_resep = '$_POST[rcode]' limit 1");
mysqli_query($con,"INSERT INTO log_status_matching SET
            `ids` = '$_POST[rcode]', 
            `status` = 'siap bagi', 
            `info` = 'changed status_bagi to siap bagi', 
            `do_by` = '$_SESSION[userLAB]', 
            `do_at` = '$time', 
            `ip_address` = '$ip_num'");

$response = array(
    'session' => 'LIB_SUCCSS',
    'exp' => 'updated',
    'ip_address' => $ip
);
echo json_encode($response);
