<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
if ($_POST) {
	extract($_POST);
	$id = mysqli_real_escape_string($con,$_POST['id']);
	$note_laborat = mysqli_real_escape_string($con,$_POST['note_laborat']);
	$sqlupdate = mysqli_query($con,"UPDATE `tbl_test_qc` SET 
				`note_laborat`='$note_laborat'
				WHERE `id`='$id' LIMIT 1");
	echo " <script>window.location='?p=TestQCFinal';</script>";
}
