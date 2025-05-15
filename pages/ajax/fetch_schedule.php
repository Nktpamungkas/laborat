<?php
session_start();
include '../../koneksi.php';

$sql = "SELECT ms.`group`, tps.no_resep
        FROM tbl_preliminary_schedule tps
        JOIN master_suhu ms ON tps.code = ms.code
        WHERE tps.status = 'ready'
        ORDER BY ms.`group`, tps.no_resep";
$result = mysqli_query($con, $sql);

$schedules = [];
while ($row = mysqli_fetch_assoc($result)) {
    $schedules[$row['group']][] = $row['no_resep'];
}

mysqli_close($con);

echo json_encode($schedules);
