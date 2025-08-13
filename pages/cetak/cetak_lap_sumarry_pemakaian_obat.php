<?php
$namaFile = 'lap_Bulanan_pemakaian_Obat_gd_kimia.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$namaFile");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
include "./../../koneksi.php";

$ip_num = $_SERVER['REMOTE_ADDR'];
$os = $_SERVER['HTTP_USER_AGENT'];
$warehouse = $_POST['warehouse'];
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
    <table border="1">
        <tr>
            <th>No</th>
            <th>Kode Obat</th>
            <th>Dyestuff/Chemical</th>
            <th>Stock Awal (gr)</th>
            <th>Masuk</th>
            <th>Pemakaian (gr)</th>
            <th>Transfer ke Gd. Lain</th>
            <th>Stock Balance</th>
            <th>Stock Minimum</th>
            <th>Buka PO</th>
            <th>Pemakaian (belum timbang)</th>
            <th>Stock Balance (future)</th>
            <th>Status</th>
            <th>Note</th>
            <th>Certification</th>
        </tr>

        <?php
       
        $no = 1;
        $sql = mysqli_query($con, "SELECT * FROM tb_stock_gd_kimia WHERE ip_address = '$ip_num'");
        while ($r = mysqli_fetch_array($sql)) {
            $status = $r['status_'];
            $style = '';

            if ($status === 'HITUNG KEBUTUHAN ORDER') {
                $style = 'background-color: #FFFF00; color: #000000;';
            } elseif ($status === 'SEGERA ORDER') {
                $style = 'background-color: #FF0000; color: #FFFFFF;';
            }

            echo "<tr>";
            echo "<td class='int' style='text-align:center'>$no</td>";
            echo "<td>{$r['kode_obat']}</td>";
            echo "<td>{$r['nama_obat']}</td>";
            echo "<td class='number'>{$r['qty_awal']}</td>";
            echo "<td class='number'>{$r['stock_masuk']}</td>";
            echo "<td class='number'>{$r['stock_keluar']}</td>";
            echo "<td class='number'>{$r['stock_transfer']}</td>";
            echo "<td class='number'>{$r['stock_balance']}</td>";
            echo "<td class='number'>{$r['stock_minimum']}</td>";
            echo "<td class='number'>{$r['buka_po']}</td>";
            echo "<td class='number'>{$r['stock_pakai_blum_timbang']}</td>";
            echo "<td class='number'>{$r['stock_balance_future']}</td>";
            echo "<td style='{$style}'>{$status}</td>";
            echo "<td>{$r['note']}</td>";
            echo "<td>{$r['ket_sertifikat']}</td>";
            echo "</tr>";
            $no++;
        }
        ?>
    </table>
</body>

</html>