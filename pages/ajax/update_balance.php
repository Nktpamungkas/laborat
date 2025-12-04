<?php
session_start();
include "../../koneksi.php";
header('Content-Type: application/json');

try {
    $element_id = trim($_POST['element_id'] ?? '');
    if (!$element_id) throw new Exception('element_id required');

    $decosub01 = trim($_POST['decosub01'] ?? "");
    $decosub02 = trim($_POST['decosub02'] ?? "");
    $decosub03 = trim($_POST['decosub03'] ?? "");
    $decosub04 = trim($_POST['decosub04'] ?? "");

    $warehouse_zone_code     = trim($_POST['warehouse_zone_code'] ?? "");
    $warehouse_location_code = trim($_POST['warehouse_location_code'] ?? "");

    $quality_level_code = trim($_POST['quality_level_code'] ?? "");
    $lot_code           = trim($_POST['lot_code'] ?? "");
    $project_code       = trim($_POST['project_code'] ?? "");
    $g_b                = trim($_POST['g_b'] ?? "");

    $primary_qty   = floatval($_POST['primary_quantity'] ?? 0);
    $secondary_qty = floatval($_POST['secondary_quantity'] ?? 0);

    $UPDATEDBY = $_SESSION['userLAB'] ?? 'anonymous';

    // Do not update quantity fields here — quantities must remain unchanged during edit
    $sql = "UPDATE balance SET
        DECOSUBCODE01 = ?,
        DECOSUBCODE02 = ?,
        DECOSUBCODE03 = ?,
        DECOSUBCODE04 = ?,
        WHSLOCATIONWAREHOUSEZONECODE = ?,
        WAREHOUSELOCATIONCODE = ?,
        QUALITYLEVELCODE = ?,
        LOTCODE = ?,
        PROJECTCODE = ?,
        G_B = ?,
        LASTUPDATEDATETIME = NOW(),
        LASTUPDATEDATETIMEUTC = NOW()
        WHERE NUMBERID = ? LIMIT 1";

    $stmt = $con->prepare($sql);
    if (!$stmt) throw new Exception('Prepare failed: ' . $con->error);

    // 11 strings: 10 fields above + element_id
    $stmt->bind_param('sssssssssss',
        $decosub01,
        $decosub02,
        $decosub03,
        $decosub04,
        $warehouse_zone_code,
        $warehouse_location_code,
        $quality_level_code,
        $lot_code,
        $project_code,
        $g_b,
        $element_id
    );

    $ok = $stmt->execute();
    if (!$ok) throw new Exception('Execute failed: ' . $stmt->error);

    $stmt->close();

    echo json_encode(['status' => 'success', 'message' => 'Element updated']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

?>
