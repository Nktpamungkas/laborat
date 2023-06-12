<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php")
$time = date('Y-m-d H:i:s');
if ($_POST) {
	extract($_POST);
	$id = mysqli_real_escape_string($con,$_POST['id']);
	$ket = mysqli_real_escape_string($con,$_POST['ket']);
	if ($_POST['tgl_masuk'] != "") {
		$masuk = " `tgl_masuk`='$_POST[tgl_masuk]', ";
	} else {
		$masuk = "";
	}
	if ($_POST['tgl_siap_kain'] != "") {
		$siapkain = " `tgl_siap_kain`='$_POST[tgl_siap_kain]', ";
	} else {
		$siapkain = "";
	}
	if ($_POST['tgl_mulai'] != "") {
		$mulai = " `tgl_mulai`='$_POST[tgl_mulai]', ";
	} else {
		$mulai = "";
	}
	if ($_POST['tgl_selesai'] != "") {
		$selesai = " `tgl_selesai`='$_POST[tgl_selesai]', ";
	} else {
		$selesai = "";
	}
	$sts = mysqli_real_escape_string($con,$_POST['sts']);
	$warna = mysqli_real_escape_string($con,$_POST['cek_warna']);
	$dye = mysqli_real_escape_string($con,$_POST['cek_dye']);
	$koreksi = mysqli_real_escape_string($con,$_POST['koreksi_resep']);
	$grp = mysqli_real_escape_string($con,$_POST['grp']);
	$sqlupdate = mysqli_query($con,"UPDATE `tbl_status_matching` SET
				" . $masuk . "
				" . $mulai . "
				" . $siapkain . "
				" . $selesai . "
				`status`='$sts',
				`cek_dye`='$dye',
				`cek_warna`='$warna',
        		`koreksi_resep`='$koreksi',
				`ket`='$ket',
				`tgl_update` = '$time'
				WHERE `id`='$id' LIMIT 1");
	if ($grp == "A") {
		echo " <script>window.location='?p=Group-A';</script>";
	} else if ($grp == "B") {
		echo " <script>window.location='?p=Group-B';</script>";
	} else if ($grp == "C") {
		echo " <script>window.location='?p=Group-C';</script>";
	} else if ($grp == "D") {
		echo " <script>window.location='?p=Group-D';</script>";
	}
}
