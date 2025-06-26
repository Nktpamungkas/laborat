<?php
include '../../koneksi.php';

$query = "SELECT code, product_name FROM master_suhu ORDER BY suhu ASC, waktu ASC";
$result = mysqli_query($con, $query);

$options = [];
while ($row = mysqli_fetch_assoc($result)) {
    $options[] = [
        'code' => $row['code'],
        'label' => $row['product_name']
    ];
}
header('Content-Type: application/json');
echo json_encode($options);
