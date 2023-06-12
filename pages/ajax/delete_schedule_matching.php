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
$sql = mysqli_query($con,"SELECT id, no_order, jenis_matching from tbl_matching where no_resep = '$_POST[rcode]' LIMIT 1");
$data = mysqli_fetch_array($sql);
$ip = get_client_ip();
mysqli_query($con,"INSERT INTO `historical_delete_matching` SET
`no_matching`= '$_POST[rcode]',
`id_matching`= '$data[id]',
`id_status`= '$_POST[rcode]',
`jenis_matching` = '$data[jenis_matching]',
`ip_adress`= '$ip',
`delete_at`= '$time',
`delete_by`= '$_SESSION[userLAB]',
`why_delete`= 'deleted from data matching',
`no_order` = '$data[no_order]'");
mysqli_query($con,"DELETE from `tbl_matching` where `no_resep`='$_POST[rcode]'");
mysqli_query($con,"DELETE from `tbl_status_matching` where `idm`='$_POST[rcode]'");
mysqli_query($con,"DELETE from `tbl_matching_detail` where `id_matching`='$data[id]'");

$ip_num = $_SERVER['REMOTE_ADDR'];
mysqli_query($con,"INSERT INTO log_status_matching SET
            `ids` = '$_POST[rcode]', 
            `status` = 'deleted', 
            `info` = 'deleted from data matching', 
            `do_by` = '$_SESSION[userLAB]', 
            `do_at` = '$time', 
            `ip_address` = '$ip_num'");

$response = array(
    'session' => 'LIB_SUCCSS',
    'exp' => 'updated',
    'ip_address' => $ip
);
echo json_encode($response);
