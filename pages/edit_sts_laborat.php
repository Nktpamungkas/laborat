<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");

if ($_POST) {

	function get_client_ip()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if (isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	$ip_num = get_client_ip();

	extract($_POST);
	$id = mysqli_real_escape_string($con, $_POST['id']);
	$sts_laborat = mysqli_real_escape_string($con, $_POST['sts_laborat']);
	$no_counter = mysqli_real_escape_string($con, $_POST['no_counter']);

	$success = true;

	mysqli_begin_transaction($con);

	if ($sts_laborat == "Waiting Approval Full") {
		$stsqc = "Kain OK";
		$stslaborat = "Approved Full";
	} elseif ($sts_laborat == "Waiting Approval Parsial") {
		$stsqc = "Kain OK Sebagian";
		$stslaborat = "Approved Parsial";
	}

	$query_update = "UPDATE `tbl_test_qc` SET 
                `sts_laborat`='$stslaborat',
                `sts_qc`='$stsqc'
                WHERE `id`='$id' LIMIT 1";

	$result_update = mysqli_query($con, $query_update);

	if (!$result_update) {
		$success = false;
	}

	$query_log = "INSERT INTO log_qc_test (no_counter, `status`, info, do_by, do_at, ip_address)
                   VALUES ('$no_counter', '$stslaborat', 'Sudah approve dari laborat', '$_SESSION[userLAB]', NOW(), '$ip_num')";

	$result_log = mysqli_query($con, $query_log);

	if (!$result_log) {
		$success = false;
	}

	if ($success) {
		mysqli_commit($con);
		echo "<script>window.location='?p=ApprovedTestReport';</script>";
	} else {
		mysqli_rollback($con);
		echo "<script>window.location='?p=ApprovedTestReport';</script>";
	}
}
