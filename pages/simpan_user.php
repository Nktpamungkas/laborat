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
	$datauser = mysqli_query($con,"SELECT count(*) as jml FROM tbl_user WHERE `username`='$user' LIMIT 1");
	$row = mysqli_fetch_array($datauser);
	if ($row['jml'] > 0) {
		echo " <script>alert('Someone already has this username!');window.location='?p=user';</script>";
	} else if ($pass != $repass) {
		echo " <script>alert('Not Match Re-New Password!');window.location='?p=user';</script>";
	} else {
		$sqlupdate = mysqli_query($con,"INSERT INTO `tbl_user` SET 
				`username`='$user', 
				`password`='$pass',
				`level`='$level',
				`status`='$status',
				`foto`='avatar.png',
				`jabatan`='$_POST[jabatan]',
				`mamber`='$_POST[thn]'
				");

		mysqli_query($con,"INSERT into tbl_log SET `what` = '$user',
					`what_do` = 'INSERT INTO tbl_user',
					`do_by` = '$_SESSION[userLAB]',
					`do_at` = '$time',
					`ip` = '$_SESSION[ip]',
					`os` = '$_SESSION[os]',
					`remark`='New user'");
		echo " <script>window.location='?p=user';</script>";
	}
}
