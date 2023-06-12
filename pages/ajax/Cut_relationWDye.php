<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';

$delete = '';
mysqli_query($con,"UPDATE db_dying.tbl_hasilcelup SET `rcode` = '$delete' where id = '$_POST[id]'");

mysqli_query($con,"INSERT into tbl_log SET `what` = '$_POST[id]',
        `what_do` = 'UPDATE db_dying.tbl_hasilcelup rcode',
        `do_by` = '$_SESSION[userLAB]',
        `do_at` = '$time',
        `ip` = '$_SESSION[ip]',
        `os` = '$_SESSION[os]',
        `remark` = 'Delete Relation'");

$response = "LIB_SUCCSS";
echo json_encode($response);
