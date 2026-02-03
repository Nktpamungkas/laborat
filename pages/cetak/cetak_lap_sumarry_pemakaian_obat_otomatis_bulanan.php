<?php

date_default_timezone_set('Asia/Jakarta');

// tanggal 1 di bulan berjalan jam 23:00:00
$awaltanggal = date('Y-m-01 23:01:00');
// $awaltanggal = date('2025-12-01 23:01:00');

// Tanggal awal = 1 hari sebelum tanggal 1 bulan berjalan
$awal = date('Y-m-d 23:01:00', strtotime('-1 day', strtotime($awaltanggal)));
$awal_ = date('Y-m-d', strtotime('-1 day', strtotime($awaltanggal)));

// Tanggal akhir = tanggal terakhir bulan berjalan jam 23:00:00
$akhir = date('Y-m-d 23:00:00');
$akhir_ = date('Y-m-d');


$awalParam = date('Y-m-d');
$Bln2 = (new DateTime($awalParam))->format('m');
$Thn2 = (new DateTime($awalParam))->format('Y');

$Bulan = $Thn2 . "-" . $Bln2;
$namaFile = "Laporan Bulanan gudang-{$Bulan}.xls";

$namaFile = "lap_Bulanan_pemakaian_Obat_gd_kimia_$Bulan.xls";

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$namaFile\"");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
include "./../../koneksi.php";

$ip_num = $_SERVER['REMOTE_ADDR'];
$os = $_SERVER['HTTP_USER_AGENT'];



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

