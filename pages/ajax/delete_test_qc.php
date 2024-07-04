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

$ip_num = get_client_ip();

$success = true;

mysqli_begin_transaction($con);


$query_delete = "UPDATE `tbl_test_qc` SET `deleted_at` = NOW() WHERE `id` = '$_POST[id]'";
$result_delete = mysqli_query($con, $query_delete);


if (!$result_delete) {
    $success = false;
}


$no_counter = $_POST['no_counter'];
$log_info = "Menghapus test $no_counter";


$query_log = "INSERT INTO log_qc_test (no_counter, `status`, info, do_by, do_at, ip_address)
                  VALUES ('$no_counter', 'Open', '$log_info', '$_SESSION[userLAB]', NOW(), '$ip_num')";
$result_log = mysqli_query($con, $query_log);


if (!$result_log) {
    $success = false;
}

if ($success) {
    mysqli_commit($con);

    $response = array(
        'session' => 'LIB_SUCCSS',
        'exp' => 'updated',
    );
    echo json_encode($response);
} else {
    mysqli_rollback($con);
    $response = array(
        'session' => 'LIB_FAILED',
        'exp' => 'updated',
    );
    echo json_encode($response);
}
