<?php
include "../../koneksi.php";

 $dispensingCodes = ['1', '2', '3'];

foreach ($dispensingCodes as $code) {
    $cond = "ms.dispensing = '$code'";

    // ✅ Cek apakah masih ada data yang belum selesai untuk kategori ini
    $check = mysqli_query($con, "
        SELECT COUNT(*) AS total 
        FROM tbl_preliminary_schedule ps
        LEFT JOIN master_suhu ms ON ps.code = ms.code
        WHERE $cond AND ps.status IN ('scheduled', 'in_progress_dispensing')
    ");

    $result = mysqli_fetch_assoc($check);
    if ($result['total'] == 0) {
        // ✅ Reset semua order_index jadi 0 jika sudah selesai
        mysqli_query($con, "
            UPDATE tbl_preliminary_schedule 
            SET order_index = 0, pass_dispensing = 1
            WHERE id IN (
                SELECT ps.id 
                FROM tbl_preliminary_schedule ps
                LEFT JOIN master_suhu ms ON ps.code = ms.code
                WHERE $cond
            )
        ");
    }
}

