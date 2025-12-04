<?php
header('Content-Type: application/json');

include "../../koneksi.php";

$id = $_POST['id'] ?? '';
$element_qty = $_POST['element_qty'] ?? '';

// Validasi
if (!$id || $element_qty === '') {
    echo json_encode([
        'success' => false,
        'message' => 'ID dan element_qty wajib diisi'
    ]);
    exit;
}

// Validasi qty tidak negatif
$qty = floatval($element_qty);
if ($qty < 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Qty tidak boleh negatif'
    ]);
    exit;
}

// Update ke database
$updateQuery = "UPDATE tbl_preliminary_schedule_element SET qty = ? WHERE tbl_preliminary_schedule_id = ?";
$stmt = mysqli_prepare($con, $updateQuery);
mysqli_stmt_bind_param($stmt, "di", $qty, $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        'success' => true,
        'message' => 'Qty berhasil diupdate'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal update database: ' . mysqli_stmt_error($stmt)
    ]);
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
