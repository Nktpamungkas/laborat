<?php
include '../../koneksi.php';

$response = ['success' => false];

$query = "UPDATE tbl_is_scheduling SET is_scheduling = 0";

if (mysqli_query($con, $query)) {
    $response['success'] = true;
}

header('Content-Type: application/json');
echo json_encode($response);
