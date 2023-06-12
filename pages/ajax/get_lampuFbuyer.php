<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";

$buyer = $_POST['buyer'];
$sql = mysqli_query($con,"SELECT lampu from vpot_lampbuy where buyer = '$buyer' order by flag");

while ($row = mysqli_fetch_array($sql)) {
    $nestedData = array();
    $nestedData[] = $row['lampu'];

    $data[] = $nestedData;
}

$json_data = $data;
//----------------------------------------------------------------------------------
echo json_encode($json_data);
