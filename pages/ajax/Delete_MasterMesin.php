<?php
include "../../koneksi.php";
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $delete = mysqli_query($con, "DELETE FROM master_mesin WHERE id = $id");

    if ($delete) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal menghapus data.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Permintaan tidak valid.'
    ]);
}
