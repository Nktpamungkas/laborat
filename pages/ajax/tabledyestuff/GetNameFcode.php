<?php
ini_set("error_reporting", 1);
include "../../../koneksi.php";

$code = $_POST['code'];
$sql = mysqli_query($con,"SELECT Product_Name, Product_Unit, ket from tbl_dyestuff where code = '$code' and `is_active` = 'TRUE'");
$result = mysqli_fetch_array($sql);
if ($result["Product_Unit"] == 0) {
    $uom = "Gr/L";
} else {
    $uom = "%";
}

if ($result["Product_Name"] == "-----------------------") {
    $resultn = array(
        'Product_Name'  => $result["Product_Name"]
    );
} else {
    if($result["ket"] == "Suhu"){
        $resultn = array(
            'ket'            => $result['ket'],
            'Product_Name'   => $result["Product_Name"]
        );
    }else{
        $resultn = array(
            'Product_Name'  => $result["Product_Name"] . ' (' . $uom . ')'
        );
    }
}

$response = json_encode($resultn);
echo $response;
