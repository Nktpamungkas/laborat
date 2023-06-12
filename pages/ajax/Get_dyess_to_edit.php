<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';

$sql_dyess = mysqli_query($con,"SELECT id, code, code_new, `Product_Name`, Product_Unit, is_active from tbl_dyestuff where id = '$_POST[id]' LIMIT 1");
$data = mysqli_fetch_array($sql_dyess);

//----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------
$json_data = array(
    "id" => $data['id'],
    "code" => $data['code'],
	"code_new" => $data['code_new'],
    "Product_Name" => $data['Product_Name'],
    "Product_Unit" => $data['Product_Unit'],
    "is_active" => $data['is_active']
);
//----------------------------------------------------------------------------------
echo json_encode($json_data);