// Logo
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
    .number { mso-number-format: "#,##0"; }
    /* untuk kolom No dengan pemisah ribuan (jika perlu) */
    .int    { mso-number-format: "#,##0"; }
    th { background-color: #f0f0f0; }
</style>
</head>

<body>
    <table border="0" width="100%" style="margin-bottom: 20px;">
        <tr>
            <!-- Logo -->
            <td colspan="2" style="width: 20%; height: 60px; text-align: center; vertical-align: middle;">
                <img src="online.indotaichen.com/laborat/login_assets/images/ITTI_Logo_.png" alt="Logo"
                    style="width:60px; height:60px; object-fit:contain;">
            </td>

            <?php
            $Balance_stock = db2_exec($conn1, "SELECT DISTINCT 
            ITEMTYPECODE, KODE_OBAT, LONGDESCRIPTION,
            DECOSUBCODE01, DECOSUBCODE02, DECOSUBCODE03,
            CERTIFICATION, NOTELAB, SAFETYSTOCK, SAFETYSTOCK_CHECK
            FROM (
                SELECT p.ITEMTYPECODE,
                    TRIM(p.SUBCODE01) || '-' || TRIM(p.SUBCODE02) || '-' || TRIM(p.SUBCODE03) AS KODE_OBAT, 
                    p.LONGDESCRIPTION,
                    p.SUBCODE01 as DECOSUBCODE01,
                    p.SUBCODE02 as DECOSUBCODE02,
                    p.SUBCODE03 as DECOSUBCODE03,                                          
                    CASE WHEN a.VALUESTRING = 1 THEN 'BV'
                         WHEN a.VALUESTRING = 2 THEN 'NON BV'
                         ELSE '' END AS CERTIFICATION,
                    a2.VALUESTRING AS NOTELAB,
                    d.LOGICALWAREHOUSECODE,
                    CASE WHEN p.BASEPRIMARYUNITCODE = 't' THEN d.SAFETYSTOCK *1000000
                         WHEN p.BASEPRIMARYUNITCODE = 'kg' THEN d.SAFETYSTOCK *1000
                         ELSE d.SAFETYSTOCK END AS SAFETYSTOCK,
                    CASE WHEN p.BASEPRIMARYUNITCODE = 't' THEN (d.SAFETYSTOCK *1000000)+(d.SAFETYSTOCK *1000000)*0.2 
                         WHEN p.BASEPRIMARYUNITCODE = 'kg' THEN (d.SAFETYSTOCK *1000)+(d.SAFETYSTOCK *1000)*0.2
                         ELSE d.SAFETYSTOCK+(d.SAFETYSTOCK *0.2)
                    END AS SAFETYSTOCK_CHECK
                FROM PRODUCT p    
                LEFT JOIN ITEMWAREHOUSELINK d ON 
                    d.ITEMTYPECODE = p.ITEMTYPECODE 
                    AND d.SUBCODE01 = p.SUBCODE01
                    AND d.SUBCODE02 = p.SUBCODE02 
                    AND d.SUBCODE03 = p.SUBCODE03
                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME ='Certification'
                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME ='NoteLab' 
                LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = p.ABSUNIQUEID AND a3.FIELDNAME ='ShowChemical'                                			
                WHERE p.ITEMTYPECODE ='DYC'
                AND a3.VALUEBOOLEAN = '1'
                AND d.LOGICALWAREHOUSECODE IN ('M510', 'M101')
            )
            ORDER BY KODE_OBAT ASC;");
            ?>

            <td colspan="9" style="width: 60%; text-align: center; vertical-align: middle; height: 80px;">
                <h3 style="margin: 0;">
                    <strong>DATA PEMAKAIAN BAHAN PEMBANTU BULAN
                        <?= ($Bln2 != "01") ? namabln($Bln2) . " " . $Thn2 : namabln($Bln2) . " " . $Thn; ?>
                    </strong>
                </h3>
            </td>

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
            <th>Kode Obat</th>
            <th>Nama Bahan</th>
            <th>Stock Awal (gr)</th>
            <th>Masuk (gr)</th>
            <th>Total Pemakaian (gr)</th>
            <th>Transfer (gr)</th>
            <th>Total Out</th>
            <th>Sisa Stock</th>
            <th>Stock Aman</th>
            <th>Sisa PO</th>
            <th>Status</th>
            <th>Note</th>
            <th>Certification</th>
        </tr>

        <?php
        $no = 1;
        function fmt2($val)
        {
            return is_numeric($val) ? (float) $val : 0.0;
        }
        while ($row = db2_fetch_assoc($Balance_stock)) {

            // Jalankan query dan aman-kan hasilnya
            // $Balance_stock_gd_pisah = db2_exec($conn1, "SELECT 
            //                                 b.ITEMTYPECODE,
            //                                 b.DECOSUBCODE01,
            //                                 b.DECOSUBCODE02,
            //                                 b.DECOSUBCODE03,
            //                                 CASE 
            //                                     WHEN b.BASEPRIMARYUNITCODE = 'kg' THEN sum(b.BASEPRIMARYQUANTITYUNIT)*1000
            //                                     WHEN b.BASEPRIMARYUNITCODE = 't' THEN sum(b.BASEPRIMARYQUANTITYUNIT)*1000000
            //                                     ELSE sum(b.BASEPRIMARYQUANTITYUNIT)
            //                                 END  AS STOCK_BALANCE,
            //                                 CASE 
            //                                     WHEN b.BASEPRIMARYUNITCODE = 'kg' THEN 'g'
            //                                     WHEN b.BASEPRIMARYUNITCODE = 't' THEN 'g'
            //                                     ELSE b.BASEPRIMARYUNITCODE
            //                                 END  AS BASEPRIMARYUNITCODE
            //                             FROM 
            //                                 BALANCE b 
            //                             WHERE 
            //                                 ITEMTYPECODE ='DYC'
            //                                 AND LOGICALWAREHOUSECODE IN ('M510','M101')
            //                                 AND DETAILTYPE = 1
            //                                 AND DECOSUBCODE01 = '{$row['DECOSUBCODE01']}' 
            //                                 AND DECOSUBCODE02 = '{$row['DECOSUBCODE02']}' 
            //                                 AND DECOSUBCODE03 = '{$row['DECOSUBCODE03']}' 
            //                             GROUP BY 
            //                                 ITEMTYPECODE,
            //                                 b.DECOSUBCODE01,
            //                                 b.DECOSUBCODE02,
            //                                 b.DECOSUBCODE03,
            //                                 b.BASEPRIMARYUNITCODE");

            // $row_Balance_stock_gd_pisah = db2_fetch_assoc($Balance_stock_gd_pisah) ?: [];

            $stock_transfer = db2_exec($conn1, "  SELECT 
                                            ITEMTYPECODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            sum(QTY_TRANSFER) AS QTY_TRANSFER,
                                            SATUAN_TRANSFER
                                            from
                                            (SELECT 
                                            ITEMTYPECODE,
                                            TEMPLATE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            sum(QTY_TRANSFER) AS QTY_TRANSFER,
                                            SATUAN_TRANSFER
                                            from
                                            (SELECT    s.TRANSACTIONDATE,
                                            s.TRANSACTIONNUMBER,
                                            CASE 
                                                WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                                                ELSE s.TEMPLATECODE
                                            END  as TEMPLATE,
                                            s3.LOGICALWAREHOUSECODE,
                                            s.LOTCODE,
                                            s.ORDERCODE,
                                            s.ORDERLINE, 
                                            s.TEMPLATECODE,
                                            s.ITEMTYPECODE,
                                            s.DECOSUBCODE01,
                                            s.DECOSUBCODE02,
                                            s.DECOSUBCODE03,
                                            s2.LONGDESCRIPTION, 
                                            u.LONGDESCRIPTION AS DESC_USERGENERIC,
                                            p.LONGDESCRIPTION as NAMA_OBAT,
                                            TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03) AS KODE_OBAT,
                                            CASE 
                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN s.USERPRIMARYQUANTITY * 1000000
                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN s.USERPRIMARYQUANTITY * 1000
                                                ELSE s.USERPRIMARYQUANTITY
                                            END AS QTY_TRANSFER,
                                            CASE 
                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                                ELSE s.USERPRIMARYUOMCODE
                                            END AS SATUAN_TRANSFER,
                                            CASE 
                                                WHEN  s.TEMPLATECODE = '201' THEN s2.LONGDESCRIPTION 
                                                WHEN  s.TEMPLATECODE = '203' AND i2.DESTINATIONWAREHOUSECODE = 'M512' THEN 'Transfer ke Finishing'
                                                WHEN  s.TEMPLATECODE = '203' AND i2.DESTINATIONWAREHOUSECODE = 'P101' THEN 'Transfer ke YND'
                                                WHEN  s.TEMPLATECODE = '303' AND s3.LOGICALWAREHOUSECODE = 'M512' THEN 'Transfer ke Finishing'
                                                WHEN  s.TEMPLATECODE = '303' AND s3.LOGICALWAREHOUSECODE = 'P101' THEN 'Transfer ke YND'
                                                WHEN  s3.TEMPLATECODE = '304' THEN 'Transfer ke ' || s3.LOGICALWAREHOUSECODE
                                            END AS KETERANGAN
                                        FROM
                                            STOCKTRANSACTION s
                                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = s.ITEMTYPECODE
                                                                AND p.SUBCODE01 = s.DECOSUBCODE01
                                                                AND p.SUBCODE02 = s.DECOSUBCODE02
                                                                AND p.SUBCODE03 = s.DECOSUBCODE03
                                        LEFT JOIN STOCKTRANSACTIONTEMPLATE s2 ON s2.CODE = s.TEMPLATECODE 
                                        LEFT JOIN INTERNALDOCUMENT i ON i.PROVISIONALCODE = s.ORDERCODE
                                        LEFT JOIN ORDERPARTNER o ON o.CUSTOMERSUPPLIERCODE = i.ORDPRNCUSTOMERSUPPLIERCODE
                                        LEFT JOIN LOGICALWAREHOUSE l ON l.CODE = o.CUSTOMERSUPPLIERCODE
                                        LEFT JOIN STOCKTRANSACTION s3 ON s3.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER AND NOT s3.LOGICALWAREHOUSECODE IN ('M510','M101') AND s3.DETAILTYPE = 2
                                        LEFT JOIN LOGICALWAREHOUSE l2 ON l2.CODE = s3.LOGICALWAREHOUSECODE
                                        LEFT JOIN USERGENERICGROUP u ON u.CODE = s.DECOSUBCODE01 AND u.USERGENERICGROUPTYPECODE ='S09'
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME ='KeteranganDYC'
                                        LEFT JOIN ( SELECT 
                                            i.INTDOCUMENTPROVISIONALCODE,
                                            i.INTDOCPROVISIONALCOUNTERCODE,
                                            i.ORDERTYPE,
                                            i.ORDERLINE ,
                                            i.ITEMTYPEAFICODE,
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
                                            i.ITEMDESCRIPTION,
                                            i.DESTINATIONWAREHOUSECODE,
                                            i.RECEIVINGDATE,
                                            i.WAREHOUSECODE AS WAREHOUSE_ASAL
                                            FROM 
                                            INTERNALDOCUMENTLINE i 
                                            WHERE i.ITEMTYPEAFICODE ='DYC'
                                        ) i2 ON i2.INTDOCUMENTPROVISIONALCODE = s.ORDERCODE AND i2.ORDERLINE = s.ORDERLINE 
                                        AND i2.SUBCODE01 = s.DECOSUBCODE01
                                        AND i2.SUBCODE02 = s.DECOSUBCODE02
                                        AND i2.SUBCODE03 = s.DECOSUBCODE03
                                        WHERE
                                            s.ITEMTYPECODE = 'DYC'
                                            AND TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) BETWEEN '$awal' AND '$akhir'
                                            AND s.TEMPLATECODE IN ('201','203','303')
                                            AND s.LOGICALWAREHOUSECODE IN ('M510','M101')
                                            AND s.DECOSUBCODE01 = '$row[DECOSUBCODE01]' 
                                            AND s.DECOSUBCODE02 = '$row[DECOSUBCODE02]' 
                                            AND s.DECOSUBCODE03 = '$row[DECOSUBCODE03]'
                                            )  AS sub
                                            WHERE TEMPLATE <> '303'
                                            GROUP BY 
                                            ITEMTYPECODE,
                                            TEMPLATE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            SATUAN_TRANSFER)
                                            GROUP BY 
                                            ITEMTYPECODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            SATUAN_TRANSFER");
            $row_stock_transfer = db2_fetch_assoc($stock_transfer) ?: [];


            $qty_pakai = db2_exec($conn1, "SELECT 
                                            ITEMTYPECODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            sum(AKTUAL_QTY_KELUAR) AS AKTUAL_QTY_KELUAR,
                                            SATUAN
                                            FROM 
                                            (SELECT
                                                s.ITEMTYPECODE,
                                                s.DECOSUBCODE01,
                                                s.DECOSUBCODE02,
                                                s.DECOSUBCODE03,
                                                CASE 
                                                    when s.CREATIONUSER = 'azwani.najwa'   AND s.TEMPLATECODE = '098' and (s.TRANSACTIONDATE ='2025-07-13' or s.TRANSACTIONDATE ='2025-10-05') then 0
                                                    WHEN s.USERPRIMARYUOMCODE = 't' THEN s.USERPRIMARYQUANTITY * 1000000
                                                    WHEN s.USERPRIMARYUOMCODE = 'kg' THEN s.USERPRIMARYQUANTITY * 1000
                                                    ELSE s.USERPRIMARYQUANTITY
                                                END AS AKTUAL_QTY_KELUAR,
                                                CASE 
                                                    WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                                    WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                                    ELSE s.USERPRIMARYUOMCODE
                                                END AS SATUAN,
                                                TRIM(s.LOGICALWAREHOUSECODE) || '' || TRIM(s.TEMPLATECODE)  AS  LOGICALTEMPLATE
                                            FROM
                                                STOCKTRANSACTION s
                                            WHERE
                                                s.ITEMTYPECODE = 'DYC'
                                                AND TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) BETWEEN '$awal' AND '$akhir'
                                                AND s.TEMPLATECODE IN ('120','098')
                                                and not (s.CREATIONUSER = 'azwani.najwa'   AND s.TEMPLATECODE = '098' and (s.TRANSACTIONDATE ='2025-07-13' or s.TRANSACTIONDATE ='2025-10-05'))
                                                AND s.LOGICALWAREHOUSECODE  IN ('M510','M101')
                                                and s.DECOSUBCODE01 = '$row[DECOSUBCODE01]' AND
                                                s.DECOSUBCODE02 = '$row[DECOSUBCODE02]' AND
                                                s.DECOSUBCODE03 = '$row[DECOSUBCODE03]'
                                                )
                                                WHERE LOGICALTEMPLATE !='M101098'
                                            GROUP BY 
                                            ITEMTYPECODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            SATUAN");
            $row_qty_pakai = db2_fetch_assoc($qty_pakai) ?: [];



            $stock_masuk = db2_exec($conn1, "SELECT 
                                                    ITEMTYPECODE,
                                                    DECOSUBCODE01,
                                                    DECOSUBCODE02,
                                                    DECOSUBCODE03,
                                                    round(sum(QTY_MASUK)) AS QTY_MASUK,
                                                    SATUAN_MASUK
                                                    FROM 
                                                    (SELECT 
                                                    ITEMTYPECODE,
                                                    TEMPLATE,
                                                    DECOSUBCODE01,
                                                    DECOSUBCODE02,
                                                    DECOSUBCODE03,
                                                    sum(QTY_MASUK) AS QTY_MASUK,
                                                    SATUAN_MASUK
                                                    FROM 
                                                    (SELECT    s.TRANSACTIONDATE,
                                                            s.TRANSACTIONNUMBER,
                                                            CASE 
                                                                WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                                                                ELSE s.TEMPLATECODE
                                                            END  as TEMPLATE,
                                                            s3.LOGICALWAREHOUSECODE AS terimadarigd,
                                                            s.TEMPLATECODE,
                                                            s.ITEMTYPECODE,
                                                            s.DECOSUBCODE01,
                                                            s.DECOSUBCODE02,
                                                            s.DECOSUBCODE03,
                                                            s2.LONGDESCRIPTION, 
                                                            p.LONGDESCRIPTION as NAMA_OBAT,
                                                            TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03) AS KODE_OBAT,
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
                                                            END AS SATUAN_MASUK,
                                                            CASE 
                                                                WHEN  s.TEMPLATECODE = 'OPN' THEN s2.LONGDESCRIPTION 
                                                                WHEN  s.TEMPLATECODE = 'QCT' THEN s.ORDERCODE 
                                                                WHEN  s.TEMPLATECODE = '304' THEN 'Terima dari ' || trim(s3.LOGICALWAREHOUSECODE)
                                                                WHEN  s.TEMPLATECODE = '303' THEN 'Terima dari ' || trim(s3.LOGICALWAREHOUSECODE)
                                                                WHEN  s.TEMPLATECODE = '203' THEN 'Terima dari ' || trim(s3.LOGICALWAREHOUSECODE)
                                                                WHEN  s.TEMPLATECODE = '204' THEN 'Terima dari ' || trim(s3.LOGICALWAREHOUSECODE)
                                                                WHEN  s.TEMPLATECODE = '125' THEN 'Retur dari ' || trim(s.ORDERCODE )
                                                            END AS KETERANGAN                    
                                                        FROM
                                                            STOCKTRANSACTION s
                                                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = s.ITEMTYPECODE
                                                                                AND p.SUBCODE01 = s.DECOSUBCODE01
                                                                                AND p.SUBCODE02 = s.DECOSUBCODE02
                                                                                AND p.SUBCODE03 = s.DECOSUBCODE03
                                                        LEFT JOIN STOCKTRANSACTIONTEMPLATE s2 ON s2.CODE = s.TEMPLATECODE 
                                                        LEFT JOIN INTERNALDOCUMENT i ON i.PROVISIONALCODE = s.ORDERCODE
                                                        LEFT JOIN ORDERPARTNER o ON o.CUSTOMERSUPPLIERCODE = i.ORDPRNCUSTOMERSUPPLIERCODE
                                                        LEFT JOIN LOGICALWAREHOUSE l ON l.CODE = o.CUSTOMERSUPPLIERCODE
                                                        LEFT JOIN STOCKTRANSACTION s3 ON s3.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER AND NOT s3.LOGICALWAREHOUSECODE = 'M101' AND  s3.DETAILTYPE = 1
                                                        LEFT JOIN LOGICALWAREHOUSE l2 ON l2.CODE = s3.LOGICALWAREHOUSECODE
                                                        WHERE
                                                            s.ITEMTYPECODE = 'DYC'
                                                            -- AND s.TRANSACTIONDATE BETWEEN '$awal' AND '$akhir'
                                                            AND TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) BETWEEN '$awal' AND '$akhir'
                                                            AND s.TEMPLATECODE IN ('QCT','304','OPN','204','125')
                                                            AND NOT COALESCE(TRIM( CASE 
                                                                WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                                                                ELSE s.TEMPLATECODE
                                                            END), '') || COALESCE(TRIM(CASE 
                                                                WHEN  s3.LOGICALWAREHOUSECODE IS NOT NULL THEN  s3.LOGICALWAREHOUSECODE
                                                                ELSE  s.LOGICALWAREHOUSECODE
                                                            END), '')  IN ('OPNM101','303M101','304M510')
                                                        AND s.LOGICALWAREHOUSECODE IN ('M510','M101')
                                                            AND s.DECOSUBCODE01 = '$row[DECOSUBCODE01]' 
                                                            AND s.DECOSUBCODE02 = '$row[DECOSUBCODE02]' 
                                                            AND s.DECOSUBCODE03 = '$row[DECOSUBCODE03]'
                                                            )  AS sub
                                                            WHERE TEMPLATE <> '304'
                                                            GROUP BY 
                                                            ITEMTYPECODE,
                                                            TEMPLATE,
                                                            DECOSUBCODE01,
                                                            DECOSUBCODE02,
                                                            DECOSUBCODE03,
                                                            SATUAN_MASUK)
                                                            GROUP BY 
                                                            ITEMTYPECODE,
                                                            DECOSUBCODE01,
                                                            DECOSUBCODE02,
                                                            DECOSUBCODE03,
                                                            SATUAN_MASUK");
            $row_stock_masuk = db2_fetch_assoc($stock_masuk) ?: [];


            $tgl_input = $akhir; // Misal: 2025-08-15
            $tgl_sebelumnya = date('Y-m-01', strtotime('-6 months', strtotime($tgl_input)));

            $buka_po = db2_exec($conn1, "SELECT 
                                            COUNTERCODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            DECOSUBCODE04,
                                            DECOSUBCODE05,
                                            sum(QTY) AS QTY,
                                            BASEPRIMARYUNITCODE
                                            from
                                            (SELECT 
                                            v.ISTANCECODE,
                                            v.COUNTERCODE,
                                            v.DECOSUBCODE01,
                                            v.DECOSUBCODE02,
                                            v.DECOSUBCODE03,
                                            v.DECOSUBCODE04,
                                            v.DECOSUBCODE05,
                                            v.DECOSUBCODE06,
                                            v.DECOSUBCODE07,
                                            CASE 
                                                WHEN v.BASEPRIMARYUNITCODE = 'kg' THEN sum(v.BASEPRIMARYQUANTITY)*1000
                                                WHEN v.BASEPRIMARYUNITCODE = 't' THEN sum(v.BASEPRIMARYQUANTITY)*1000000
                                                else sum(v.BASEPRIMARYQUANTITY)
                                            END AS QTY,
                                            CASE 
                                                WHEN v.BASEPRIMARYUNITCODE = 'kg' THEN 'g'
                                                WHEN v.BASEPRIMARYUNITCODE = 't' THEN 'g'
                                                else v.BASEPRIMARYUNITCODE
                                            END AS BASEPRIMARYUNITCODE,
                                            date(p.CREATIONDATETIME) AS PO_DATE,
                                            p.CREATIONDATETIME 
                                            FROM 
                                            VIEWAVANALYSISPART1 v 
                                            LEFT JOIN PURCHASEORDERLINE p ON p.PURCHASEORDERCODE = v.ISTANCECODE AND p.ORDERLINE = v.ISTANCELINE 
                                            AND p.SUBCODE01 = v.DECOSUBCODE01 
                                            AND p.SUBCODE02 = v.DECOSUBCODE02 
                                            AND p.SUBCODE03 = v.DECOSUBCODE03 
                                            WHERE 
                                            v.ISTANCETYPE = '6'
                                             AND v.LOGICALWAREHOUSECODE IN ('M510','M101')
                                            AND date(p.CREATIONDATETIME) BETWEEN '$tgl_sebelumnya' AND '$akhir'
                                            and v.DECOSUBCODE01 = '$row[DECOSUBCODE01]' AND
                                            v.DECOSUBCODE02 = '$row[DECOSUBCODE02]' AND
                                            v.DECOSUBCODE03 = '$row[DECOSUBCODE03]'
                                            GROUP BY 
                                            v.ISTANCECODE,
                                            v.COUNTERCODE,
                                            v.DECOSUBCODE01,
                                            v.DECOSUBCODE02,
                                            v.DECOSUBCODE03,
                                            v.DECOSUBCODE04,
                                            v.DECOSUBCODE05,
                                            v.DECOSUBCODE06,
                                            v.DECOSUBCODE07,
                                            v.BASEPRIMARYUNITCODE,
                                            p.CREATIONDATETIME)
                                            GROUP BY 
                                            COUNTERCODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            DECOSUBCODE04,
                                            DECOSUBCODE05,
                                            BASEPRIMARYUNITCODE");
            $row_buka_po = db2_fetch_assoc($buka_po) ?: [];

            $pakai_belum_timbang = db2_exec($conn1, "SELECT 
                                                                                LOGICALWAREHOUSECODE,
                                                                                COUNTERCODE,
                                                                                ITEMTYPECODE,
                                                                                DECOSUBCODE01,
                                                                                DECOSUBCODE02,
                                                                                DECOSUBCODE03,
                                                                               STATUS,
                                                                                sum(BASEPRIMARYQUANTITY) AS USERPRIMARYQUANTITY,
                                                                                BASEPRIMARYUNITCODE
                                                                            FROM 
                                                                                (
                                                                                SELECT 
                                                                                    v.WAREHOUSECODE AS LOGICALWAREHOUSECODE,
                                                                                    p.PRODUCTIONORDERCOUNTERCODE AS COUNTERCODE,
                                                                                    v.PRODUCTIONORDERCODE AS ISTANCECODE,
                                                                                    v.ITEMTYPEAFICODE AS ITEMTYPECODE,
                                                                                    v.ISSUEDATE AS DUEDATE,
                                                                                    v.SUBCODE01 AS DECOSUBCODE01,
                                                                                    v.SUBCODE02 AS DECOSUBCODE02,
                                                                                    v.SUBCODE03 AS DECOSUBCODE03,
                                                                            	    v.PROGRESSSTATUS as STATUS,
                                                                                    p.STATUS AS PRODUCTIONORDER_STATUS,
                                                                                    CASE 
                                                                                        WHEN v.BASEPRIMARYUOMCODE ='kg' THEN v.BASEPRIMARYQUANTITY * 1000
                                                                                        WHEN v.BASEPRIMARYUOMCODE ='t' THEN v.BASEPRIMARYQUANTITY * 1000000
                                                                                        ELSE BASEPRIMARYQUANTITY
                                                                                    END AS BASEPRIMARYQUANTITY,
                                                                                    v.BASEPRIMARYUOMCODE AS BASEPRIMARYUNITCODE
                                                                                    FROM 
                                                                                        PRODUCTIONRESERVATION v 
                                                                                    LEFT JOIN PRODUCTIONORDER p ON p.CODE = v.PRODUCTIONORDERCODE 
                                                                                    WHERE 
                                                                                        v.ITEMTYPEAFICODE ='DYC'
                                                                            		    AND v.PROGRESSSTATUS = 0
                                                                                        AND p.STATUS = 0
                                                                                        AND p.PRODUCTIONORDERCOUNTERCODE = '640'
                                                                                        AND v.WAREHOUSECODE  in ('M510','M101')
                                                                                        AND v.SUBCODE01 = '$row[DECOSUBCODE01]' 
                                                                                        AND v.SUBCODE02 = '$row[DECOSUBCODE02]' 
                                                                                        AND v.SUBCODE03 = '$row[DECOSUBCODE03]' 
                                                                                        AND v.ISSUEDATE BETWEEN '$awal' AND '$akhir')
                                                                                GROUP BY 
                                                                                    LOGICALWAREHOUSECODE,
                                                                                    COUNTERCODE,
                                                                            	    STATUS,
                                                                                    ITEMTYPECODE,
                                                                                    DECOSUBCODE01,
                                                                                    DECOSUBCODE02,
                                                                                    DECOSUBCODE03,
                                                                                    BASEPRIMARYUNITCODE");
            $row_pakai_belum_timbang = db2_fetch_assoc($pakai_belum_timbang) ?: [];

            // stok awal MySQL
            $tahunBulan = date('Y-m', strtotime($awal));
                                    $kode_obat = $row['KODE_OBAT'];

                                    $date = new DateTime($awal);
                                    $date->modify('-1 month');
                                    $tahunBulan2 = $date->format('Y-m');

                                    if($tahunBulan2 == '2025-09') {
                                        $q_qty_awal = mysqli_query($con, "SELECT kode_obat,
                                        SUBCODE01,
                                        SUBCODE02,
                                        SUBCODE03,
                                        SUM(qty_awal) as qty_awal 
                                        FROM stock_awal_obat_gdkimia_1
                                        WHERE kode_obat = '$kode_obat'
                                        AND logicalwarehouse  IN ('M510','M101')
                                        group by 
                                        kode_obat,
                                        SUBCODE01,
                                        SUBCODE02,
                                        SUBCODE03  
                                        ORDER BY kode_obat ASC");
                                    }else{
                                        $q_qty_awal = mysqli_query($con, "SELECT 
                                        tgl_tutup,
                                        DATE_FORMAT(DATE_SUB(tgl_tutup, INTERVAL 1 MONTH), '%Y-%m') AS tahun_bulan,
                                        KODE_OBAT,
                                        DECOSUBCODE01,
                                        DECOSUBCODE02,
                                        DECOSUBCODE03,
                                        SUM(BASEPRIMARYQUANTITYUNIT*1000) AS qty_awal
                                    FROM                                                      
                                     (SELECT distinct
                                        tgl_tutup,
                                        DATE_FORMAT(DATE_SUB(tgl_tutup, INTERVAL 1 MONTH), '%Y-%m') AS tahun_bulan,
                                        KODE_OBAT,
                                        LONGDESCRIPTION,
                                        DECOSUBCODE01,
                                        DECOSUBCODE02,
                                        DECOSUBCODE03,
                                        LOGICALWAREHOUSECODE,
                                        WAREHOUSELOCATIONCODE,
                                        WHSLOCATIONWAREHOUSEZONECODE,
                                        LOTCODE,
                                        BASEPRIMARYQUANTITYUNIT
                                    FROM tblopname_11 t
                                    WHERE 
                                        KODE_OBAT = '$kode_obat'
                                        AND LOGICALWAREHOUSECODE  IN ('M510','M101')
                                        AND tgl_tutup = (
                                            SELECT MAX(tgl_tutup)
                                            FROM tblopname_11
                                            WHERE 
                                                KODE_OBAT = '$kode_obat'
                                                AND LOGICALWAREHOUSECODE  IN ('M510','M101')
                                               AND  tgl_tutup = '$awal_'
                                        )) AS SUB
                                    GROUP BY tgl_tutup, KODE_OBAT");    
                                    } 
            $row_qty_awal = mysqli_fetch_array($q_qty_awal) ?: [];

            $Balance_stock_gd_pisah = mysqli_query($con, "SELECT 
                                        tgl_tutup,
                                        DATE_FORMAT(DATE_SUB(tgl_tutup, INTERVAL 1 MONTH), '%Y-%m') AS tahun_bulan,
                                        KODE_OBAT,
                                        DECOSUBCODE01,
                                        DECOSUBCODE02,
                                        DECOSUBCODE03,
                                        SUM(BASEPRIMARYQUANTITYUNIT*1000) AS STOCK_BALANCE
                                    FROM                                                      
                                     (SELECT distinct 
                                        tgl_tutup,
                                        DATE_FORMAT(DATE_SUB(tgl_tutup, INTERVAL 1 MONTH), '%Y-%m') AS tahun_bulan,
                                        KODE_OBAT,
                                        LONGDESCRIPTION,
                                        DECOSUBCODE01,
                                        DECOSUBCODE02,
                                        DECOSUBCODE03,
                                        LOGICALWAREHOUSECODE,
                                        WAREHOUSELOCATIONCODE,
                                        WHSLOCATIONWAREHOUSEZONECODE,
                                        LOTCODE,
                                        BASEPRIMARYQUANTITYUNIT
                                    FROM tblopname_11 t
                                    WHERE 
                                        KODE_OBAT = '$kode_obat'
                                        AND LOGICALWAREHOUSECODE IN ('M510','M101')
                                        AND tgl_tutup = (
                                            SELECT MAX(tgl_tutup)
                                            FROM tblopname_11
                                            WHERE 
                                                KODE_OBAT = '$kode_obat'
                                                AND LOGICALWAREHOUSECODE IN ('M510','M101')
                                                AND tgl_tutup = '$akhir_'
                                        )) AS SUB
                                    GROUP BY tgl_tutup, KODE_OBAT");
            $row_Balance_stock_gd_pisah = mysqli_fetch_array($Balance_stock_gd_pisah);

            // Konversi nilai aman
            $qty_awal = fmt2($row_qty_awal['qty_awal'] ?? 0);
            $qty_masuk = fmt2($row_stock_masuk['QTY_MASUK'] ?? 0);
            $qty_Keluar = fmt2($row_qty_pakai['AKTUAL_QTY_KELUAR'] ?? 0);
            $qty_Transfer = fmt2($row_stock_transfer['QTY_TRANSFER'] ?? 0);
            $qty_Balance_stock_gd_pisah = fmt2($row_Balance_stock_gd_pisah['STOCK_BALANCE'] ?? 0);
            $qty_stock_minimum = fmt2($row['SAFETYSTOCK'] ?? 0);
            $qty_stock_buka_PO = fmt2($row_buka_po['QTY'] ?? 0);
            $total_out = fmt2(($row_qty_pakai['AKTUAL_QTY_KELUAR'] ?? 0) + ($row_stock_transfer['QTY_TRANSFER'] ?? 0));

            $totalTY_ = fmt2($row_Balance_stock_gd_pisah['STOCK_BALANCE'] ?? 0)  + fmt2($row_buka_po['QTY'] ?? 0);

            $status = ($totalTY_ < $qty_stock_minimum)
                ? 'SEGERA ORDER'
                : (($totalTY_ >= $qty_stock_minimum && $totalTY_ < ($row['SAFETYSTOCK_CHECK'] ?? 0))
                    ? 'HITUNG KEBUTUHAN ORDER'
                    : '');
            $style = ($status == 'SEGERA ORDER')
                ? 'background-color: #f44336; color:white; font-weight:bold;'
                : (($status == 'HITUNG KEBUTUHAN ORDER')
                    ? 'background-color: #fff176; color:black; font-weight:bold;'
                    : '');

            echo "<tr>
                <td class='int' style='text-align:center'>{$no}</td>
                <td>{$row['KODE_OBAT']}</td>
                <td>{$row['LONGDESCRIPTION']}</td>
                <td class='number'>" . number_format($qty_awal, 0, '.', ',') . "</td>
                <td class='number'>" . number_format($qty_masuk, 0, '.', ',') . "</td>
                <td class='number'>" . number_format($qty_Keluar, 0, '.', ',') . "</td>
                <td class='number'>" . number_format($qty_Transfer, 0, '.', ',') . "</td>
                <td class='number'>" . number_format($total_out, 0, '.', ',') . "</td>
                <td class='number'>" . number_format($qty_Balance_stock_gd_pisah, 0, '.', ',') . "</td>
                <td class='number'>" . number_format($qty_stock_minimum, 0, '.', ',') . "</td>
                <td class='number'>" . number_format($qty_stock_buka_PO, 0, '.', ',') . "</td>
                <td style='{$style}'>" . htmlspecialchars($status) . "</td>
                <td>{$row['NOTELAB']}</td>
                <td>{$row['CERTIFICATION']}</td>
            </tr>";
            $no++;
        }
        ?>
    </table>

    <br><br>
    <table style="width:auto;" border="1">
        <tr>
            <td colspan="3"></td>
            <td colspan="3" style="text-align:center;">Dibuat Oleh :</td>
            <td colspan="4" style="text-align:center;">Diperiksa Oleh :</td>
            <td colspan="4" style="text-align:center;">Mengetahui :</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center;">Nama</td>
            <td colspan="3"></td>
            <td colspan="4"></td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center;">Jabatan</td>
            <td colspan="3"></td>
            <td colspan="4"></td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center;">Tanggal</td>
            <td colspan="3"></td>
            <td colspan="4"></td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center;">Tanda Tangan</td>
            <td colspan="3"><br><br><br><br></td>
            <td colspan="4"></td>
            <td colspan="4"></td>
        </tr>
    </table>
</body>
</html>