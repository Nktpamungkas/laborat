<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set("display_errors", 1);

include "../../koneksi.php"; // pastikan file ini hanya koneksi

try {
    // $result = mysqli_query($con, "SELECT * FROM tbl_preliminary_schedule ORDER BY CAST(SUBSTRING_INDEX(temp, '°', 1) AS UNSIGNED) DESC, id DESC");
    $result = mysqli_query($con, "SELECT * FROM tbl_preliminary_schedule ORDER BY id DESC");

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
