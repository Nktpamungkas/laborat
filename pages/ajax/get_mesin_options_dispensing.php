<?php
header('Content-Type: application/json');
include "../../koneksi.php";

$groupName = $_GET['group'] ?? '';
$groupName = trim($groupName);

$keterangan = '';
$suhu = null;
$machines = [];

// Ambil dyeing dan suhu dari master_suhu
$stmt = $con->prepare("SELECT dyeing, suhu FROM master_suhu WHERE `group` = ? LIMIT 1");
$stmt->bind_param("s", $groupName);
$stmt->execute();
$stmt->bind_result($dyeingValue, $suhu);
$stmt->fetch();
$stmt->close();

// Konversi dyeing ke keterangan
if ($dyeingValue == "1") {
    $keterangan = 'POLY';
} elseif ($dyeingValue == "2") {
    $keterangan = 'COTTON';
}

// Logika pilihan mesin
if ($keterangan === 'COTTON' && $suhu == 80) {
    $machines = ['A6', 'C1'];
} elseif ($keterangan) {
    $stmtMesin = $con->prepare("
        SELECT no_machine 
        FROM master_mesin 
        WHERE keterangan = ? AND no_machine NOT IN ('A6', 'C1')
    ");
    $stmtMesin->bind_param("s", $keterangan);
    $stmtMesin->execute();
    $resultMesin = $stmtMesin->get_result();
    
    while ($row = $resultMesin->fetch_assoc()) {
        $machines[] = $row['no_machine'];
    }

    $stmtMesin->close();
}

echo json_encode($machines);
