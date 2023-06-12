<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
mysqli_query($con,"DELETE FROM tbl_status_matching WHERE id = '$_POST[id]'");
echo "<script>location.href='../../index1.php?p=Schedule-Matching'</script>";
