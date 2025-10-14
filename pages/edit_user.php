<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
if ($_POST) {
	extract($_POST);
	$id = mysqli_real_escape_string($con,$_POST['id']);
	$user = mysqli_real_escape_string($con,$_POST['username']);
	$pass = mysqli_real_escape_string($con,$_POST['password']);
	$repass = mysqli_real_escape_string($con,$_POST['re_password']);
	$level = mysqli_real_escape_string($con,$_POST['level']);
	$status = mysqli_real_escape_string($con,$_POST['status']);
	$roles = isset($_POST['roles']) ? implode(';', $_POST['roles']) : '';
	if ($pass != $repass) {
		echo " <script>alert('Not Match Re-New Password!!');window.location='?p=user';</script>";
	} else {
		$sqlupdate = mysqli_query($con,"UPDATE `tbl_user` SET 
				`username`='$user', 
				`password`='$pass',
				`level`='$level',
				`status`='$status',
				`mamber`='$_POST[thn]',
				`jabatan`='$_POST[jabatan]',
				`pic_cycletime`='$roles'
				WHERE `id`='$id' LIMIT 1");

		mysqli_query($con,"INSERT into tbl_log SET `what` = '$id',
					`what_do` = 'UPDATE table tbl_user',
					`do_by` = '$_SESSION[userLAB]',
					`do_at` = '$time',
					`ip` = '$_SESSION[ip]',
					`os` = '$_SESSION[os]',
					`remark`='edit user'");
		echo " <script>window.location='?p=user';</script>";
	}
}
