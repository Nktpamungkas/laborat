<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();

$no_resep       = htmlspecialchars($_POST['no_resep']);
$bottle_qty_1   = (int) $_POST['bottle_qty_1'];
$bottle_qty_2   = (int) $_POST['bottle_qty_2'];
$temp_1         = htmlspecialchars($_POST['temp_1']); 
$temp_2         = htmlspecialchars($_POST['temp_2']);
$username       = $_SESSION['userLAB'];

for ($i = 0; $i < $bottle_qty_1; $i++) {
    mysqli_query($con, "INSERT INTO `tbl_preliminary_schedule` SET
        `no_resep` = '$no_resep',
        `temp` = '$temp_1',
        `username` = '$username'
    ") or die(mysqli_error($con));
}

for ($i = 0; $i < $bottle_qty_2; $i++) {
    mysqli_query($con, "INSERT INTO `tbl_preliminary_schedule` SET
        `no_resep` = '$no_resep',
        `temp` = '$temp_2',
        `username` = '$username'
    ") or die(mysqli_error($con));
}

$response = array(
    'session' => 'LIB_SUCCESS'
);
echo json_encode($response);
?>