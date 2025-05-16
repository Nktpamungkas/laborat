<?php
session_start();
include '../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['no_resep'])) {
    $no_resep = $_POST['no_resep'];

    $stmt = $con->prepare("SELECT status FROM tbl_preliminary_schedule WHERE no_resep = ?");
    $stmt->bind_param("s", $no_resep);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $current_status = $row['status'];
        $next_status = null;

        if ($current_status === 'in_progress_darkroom') {
            $next_status = 'ok';
        } else {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => "Status tidak valid untuk tahap Darkroom."]);
            $stmt->close();
            $con->close();
            exit;
        }

        $stmt->close();
        $update = $con->prepare("UPDATE tbl_preliminary_schedule SET status = ?, darkroom_end = now() WHERE no_resep = ?");
        $update->bind_param("ss", $next_status, $no_resep);

        if ($update->execute()) {
            echo json_encode(["success" => true, "new_status" => $next_status]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $update->error]);
        }

        $update->close();
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "error" => "Data tidak ditemukan."]);
    }

    $con->close();
}
?>
