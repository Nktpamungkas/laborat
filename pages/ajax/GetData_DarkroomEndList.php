<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set("display_errors", 1);

include "../../koneksi.php";

try {
    $statuses = [
        'in_progress_darkroom',
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
            tm.warna
        FROM tbl_preliminary_schedule tps
        INNER JOIN (
            SELECT MIN(id) AS id
            FROM tbl_preliminary_schedule
            WHERE status IN ($statusList)
            GROUP BY no_resep
        ) AS sub ON tps.id = sub.id
        LEFT JOIN master_suhu ms ON tps.code = ms.code
        LEFT JOIN tbl_matching tm
                ON (
                    CASE 
                        WHEN LEFT(tps.no_resep, 2) = 'DR' THEN LEFT(tps.no_resep, LENGTH(tps.no_resep) - 2)
                        ELSE tps.no_resep
                    END
                ) = tm.no_resep
        LEFT JOIN tbl_status_matching tsm 
                ON (
                    CASE 
                        WHEN LEFT(tps.no_resep, 2) = 'DR' THEN LEFT(tps.no_resep, LENGTH(tps.no_resep) - 2)
                        ELSE tps.no_resep
                    END
                ) = tsm.idm
        ORDER BY 
            (tps.status = 'in_progress_darkroom') DESC,
            tps.id ASC
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
