<?php
include "../../koneksi.php";

$code = $_POST['code'] ?? '';
$customer = $_POST['customer'] ?? '';
$tgl_approve_rmp = $_POST['tgl_approve_rmp'] ?? null;
$pic_lab = $_POST['pic_lab'] ?? '';
$status = $_POST['status'] ?? '';

$tgl_approve_lab = null;
$tgl_rejected_lab = null;

if ($status === 'Approved') {
    $tgl_approve_lab = date('Y-m-d');
} elseif ($status === 'Rejected') {
    $tgl_rejected_lab = date('Y-m-d');
}

if (!$code || !$customer || !$pic_lab || !$status) {
    echo "Data tidak lengkap.";
    exit;
}

$stmt = $con->prepare("INSERT INTO approval_bon_order 
    (code, customer, tgl_approve_rmp, tgl_approve_lab, tgl_rejected_lab, pic_lab, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $code, $customer, $tgl_approve_rmp, $tgl_approve_lab, $tgl_rejected_lab,  $pic_lab, $status);

if ($stmt->execute()) {
    echo "Data berhasil disimpan sebagai $status.";
} else {
    echo "Gagal menyimpan: " . $con->error;
}

$stmt->close();
$con->close();
?>
