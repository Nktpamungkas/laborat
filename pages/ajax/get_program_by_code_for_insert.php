<?php
include "../../koneksi.php";

header('Content-Type: application/json');


if (isset($_GET['code']) && isset($_GET['machine'])) {
    $code = mysqli_real_escape_string($con, $_GET['code']);
    $machine = mysqli_real_escape_string($con, $_GET['machine']);

    $groupQuery = mysqli_query($con, "SELECT id_group FROM tbl_preliminary_schedule WHERE no_machine = '$machine' LIMIT 1");
    
    if ($groupRow = mysqli_fetch_assoc($groupQuery)) {
        $id_group = $groupRow['id_group'];

        $checkCodeQuery = mysqli_query($con, "SELECT product_name FROM master_suhu WHERE code = '$code' AND `group` = '$id_group' LIMIT 1");

        if ($codeRow = mysqli_fetch_assoc($checkCodeQuery)) {
            echo json_encode([
                'status' => 'success',
                'product_name' => $codeRow['product_name'],
                'group' => $id_group
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kode tidak diperbolehkan untuk mesin ini'
            ]);
        }

    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mesin tidak ditemukan dalam preliminary schedule'
        ]);
    }

} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Parameter code dan machine wajib diisi'
    ]);
}

