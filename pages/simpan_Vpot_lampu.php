<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
$time = date('Y-m-d H:i:s');
if ($_POST) {
    extract($_POST);
    $Buyer = strtoupper(mysqli_real_escape_string($con,$_POST['Buyer']));
    $sql = mysqli_query($con,"SELECT * FROM vpot_lampbuy where buyer = '$Buyer'");
    $row = mysqli_num_rows($sql);
    if ($row > 0) {
        echo " <script>
                alert('Buyer telah memiliki list lampu, harap pergi menuju edit !');
                window.location='?p=Lampu-Buyer';</script>";
    } else {
        if (!empty($_POST['lampu1'])) {
            mysqli_query($con,"INSERT INTO `vpot_lampbuy` SET 
                `flag` = 1,
                `buyer`='$Buyer',
                `lampu`='$_POST[lampu1]',
                `created_at`='$time',
               `create_by`='$_SESSION[userLAB]'
               ");
        }
        if (!empty($_POST['lampu2'])) {
            mysqli_query($con,"INSERT INTO `vpot_lampbuy` SET 
                `flag` = 2,
                `buyer`='$Buyer',
                `lampu`='$_POST[lampu2]',
                `created_at`='$time',
               `create_by`='$_SESSION[userLAB]'
               ");
        }
        if (!empty($_POST['lampu3'])) {
            mysqli_query($con,"INSERT INTO `vpot_lampbuy` SET 
                `flag` = 3,
                `buyer`='$Buyer',
                `lampu`='$_POST[lampu3]',
                `created_at`='$time',
               `create_by`='$_SESSION[userLAB]'
               ");
        }

        mysqli_query($con,"INSERT into tbl_log SET `what` = '$Buyer',
                        `what_do` = 'INSERT INTO vpot_lampbuy',
                        `do_by` = '$_SESSION[userLAB]',
                        `do_at` = '$time',
                        `ip` = '$_SESSION[ip]',
                        `os` = '$_SESSION[os]',
                        `remark`='Renew data lampu'");

        echo " <script>window.location='?p=Lampu-Buyer';</script>";
    }
}
