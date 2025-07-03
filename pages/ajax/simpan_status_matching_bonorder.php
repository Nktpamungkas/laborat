<?php
include "../../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salesorder = mysqli_real_escape_string($con, $_POST['salesorder'] ?? '');
    $orderline  = mysqli_real_escape_string($con, $_POST['orderline'] ?? '');
    $warna      = mysqli_real_escape_string($con, $_POST['warna'] ?? '');
    $benang     = mysqli_real_escape_string($con, $_POST['benang'] ?? '');
    $po         = mysqli_real_escape_string($con, $_POST['po_greige'] ?? '');
    $pic        = mysqli_real_escape_string($con, $_POST['pic_check'] ?? '');
    $status     = mysqli_real_escape_string($con, $_POST['status_bonorder'] ?? '');

    if (empty($pic) || empty($status)) {
        echo "PIC dan Status harus dipilih!";
        exit;
    }

    // Cek apakah data sudah ada berdasarkan unique key
    $checkSql = "SELECT * FROM status_matching_bon_order
                 WHERE salesorder = '$salesorder'
                   AND orderline = '$orderline'
                   AND warna = '$warna'
                   AND po_greige = '$po'
                 LIMIT 1";
    $checkResult = mysqli_query($con, $checkSql);

    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        // Data sudah ada -> lakukan update
        $updateSql = "UPDATE status_matching_bon_order SET 
                        benang = '$benang',
                        pic_check = '$pic',
                        status_bonorder = '$status'
                      WHERE salesorder = '$salesorder'
                        AND orderline = '$orderline'
                        AND warna = '$warna'
                        AND po_greige = '$po'";

        if (mysqli_query($con, $updateSql)) {
            echo "Data berhasil diupdate!";
        } else {
            echo "Gagal update: " . mysqli_error($con);
        }
    } else {
        // Data belum ada -> lakukan insert
        $insertSql = "INSERT INTO status_matching_bon_order 
                      (salesorder, orderline, warna, benang, po_greige, pic_check, status_bonorder)
                      VALUES ('$salesorder', '$orderline', '$warna', '$benang', '$po', '$pic', '$status')";

        if (mysqli_query($con, $insertSql)) {
            echo "Data berhasil disimpan!";
        } else {
            echo "Gagal simpan: " . mysqli_error($con);
        }
    }
}
?>
