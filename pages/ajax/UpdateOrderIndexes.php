<?php
header('Content-Type: application/json');
include "../../koneksi.php";

$data = json_decode(file_get_contents("php://input"), true);
$orders = $data['orders'] ?? [];

try {
    foreach ($orders as $item) {
        $id = intval($item['id']);
        $orderIndex = intval($item['order_index']);
        mysqli_query($con, "UPDATE tbl_preliminary_schedule SET order_index = $orderIndex WHERE id = $id");
    }

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
