<?php
ini_set("error_reporting", 1);
header('Content-Type: application/json');

include "../../koneksi.php";
session_start();

$no_resep = trim($_GET['no_resep'] ?? '');

if ($no_resep === '') {
    echo json_encode(['success' => false, 'found' => false, 'message' => 'no_resep kosong']);
    exit;
}

// hanya proses format angka-angka: 00234124-503
if (!preg_match('/^(\d+)\-(\d+)$/', $no_resep, $m)) {
    echo json_encode(['success' => true, 'found' => false, 'message' => 'format tidak sesuai']);
    exit;
}

$po    = $m[1]; // PRODUCTIONORDERCODE
$group = $m[2]; // GROUPLINE

if (!isset($conn1) || !$conn1) {
    echo json_encode(['success' => false, 'found' => false, 'message' => 'Koneksi DB2 tidak tersedia']);
    exit;
}

$sql = "
    SELECT 1
    FROM ITXVIEWRESEP
    WHERE PRODUCTIONORDERCODE = ?
      AND GROUPLINE = ?
      AND SUBCODE01 IN ('D', 'Y', 'R')
    FETCH FIRST 1 ROW ONLY
";

$stmt = db2_prepare($conn1, $sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'found' => false, 'message' => 'Prepare DB2 gagal']);
    exit;
}

$ok = db2_execute($stmt, [$po, $group]);
if (!$ok) {
    echo json_encode(['success' => false, 'found' => false, 'message' => 'Execute DB2 gagal']);
    exit;
}

$row = db2_fetch_assoc($stmt);
if ($row) {
    echo json_encode(['success' => true, 'found' => true, 'message' => 'BON RESEP DITEMUKAN']);
} else {
    echo json_encode(['success' => true, 'found' => false, 'message' => 'Tidak ditemukan']);
}
