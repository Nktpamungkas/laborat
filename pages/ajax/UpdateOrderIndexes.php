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

// $data = json_decode(file_get_contents('php://input'), true);

// if (!$data) {
//     echo json_encode(['success' => false, 'error' => 'No data received']);
//     exit;
// }

// $success = true;

// foreach ($data as $item) {
//     $id = intval($item['id']);
//     $orderIndex = intval($item['order_index']);
//     $rowNumber = intval($item['row_number']);
//     $cycleNumber = intval($item['cycle_number']);

//     $sql = "UPDATE tbl_preliminary_schedule 
//             SET order_index = $orderIndex, row_number = $rowNumber, cycle_number = $cycleNumber 
//             WHERE id = $id";
//     mysqli_query($con, $sql);
// }


// echo json_encode(['success' => $success]);