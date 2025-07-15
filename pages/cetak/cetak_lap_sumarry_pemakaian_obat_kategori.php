<?php
$namaFile = 'lap_Bulanan_kategori_pemakaian_Obat_gd_kimia.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$namaFile");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
include "./../../koneksi.php";

$ip_num = $_SERVER['REMOTE_ADDR'];
$os = $_SERVER['HTTP_USER_AGENT'];

// optional: atur timezone untuk tgl_tarik_data jika diperlukan
date_default_timezone_set('Asia/Jakarta');
?>

<html>

<head>
    <meta charset="UTF-8">
    <style>
        td,
        th {
            mso-number-format: "\@";
            /* force text format for Excel */
            padding: 5px;
            border: 1px solid #000;
        }

        .number {
            mso-number-format: "#,##0.00";
            /* angka dengan koma */
        }

        .int {
            mso-number-format: "0";
            /* angka tanpa koma */
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    
    <table border="0" width="100%" style="margin-bottom: 20px;">
    <tr>
        <!-- Logo -->
        <td colspan="3" style="width: 20%; text-align: left; vertical-align: middle;">
            <img src="login_assets/images/ITTI_Logo 2.png" alt="Logo" style="height: 80px;">
        </td>

        <!-- Judul -->
        <td colspan="5" style="width: 60%; text-align: center; vertical-align: middle; height: 80px;">
            <h3 style="margin: 0;">
                <strong>DATA PEMAKAIAN BAHAN PEMBANTU BULAN - DESKRIPSI BULAN + TAHUN</strong>
            </h3>
        </td>

        <!-- No Form -->
        <td style="width: 20%; font-size: 12px; vertical-align: top;">
            <table border="0">
                <tr>
                    <td ><strong>No Form</strong></td>
                    <td colspan="2">: FW-19-LAB-11</td>
                </tr>
                <tr>
                    <td><strong>No Revisi</strong></td>
                    <td colspan="2">: 06</td>
                </tr>
                <tr>
                    <td><strong>Tgl. Revisi</strong></td>
                    <td colspan="2">:</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table></table>
    <table border="1">
        <tr>
            <th>No</th>
            <th colspan="2">Kode Obat</th>
            <th colspan="2">Dyestuff/Chemical</th>
            <th>Stock Awal (gr)</th>
            <th>Masuk (gr)</th>
            <th>Total Pemakaian (gr)</th>
            <th>Sisa Stock (gr)</th>
            <th>Stock Aman (gr)</th>
            <th>Sisa PO (gr)</th>            
        </tr>

        <?php
        
            echo "<tr>";
            echo "<td class='int' style='text-align:center'></td>";
            echo "<td colspan= '2'></td>";
            echo "<td colspan= '2' ></td>";
            echo "<td class='number'></td>";
            echo "<td class='number'></td>";
            echo "<td class='number'></td>";
            echo "<td class='number'></td>";
            echo "<td class='number'></td>";
            echo "<td class='number'></td>";
            echo "</tr>"
           
        ?>
    </table>

    
    
<table></table>
<table></table>

<table style="width: auto;" border="1">
     <tr>
  <td colspan="3"></td>
  <td colspan="2" style="text-align: center; vertical-align: middle;">Dibuat Oleh :</td>
  <td colspan="3" style="text-align: center; vertical-align: middle;">Diperiksa Oleh :</td>
  <td colspan="3" style="text-align: center; vertical-align: middle;">Mengetahui :</td>
</tr>
<tr>
  <td colspan="3" style="text-align: center; vertical-align: middle;">Nama</td>
  <td colspan="2" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
</tr>
<tr>
  <td colspan="3" style="text-align: center; vertical-align: middle;">Jabatan</td>
  <td colspan="2" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
</tr>
<tr>
  <td colspan="3" style="text-align: center; vertical-align: middle;">Tanggal</td>
  <td colspan="2" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
</tr>
<tr>
  <td colspan="3" style="text-align: center; vertical-align: middle;">Tanda Tangan</td>
  <td colspan="2" style="text-align: center; vertical-align: middle;"><br><br><br><br></td>
  <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
</tr>
</table>

</table>

</body>

</html>