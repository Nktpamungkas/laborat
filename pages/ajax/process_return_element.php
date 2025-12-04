<?php
header('Content-Type: application/json');
require_once '../../koneksi.php';

// Simple JSON response helper
function res($success, $message = '', $data = null) {
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit;
}

if (!isset($_POST['element_id']) || !isset($_POST['qty_return']) 
    // || !isset($_POST['no_resep'])
) {
    res(false, 'element_id, qty_return and no_resep are required');
}

$element_id = $_POST['element_id'];
$qty_return_raw = $_POST['qty_return'];
// $no_resep = $_POST['no_resep'];

// sanitize and validate qty
if (!is_numeric($qty_return_raw)) {
    res(false, 'qty_return must be a number');
}
$qty_return = floatval($qty_return_raw);
if ($qty_return < 0) {
    res(false, 'qty_return cannot be a (-) decimal');
}

// Begin transaction
$con->begin_transaction();
try {
    // 1) Fetch current qty from balance
    $sql = "SELECT BASEPRIMARYQUANTITYUNIT FROM balance WHERE NUMBERID = ? LIMIT 1";
    $stmt = $con->prepare($sql);
    if (!$stmt) throw new Exception('Prepare failed (select): ' . $con->error);
    $stmt->bind_param('s', $element_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$row = $result->fetch_assoc()) {
        throw new Exception('Element not found');
    }
    $curr_qty = floatval($row['BASEPRIMARYQUANTITYUNIT']);
    $stmt->close();

    // 2) Overwrite qty (user requested overwrite, not subtract)
    $new_qty = $qty_return;

    // 3) Update balance table (overwrite)
    $sql = "UPDATE balance SET BASEPRIMARYQUANTITYUNIT = ?, LASTUPDATEDATETIME = NOW() WHERE NUMBERID = ?";
    $stmt = $con->prepare($sql);
    if (!$stmt) throw new Exception('Prepare failed (update): ' . $con->error);
    $stmt->bind_param('ds', $new_qty, $element_id);
    if (!$stmt->execute()) {
        throw new Exception('Update failed: ' . $stmt->error);
    }
    $stmt->close();

    // 5) Insert into balance_transactions with action 'Waste'
    $qty_waste_kg = $curr_qty - $new_qty;
    $qty_waste_gr = $qty_waste_kg * 1000;

    $qty_before = $curr_qty;
    $qty_after = $new_qty;
    $action = 'Waste';
    $uom = 'gr';
    $uom_balance = 'kg';
    $no_resep = NULL;

    $sql = "INSERT INTO balance_transactions (element_id, no_resep, action, uom, qty, uom_balance, qty_element_before, qty_element_after, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $con->prepare($sql);
    if (!$stmt) throw new Exception('Prepare failed (insert transaction): ' . $con->error);
    $stmt->bind_param('ssssdsdd', $element_id, $no_resep, $action, $uom, $qty_waste_gr, $uom_balance, $qty_before, $qty_after);
    if (!$stmt->execute()) {
        throw new Exception('Insert transaction failed: ' . $stmt->error);
    }
    $stmt->close();

    // 6) Delete row(s) in tbl_resep_element for that element_id AND specific no_resep
    $sql = "DELETE FROM tbl_resep_element WHERE element_id = ?";
    $stmt = $con->prepare($sql);
    if (!$stmt) throw new Exception('Prepare failed (delete): ' . $con->error);
    $stmt->bind_param('s', $element_id);
    if (!$stmt->execute()) {
        throw new Exception('Delete failed: ' . $stmt->error);
    }
    $affected = $stmt->affected_rows;
    $stmt->close();

    // Commit
    $con->commit();

    res(true, 'Return processed (overwrite)', ['new_qty' => $new_qty, 'deleted_rows' => $affected]);

} catch (Exception $e) {
    $con->rollback();
    res(false, $e->getMessage());
}
