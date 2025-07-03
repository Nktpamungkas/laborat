<?php
header('Content-Type: application/json');
include "../../koneksi.php";

try {
    $result = mysqli_query($con, "
        SELECT 
            tbl_preliminary_schedule.*, 
            master_suhu.product_name,
            master_suhu.suhu,
            master_suhu.waktu,
            master_suhu.dispensing,
            tbl_matching.jenis_matching
        FROM tbl_preliminary_schedule
        LEFT JOIN master_suhu 
            ON tbl_preliminary_schedule.code = master_suhu.code
        LEFT JOIN tbl_matching ON 
			CASE WHEN LEFT(tbl_preliminary_schedule.no_resep, 2) = 'DR' 
				THEN LEFT(tbl_preliminary_schedule.no_resep, LENGTH(tbl_preliminary_schedule.no_resep) - 2)
				ELSE tbl_preliminary_schedule.no_resep
			END = tbl_matching.no_resep
        WHERE tbl_preliminary_schedule.status != 'ready'
        ORDER BY 
            CASE 
                WHEN tbl_matching.jenis_matching IN ('LD', 'LD NOW') THEN 1
                WHEN tbl_matching.jenis_matching IN ('Matching Ulang', 'Matching Ulang NOW', 'Matching Development', 'Perbaikan' , 'Perbaikan NOW') THEN 2
                ELSE 3
            END,
            CASE 
                WHEN tbl_preliminary_schedule.order_index > 0 THEN 0 
                ELSE 1 
            END, 
            tbl_preliminary_schedule.order_index ASC,
            master_suhu.suhu DESC, 
            master_suhu.waktu DESC, 
            tbl_preliminary_schedule.no_machine ASC,
            tbl_preliminary_schedule.is_old_data ASC
    ");

    // Cek dan isi order_index jika masih nol
    $counter = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['order_index'] == 0) {
            $id = $row['id'];
            mysqli_query($con, "UPDATE tbl_preliminary_schedule SET order_index = $counter WHERE id = $id");
            $row['order_index'] = $counter;
        }
        $data[] = $row;
        $counter++;
    }

    // Urutkan ulang array berdasarkan order_index agar sesuai drag
    usort($data, function($a, $b) {
        return $a['order_index'] - $b['order_index'];
    });

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
