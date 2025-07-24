<?php
include '../../koneksi.php';
header('Content-Type: application/json');
session_start();

$no_resep = trim($_POST['no_resep']);
$no_machine = trim($_POST['no_machine']);
$code = trim($_POST['temp']);
$id_group = trim($_POST['id_group']);
$qty = (int) trim($_POST['bottle_qty']);
$status = 'scheduled';
$username = $_SESSION['userLAB'];

$checkEnd = mysqli_query($con, "SELECT COUNT(*) as total FROM tbl_preliminary_schedule WHERE no_resep = '$no_resep' AND status = 'end'");
$dataEnd = mysqli_fetch_assoc($checkEnd);

if ($dataEnd['total'] > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'ERROR - data suffix di END!'
    ]);
    exit;
}

$checkHold = mysqli_query($con, "SELECT COUNT(*) as total FROM tbl_preliminary_schedule WHERE no_resep = '$no_resep' AND status = 'hold'");
$dataHold = mysqli_fetch_assoc($checkHold);

if ($dataHold['total'] > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'ERROR - data suffix di HOLD!'
    ]);
    exit;
}

// Ambil jumlah jadwal mesin saat ini
$countQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM tbl_preliminary_schedule WHERE no_machine = '$no_machine' AND status = 'scheduled'");
$countData = mysqli_fetch_assoc($countQuery);
$currentCount = (int) $countData['total'];

$maxAllowed = 24 - $currentCount;

if ($qty > $maxAllowed) {
    echo json_encode(["success" => false, "message" => "Jumlah botol melebihi kapasitas mesin (maksimal $maxAllowed)."]);
    exit;
}

if ($no_resep && $no_machine && $code && $id_group && $qty > 0) {

    $stmtInsert = $con->prepare("INSERT INTO tbl_preliminary_schedule (no_resep, no_machine, code, id_group, status, username, user_scheduled) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmtInsert->bind_param("sssssss", $no_resep, $no_machine, $code, $id_group, $status, $username, $username);

    $success = true;
    for ($i = 0; $i < $qty; $i++) {
        if (!$stmtInsert->execute()) {
            $success = false;
            break;
        }
    }

    $stmtInsert->close();

    if ($success) {
        $stmtGetDatetime = $con->prepare("
            SELECT creationdatetime 
            FROM tbl_preliminary_schedule 
            WHERE no_resep = ?
            ORDER BY id DESC 
            LIMIT 1
        ");
        $stmtGetDatetime->bind_param("s", $no_resep);
        $stmtGetDatetime->execute();
        $resultDatetime = $stmtGetDatetime->get_result();
        $row = $resultDatetime->fetch_assoc();
        $stmtGetDatetime->close();

        if ($row) {
            $latestDatetime = $row['creationdatetime'];

            // Cek apakah ada no_resep yang sama dan is_old_cycle = 0, selain data baru
            $stmtCheckOld = $con->prepare("
                SELECT id 
                FROM tbl_preliminary_schedule 
                WHERE no_resep = ? AND is_old_cycle = 0
                LIMIT 1
            ");
            $stmtCheckOld->bind_param("s", $no_resep);
            $stmtCheckOld->execute();
            $resultOld = $stmtCheckOld->get_result();
            $stmtCheckOld->close();

            if ($resultOld->num_rows > 0) {
                // Update creationdatetime pada data lama
                $stmtUpdate = $con->prepare("
                    UPDATE tbl_preliminary_schedule 
                    SET creationdatetime = ? 
                    WHERE no_resep = ? AND is_old_cycle = 0
                ");
                $stmtUpdate->bind_param("ss", $latestDatetime, $no_resep);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            }
        }
        echo json_encode(["success" => true, "message" => "Berhasil menambahkan $qty no.resep."]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menyimpan salah satu data."]);
    }

} else {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap atau jumlah tidak valid."]);
}
