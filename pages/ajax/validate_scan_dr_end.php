<?php
include '../../koneksi.php';
header('Content-Type: application/json');

$noResep = $_GET['no_resep'] ?? '';

if ($noResep === '') {
    echo json_encode(["valid" => false, "error" => "No. Resep kosong."]);
    exit;
}

$stmt = $con->prepare("
    SELECT COUNT(*) AS total 
    FROM tbl_preliminary_schedule 
    WHERE no_resep = ? AND status = 'in_progress_darkroom' AND is_old_cycle = 0
");
$stmt->bind_param("s", $noResep);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode(["valid" => ($data['total'] > 0)]);
