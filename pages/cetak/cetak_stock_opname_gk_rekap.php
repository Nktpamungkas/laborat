<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Rekap Stock Opname GK " . date($_GET['tgl_stk_op'])." ". $_GET['jam_stk_op'] . " Kategori ". $_GET['kategori'] . ".xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
ob_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <style>
            td,
            th {
                mso-number-format: "\@";
                padding: 5px;
                border: 1px solid #000;
            }

            .number {
                mso-number-format: "#,##0";
            }

            .int {
                mso-number-format: "0";
            }

            th {
                background-color: #f0f0f0;
            }
        </style>
    </head>
    <body>
    <?php
    ini_set("error_reporting", 1);
    $_POST=$_GET;
    $_POST['jenis_data']="excel";
    include "../ajax/stock_opname_gk_rekap_stock_opname.php";
    ?>
    </body>
</html>
<?php
ob_end_flush(); 
exit();
?>