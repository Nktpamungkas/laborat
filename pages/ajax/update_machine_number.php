<?php
header('Content-Type: application/json');
include "../../koneksi.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id'] ?? 0);
$no_machine = mysqli_real_escape_string($con, $data['no_machine'] ?? '');

if ($id && $no_machine !== '') {
    $result = mysqli_query($con, "UPDATE tbl_preliminary_schedule SET no_machine = '$no_machine' WHERE id = $id");

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => mysqli_error($con)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}
