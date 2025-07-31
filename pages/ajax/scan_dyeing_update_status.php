<?php
session_start();
include '../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['no_resep'])) {
    $no_resep = $_POST['no_resep'];
    $userDyeing = $_SESSION['userLAB'] ?? '';
    $isForceStop = isset($_POST['force_stop']) && $_POST['force_stop'] === 'true';

    if ($isForceStop) {
        $stmtSD = $con->prepare("
            UPDATE tbl_preliminary_schedule 
            SET status = 'stop_dyeing'
            WHERE no_resep = ? AND is_old_cycle = 0
        ");
        $stmtSD->bind_param("s", $no_resep);

        if ($stmtSD->execute()) {
            echo json_encode(["success" => true, "new_status" => 'stop_dyeing']);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }

        $stmtSD->close();
        $con->close();
        exit;
    }

    $stmt = $con->prepare("SELECT COUNT(*) AS total, SUM(status = 'in_progress_dispensing') AS matching FROM tbl_preliminary_schedule WHERE no_resep = ? AND is_old_cycle = 0");
    $stmt->bind_param("s", $no_resep);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $total = (int)$row['total'];
        $matching = (int)$row['matching'];

        if ($total > 0 && $total === $matching) {
            $stmt->close();
            $next_status = 'in_progress_dyeing';

            $update = $con->prepare("
                UPDATE tbl_preliminary_schedule 
                SET status = ?, dyeing_start = NOW(), user_dyeing = ?
                WHERE no_resep = ? AND is_old_cycle = 0
            ");
            $update->bind_param("sss", $next_status, $userDyeing, $no_resep);

            if ($update->execute()) {
                echo json_encode(["success" => true, "new_status" => $next_status]);
                resetOrderIndexIfDone($con);
            } else {
                http_response_code(500);
                echo json_encode(["success" => false, "error" => $update->error]);
            }

            $update->close();
        } else {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => "Status tidak valid."]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "error" => "Data tidak ditemukan."]);
    }

    $stmt->close();
    $con->close();
}

function resetOrderIndexIfDone($con): void {
    $codes = ['1', '2', '3'];

    foreach ($codes as $code) {
        $stmt = $con->prepare("
            SELECT COUNT(*) FROM tbl_preliminary_schedule ps
            LEFT JOIN master_suhu ms ON ps.code = ms.code
            WHERE ms.dispensing = ? AND ps.status IN ('scheduled', 'in_progress_dispensing')
        ");
        if (!$stmt) continue;

        $stmt->bind_param("s", $code);
        $stmt->execute();
        $count = 0;
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ((int)$count === 0) {
            $update = $con->prepare("
                UPDATE tbl_preliminary_schedule ps
                LEFT JOIN master_suhu ms ON ps.code = ms.code
                SET ps.order_index = NULL, ps.pass_dispensing = 1
                WHERE ms.dispensing = ? AND ps.is_old_cycle = 0
            ");
            if ($update) {
                $update->bind_param("s", $code);
                $update->execute();
                $update->close();
            }
        }
    }
}
?>