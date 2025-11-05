<?php
date_default_timezone_set('Asia/Jakarta');
// tanggal 1 di bulan berjalan jam 23:00:00
$awaltanggal = date('Y-m-01 23:01:00');

// Tanggal awal = 1 hari sebelum tanggal 1 bulan berjalan
$awal = date('Y-m-d', strtotime('-1 day', strtotime($awaltanggal)));

// Tanggal akhir = tanggal terakhir bulan berjalan jam 23:00:00
$akhir = date('Y-m-t 23:00:00');
// $akhir = '2025-10-21';

$awalParam = $_GET['awal'] ?? '';
$Bln2 = (new DateTime($awalParam))->format('m');
$Thn2 = (new DateTime($awalParam))->format('Y');
$Bulan = $Thn2 . "-" . $Bln2;

$namaFile = "lap_Bulanan_kategori_pemakaian_Obat_gd_kimia_bulanan_$Bulan.xls";

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$namaFile\"");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
include "./../../koneksi.php";

$ip_num = $_SERVER['REMOTE_ADDR'];
$os = $_SERVER['HTTP_USER_AGENT'];


$awalParam = $_GET['awal'] ?? '';
$Bln2 = (new DateTime($awalParam))->format('m');
$Thn2 = (new DateTime($awalParam))->format('Y');

