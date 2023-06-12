<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php")
if ($_POST) {
    extract($_POST);
    $id = mysqli_real_escape_string($con,$_POST['id']);
    $ket = mysqli_real_escape_string($con,$_POST['ket']);
    $matcher = mysqli_real_escape_string($con,$_POST['matcher']);
    $kt_st = mysqli_real_escape_string($con,$_POST['ket_st']);
    $grp = mysqli_real_escape_string($con,$_POST['grp']);
    $sqlupdate=mysqli_query($con,"UPDATE `tbl_status_matching` SET
				`matcher`='$matcher',
				`grp`='$grp',
				`kt_status`='$kt_st',
				`ket`='$ket'
				WHERE `id`='$id' LIMIT 1");
        echo " <script>window.location='?p=data-matching';</script>";
}
