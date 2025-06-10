<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "../../koneksi.php";

$no_resep = $_POST['no_resep'];
$stage = (int)$_POST['stage'];
$status = $_POST['status'];
$waktu = date('Y-m-d H:i:s');
$keterangan = $_POST['keterangan'] ?? '';

// Ambil siklus terakhir
$q = mysqli_query($con, "SELECT MAX(cycle) as cycle FROM tbl_cycle_log WHERE no_resep = '$no_resep'");
$data = mysqli_fetch_assoc($q);
$cycle = (int)$data['cycle'];

if ($status === 'repeat') {
    $cycle += 1; // mulai siklus baru
}

$sql = "INSERT INTO tbl_cycle_log (no_resep, stage, status, waktu, keterangan, cycle)
        VALUES ('$no_resep', $stage, '$status', '$waktu', '$keterangan', $cycle)";
$result = mysqli_query($con, $sql);

echo $result ? 'success' : 'error';
