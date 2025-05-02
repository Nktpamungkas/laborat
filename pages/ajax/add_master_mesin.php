<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../../koneksi.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $no_machine = trim($_POST['no_machine']);
    $suhu = trim($_POST['suhu']);
    $program = trim($_POST['program']);
    $keterangan = trim($_POST['keterangan']);

    if (!empty($suhu)) {
        $suhu = $suhu . '°C';
    }

    $stmt = mysqli_prepare($con, "INSERT INTO master_mesin (no_machine, suhu, program, keterangan) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssss', $no_machine, $suhu, $program, $keterangan);
    
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambahkan!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . mysqli_error($con)]);
    }

    mysqli_stmt_close($stmt);
}

