<?php
header('Content-Type: application/json');
include "../../koneksi.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id'] ?? 0);
$no_machine = mysqli_real_escape_string($con, $data['no_machine'] ?? '');

if ($id && $no_machine !== '') {
    $res = mysqli_query($con, "SELECT is_old_data, id_group FROM tbl_preliminary_schedule WHERE id = $id LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    $isOldData = intval($row['is_old_data'] ?? 0);
    $existingGroupId = $row['id_group'] ?? '';

    if ($isOldData === 1) {
        // Cek apakah di mesin target ada data old_data
        $check = mysqli_query($con, "
            SELECT COUNT(*) AS total 
            FROM tbl_preliminary_schedule 
            WHERE no_machine = '$no_machine' AND is_old_data = 1 AND is_old_cycle = 0 AND id_group = $existingGroupId
        ");
        $checkRow = mysqli_fetch_assoc($check);
        $oldCountInTarget = intval($checkRow['total']);

        if ($oldCountInTarget === 0) {
            mysqli_query($con, "UPDATE tbl_preliminary_schedule SET is_old_data = 0 WHERE id = $id");
        }
    }

    $update = mysqli_query($con, "UPDATE tbl_preliminary_schedule SET no_machine = '$no_machine' WHERE id = $id");

    if ($update) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => mysqli_error($con)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}