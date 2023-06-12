<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";

mysqli_query($con,"UPDATE tbl_colorist set `is_active` = '$_POST[sts]' where `id` = '$_POST[id]'");
mysqli_query($con,"INSERT into tbl_log SET `what` = '$_POST[id]',
`what_do` = 'EDIT tbl_colorist',
`do_by` = '$_SESSION[userLAB]',
`do_at` = '$time',
`ip` = '$_SESSION[ip]',
`os` = '$_SESSION[os]',
`remark`='EDIT colorist $nama'");

echo " <script>window.location='?p=Colorist';</script>";
