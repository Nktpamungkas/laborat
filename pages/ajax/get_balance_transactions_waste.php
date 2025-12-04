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
$sql = "SELECT qty,
            created_at
    FROM balance_transactions
    WHERE element_id = ? AND action = 'Waste'
    ORDER BY created_at DESC";

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
        'qty' => floatval($row['qty']),
        'created_at' => $row['created_at'],
    ];
}
$stmt->close();
echo json_encode(['data' => $data]);
exit;
