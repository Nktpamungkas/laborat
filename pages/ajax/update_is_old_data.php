<?php
include '../../koneksi.php';

if (isset($_POST['resepList'])) {
    $list = json_decode($_POST['resepList'], true);

    if (!is_array($list)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid format"]);
        exit;
    }

    $placeholders = implode(',', array_fill(0, count($list), '?'));
    $types = str_repeat('s', count($list));

    $stmt = $con->prepare("UPDATE tbl_preliminary_schedule SET is_old_data = 0 WHERE no_resep IN ($placeholders)");
    $stmt->bind_param($types, ...$list);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "updated" => count($list)]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to update."]);
    }

    $stmt->close();
    $con->close();
} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid or missing resepList"]);
}
?>
