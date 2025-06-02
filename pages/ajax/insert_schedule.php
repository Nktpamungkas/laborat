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

    $stmt = $con->prepare("INSERT INTO tbl_preliminary_schedule (no_resep, no_machine, code, id_group, status, username) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $no_resep, $no_machine, $code, $id_group, $status, $username);

    $success = true;
    for ($i = 0; $i < $qty; $i++) {
        if (!$stmt->execute()) {
            $success = false;
            break;
        }
    }

    $stmt->close();

    if ($success) {
        echo json_encode(["success" => true, "message" => "Berhasil menambahkan $qty no.resep."]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menyimpan salah satu data."]);
    }

} else {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap atau jumlah tidak valid."]);
}
