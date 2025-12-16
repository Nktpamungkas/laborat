<?php
session_start();
include '../../koneksi.php';

$sql = "SELECT
            CASE
                WHEN tps.is_bonresep = 1 THEN 'BON_RESEP'
                ELSE ms.`group`
            END AS `group`,
            tps.no_resep
        FROM
            tbl_preliminary_schedule tps
        LEFT JOIN master_suhu ms ON tps.code = ms.code 
        -- LEFT JOIN tbl_matching ON tps.no_resep = tbl_matching.no_resep
        LEFT JOIN tbl_matching ON 
            CASE WHEN LEFT(tps.no_resep, 2) = 'DR' 
                THEN LEFT(tps.no_resep, LENGTH(tps.no_resep) - 2)
                ELSE tps.no_resep
            END = tbl_matching.no_resep
        WHERE
            tps.STATUS = 'ready' 
        ORDER BY
            CASE 
                WHEN tbl_matching.jenis_matching IN ('LD', 'LD NOW') THEN 1
                WHEN tbl_matching.jenis_matching IN ('Matching Ulang', 'Matching Ulang NOW', 'Matching Development', 'Perbaikan' , 'Perbaikan NOW') THEN 2
                ELSE 3
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
