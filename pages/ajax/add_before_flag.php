<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$time = date('Y-m-d H:i:s');

$sql_greather = mysqli_query($con,"SELECT id from tbl_matching_detail where id_status = '$_POST[id_status]' and flag >= '$_POST[flag]'");

$i = intval($_POST['flag']);
while ($li = mysqli_fetch_array($sql_greather)) {
    $i++;
    mysqli_query($con,"UPDATE tbl_matching_detail set 
    `flag` = '$i',
    `last_edit_by` = '$_SESSION[userLAB]',
    `last_edit_at` = NOW()
    where id = $li[id]");
}

mysqli_query($con,"INSERT into tbl_matching_detail SET  
                    `id_matching` = '$_POST[id_matching]',
                    `id_status` = '$_POST[id_status]',
                    `flag` = '$_POST[flag]',
                    `conc1` = 0,
                    `conc2` = 0,
                    `conc3` = 0,
                    `conc4` = 0,
                    `conc5` = 0,
                    `conc6` = 0,
                    `conc7` = 0,
                    `conc8` = 0,
                    `conc9` = 0,
                    `conc10` = 0,
                    `time_1` = NOW(),
                    `doby1` = '$_SESSION[userLAB]',
                    `inserted_at` = NOW(),
                    `inserted_by` = '$_SESSION[userLAB]'
                    ");

$SQL_rcode  = mysqli_query($con,"SELECT idm from tbl_status_matching where id = '$_POST[id_status]' LIMIT 1");
$rcode_ = mysqli_fetch_array($SQL_rcode);
$ip_num = $_SERVER['REMOTE_ADDR'];
mysqli_query($con,"INSERT into log_status_matching set 
                `ids` = '$rcode_[idm]',
                `status` = 'selesai',
                `info` = 'modifikasi resep, add new row before $_POST[flag]',
                `do_by` = '$_SESSION[userLAB]', 
                `do_at` = '$time', 
                `ip_address` = '$ip_num'");
$response = array(
    'session' => 'LIB_SUCCSS',
    'exp' => $_POST['flag']
);
echo json_encode($response);
