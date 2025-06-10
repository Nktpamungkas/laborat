<?php
include "../../koneksi.php";

$query = mysqli_query($con, "SELECT * FROM tbl_cycle_log ORDER BY no_resep, cycle, stage, waktu");
$logs = [];
while ($row = mysqli_fetch_assoc($query)) {
    $logs[] = $row;
}
header('Content-Type: application/json');
echo json_encode($logs);