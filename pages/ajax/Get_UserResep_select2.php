<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";

$search = $_GET['search'];
if ($search == "") {
    $sql = mysqli_query($con,"SELECT id, nama, is_active FROM tbl_user_resep where `is_active` = 'TRUE'");
} else {
    $sql = mysqli_query($con,"SELECT id, nama, is_active FROM tbl_user_resep where `is_active` = 'TRUE' and nama like '%$search%'");
}
$result = mysqli_num_rows($sql);
if ($result > 0) {
    $list = array();
    $key = 0;
    while ($row = mysqli_fetch_array($sql)) {
        $list[$key]['id'] = $row['nama'];
        $list[$key]['text'] = $row['nama'];
        $key++;
    }
    echo json_encode($list);
} else {
    echo "Keyword tidak cocok!";
}
