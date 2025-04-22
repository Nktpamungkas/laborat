<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();

$no_resep       = $_POST['no_resep'];
$bottle_qty_1   = $_POST['bottle_qty_1'];
$bottle_qty_2   = $_POST['bottle_qty_2'];
$temp_1           = $_POST['temp_1'];
$temp_2           = $_POST['temp_2'];
$username       = $_SESSION['userLAB'];

mysqli_query($con,"INSERT INTO `tbl_preliminary_schedule` SET
                    `no_resep` = '$no_resep',
                    `bottle_qty_1` = '$bottle_qty_1',
                    `bottle_qty_2` = '$bottle_qty_2',
                    `temp_1` = '$temp_1',
                    `temp_2` = '$temp_2',
                    `username` = '$username'
") or die(mysqli_error($con));


$response = array(
    'session' => 'LIB_SUCCESS'
);
echo json_encode($response);
?>