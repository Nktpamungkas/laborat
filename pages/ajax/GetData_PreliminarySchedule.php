<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set("display_errors", 1);

include "../../koneksi.php"; // pastikan file ini hanya koneksi

try {
    // $result = mysqli_query($con, "SELECT * FROM tbl_preliminary_schedule ORDER BY id DESC");
    $result = mysqli_query($con, "SELECT
                                        tbl_preliminary_schedule.*,
                                        master_suhu.product_name,
                                        tbl_matching.jenis_matching,
                                        tbl_preliminary_schedule_element.element_id,
                                        balance.ELEMENTSCODE AS element_code,
                                        tbl_preliminary_schedule_element.qty AS element_qty
                                    FROM
                                        tbl_preliminary_schedule
                                        LEFT JOIN master_suhu ON tbl_preliminary_schedule.CODE = master_suhu.CODE 
                                        LEFT JOIN tbl_matching ON 
                                                                CASE WHEN LEFT(tbl_preliminary_schedule.no_resep, 2) = 'DR' 
                                                                    THEN LEFT(tbl_preliminary_schedule.no_resep, LENGTH(tbl_preliminary_schedule.no_resep) - 2)
                                                                    ELSE tbl_preliminary_schedule.no_resep
                                                                END = tbl_matching.no_resep
                                        LEFT JOIN tbl_preliminary_schedule_element ON tbl_preliminary_schedule.id = tbl_preliminary_schedule_element.tbl_preliminary_schedule_id 
                                        LEFT JOIN balance ON tbl_preliminary_schedule_element.element_id = balance.numberid 
                                    WHERE
                                        tbl_preliminary_schedule.STATUS = 'ready' 
                                    ORDER BY
                                        tbl_preliminary_schedule.id DESC");


    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
