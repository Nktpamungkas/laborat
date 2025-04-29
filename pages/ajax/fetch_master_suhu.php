<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';

// Fetch data from master_suhu table
$query = "SELECT * FROM master_suhu";
$result = mysqli_query($con, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode(['data' => $data]);
?>
