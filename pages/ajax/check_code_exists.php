<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../../koneksi.php';
header('Content-Type: application/json');

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    
    $query = "SELECT code FROM master_suhu";
    $result = mysqli_query($con, $query);

    $duplicate = false;

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $dbCode = $row['code']; // ambil angka dari DB
            if ($dbCode === $code) {
                $duplicate = true;
                break;
            }
        }

        if ($duplicate) {
            echo json_encode(['status' => 'exists']);
        } else {
            echo json_encode(['status' => 'not_exists']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Query gagal: ' . mysqli_error($con)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Product name tidak valid']);
}


