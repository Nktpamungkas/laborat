<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../../koneksi.php';
header('Content-Type: application/json');

if (isset($_GET['no_machine'])) {
    $no_machine = mysqli_real_escape_string($con, strtolower($_GET['no_machine'])); 

    $query = "SELECT COUNT(*) AS count FROM master_mesin WHERE LOWER(no_machine) = '$no_machine'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        echo json_encode(['status' => 'exists']);
    } else {
        echo json_encode(['status' => 'not_exists']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No. Machine tidak valid']);
}



