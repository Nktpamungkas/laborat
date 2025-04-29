<?php
include "../../koneksi.php";

header('Content-Type: application/json');

if (isset($_GET['code'])) {
    $code = mysqli_real_escape_string($con, $_GET['code']);
    $query = mysqli_query($con, "SELECT product_name FROM master_suhu WHERE code = '$code' LIMIT 1");

    if ($row = mysqli_fetch_assoc($query)) {
        echo json_encode([
            'status' => 'success',
            'product_name' => $row['product_name']
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Kode tidak ditemukan'
        ]);
    }
}
