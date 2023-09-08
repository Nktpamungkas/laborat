<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';
$time = date('Y-m-d H:i:s');
$id = mysqli_real_escape_string($con,$_POST['id']);
$Code = mysqli_real_escape_string($con,$_POST['Code']);
$Ket = mysqli_real_escape_string($con,$_POST['Ket']);
$Code_New = mysqli_real_escape_string($con,$_POST['code_new']);
$Product_Name = mysqli_real_escape_string($con,$_POST['Product_Name']);
$liquid_powder = mysqli_real_escape_string($con,$_POST['liquid_powder']);
$is_active = mysqli_real_escape_string($con,$_POST['is_active']);

mysqli_query($con,"UPDATE `tbl_dyestuff` SET 
                `ket`='$Ket',
                `code`='$Code',
				`code_new`='$Code_New',
                `Product_Name`='$Product_Name',
				`liquid_powder`='$liquid_powder',
                `is_active`='$is_active',
                `Product_Unit`='$_POST[Product_Unit]',
                `last_updated_at`='$time',
                `last_updated_by`='$_SESSION[userLAB]'
                WHERE `id`='$id' LIMIT 1");
mysqli_query($con,"INSERT into tbl_log SET `what` = '$id',
        `what_do` = 'UPDATE tbl_dyestuff',
        `do_by` = '$_SESSION[userLAB]',
        `do_at` = '$time',
        `ip` = '$_SESSION[ip]',
        `os` = '$_SESSION[os]',
        `remark`='$Code'");

$response = "LIB_SUCCSS";
echo json_encode($response);
