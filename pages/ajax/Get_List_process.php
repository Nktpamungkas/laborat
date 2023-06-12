<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";

$search = $_GET['search'];
if ($search == "") {
    $sql = mysqli_query($con,"SELECT nama_proses FROM master_proses where is_active = 'TRUE' order by id desc");
} else {
    $sql = mysqli_query($con,"SELECT nama_proses FROM master_proses  where is_active = 'TRUE' and nama_proses like '%$search%' order by id desc");
}
$result = mysqli_num_rows($sql);
if ($result > 0) {
    $list = array();
    $key = 0;
    while ($row = mysqli_fetch_array($sql)) {
        $list[$key]['id'] = $row['nama_proses'];
        $list[$key]['text'] = $row['nama_proses'];
        $key++;
    }
    echo json_encode($list);
} else {
    echo "Keyword tidak cocok!";
}
