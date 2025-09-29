<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Rekap Stock Opname GK " . date($_GET['tgl_stk_op'])." ". $_GET['jam_stk_op'] . " Kategori ". $_GET['kategori'] . ".xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
ob_start();
?>
<?php
ini_set("error_reporting", 1);
$_POST=$_GET;
include "../ajax/stock_opname_gk_rekap_stock_opname.php";
ob_end_flush(); 
exit();
?>