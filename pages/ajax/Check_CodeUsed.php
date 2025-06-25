<?php
header('Content-Type: application/json');
include '../../koneksi.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

$response = ['used' => false, 'error' => false];

// Ambil code berdasarkan ID
$getCode = mysqli_query($con, "SELECT code FROM master_suhu WHERE id = '$id'");

if (!$getCode || mysqli_num_rows($getCode) == 0) {
    $response['error'] = true;
    $response['message'] = 'Data tidak ditemukan.';
    echo json_encode($response);
    exit;
}

$row = mysqli_fetch_assoc($getCode);
$code = $row['code'];

// Cek apakah code digunakan di tbl_preliminary_schedule
$check = mysqli_query($con, "SELECT COUNT(*) as total FROM tbl_preliminary_schedule WHERE code = '$code'");
$data = mysqli_fetch_assoc($check);

if ($data['total'] > 0) {
    $response['used'] = true;
}

echo json_encode($response);
