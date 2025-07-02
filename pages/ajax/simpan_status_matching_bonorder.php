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

    $sql = "INSERT INTO status_matching_bon_order (salesorder,
                                            orderline,
                                            warna,
                                            benang,
                                            po_greige,
                                            pic_check,
                                            status_bonorder)
            VALUES ('$salesorder', 
                    '$orderline', 
                    '$warna', 
                    '$benang', 
                    '$po', 
                    '$pic', 
                    '$status')";

    if (mysqli_query($con, $sql)) {
        echo "Data berhasil disimpan!";
    } else {
        echo "Gagal simpan: " . mysqli_error($con);
    }
}
