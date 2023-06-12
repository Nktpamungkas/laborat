<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';

mysqli_query($con,"UPDATE db_dying.tbl_hasilcelup SET `k_resep` = '$_POST[value]' where id = '$_POST[pk]'");

mysqli_query($con,"INSERT into tbl_log SET `what` = '$_POST[pk]',
        `what_do` = 'UPDATE db_dying.tbl_hasilcelup k_resep',
        `do_by` = '$_SESSION[userLAB]',
        `do_at` = '$time',
        `ip` = '$_SESSION[ip]',
        `os` = '$_SESSION[os]',
        `remark`='$_POST[value]'");

$response = "LIB_SUCCSS";
echo json_encode($response);
