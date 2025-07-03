<?php
include '../../koneksi.php';

$no_resep = $_GET['no_resep'] ?? '';
$no_resep = mysqli_real_escape_string($con, $no_resep);

$result = mysqli_query($con, "SELECT temp_code, temp_code2 FROM tbl_matching WHERE no_resep = '$no_resep' LIMIT 1");

$data = ['temp_code' => '', 'temp_code2' => ''];

if ($row = mysqli_fetch_assoc($result)) {
    $data['temp_code'] = $row['temp_code'];
    $data['temp_code2'] = $row['temp_code2'];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
