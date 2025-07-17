<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "../../koneksi.php";

$no_resep = $_GET['no_resep'] ?? '';
$response = ['success' => false, 'codes' => []];

if ($no_resep) {
    $no_resep_base = $no_resep;
    $matching_column = 'temp_code';

    if (str_ends_with($no_resep, '-A')) {
        $no_resep_base = substr($no_resep, 0, -2);
        // kolom tetap 'temp_code'
    } elseif (str_ends_with($no_resep, '-B')) {
        $no_resep_base = substr($no_resep, 0, -2);
        $matching_column = 'temp_code2';
    }

    $query = "
        SELECT DISTINCT code AS code FROM tbl_preliminary_schedule 
        WHERE no_resep = ? AND status = 'repeat' AND code IS NOT NULL AND code <> '-'
        UNION
        SELECT DISTINCT $matching_column AS code FROM tbl_matching 
        WHERE no_resep = ? AND $matching_column IS NOT NULL AND $matching_column <> '-'
    ";

    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $no_resep, $no_resep_base);
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
