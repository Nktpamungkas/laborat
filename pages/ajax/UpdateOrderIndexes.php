<?php
include "../../koneksi.php";
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

foreach ($data as $item) {
    $id = intval($item['id']);
    $index = intval($item['order_index']);
    mysqli_query($con, "UPDATE tbl_preliminary_schedule SET order_index = $index WHERE id = $id");
}

echo json_encode(["success" => true]);
