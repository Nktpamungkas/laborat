<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
if ($_POST) {
	extract($_POST);
	$id = mysqli_real_escape_string($con,$_POST['id']);
	$nama = mysqli_real_escape_string($con,$_POST['nama']);
	$sts = mysqli_real_escape_string($con,$_POST['sts']);

	$sqlupdate = mysqli_query($con,"UPDATE `tbl_matcher` SET 
				`nama`='$nama', 
				`status`='$sts'
				WHERE `id`='$id' LIMIT 1");

	mysqli_query($con,"INSERT into tbl_log SET `what` = '$id',
				`what_do` = 'UPDATE tbl_matcher',
				`do_by` = '$_SESSION[userLAB]',
				`do_at` = '$time',
				`ip` = '$_SESSION[ip]',
				`os` = '$_SESSION[os]',
				`remark`='Edit matcher'");


	echo " <script>window.location='?p=Matcher';</script>";
}
