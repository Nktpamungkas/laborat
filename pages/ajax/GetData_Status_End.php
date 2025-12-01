<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set("display_errors", 1);

include "../../koneksi.php";

try {
    $statuses = [
        'end',
    ];

    $statusList = "'" . implode("','", $statuses) . "'";
    
    // ambil rcode dari GET (karena kita kirim sebagai query string)
    $rcode = isset($_GET['rcode']) ? trim($_GET['rcode']) : '';
    // siapkan filter tambahan (optional)
    $filterResep = '';
    if ($rcode !== '') {
        $safeRcode = mysqli_real_escape_string($con, $rcode);

        // filter ke no_resep asli dan hasil cutting (DR-xxx)
        $filterResep = "AND tps.no_resep LIKE '%{$safeRcode}%'";
    }

    $result = mysqli_query($con, "SELECT 
                                        tps.*, 
                                        ms.product_name,
                                        ms.suhu,
                                        ms.waktu,
                                        ms.dispensing,
                                        tsm.grp,
                                        tm.warna,
                                        CASE 
                                                WHEN LEFT(tps.no_resep, 2) = 'DR' 
                                                THEN SUBSTRING_INDEX(tps.no_resep, '-', 1)
                                                ELSE tps.no_resep
                                        END AS no_resep_cutting
                                -- 		lsm.status AS info
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
                                WHERE
                                    1=1
                                    {$filterResep}
                                ORDER BY 
                                        tps.id ASC LIMIT 100");
    // prepared statement untuk ambil status terakhir di log_status_matching
    $stmt = $con->prepare("SELECT `status`
                            FROM log_status_matching
                            WHERE ids = ?
                            ORDER BY id DESC
                            LIMIT 1");

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $no_resep_cutting = $row['no_resep_cutting'];

        $lastStatus = null;

        if ($stmt) {
            $stmt->bind_param('s', $no_resep_cutting);
            $stmt->execute();
            $res2 = $stmt->get_result();
            if ($r2 = $res2->fetch_assoc()) {
                $lastStatus = $r2['status'];
            }
        }

        // tambahin field baru, misal namanya 'status_log'
        $row['info'] = $lastStatus; // bisa null kalau tidak ada
        
        $data[] = $row;
    }

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
