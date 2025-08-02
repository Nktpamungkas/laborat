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
        WHERE tbl_preliminary_schedule.status NOT IN ('ready')
        ORDER BY
            CASE 
                WHEN tbl_matching.jenis_matching IN ('LD', 'LD NOW') THEN 1
                WHEN tbl_matching.jenis_matching IN ('Matching Ulang', 'Matching Ulang NOW', 'Matching Development', 'Perbaikan', 'Perbaikan NOW') THEN 2
                ELSE 3
            END,
            CASE 
                WHEN tbl_preliminary_schedule.order_index > 0 THEN 0 
                ELSE 1 
            END, 
            tbl_preliminary_schedule.order_index ASC,
            master_suhu.suhu DESC, 
            master_suhu.waktu DESC, 
            tbl_preliminary_schedule.no_resep,
            tbl_preliminary_schedule.no_machine ASC,
            tbl_preliminary_schedule.is_old_data ASC
    ");

    $data = [];
    $usedIndexes = [];

    while ($row = mysqli_fetch_assoc($result)) {
        if ((int)$row['order_index'] > 0) {
            $usedIndexes[] = (int)$row['order_index'];
        }
        if ((int)$row['pass_dispensing'] == 0 || (int)$row['order_index'] > 0) {
            $data[] = $row;
        }
    }

    // Lengkapi order_index jika masih kosong
    $nextIndex = 1;
    foreach ($data as &$row) {
        if ((int)$row['order_index'] === 0) {
            while (in_array($nextIndex, $usedIndexes)) {
                $nextIndex++;
            }

            $id = (int)$row['id'];
            mysqli_query($con, "UPDATE tbl_preliminary_schedule SET order_index = $nextIndex WHERE id = $id AND pass_dispensing = 0");
            $row['order_index'] = $nextIndex;
            $usedIndexes[] = $nextIndex;
            $nextIndex++;
        }
    }
    unset($row);

    // Group by dispensing
    $grouped = ['1' => [], '2' => [], '3' => []];
    foreach ($data as $row) {
        $code = $row['dispensing'] ?? '';
        if (in_array($code, ['1', '2', '3'])) {
            $grouped[$code][] = $row;
        }
    }

    $finalData = [];
    $rowsPerCycle = 16;

    foreach ($grouped as $dispCode => $items) {
        usort($items, fn($a, $b) => $a['order_index'] - $b['order_index']);

        $rowCounter = 0;
        $cycleCounter = 1;

        foreach ($items as &$item) {
            $rowCounter++;
            $id = (int)$item['id'];

            // Kalau belum ada row_number / cycle_number, simpan ke DB
            if ((int)$item['row_number'] === 0 || $item['row_number'] === null) {
                mysqli_query($con, "
                    UPDATE tbl_preliminary_schedule 
                    SET row_number = $rowCounter, cycle_number = $cycleCounter 
                    WHERE id = $id
                ");
            }

            // Tetap pakai data dari database
            $item['rowNumber'] = $item['row_number'];
            $item['cycleNumber'] = $item['cycle_number'];

            if ($rowCounter >= $rowsPerCycle) {
                $cycleCounter++;
                $rowCounter = 0;
            }

            $finalData[] = $item;
        }
    }

    echo json_encode($finalData);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
