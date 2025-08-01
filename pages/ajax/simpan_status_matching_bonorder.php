<?php
include "../../koneksi.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $today = date('Y-m-d H:i:s');
    $salesorder = mysqli_real_escape_string($con, $_POST['salesorder'] ?? '');
    $orderline  = mysqli_real_escape_string($con, $_POST['orderline'] ?? '');
    $warna      = mysqli_real_escape_string($con, $_POST['warna'] ?? '');
    $benang     = mysqli_real_escape_string($con, $_POST['benang'] ?? '');
    $po         = mysqli_real_escape_string($con, $_POST['po_greige'] ?? '');
    $pic        = mysqli_real_escape_string($con, $_POST['pic_check'] ?? '');
    $status     = mysqli_real_escape_string($con, $_POST['status_bonorder'] ?? '');
    $user       = mysqli_real_escape_string($con, $_POST['user'] ?? '');
    $ip       = mysqli_real_escape_string($con, $_POST['ip'] ?? '');

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

        $insertLog = "INSERT INTO tbl_log_history_matching 
                      (salesorder, orderline, warna, po_greige, benang, values_pic, values_status, ip_update, user_update, date_update, process)
                      VALUES ('$salesorder', '$orderline', '$warna', '$po', '$benang', '$pic', '$status', '$ip','$user', '$today', 'update' )";                
        if (mysqli_query($con, $updateSql) && mysqli_query($con, $insertLog)) {
            echo "Data berhasil diupdate!";
        } else {
            echo "Gagal update: " . mysqli_error($con);
        }
    } else {
        // Data belum ada -> lakukan insert
        $insertSql = "INSERT INTO status_matching_bon_order 
                      (salesorder, orderline, warna, benang, po_greige, pic_check, status_bonorder)
                      VALUES ('$salesorder', '$orderline', '$warna', '$benang', '$po', '$pic', '$status')";

        $insertLog = "INSERT INTO tbl_log_history_matching 
                      (salesorder, orderline, warna, po_greige, benang, values_pic, values_status, ip_update, user_update, date_update, process)
                      VALUES ('$salesorder', '$orderline', '$warna', '$po','$benang' , '$pic', '$status', '$ip','$user', '$today', 'insert' )";
        if (mysqli_query($con, $insertSql) && mysqli_query($con, $insertLog)) {
            echo "Data berhasil disimpan!";
        } else {
            echo "Gagal simpan: " . mysqli_error($con);
        }
    }
}
?>
