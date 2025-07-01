<?php
include '../../koneksi.php';

$query = "SELECT code, product_name, program, dyeing, dispensing FROM master_suhu ORDER BY suhu ASC, waktu ASC";
$result = mysqli_query($con, $query);

$options = [];
while ($row = mysqli_fetch_assoc($result)) {
    $options[] = [
        'code' => $row['code'],
        'label' => $row['product_name'],
        'program' => $row['program'],
        'dyeing' => $row['dyeing'],
        'dispensing' => $row['dispensing']  
    ];
}
header('Content-Type: application/json');
echo json_encode($options);
