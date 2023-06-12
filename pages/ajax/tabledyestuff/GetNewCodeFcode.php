<?php
ini_set("error_reporting", 1);
include "../../../koneksi.php";

$code = $_POST['code'];
$sql = mysqli_query($con,"SELECT code_new, Product_Name, Product_Unit from tbl_dyestuff where code = '$code' and `is_active` = 'TRUE'");
$result = mysqli_fetch_array($sql);

if ($result["Product_Name"] == "-----------------------") {
    $resultn = $result["code_new"];
} else {
    $resultn = $result["code_new"];
}

$response1 = json_encode($resultn);
echo $response1;
