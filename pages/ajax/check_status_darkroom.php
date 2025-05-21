<?php
header('Content-Type: application/json');
include '../../koneksi.php';

$no_resep = $_POST['no_resep'] ?? '';

if ($no_resep) {
    $query = mysqli_query($con, "SELECT status FROM tbl_preliminary_schedule WHERE no_resep = '$no_resep' LIMIT 1");
    if ($row = mysqli_fetch_assoc($query)) {
        echo json_encode(['status' => $row['status']]);
    } else {
        echo json_encode(['status' => 'not_found']);
    }
} else {
    echo json_encode(['status' => 'invalid']);
}
?>
