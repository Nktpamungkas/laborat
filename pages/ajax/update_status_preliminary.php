<?php
include "../../koneksi.php";
session_start();

$user   = $_SESSION['userLAB'] ?? "Guest";
$status = $_POST['status'] ?? 'unknown';
$ip_num = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

$stmt = mysqli_query($con, "INSERT INTO log_preliminary (username, `status`, ip_comp) VALUES ('$user', '$status', '$ip_num')");
?>
