<?php
session_start();
include '../../koneksi.php';

header('Content-Type: application/json');

$userDarkroomStart = $_SESSION['userLAB'] ?? '';

if (!$userDarkroomStart) {
    echo json_encode([
        'success' => false,
        'message' => 'Session telah habis, silahkan login ulang terlebih dahulu!'
    ]);
    exit;
}

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Data tidak valid."]);
    exit;
}

$allNoResep = array_merge(
    $data['repeat'] ?? [],
    $data['end'] ?? [],
    $data['progress'] ?? []
);

if (empty($allNoResep)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Tidak ada data yang dikirim."]);
    exit;
}

$con->begin_transaction();

try {
    foreach ($data['repeat'] ?? [] as $no_resep) {
        processUpdate($con, $no_resep, ['in_progress_dyeing', 'stop_dyeing'], 'repeat', $userDarkroomStart);
    }

    foreach ($data['end'] ?? [] as $no_resep) {
        processUpdate($con, $no_resep, ['in_progress_dyeing', 'stop_dyeing'], 'end', $userDarkroomStart, true);
    }

    foreach ($data['progress'] ?? [] as $no_resep) {
        processUpdate($con, $no_resep, ['in_progress_dyeing', 'stop_dyeing'], 'in_progress_darkroom', $userDarkroomStart);
    }

    $con->commit();

    echo json_encode([
        "success" => true,
        "message" => "Semua data berhasil diproses."
    ]);
} catch (Exception $e) {
    $con->rollback();
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Gagal memproses batch: " . $e->getMessage()
    ]);
}

$con->close();


function processUpdate($con, $no_resep, $expected_statuses, $new_status, $userDarkroomStart, $update_end_time = false) {
    $stmt = $con->prepare("SELECT status FROM tbl_preliminary_schedule WHERE no_resep = ? AND is_old_cycle = 0");
    $stmt->bind_param("s", $no_resep);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || !$row = $result->fetch_assoc()) {
        throw new Exception("No. Resep $no_resep tidak ditemukan.");
    }

    // Cek apakah status sekarang termasuk yang diizinkan
    if (!in_array($row['status'], (array)$expected_statuses)) {
        throw new Exception("Status No. Resep $no_resep tidak sesuai ($row[status]).");
    }

    $stmt->close();

    // Update berdasarkan jenis transisi status
    if ($update_end_time) {
        $update = $con->prepare("
            UPDATE tbl_preliminary_schedule 
            SET status = ?, sekali_celup = NOW(), user_darkroom_end = ?
            WHERE no_resep = ? AND is_old_cycle = 0
        ");
    } elseif ($new_status === 'in_progress_darkroom') {
        $update = $con->prepare("
            UPDATE tbl_preliminary_schedule 
            SET status = ?, darkroom_start = NOW(), user_darkroom_start = ?
            WHERE no_resep = ? AND is_old_cycle = 0
        ");
    } else {
        $update = $con->prepare("
            UPDATE tbl_preliminary_schedule 
            SET status = ?, user_darkroom_start = ?
            WHERE no_resep = ? AND is_old_cycle = 0
        ");
    }

    $update->bind_param("sss", $new_status, $userDarkroomStart, $no_resep);

    if (!$update->execute()) {
        throw new Exception("Update gagal untuk $no_resep: " . $update->error);
    }

    $update->close();
}