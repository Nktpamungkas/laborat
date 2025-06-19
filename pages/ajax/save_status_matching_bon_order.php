<?php
include "../../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salesOrderCode = mysqli_real_escape_string($con, $_POST['sales_order_code']);
    $poGreige       = mysqli_real_escape_string($con, $_POST['po_greige']);
    $pic            = mysqli_real_escape_string($con, $_POST['pic']);
    $status         = mysqli_real_escape_string($con, $_POST['status']);

    // Cek apakah data sudah ada
    $cek = mysqli_query($con, "SELECT id FROM status_matching_bon_order 
                               WHERE sales_order_code = '$salesOrderCode' 
                               AND po_greige = '$poGreige'");

    if (mysqli_num_rows($cek) > 0) {
        // Update jika sudah ada
        $update = mysqli_query($con, "UPDATE status_matching_bon_order 
            SET pic_check = '$pic', status_bon_order = '$status' 
            WHERE sales_order_code = '$salesOrderCode' 
            AND po_greige = '$poGreige'");

        echo $update ? 'updated' : 'failed';
    } else {
        // Insert jika belum ada
        $insert = mysqli_query($con, "INSERT INTO status_matching_bon_order 
            (sales_order_code, po_greige, pic_check, status_bon_order) 
            VALUES ('$salesOrderCode', '$poGreige', '$pic', '$status')");

        echo $insert ? 'saved' : 'failed';
    }
}
