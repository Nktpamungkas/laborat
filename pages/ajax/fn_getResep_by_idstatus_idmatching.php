<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
// $responce = new StdClass;

$id_status = $_POST['id_status'];
$id_matching = $_POST['id_matching'];

$sql = mysqli_query($con,"SELECT * FROM tbl_matching_detail WHERE id_matching = '$id_matching' and id_status = '$id_status' order by flag asc");
$w = 1;
// flag, kode, nama, conc1, conc2, conc3, conc4, conc5, conc6, conc7, conc8,
//  conc9, conc10, lab, jenis, aktual, remark, inserted_at, inserted_by, last_edit_at, last_edit_by
$responce = array();
while ($li = mysqli_fetch_array($sql)) {

    if (floatval($li['conc1']) == 0) {
        $conc1 = 'bg-black text-black';
    } else {
        $conc1 = floatval($li['conc1']);
    }
    if (floatval($li['conc2']) == 0) {
        $conc2 = 'bg-black text-black';
    } else {
        $conc2 = floatval($li['conc2']);
    }
    if (floatval($li['conc3']) == 0) {
        $conc3 = 'bg-black text-black';
    } else {
        $conc3 = floatval($li['conc3']);
    }
    if (floatval($li['conc4']) == 0) {
        $conc4 = 'bg-black text-black';
    } else {
        $conc4 = floatval($li['conc4']);
    }
    if (floatval($li['conc5']) == 0) {
        $conc5 = 'bg-black text-black';
    } else {
        $conc5 = floatval($li['conc5']);
    }
    if (floatval($li['conc6']) == 0) {
        $conc6 = 'bg-black text-black';
    } else {
        $conc6 = floatval($li['conc6']);
    }
    if (floatval($li['conc7']) == 0) {
        $conc7 = 'bg-black text-black';
    } else {
        $conc7 = floatval($li['conc7']);
    }
    if (floatval($li['conc8']) == 0) {
        $conc8 = 'bg-black text-black';
    } else {
        $conc8 = floatval($li['conc8']);
    }
    if (floatval($li['conc9']) == 0) {
        $conc9 = 'bg-black text-black';
    } else {
        $conc9 = floatval($li['conc9']);
    }
    if (floatval($li['conc10']) == 0) {
        $conc10 = 'bg-black text-black';
    } else {
        $conc10 = floatval($li['conc10']);
    }
    $responce[$w] = array(
        $li['flag'],
        $li['kode'],
        $conc1,
        $conc2,
        $conc3,
        $conc4,
        $conc5,
        $conc6,
        $conc7,
        $conc8,
        $conc9,
        $conc10,
        $li['nama']
    );
    $w++;
}

echo json_encode($responce);