$Bulan = $Thn2 . "-" . $Bln2;
$namaFile = "Laporan Bulanan gudang-{$Bulan}.xls";

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
    td, th {
        padding: 5px;
        border: 1px solid #000;
    }
    /* 2 desimal + pemisah ribuan (Excel akan sesuaikan tanda sesuai regional) */
    .number { mso-number-format: "#,##0.00"; }
    /* untuk kolom No dengan pemisah ribuan (jika perlu) */
    .int    { mso-number-format: "#,##0"; }
    th { background-color: #f0f0f0; }
</style>
</head>

<body>

    <table border="0" width="100%" style="margin-bottom: 20px;">
        <tr>
            <!-- Logo -->
           <td colspan="2" 
                style="width: 20%; height: 60px; text-align: center; vertical-align: middle;">
                <img src="online.indotaichen.com/laborat/login_assets/images/ITTI_Logo_.png" 
                    alt="Logo" 
                    style="width:60px; height:60px; object-fit:contain;">
            </td>

            <!-- Judul -->
            <td colspan="8" style="width: 60%; text-align: center; vertical-align: middle; height: 80px;">
                <h3 style="margin: 0;">
                    <strong>DATA PEMAKAIAN BAHAN PEMBANTU BULAN
                        <?= ($Bln2 != "01") ? namabln($Bln2) . " " . $Thn2 : namabln($Bln2) . " " . $Thn; ?>
                    </strong>
                </h3>
            </td>
                <?php

                $db_stocktransaction = db2_exec($conn1, "SELECT DISTINCT
                                                            p.ITEMTYPECODE,
                                                            u.LONGDESCRIPTION,
                                                            p.SUBCODE01 as DECOSUBCODE01
                                                            FROM 
                                                            PRODUCT p     
                                                            LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = p.ABSUNIQUEID AND a3.FIELDNAME ='ShowChemical' 
                                                            LEFT JOIN USERGENERICGROUP u ON u.CODE = p.SUBCODE01 AND u.USERGENERICGROUPTYPECODE ='S09'	                              			
                                                            WHERE 
                                                            p.ITEMTYPECODE ='DYC'
                                                            AND a3.VALUEBOOLEAN = '1'
                                                ");

                ?>
            <!-- No Form -->
            <td style="width: 20%; font-size: 12px; vertical-align: top;">
                <table border="0">
                    <tr>
                        <td colspan="2"><strong>No Form</strong></td>
                        <td colspan="3">: FW-19-LAB-11</td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>No Revisi</strong></td>
                        <td colspan="3">: 06</td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Tgl. Revisi</strong></td>
                        <td colspan="3">:</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
<br><br>
    <table border="1">
        <tr>
            <th>No</th>
            <th colspan="1">Kode Obat</th>
            <th colspan="2">Nama Bahan Dyestuff/Chemical</th>
            <th>Stock Awal (gr)</th>
            <th>Masuk (gr)</th>
            <th>Total Pemakaian (gr)</th>
            <th>Transfer (gr)</th>
            <th>Total Out (gr)</th>             
            <th>Sisa Stock</th>
            <th>Stock Aman</th>
            <th>Sisa PO</th>
            <th>Status</th>
            <th>Note</th>
            <th>Certification</th>
        </tr>

        <?php
        function fmt2($val)
        {
            return is_numeric($val) ? (float) $val : 0.0;
        }

        $no = 1;
       while ($row = db2_fetch_assoc($db_stocktransaction)) {                                    

                                $stock_transfer = db2_exec($conn1, "SELECT 
                                        ITEMTYPECODE,
                                        DECOSUBCODE01,
                                        sum(QTY_TRANSFER) AS QTY_TRANSFER,
                                        SATUAN_TRANSFER
                                        FROM 
                                        (SELECT 
                                        ITEMTYPECODE,
                                        TEMPLATE,
                                        DECOSUBCODE01,
                                        sum(QTY_TRANSFER) AS QTY_TRANSFER,
                                        SATUAN_TRANSFER
                                        FROM 
                                        (SELECT
                                            s.ITEMTYPECODE,
                                            s.DECOSUBCODE01,
                                            s.DECOSUBCODE02,
                                            s.DECOSUBCODE03,
                                             CASE 
                                                WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                                                ELSE s.TEMPLATECODE
                                            END  as TEMPLATE,
                                            CASE 
                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN s.USERPRIMARYQUANTITY * 1000000
                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN s.USERPRIMARYQUANTITY * 1000
                                                ELSE s.USERPRIMARYQUANTITY
                                            END AS QTY_TRANSFER,
                                            CASE 
                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                                ELSE s.USERPRIMARYUOMCODE
                                            END AS SATUAN_TRANSFER
                                        FROM
                                            STOCKTRANSACTION s
                                             LEFT JOIN STOCKTRANSACTION s3 ON s3.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER AND NOT s3.LOGICALWAREHOUSECODE IN ('M510','M101') AND s3.DETAILTYPE = 2
                                        WHERE
                                            s.ITEMTYPECODE = 'DYC'
                                             AND TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) BETWEEN '$awal' AND '$akhir'
--                                            AND s.TRANSACTIONDATE BETWEEN '$awal' AND '$akhir'
                                            AND s.TEMPLATECODE IN ('201','203','303')
                                            AND s.LOGICALWAREHOUSECODE IN ('M510', 'M101')
                                            and s.DECOSUBCODE01 = '$row[DECOSUBCODE01]'
                                            AND NOT TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03) ='E-1-000' 
                                        )
                                            WHERE TEMPLATE <> '303'
                                        GROUP BY 
                                        ITEMTYPECODE,                                        
                                        TEMPLATE,
                                        DECOSUBCODE01,
                                        SATUAN_TRANSFER)
                                        GROUP BY 
                                        ITEMTYPECODE,
                                        DECOSUBCODE01,
                                        SATUAN_TRANSFER");
                                    $row_stock_transfer = db2_fetch_assoc($stock_transfer) ?: [];

                                    $stock_masuk = db2_exec($conn1, "SELECT 
                                    ITEMTYPECODE,
                                    DECOSUBCODE01,
                                    sum(QTY_MASUK) AS QTY_MASUK,
                                    SATUAN_MASUK
                                    FROM 
                                    (SELECT 
                                    ITEMTYPECODE,
                                    TEMPLATE,
                                    DECOSUBCODE01,
                                    sum(QTY_MASUK) AS QTY_MASUK,
                                    SATUAN_MASUK
                                    FROM 
                                    (SELECT
                                        s.ITEMTYPECODE,
                                        s.DECOSUBCODE01,
                                         CASE 
                                                WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                                                ELSE s.TEMPLATECODE
                                            END  as TEMPLATE,
                                        CASE 
                                            when s.CREATIONUSER = 'MT_STI'   AND s.TEMPLATECODE = 'OPN' and (s.TRANSACTIONDATE ='2025-07-13' or s.TRANSACTIONDATE ='2025-10-05') then 0
                                            WHEN s.USERPRIMARYUOMCODE = 't' THEN s.USERPRIMARYQUANTITY * 1000000
                                            WHEN s.USERPRIMARYUOMCODE = 'kg' THEN s.USERPRIMARYQUANTITY * 1000
                                            ELSE s.USERPRIMARYQUANTITY
                                        END AS QTY_MASUK,
                                        CASE 
                                            WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                            WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                            ELSE s.USERPRIMARYUOMCODE
                                        END AS SATUAN_MASUK
                                    FROM
                                        STOCKTRANSACTION s
                                        LEFT JOIN STOCKTRANSACTION s3 ON s3.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER AND NOT s3.LOGICALWAREHOUSECODE = 'M101' AND  s3.DETAILTYPE = 1
                                    WHERE
                                        s.ITEMTYPECODE = 'DYC'
                                        AND TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) BETWEEN '$awal' AND '$akhir'
                                        -- AND s.TRANSACTIONDATE BETWEEN '$awal' AND '$akhir'
                                        AND s.TEMPLATECODE IN ('QCT','304','OPN','204','125')
                                         AND COALESCE(TRIM( CASE 
                                                                WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                                                                ELSE s.TEMPLATECODE
                                                            END), '') || COALESCE(TRIM(s.LOGICALWAREHOUSECODE), '') <> 'OPNM101'
                                        and s.CREATIONUSER != 'MT_STI'
                                        AND s.LOGICALWAREHOUSECODE IN ('M510', 'M101')
                                        and s.DECOSUBCODE01 = '$row[DECOSUBCODE01]'
                                        AND NOT TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03) ='E-1-000' 
                                    )
                                    WHERE TEMPLATE <> '304'
                                    GROUP BY 
                                    ITEMTYPECODE,
                                    TEMPLATE,
                                    DECOSUBCODE01,
                                    SATUAN_MASUK)
                                    GROUP BY 
                                    ITEMTYPECODE,
                                    DECOSUBCODE01,
                                    SATUAN_MASUK");
                                    $row_stock_masuk = db2_fetch_assoc($stock_masuk) ?: [];

                                    $qty_pakai = db2_exec($conn1, "SELECT 
                                        ITEMTYPECODE,
                                        DECOSUBCODE01,
                                        sum(AKTUAL_QTY_KELUAR) AS AKTUAL_QTY_KELUAR,
                                        SATUAN
                                        FROM 
                                        (SELECT
                                            s.ITEMTYPECODE,
                                            s.DECOSUBCODE01,
                                            CASE 
                                                when s.TEMPLATECODE = '098' and  s.TRANSACTIONDATE ='2025-10-05' AND s.LOGICALWAREHOUSECODE ='M510' then 0
                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN s.USERPRIMARYQUANTITY * 1000000
                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN s.USERPRIMARYQUANTITY * 1000
                                                ELSE s.USERPRIMARYQUANTITY
                                            END AS AKTUAL_QTY_KELUAR,
                                            CASE 
                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                                ELSE s.USERPRIMARYUOMCODE
                                            END AS SATUAN
                                        FROM
                                            STOCKTRANSACTION s
                                        WHERE
                                            s.ITEMTYPECODE = 'DYC'
                                            AND TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) BETWEEN '$awal' AND '$akhir'
                                            --  AND s.TRANSACTIONDATE BETWEEN '$awal' AND '$akhir'
                                            AND s.TEMPLATECODE  IN ('120','098')
                                            AND s.LOGICALWAREHOUSECODE IN ('M510', 'M101')
                                            and s.DECOSUBCODE01 = '$row[DECOSUBCODE01]' 
                                            AND NOT TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03) ='E-1-000' 
                                        )
                                        GROUP BY 
                                        ITEMTYPECODE,
                                        DECOSUBCODE01,
                                        SATUAN");
                                    $row_qty_pakai = db2_fetch_assoc($qty_pakai) ?: [];

                                    $Balance_stock = db2_exec($conn1, "SELECT 
                                        ITEMTYPECODE,
                                        DECOSUBCODE01,
                                        SUM(STOCK_BALANCE) AS STOCK_BALANCE,
                                        BASEPRIMARYUNITCODE
                                        FROM
                                        (SELECT 	TRIM(DECOSUBCODE01) || '-' || TRIM(DECOSUBCODE02) || '-' || TRIM(DECOSUBCODE03) AS KODE_OBAT,
                                                    b.ITEMTYPECODE,
                                                    b.DECOSUBCODE01,
                                                    CASE 
                                                        WHEN b.BASEPRIMARYUNITCODE = 'kg' THEN b.BASEPRIMARYQUANTITYUNIT*1000
                                                        WHEN b.BASEPRIMARYUNITCODE = 't' THEN b.BASEPRIMARYQUANTITYUNIT*1000000
                                                        ELSE b.BASEPRIMARYQUANTITYUNIT
                                                    END  AS STOCK_BALANCE,
                                                    CASE 
                                                        WHEN b.BASEPRIMARYUNITCODE = 'kg' THEN 'g'
                                                        WHEN b.BASEPRIMARYUNITCODE = 't' THEN 'g'
                                                        ELSE b.BASEPRIMARYUNITCODE
                                                    END  AS BASEPRIMARYUNITCODE
                                                    FROM 
                                                    BALANCE b 
                                                    WHERE 
                                                    ITEMTYPECODE ='DYC'
                                                    AND LOGICALWAREHOUSECODE IN ('M510', 'M101')
                                                    AND DETAILTYPE = 1)
                                                    WHERE 
                                                    DECOSUBCODE01 = '$row[DECOSUBCODE01]' 
                                                    AND NOT KODE_OBAT ='E-1-000' 
                                                    GROUP BY
                                                    ITEMTYPECODE,
                                                    DECOSUBCODE01,
                                                    BASEPRIMARYUNITCODE ");
                                    $row_balance = db2_fetch_assoc($Balance_stock) ?: [];

                                    $stock_minimum = db2_exec($conn1, " SELECT 
                                            i.ITEMTYPECODE,
                                            i.SUBCODE01,
                                            i.SUBCODE02, 
                                            i.SUBCODE03,
                                            i.SUBCODE04,
                                            i.SUBCODE05,
                                            i.SUBCODE06,
                                            i.SUBCODE07,
                                            i.SUBCODE08,
                                            i.SUBCODE09,
                                            i.SUBCODE10,
                                            CASE 
                                                WHEN p.BASEPRIMARYUNITCODE = 't' THEN i.SAFETYSTOCK *1000000
                                                WHEN p.BASEPRIMARYUNITCODE = 'kg' THEN i.SAFETYSTOCK *1000
                                                ELSE i.SAFETYSTOCK
                                            END AS SAFETYSTOCK,
                                            CASE 
                                                WHEN p.BASEPRIMARYUNITCODE = 't' THEN 'g'
                                                WHEN p.BASEPRIMARYUNITCODE = 'kg' THEN 'g'
                                                ELSE p.BASEPRIMARYUNITCODE
                                            END AS BASEPRIMARYUNITCODE,
                                            CASE 
                                                WHEN a.VALUESTRING = 1 THEN 'BV'
                                                WHEN a.VALUESTRING = 2 THEN 'NON BV'
                                                ELSE ''
                                            END CERTIFICATION,
                                            a2.VALUESTRING AS NOTELAB
                                            FROM 
                                            ITEMWAREHOUSELINK i 
                                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = i.ITEMTYPECODE 
                                            AND p.SUBCODE01 = i.SUBCODE01
                                            AND p.SUBCODE02 = i.SUBCODE02 
                                            AND p.SUBCODE03 = i.SUBCODE03 
                                            AND p.SUBCODE04 = i.SUBCODE04 
                                            AND p.SUBCODE05 = i.SUBCODE05 
                                            AND p.SUBCODE06 = i.SUBCODE06 
                                            AND p.SUBCODE07 = i.SUBCODE07 
                                            AND p.SUBCODE08 = i.SUBCODE08 
                                            AND p.SUBCODE09 = i.SUBCODE09 
                                            AND p.SUBCODE10 = i.SUBCODE10 
                                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME ='Certification'
                                            LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME ='NoteLab'
                                            WHERE  
                                            i.ITEMTYPECODE ='DYC'
                                            AND i.LOGICALWAREHOUSECODE IN ('M510', 'M101')
                                            AND i.SUBCODE01 = '$row[DECOSUBCODE01]'
                                            AND NOT TRIM(i.SUBCODE01) || '-' || TRIM(i.SUBCODE02) || '-' || TRIM(i.SUBCODE03) ='E-1-000'  ");
                                    $row_stock_minimum = db2_fetch_assoc($stock_minimum) ?: [];

                                    $buka_po = db2_exec($conn1, "SELECT 
                                            LOGICALWAREHOUSECODE,
                                            COUNTERCODE,
                                            DECOSUBCODE01,
                                            CASE 
                                                WHEN BASEPRIMARYUNITCODE = 'kg' THEN sum(BASEPRIMARYQUANTITY)*1000
                                                WHEN BASEPRIMARYUNITCODE = 't' THEN sum(BASEPRIMARYQUANTITY)*1000000
                                                else sum(BASEPRIMARYQUANTITY)
                                            END AS QTY,
                                            CASE 
                                                WHEN BASEPRIMARYUNITCODE = 'kg' THEN 'g'
                                                WHEN BASEPRIMARYUNITCODE = 't' THEN 'g'
                                                else BASEPRIMARYUNITCODE
                                            END AS BASEPRIMARYUNITCODE 
                                            FROM 
                                            VIEWAVANALYSISPART1 v 
                                            WHERE ISTANCETYPE = '6'
                                            AND LOGICALWAREHOUSECODE IN ('M510', 'M101')
                                            AND DUEDATE BETWEEN '$awal' AND '$akhir'
                                            and DECOSUBCODE01 = '$row[DECOSUBCODE01]'
                                            AND NOT TRIM(DECOSUBCODE01) || '-' || TRIM(DECOSUBCODE02) || '-' || TRIM(DECOSUBCODE03) ='E-1-000' 
                                            GROUP BY 
                                            LOGICALWAREHOUSECODE,
                                            COUNTERCODE,
                                            DECOSUBCODE01,
                                            BASEPRIMARYUNITCODE");
                                    $row_buka_po = db2_fetch_assoc($buka_po) ?: [];

                                    $tahunBulan = date('Y-m', strtotime($akhir));

                                    $date = new DateTime($akhir);
                                    $date->modify('-1 month');
                                    $tahunBulan2 = $date->format('Y-m');
        
                                    if ($tahunBulan2 == '2025-09') {
                                        $q_qty_awal = mysqli_query($con, "SELECT
                                            SUBCODE01,
                                            SUM(qty_awal) AS qty_awal
                                                FROM
                                                (SELECT * 
                                            FROM stock_awal_obat_gdkimia_1
                                            WHERE logicalwarehouse IN ('M510', 'M101')                                                          
                                            and not kode_obat = 'E-1-000'                                                              
                                            ) as T
                                            WHERE 
                                            SUBCODE01 = '$row[DECOSUBCODE01]'
                                            group by 
                                            SUBCODE01
                                            ORDER BY SUBCODE01 ASC");
                                    } else {
                                        $q_qty_awal = mysqli_query($con, "SELECT tgl_tutup,
                                                tahun_bulan,
                                                DECOSUBCODE01,
                                                SUM(BASEPRIMARYQUANTITYUNIT*1000) AS qty_awal 
                                                from(SELECT DISTINCT
                                                tgl_tutup,
                                                DATE_FORMAT(DATE_SUB(tgl_tutup, INTERVAL 1 MONTH), '%Y-%m') AS tahun_bulan,
                                                DECOSUBCODE01,
                                                BASEPRIMARYQUANTITYUNIT
                                            FROM tblopname_11 t
                                            WHERE 
                                                DECOSUBCODE01 = '$row[DECOSUBCODE01]'
                                                AND LOGICALWAREHOUSECODE IN ('M510', 'M101') 
                                                AND tgl_tutup = (
                                                    SELECT MAX(tgl_tutup)
                                                    FROM tblopname_11
                                                    WHERE 
                                                        DECOSUBCODE01  = '$row[DECOSUBCODE01]'
                                                        and not KODE_OBAT ='E-1-000'
                                                        AND LOGICALWAREHOUSECODE IN ('M510', 'M101') 
                                                        AND DATE_FORMAT(tgl_tutup, '%Y-%m') = '$tahunBulan2'
                                                ) and not KODE_OBAT ='E-1-000') as sub
                                            GROUP BY tgl_tutup, DECOSUBCODE01");
                                    }                                
                                    $row_qty_awal = mysqli_fetch_array($q_qty_awal) ?: [];

            // hitung sebagai float (TANPA formatting)
            $qty_awal = fmt2($row_qty_awal['qty_awal'] ?? 0);
            $qty_masuk = fmt2($row_stock_masuk['QTY_MASUK'] ?? 0);
            $qty_Keluar = fmt2($row_qty_pakai['AKTUAL_QTY_KELUAR'] ?? 0);
            $qty_Transfer = fmt2($row_stock_transfer['QTY_TRANSFER'] ?? 0);
            $qty_Balance_pisah = fmt2($row_Balance_stock_gd_pisah['STOCK_BALANCE'] ?? 0);
            $qty_stock_minimum = fmt2($row['SAFETYSTOCK'] ?? 0);
            $qty_stock_buka_PO = fmt2($row_buka_po['QTY'] ?? 0);

            $total_out = fmt2(($row_qty_pakai['AKTUAL_QTY_KELUAR'] ?? 0) + ($row_stock_transfer['QTY_TRANSFER'] ?? 0));
            $totalTY_ = fmt2($row_Balance_stock_gd_pisah['STOCK_BALANCE'] ?? 0) + fmt2($row_buka_po['QTY'] ?? 0);

            echo "<tr>
                <td class='int' style='text-align:center'>{$no}</td>
                <td>{$row['DECOSUBCODE01']}</td>
                <td colspan='2'>{$row['LONGDESCRIPTION']}</td>
                <td class='number'>" . number_format($qty_awal, 2, '.', ',') . "</td>
                <td class='number'>" . number_format($qty_masuk, 2, '.', ',') . "</td>
                <td class='number'>" . number_format($qty_Keluar, 2, '.', ',') . "</td>
                <td class='number'>" . number_format($qty_Transfer, 2, '.', ',') . "</td>
                <td class='number'>" . number_format($total_out, 2, '.', ',') . "</td>
                <td class='number'>" . number_format($qty_Balance_pisah, 2, '.', ',') . "</td>
                <td  class='number'>" . '0.00' . "</td>
                <td  class='number'>" . '0.00' . "</td>
                <td>".' '."</td>
                <td>" . ' ' . "</td>
                <td>" . ' ' . "</td>
            </tr>";
            $no++;
        }
        ?>
    </table>

    <br><br>

    <table style="width: auto;" border="1">
        <tr>
            <td colspan="4"></td>
            <td colspan="3" style="text-align: center;">Dibuat Oleh :</td>
            <td colspan="3" style="text-align: center;">Diperiksa Oleh :</td>
            <td colspan="5" style="text-align: center;">Mengetahui :</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">Nama</td>
            <td colspan="3" style="text-align: center;"></td>
            <td colspan="3" style="text-align: center;"></td>
            <td colspan="5" style="text-align: center;"></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">Jabatan</td>
            <td colspan="3" style="text-align: center;"></td>
            <td colspan="3" style="text-align: center;"></td>
            <td colspan="5" style="text-align: center;"></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">Tanggal</td>
            <td colspan="3" style="text-align: center;"></td>
            <td colspan="3" style="text-align: center;"></td>
            <td colspan="5" style="text-align: center;"></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">Tanda Tangan</td>
            <td colspan="3" style="text-align: center;"><br><br><br><br></td>
            <td colspan="3" style="text-align: center;"></td>
            <td colspan="5" style="text-align: center;"></td>
        </tr>
    </table>

</body>

</html>