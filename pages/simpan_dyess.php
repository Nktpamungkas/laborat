<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
$time = date('Y-m-d H:i:s');
if ($_POST) {
    extract($_POST);
    $ket = mysqli_real_escape_string($con,$_POST['ket']);
    $Code = mysqli_real_escape_string($con,$_POST['Code']);
	$Code_New = mysqli_real_escape_string($con,$_POST['Code_new']);
    $Product_Name = mysqli_real_escape_string($con,$_POST['Product_name']);
    $liquid_powder = mysqli_real_escape_string($con,$_POST['liquid_powder']);
    $Product_Unit = mysqli_real_escape_string($con,$_POST['Product_Unit']);
    $is_active = mysqli_real_escape_string($con,$_POST['is_active']);
    mysqli_query($con,"INSERT INTO `tbl_dyestuff` SET 
				`ket`='$ket',
				`code`='$Code',
				`code_new`='$Code_New',
				`Product_Name`='$Product_Name',
				`liquid_powder`='$liquid_powder',
				`Product_Unit`='$Product_Unit',
				`is_active`='$is_active',
				`created_at`='$time',
                `created_by`='$_SESSION[userLAB]'
                ");
    mysqli_query($con,"INSERT into tbl_log SET `what` = '$Code',
                                            `what_do` = 'INSERT INTO tbl_dyestuff',
                                            `do_by` = '$_SESSION[userLAB]',
                                            `do_at` = '$time',
                                            `ip` = '$_SESSION[ip]',
                                            `os` = '$_SESSION[os]',
                                            `remark`='$Product_Name'");

    echo " <script>window.location='?p=Manage-Dyestuff';</script>";
}
