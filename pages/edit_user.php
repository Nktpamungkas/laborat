<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
if ($_POST) {
	extract($_POST);
	$id = mysqli_real_escape_string($con, $_POST['id']);
	$user = mysqli_real_escape_string($con, $_POST['username']);
	$level = mysqli_real_escape_string($con, $_POST['level']);
	$status = mysqli_real_escape_string($con, $_POST['status']);
	$thn = mysqli_real_escape_string($con, $_POST['thn']);
	$jabatan = mysqli_real_escape_string($con, $_POST['jabatan']);
	$roles = isset($_POST['roles']) ? implode(';', $_POST['roles']) : '';

	if (empty($user)) {
		echo "<script>alert('Username tidak boleh kosong!');window.location='?p=user';</script>";
		exit;
	}

	// Jika password diisi, cek konfirmasi dan update password
	if (!empty($_POST['password'])) {
		$pass = mysqli_real_escape_string($con, $_POST['password']);
		$repass = mysqli_real_escape_string($con, $_POST['re_password']);
		if ($pass != $repass) {
			echo "<script>alert('Not Match Re-New Password!!');window.location='?p=user';</script>";
			exit;
		}
		$updatePassword = ", `password`='$pass'";
	} else {
		$updatePassword = "";
	}

	$sqlupdate = mysqli_query($con, "UPDATE `tbl_user` SET 
			`username`='$user'
			$updatePassword,
			`level`='$level',
			`status`='$status',
			`mamber`='$thn',
			`jabatan`='$jabatan',
			`pic_cycletime`='$roles'
			WHERE `id`='$id' LIMIT 1");

	$time = date('Y-m-d H:i:s');
	mysqli_query($con, "INSERT into tbl_log SET `what` = '$id',
				`what_do` = 'UPDATE table tbl_user',
				`do_by` = '{$_SESSION['userLAB']}',
				`do_at` = '$time',
				`ip` = '{$_SESSION['ip']}',
				`os` = '{$_SESSION['os']}',
				`remark`='edit user'");
	echo "<script>window.location='?p=user';</script>";
}
