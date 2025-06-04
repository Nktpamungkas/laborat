<?php
header('Content-Type: application/json');
include "../../koneksi.php";

$data = json_decode(file_get_contents("php://input"), true);
$fromId = intval($data['from_id']);
$toId = intval($data['to_id']);

try {
    $getIndexes = mysqli_query($con, "SELECT id, order_index FROM tbl_preliminary_schedule WHERE id IN ($fromId, $toId)");
    $indexes = [];
    while ($row = mysqli_fetch_assoc($getIndexes)) {
        $indexes[$row['id']] = $row['order_index'];
    }

    if (count($indexes) == 2) {
        mysqli_query($con, "UPDATE tbl_preliminary_schedule SET order_index = {$indexes[$toId]} WHERE id = $fromId");
        mysqli_query($con, "UPDATE tbl_preliminary_schedule SET order_index = {$indexes[$fromId]} WHERE id = $toId");

        echo json_encode(['success' => true]);
    } else {
        throw new Exception("Data tidak lengkap.");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
