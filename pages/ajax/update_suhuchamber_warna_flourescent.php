<?php
    ini_set("error_reporting", 1);
    include "../../koneksi.php";
    session_start();

    $idm        = $_GET['idm'] ?? '';
    $setting    = $_POST['setting'] ?? '';
    $value      = $_POST['value'] ?? null;

    // Validasi kolom yang boleh di-update
    $allowed = ['suhu_chamber', 'warna_flourescent'];
        if (!$idm || !in_array($setting, $allowed)) {
        exit('Invalid request');
    }

    // 1. Ambil no_resep dari tbl_status_matching
    $query      = "SELECT idm AS no_resep FROM tbl_status_matching WHERE id = '$idm'";
    $result     = mysqli_query($con, $query);
    $row        = mysqli_fetch_assoc($result);
    $no_resep   = $row['no_resep'] ?? '';

    if ($no_resep) {
        // 2. Update tbl_matching pakai no_resep
        $update = "UPDATE tbl_matching SET $setting = '$value' WHERE no_resep = '$no_resep'";
        if (mysqli_query($con, $update)) {
            echo 'OK';
        } else {
            echo 'ERROR';
        }
    } else {
        echo 'ERROR';
    }

    mysqli_close($con);
?>
