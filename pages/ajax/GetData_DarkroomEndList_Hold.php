<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set("display_errors", 1);

include "../../koneksi.php";

try {
    $statuses = [
        'hold',
    ];

    $statusList = "'" . implode("','", $statuses) . "'";
    
    $result = mysqli_query($con, "
        SELECT 
            tps.*, 
            ms.product_name,
            ms.suhu,
            ms.waktu,
            ms.dispensing,
            tsm.grp,
            tm.warna,
            lsm.status AS info
        FROM tbl_preliminary_schedule tps
        INNER JOIN (
            SELECT MIN(id) AS id
            FROM tbl_preliminary_schedule
            WHERE status IN ($statusList)
            GROUP BY no_resep
        ) AS sub 
            ON tps.id = sub.id
        LEFT JOIN master_suhu ms 
            ON tps.code = ms.code
        LEFT JOIN tbl_matching tm
            ON (
                CASE 
                    WHEN LEFT(tps.no_resep, 2) = 'DR' 
                    THEN SUBSTRING_INDEX(tps.no_resep, '-', 1)
                    ELSE tps.no_resep
                END
            ) = tm.no_resep
        LEFT JOIN tbl_status_matching tsm 
            ON (
                CASE 
                    WHEN LEFT(tps.no_resep, 2) = 'DR' 
                    THEN SUBSTRING_INDEX(tps.no_resep, '-', 1)
                    ELSE tps.no_resep
                END
            ) = tsm.idm
        LEFT JOIN (
            SELECT l1.ids, l1.status
            FROM log_status_matching l1
            INNER JOIN (
                SELECT ids, MAX(id) AS max_id
                FROM log_status_matching
                GROUP BY ids
            ) l2 
                ON l1.ids = l2.ids AND l1.id = l2.max_id
        ) lsm
            ON (
                CASE 
                    WHEN LEFT(tps.no_resep, 2) = 'DR' 
                    THEN SUBSTRING_INDEX(tps.no_resep, '-', 1)
                    ELSE tps.no_resep
                END
            ) = lsm.ids
        ORDER BY 
            tps.id ASC;
    ");

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
