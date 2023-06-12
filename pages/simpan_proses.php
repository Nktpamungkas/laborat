<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
$time = date('Y-m-d H:i:s');
if ($_POST) {
    extract($_POST);
    $Proses_desc = strtoupper(mysqli_real_escape_string($con,$_POST['Proses_desc']));
    $is_active = mysqli_real_escape_string($con,$_POST['is_active']);
    mysqli_query($con,"INSERT INTO `master_proses` SET 
				`nama_proses`='$Proses_desc',
				`is_active`='$is_active',
				`created_at`='$time',
                `created_by`='$_SESSION[userLAB]'
                ");

    mysqli_query($con,"INSERT into tbl_log SET `what` = '$Proses_desc',
                    `what_do` = 'INSERT INTO master_proses',
                    `do_by` = '$_SESSION[userLAB]',
                    `do_at` = '$time',
                    `ip` = '$_SESSION[ip]',
                    `os` = '$_SESSION[os]',
                    `remark`='Insert new Proses'");
    echo " <script>window.location='?p=Manage-Proses';</script>";
}
