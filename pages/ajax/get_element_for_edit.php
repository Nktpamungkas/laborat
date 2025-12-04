<?php
header('Content-Type: application/json');
include "../../koneksi.php";

$response = ['success' => false, 'data' => null, 'message' => 'Unknown error'];

// accept element_id via GET or POST
$element_id = $_GET['element_id'] ?? $_POST['element_id'] ?? null;
if (!$element_id) {
    echo json_encode(['success' => false, 'message' => 'element_id required']);
    exit;
}

$sql = "SELECT
    NUMBERID as element_id,
    ELEMENTSCODE as element_code,
    DECOSUBCODE01 as decosub01,
    DECOSUBCODE02 as decosub02,
    DECOSUBCODE03 as decosub03,
    DECOSUBCODE04 as decosub04,
    WHSLOCATIONWAREHOUSEZONECODE as warehouse_zone_code,
    WAREHOUSELOCATIONCODE as warehouse_location_code,
    QUALITYLEVELCODE as quality_level_code,
    LOTCODE as lot_code,
    PROJECTCODE as project_code,
    G_B as g_b,
    BASEPRIMARYQUANTITYUNIT as primary_qty,
    BASESECONDARYQUANTITYUNIT as secondary_qty
    FROM balance WHERE NUMBERID = ? LIMIT 1";

$stmt = $con->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $con->error]);
    exit;
}

$stmt->bind_param('s', $element_id);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    // build a human-friendly item text
    $item_text = trim(($row['decosub01'] ?? '') . ' ' . ($row['decosub02'] ?? '') . ' ' . ($row['decosub03'] ?? '') . ' ' . ($row['decosub04'] ?? ''));

    $row['item_text'] = $item_text;
    // item_id not strictly available; we can use concatenated code if needed
    $row['item_id'] = trim(($row['decosub01'] ?? '') . ($row['decosub02'] ?? '') . ($row['decosub03'] ?? '') . ($row['decosub04'] ?? ''));

    $response['success'] = true;
    $response['data'] = $row;
    $response['message'] = 'OK';
} else {
    $response['message'] = 'Not found';
}

$stmt->close();
echo json_encode($response);
exit;

?>
