<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "../../koneksi.php";

$no_resep = $_GET['no_resep'] ?? '';
$response = ['success' => false, 'codes' => []];

if ($no_resep) {
    $stmt = $con->prepare("SELECT DISTINCT code FROM tbl_preliminary_schedule WHERE no_resep = ? AND darkroom_end IS NOT NULL");
    $stmt->bind_param("s", $no_resep);
    $stmt->execute();
    $result = $stmt->get_result();

    $codes = [];
    while ($row = $result->fetch_assoc()) {
        $codes[] = $row['code'];
    }

    if (!empty($codes)) {
        $response['success'] = true;
        $response['codes'] = $codes;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
