<?php
    ini_set("error_reporting", 1);
    include "../../koneksi.php";
    session_start();
    $time = date('Y-m-d H:i:s');

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
            // 3. Log perubahan
            $SQL_rcode  = mysqli_query($con,"SELECT idm from tbl_status_matching where idm = '$idm' LIMIT 1");
            $rcode_ = mysqli_fetch_array($SQL_rcode);
            $ip_num = $_SERVER['REMOTE_ADDR'];
            mysqli_query($con,"INSERT INTO log_status_matching set 
                                    `ids` = '$rcode_[idm]',
                                    `status` = 'selesai',
                                    `info` = 'Perubaahan $setting menjadi $value',
                                    `do_by` = '$_SESSION[userLAB]', 
                                    `do_at` = '$time', 
                                    `ip_address` = '$ip_num'");
            echo 'OK';
        } else {
            echo 'ERROR';
        }
    } else {
        echo 'ERROR';
    }

    mysqli_close($con);
?>
