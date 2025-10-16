<?php
include '../../koneksi.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? intval($_POST['status']) : 1;

if ($id <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID tidak valid'
    ]);
    exit;
}

$query = "UPDATE master_suhu SET status = ? WHERE id = ?";
$stmt = $con->prepare($query);

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Prepare statement gagal: ' . $con->error
    ]);
    exit;
}

if ($stmt->bind_param('ii', $status, $id) && $stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Status berhasil diperbarui'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal memperbarui status: ' . $stmt->error
    ]);
}
$stmt->close();
$con->close();
