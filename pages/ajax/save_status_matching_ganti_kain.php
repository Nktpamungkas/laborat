<?php
include "../../koneksi.php";

if (!$con) {
    http_response_code(500);
    echo "Gagal koneksi DB";
    exit;
}

$id_gantikain = $_POST['id_gantikain'];
$pic_lab = $_POST['pic_lab'];
$status_lab = $_POST['status_lab'];

// Cek apakah sudah ada
$q = mysqli_query($con, "SELECT id FROM status_matching_ganti_kain WHERE id_gantikain = '$id_gantikain'");
if (mysqli_num_rows($q) > 0) {
    $result = mysqli_query($con, "UPDATE status_matching_ganti_kain SET pic_lab='$pic_lab', status_lab='$status_lab', updated_at=NOW() WHERE id_gantikain='$id_gantikain'");
} else {
    $result = mysqli_query($con, "INSERT INTO status_matching_ganti_kain (id_gantikain, pic_lab, status_lab) VALUES ('$id_gantikain', '$pic_lab', '$status_lab')");
}

if ($result) {
    echo "Sukses";
} else {
    http_response_code(500);
    echo "Gagal simpan";
}
