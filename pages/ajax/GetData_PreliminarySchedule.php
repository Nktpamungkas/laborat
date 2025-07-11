<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set("display_errors", 1);

include "../../koneksi.php"; // pastikan file ini hanya koneksi

try {
    // $result = mysqli_query($con, "SELECT * FROM tbl_preliminary_schedule ORDER BY id DESC");
    $result = mysqli_query($con, "
        SELECT 
            tbl_preliminary_schedule.*, 
            master_suhu.product_name 
        FROM tbl_preliminary_schedule
        LEFT JOIN master_suhu 
            ON tbl_preliminary_schedule.code = master_suhu.code
        WHERE tbl_preliminary_schedule.status = 'ready'
        ORDER BY tbl_preliminary_schedule.id DESC
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
