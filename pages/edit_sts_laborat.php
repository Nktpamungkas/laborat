<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
if ($_POST) {
	extract($_POST);
	$id = mysqli_real_escape_string($con,$_POST['id']);
	$sts_laborat = mysqli_real_escape_string($con,$_POST['sts_laborat']);
	if($sts_laborat=="Waiting Approval Full"){ $stsqc="Kain OK"; $stslaborat="Approved Full";}
	if($sts_laborat=="Waiting Approval Parsial"){ $stsqc="Kain OK Sebagian"; $stslaborat="Approved Parisal";}
	$sqlupdate = mysqli_query($con,"UPDATE `tbl_test_qc` SET 
				`sts_laborat`='$stslaborat',
				`sts_qc`='$stsqc'
				WHERE `id`='$id' LIMIT 1");
	echo " <script>window.location='?p=ApprovedTestReport';</script>";
}
