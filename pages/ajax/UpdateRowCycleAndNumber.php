<?php
header('Content-Type: application/json');
include "../../koneksi.php";

try {
    // Ambil semua data yg tidak 'ready' dan punya order_index
    $result = mysqli_query($con, "
        SELECT 
            ps.id,
            ps.order_index,
            ms.dispensing
        FROM tbl_preliminary_schedule ps
        LEFT JOIN master_suhu ms ON ps.code = ms.code
        WHERE ps.status != 'ready'
        ORDER BY ms.dispensing, ps.order_index ASC
    ");

    $dataByDispensing = [
        "1" => [], // Poly
        "2" => [], // Cotton
        ""  => []  // White
    ];

    while ($row = mysqli_fetch_assoc($result)) {
        $code = trim($row['dispensing'] ?? '');
        if (!in_array($code, ['1', '2'])) $code = '';
        $dataByDispensing[$code][] = $row;
    }

    // Update per dispensing group
    foreach ($dataByDispensing as $code => $rows) {
        $rowsPerBlock = 16;
        $cycle = 1;

        for ($i = 0; $i < count($rows); $i++) {
            $row = $rows[$i];
            $rowNumber = ($i % $rowsPerBlock) + 1;

            if ($rowNumber === 1 && $i !== 0) $cycle++;

            $id = (int)$row['id'];
            $update = mysqli_query($con, "
                UPDATE tbl_preliminary_schedule 
                SET row_number = $rowNumber, cycle_number = $cycle 
                WHERE id = $id
            ");

            if (!$update) {
                throw new Exception("Gagal update ID $id: " . mysqli_error($con));
            }
        }
    }

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
