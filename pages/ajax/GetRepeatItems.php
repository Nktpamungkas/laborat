<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set("display_errors", 1);

include "../../koneksi.php";

try {
    $statuses = ['repeat'];
    $statusList = "'" . implode("','", $statuses) . "'";

    $result = mysqli_query($con, "
        SELECT 
            tps.*, 
            ms.product_name,
            ms.suhu,
            ms.waktu,
            ms.dispensing
        FROM tbl_preliminary_schedule tps
        INNER JOIN (
            SELECT MIN(id) AS id
            FROM tbl_preliminary_schedule
            WHERE status IN ($statusList)
            GROUP BY no_resep
        ) AS sub ON tps.id = sub.id
        LEFT JOIN master_suhu ms ON tps.code = ms.code
        ORDER BY (tps.status = 'repeat') DESC, tps.id ASC
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
