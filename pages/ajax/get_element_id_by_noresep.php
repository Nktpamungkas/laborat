<?php
header('Content-Type: application/json');
require_once '../../koneksi.php'; // sesuaikan

$response = [
    'success' => false,
    'element_id' => null,
];

// --- Validate input ---
if (!isset($_POST['no_resep']) || empty($_POST['no_resep'])) {
    echo json_encode(['success' => false, 'message' => 'no_resep required']);
    exit;
}

$no_resep = $_POST['no_resep'];

// normalize and if starts with DR then remove last 2 characters
$no_resep = trim($no_resep);
if (strtoupper(substr($no_resep, 0, 2)) === 'DR' && strlen($no_resep) > 2) {
    // remove last 2 characters
    $no_resep = substr($no_resep, 0, -2);
}

// --- Ambil element_id berdasarkan no_resep ---
$queryElement = " SELECT element_id, element_code
    FROM tbl_resep_element 
    WHERE no_resep = ?
    LIMIT 1
";

$stmt = $con->prepare($queryElement);
$stmt->bind_param("s", $no_resep);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $response['element_id'] = $row['element_id'];
    $response['element_code'] = $row['element_code'];
    $response['success'] = true;
}

echo json_encode($response);
exit;
