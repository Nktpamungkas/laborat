<?php
session_start();
include '../../koneksi.php';

$sql = "SELECT
            ms.`group`,
            tps.no_resep 
        FROM
            tbl_preliminary_schedule tps
            LEFT JOIN master_suhu ms ON tps.code = ms.code 
            LEFT JOIN tbl_matching ON tps.no_resep = tbl_matching.no_resep
        WHERE
            tps.STATUS = 'ready' 
        ORDER BY
            CASE 
                    WHEN tbl_matching.jenis_matching IN ('LD', 'LD NOW') THEN 1
                    WHEN tbl_matching.jenis_matching IN ('Matching Ulang', 'Matching Ulang NOW', 'Matching Development') THEN 2
                    WHEN tbl_matching.jenis_matching = 'Perbaikan' THEN 3
                    ELSE 4
            END,
            CASE 
                    WHEN tps.order_index > 0 THEN 0 
                    ELSE 1 
            END, 
            tps.order_index ASC,
            ms.suhu DESC, 
            ms.waktu DESC, 
            tps.no_resep ASC";
$result = mysqli_query($con, $sql);

$schedules = [];
while ($row = mysqli_fetch_assoc($result)) {
    $schedules[$row['group']][] = $row['no_resep'];
}

mysqli_close($con);

echo json_encode($schedules);
