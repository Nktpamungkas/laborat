<?php
include '../../koneksi.php';

if (isset($_POST['no_resep'])) {
    $no_resep = $_POST['no_resep'];

    $stmt = $conn->prepare("UPDATE dyeing_schedule SET is_old_data = 0 WHERE no_resep = ?");
    $stmt->bind_param("s", $no_resep);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to update."]);
    }

    $stmt->close();
    $conn->close();
}
?>
