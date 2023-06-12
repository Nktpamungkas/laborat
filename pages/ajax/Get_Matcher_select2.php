<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";

$search = $_GET['search'];
if ($search == "") {
    $sql = mysqli_query($con,"SELECT id, nama, `status` FROM tbl_matcher where `status` = 'Aktif' order by id desc");
} else {
    $sql = mysqli_query($con,"SELECT id, nama, `status` FROM tbl_matcher where `status` = 'Aktif' and nama like '%$search%'");
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
