<?php

    session_start();
    include '../../koneksi.php';

    $isScheduling = "UPDATE tbl_is_scheduling SET is_scheduling = 1";
    mysqli_query($con, $isScheduling);

    // $currentDate = date('Y-m-d');       // Misal: 2025-05-09
    // $datePrefix = date('Ymd');          // Untuk prefix ID: 20250509

    // // Ambil data terakhir dari DB
    // $result = mysqli_query($con, "SELECT last_group_date, last_group_number FROM tbl_is_scheduling LIMIT 1");
    // $row = mysqli_fetch_assoc($result);

    // $lastDate = $row['last_group_date'];
    // $lastNumber = $row['last_group_number'];

    // // Reset nomor urut jika tanggal berbeda
    // if ($lastDate !== $currentDate) {
    //     $groupNumber = 1;
    // } else {
    //     $groupNumber = $lastNumber + 1;
    // }

    // // Format ID Group
    // $groupId = $datePrefix . '_' . str_pad($groupNumber, 4, '0', STR_PAD_LEFT);

    // // Update semua baris dengan status 'ready'
    // mysqli_query($con, "UPDATE tbl_preliminary_schedule SET id_group = '$groupId' WHERE status = 'ready'");

    // // Simpan tanggal dan nomor urut terbaru
    // mysqli_query($con, "UPDATE tbl_is_scheduling SET last_group_date = '$currentDate', last_group_number = $groupNumber");

    // echo "ID Group baru: $groupId berhasil diterapkan.";