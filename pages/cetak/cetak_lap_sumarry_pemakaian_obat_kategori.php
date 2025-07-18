<?php
$namaFile = 'lap_Bulanan_kategori_pemakaian_Obat_gd_kimia.xls';

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$namaFile\"");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
include "./../../koneksi.php";

$ip_num = $_SERVER['REMOTE_ADDR'];
$os = $_SERVER['HTTP_USER_AGENT'];

date_default_timezone_set('Asia/Jakarta');

$awalParam = $_GET['awal'] ?? '';
$Bln2 = (new DateTime($awalParam))->format('m');
$Thn2 = (new DateTime($awalParam))->format('Y');

$Bulan = $Thn2 . "-" . $Bln2;
$namaFile = "Laporan harian gudang-{$Bulan}.xls";

$d = cal_days_in_month(CAL_GREGORIAN, $Bln2, $Thn2);
if ($Thn2 != "" and $Bln2 != "") {
    $Lalu = $Bln2 - 1;
    if ($Lalu == "0") {
        $BlnLalu = "12";
        $Thn = $Thn2 - 1;
    } else {
        $BlnLalu = $Lalu;
        $Thn = $Thn2;
    }
}

function namabln($b)
{
    $bulan = [
        "1" => "JANUARI",
        "2" => "FEBRUARI",
        "3" => "MARET",
        "4" => "APRIL",
        "5" => "MEI",
        "6" => "JUNI",
        "7" => "JULI",
        "8" => "AGUSTUS",
        "9" => "SEPTEMBER",
        "10" => "OKTOBER",
        "11" => "NOVEMBER",
        "12" => "DESEMBER"
    ];
    return $bulan[(int) $b] ?? $b;
}

// Generate base64 logo
$logoPath = realpath(__DIR__ . '\\images\\ITTI_Logo.png');
$logoBase64 = '';
if (file_exists($logoPath)) {
    $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
    $logoData = base64_encode(file_get_contents($logoPath));
    $logoBase64 = 'data:image/' . $logoType . ';base64,' . $logoData;
}
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
            mso-number-format: "#,##0.00";
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

    <table border="0" width="100%" style="margin-bottom: 20px;">
        <tr>
            <!-- Logo -->
            <td colspan="3" style="width: 20%; text-align: left; vertical-align: middle;">
                <?php if ($logoBase64): ?>
                    <img src="<?= $logoBase64 ?>" alt="Logo" style="height: 80px;">
                <?php else: ?>
                    <strong>Logo tidak ditemukan</strong>
                <?php endif; ?>
            </td>

            <!-- Judul -->
            <td colspan="5" style="width: 60%; text-align: center; vertical-align: middle; height: 80px;">
                <h3 style="margin: 0;">
                    <strong>DATA PEMAKAIAN BAHAN PEMBANTU BULAN
                        <?= ($Bln2 != "01") ? namabln($Bln2) . " " . $Thn2 : namabln($Bln2) . " " . $Thn; ?>
                    </strong>
                </h3>
            </td>

            <!-- No Form -->
            <td style="width: 20%; font-size: 12px; vertical-align: top;">
                <table border="0">
                    <tr>
                        <td><strong>No Form</strong></td>
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
<br><br>
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
        $no = 1;
        $sql = mysqli_query($con, "SELECT * FROM tb_stock_gd_kimia_kategori WHERE ip_address = '$ip_num'");
        while ($r = mysqli_fetch_array($sql)) {
            echo "<tr>";
            echo "<td class='int' style='text-align:center'>$no</td>";
            echo "<td colspan='2'>{$r['kode_obat']}</td>";
            echo "<td colspan='2'>{$r['nama_obat']}</td>";
            echo "<td class='number'>{$r['qty_awal']}</td>";
            echo "<td class='number'>{$r['stock_masuk']}</td>";
            echo "<td class='number'>{$r['stock_keluar']}</td>";
            echo "<td class='number'>{$r['stock_balance']}</td>";
            echo "<td class='number'>{$r['stock_minimum']}</td>";
            echo "<td class='number'>{$r['sisa_po']}</td>";
            echo "</tr>";
            $no++;
        }
        ?>
    </table>

    <br><br>

    <table style="width: auto;" border="1">
        <tr>
            <td colspan="3"></td>
            <td colspan="2" style="text-align: center;">Dibuat Oleh :</td>
            <td colspan="3" style="text-align: center;">Diperiksa Oleh :</td>
            <td colspan="3" style="text-align: center;">Mengetahui :</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">Nama</td>
            <td colspan="2" style="text-align: center;"></td>
            <td colspan="3" style="text-align: center;"></td>
            <td colspan="3" style="text-align: center;"></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">Jabatan</td>
            <td colspan="2" style="text-align: center;"></td>
            <td colspan="3" style="text-align: center;"></td>
            <td colspan="3" style="text-align: center;"></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">Tanggal</td>
            <td colspan="2" style="text-align: center;"></td>
            <td colspan="3" style="text-align: center;"></td>
            <td colspan="3" style="text-align: center;"></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">Tanda Tangan</td>
            <td colspan="2" style="text-align: center;"><br><br><br><br></td>
            <td colspan="3" style="text-align: center;"></td>
            <td colspan="3" style="text-align: center;"></td>
        </tr>
    </table>

</body>

</html>