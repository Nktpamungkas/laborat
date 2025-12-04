<?php
header('Content-Type: application/json');
require_once '../../koneksi.php';

$element_id = null;
if (isset($_GET['element_id'])) {
    $element_id = $_GET['element_id'];
} elseif (isset($_POST['element_id'])) {
    $element_id = $_POST['element_id'];
}

if (!$element_id) {
    echo json_encode(['data' => [], 'message' => 'element_id required']);
    exit;
}

// Query grouped by no_resep with conditional aggregates for Preliminary and Waste
$sql = "SELECT no_resep,
           COUNT(*) AS trx_count,
           SUM(qty) AS total_qty,
           MAX(created_at) AS last_date
    FROM balance_transactions
    WHERE element_id = ? AND action = 'Preliminary-Cycle'
    GROUP BY no_resep
    ORDER BY last_date DESC";

$stmt = $con->prepare($sql);
if (!$stmt) {
    echo json_encode(['data' => [], 'message' => 'Prepare failed: ' . $con->error]);
    exit;
}

$stmt->bind_param('s', $element_id);
$stmt->execute();
$res = $stmt->get_result();
$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = [
        'no_resep' => $row['no_resep'],
        'trx_count' => intval($row['trx_count']),
        'total_qty' => floatval($row['total_qty']),
        'last_date' => $row['last_date'],
    ];
}
$stmt->close();
echo json_encode(['data' => $data]);
exit;
