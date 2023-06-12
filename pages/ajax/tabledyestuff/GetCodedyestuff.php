<?php
ini_set("error_reporting", 1);
include "../../../koneksi.php";

$search = $_GET['search'];
$sql = mysqli_query($con,"SELECT * FROM tbl_dyestuff WHERE code LIKE '%$search%' and `is_active` = 'TRUE' ORDER BY id asc");
$result = mysqli_num_rows($sql);

if ($result > 0) {
    $list = array();
    $key = 0;
    while ($row = mysqli_fetch_array($sql)) {
        $list[$key]['id'] = $row['code'];
        $list[$key]['text'] = $row['code'];
        $key++;
    }
    echo json_encode($list);
} else {
    echo "Keyword tidak cocok!";
}
