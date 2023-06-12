<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
$date = date('Y-m-d H:i:s');
if ($_POST) {
    extract($_POST);
    $id = mysqli_real_escape_string($con,$_POST['id']);
    $nama = mysqli_real_escape_string($con,$_POST['nama']);
    $sts = mysqli_real_escape_string($con,$_POST['sts']);
    $sqlupdate = mysqli_query($con,"INSERT INTO `tbl_colorist` SET
				`nama`='$nama',
				`is_active`='$sts',
                `created_at` =  '$date',
                `created_by` = '$_SESSION[userLAB]'
        ");

    mysqli_query($con,"INSERT into tbl_log SET `what` = '$nama',
                        `what_do` = 'INSERT INTO tbl_colorist',
                        `do_by` = '$_SESSION[userLAB]',
                        `do_at` = '$time',
                        `ip` = '$_SESSION[ip]',
                        `os` = '$_SESSION[os]',
                        `remark`='Insert new colorist $nama'");
    echo " <script>window.location='?p=Colorist';</script>";
}
