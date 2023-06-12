<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";

$search = $_GET['search'];
if ($search == "") {
    $sql = mysqli_query($con,"SELECT id, buyer FROM vpot_lampbuy group by buyer order by id desc");
} else {
    $sql = mysqli_query($con,"SELECT id, buyer FROM vpot_lampbuy where buyer like '%$search%' group by buyer order by id desc");
}
$result = mysqli_num_rows($sql);
if ($result > 0) {
    $list = array();
    $key = 0;
    while ($row = mysqli_fetch_array($sql)) {
        $list[$key]['id'] = $row['buyer'];
        $list[$key]['text'] = $row['buyer'];
        $key++;
    }
    echo json_encode($list);
} else {
    echo "Keyword tidak cocok!";
}
