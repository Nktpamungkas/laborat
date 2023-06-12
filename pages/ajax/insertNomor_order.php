<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$time = date('Y-m-d H:i:s');
$ip_num = $_SERVER['REMOTE_ADDR'];

$Sql_Cek_Flag = mysqli_query($con,"SELECT id, max(flag) as maxflag, id_matching, id_status, r_code, `order`, lot
                FROM tbl_orderchild where id_matching = '$_POST[id_matching]' 
                group by id_matching");
$row = mysqli_num_rows($Sql_Cek_Flag);
$row_data = mysqli_fetch_array($Sql_Cek_Flag);
if ($row > 0) {
    $flag = intval($row_data['maxflag']) + 1;
} else {
    $flag = 1;
}
$Sql_Cek_Duplikasi = mysqli_query($con,"SELECT id, flag, id_matching, id_status, r_code, `order`, lot
FROM tbl_orderchild where id_matching = '$_POST[id_matching]' and `order` = '$_POST[no_order]' LIMIT 1");
$sql_row = mysqli_num_rows($Sql_Cek_Duplikasi);
if ($sql_row > 0) {
    $benang = str_replace("'", "''", $_POST['addt_benang']);
    mysqli_query($con,"INSERT INTO tbl_orderchild SET 
            `flag` = '$flag', 
            `id_matching` = '$_POST[id_matching]', 
            `id_status`= '$_POST[id_status]', 
            `r_code` = '$_POST[Rcode]', 
            `order` = '$_POST[no_order]', 
            `lot` = '$_POST[lot]', 
            `jenis_benang` = '$benang', 
            `created_by` = '$_SESSION[userLAB]', 
            `created_at` = '$time'");
    $LIB_SUCCSS = "LIB_SUCCSS";
} else {
    $benang = str_replace("'", "''", $_POST['addt_benang']);
    mysqli_query($con,"INSERT INTO tbl_orderchild SET 
            `flag` = '$flag', 
            `id_matching` = '$_POST[id_matching]', 
            `id_status`= '$_POST[id_status]', 
            `r_code` = '$_POST[Rcode]', 
            `order` = '$_POST[no_order]', 
            `lot` = '$_POST[lot]', 
            `jenis_benang` = '$benang', 
            `created_by` = '$_SESSION[userLAB]', 
            `created_at` = '$time'");
    $LIB_SUCCSS = "LIB_SUCCSS";
}
$sqlNoResep = mysqli_query($con,"SELECT idm from tbl_status_matching where id = '$_POST[id_status]'");
$NoResep = mysqli_fetch_array($sqlNoResep);
mysqli_query($con,"INSERT into log_status_matching set 
                `ids` = '$NoResep[idm]',
                `status` = 'insert order child',
                `info` = '$_POST[no_order]',
                `do_by` = '$_SESSION[userLAB]', 
                `do_at` = '$time', 
                `ip_address` = '$ip_num'");

$response = array(
    'session' => $LIB_SUCCSS,
    'exp' => 'inserted'
);
echo json_encode($response);
