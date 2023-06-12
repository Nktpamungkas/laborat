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
$sql = mysqli_query($con,"SELECT id, no_order, jenis_matching from tbl_matching where no_resep = '$_POST[idm]' LIMIT 1");
$data = mysqli_fetch_array($sql);
mysqli_query($con,"INSERT INTO `historical_delete_matching` SET
`no_matching`= '$_POST[idm]',
`id_matching`= '$_POST[id_matching]',
`id_status`= '$_POST[id_status]',
`jenis_matching` = '$data[jenis_matching]',
`ip_adress`= '$ip',
`delete_at`= '$time',
`delete_by`= '$_SESSION[userLAB]',
`why_delete`= '$_POST[why_batal]',
`no_order` = '$_POST[no_order]'");
mysqli_query($con,"DELETE from `tbl_matching_detail` where `id_matching`='$_POST[id_matching]' and `id_status`='$_POST[id_status]'");
mysqli_query($con,"DELETE from `tbl_status_matching` where `id`='$_POST[id_status]'");
mysqli_query($con,"DELETE from `tbl_matching` where `no_resep`='$_POST[idm]'");

$ip_num = $_SERVER['REMOTE_ADDR'];
mysqli_query($con,"INSERT INTO log_status_matching SET
            `ids` = '$_POST[idm]', 
            `status` = 'deleted', 
            `info` = '$_POST[why_batal]', 
            `do_by` = '$_SESSION[userLAB]', 
            `do_at` = '$time', 
            `ip_address` = '$ip_num'");

$response = array(
    'session' => 'LIB_SUCCSS',
    'exp' => 'updated',
    'ip_address' => $ip
);
echo json_encode($response);
