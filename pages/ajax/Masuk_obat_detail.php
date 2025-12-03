<?php
// koneksi ke DB
include "../../koneksi.php";

$code1 = $_POST['code1'];
$code2 = $_POST['code2'];
$code3 = $_POST['code3'];
$tgl1 = $_POST['tgl1'];
$tgl2 = $_POST['tgl2'];
$tgl_filter_masuk = $_POST['tgl_filter_masuk'];
$time = $_POST['time'];
$time2 = $_POST['time2'];
$warehouse = $_POST['warehouse'];

// echo "<pre>";
// print_r($_POST); // Debug POST value
// echo "</pre>";

// if ($warehouse == 'M101') {
//     $templateCodes = "'QCT','OPN','204'";
// } else {
//     $templateCodes = "'QCT','304','OPN','204'";
// }

//UNTUK WAREHOUSE 2
if (preg_match_all("/'([^']+)'/", $warehouse, $matches)) {
    $warehouses = $matches[1]; // hasil array: ['M510', 'M101']
} else {
    $warehouses = [$warehouse]; // fallback
}

if (count($warehouses) === 1 && in_array($warehouses[0], ['M101', 'M510'])) {
    $warehouse_ = "";
} else {
    $warehouse_ = "NOT s3.LOGICALWAREHOUSECODE IN ('M510','M101') AND";
}

//UNTUK TEMPLATE
if (preg_match_all("/'([^']+)'/", $warehouse, $matches)) {
    $warehouses = $matches[1]; // hasil array: ['M510', 'M101']
} else {
    $warehouses = [$warehouse]; // fallback
}

if (count($warehouses) === 1 && in_array($warehouses[0], ['M101', 'M510'])) {
    $wheretemplate = "";
} else {
    $wheretemplate = "WHERE TEMPLATE <> '304'";
}

$query = "SELECT 
                                        *
                                        FROM 
                                        (SELECT    s.TRANSACTIONDATE,
                                        VARCHAR_FORMAT(TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME), 'YYYY-MM-DD HH24:MI:SS') as TGL_WAKTU,
s.TRANSACTIONNUMBER,
                    CASE 
                    	WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                    	ELSE s.TEMPLATECODE
                    END  AS TEMPLATE,
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
                        when s.CREATIONUSER = 'MT_STI'  AND s.TEMPLATECODE = 'OPN' and (s.TRANSACTIONDATE ='2025-07-13' or s.TRANSACTIONDATE ='2025-10-05' ) then 0
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
	                    WHEN  s.TEMPLATECODE = 'OPN' THEN a.VALUESTRING 
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
                LEFT JOIN STOCKTRANSACTION s3 ON s3.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER AND  $warehouse_  s3.DETAILTYPE = 1
                LEFT JOIN LOGICALWAREHOUSE l2 ON l2.CODE = s3.LOGICALWAREHOUSECODE
                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME ='KeteranganDYC'
                WHERE
                    s.ITEMTYPECODE = 'DYC'
                    -- AND s.TRANSACTIONDATE BETWEEN '$tgl1' AND '$tgl2'
                    AND TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) BETWEEN '$tgl1 $time:00' AND '$tgl2 $time2:00'
                    AND s.TEMPLATECODE IN ('QCT','304','OPN','204','125')
                   AND s.LOGICALWAREHOUSECODE $warehouse
                   AND NOT COALESCE(TRIM( CASE 
                                                                WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                                                                ELSE s.TEMPLATECODE
                                                            END), '') || COALESCE(TRIM(s.LOGICALWAREHOUSECODE), '')  IN ('OPNM101','303M101','304M510')
                   and NOT (s.CREATIONUSER = 'MT_STI'  AND s.TEMPLATECODE = 'OPN' and (s.TRANSACTIONDATE ='2025-07-13' or s.TRANSACTIONDATE ='2025-10-05' ))
                   and s.DECOSUBCODE01 = '$code1' 
                   AND s.DECOSUBCODE02 = '$code2' 
                   AND s.DECOSUBCODE03 = '$code3'
                    )
                   $wheretemplate
                    ";


$stmt = db2_exec($conn1, $query);
if (!$stmt) {
    echo "<p class='text-danger'>Query gagal: " . db2_stmt_errormsg() . "</p>";
    exit;
}
$no = 1;
$kode_obat_label = '';
$nama_obat_label = '';
$rows2=[];
while ($row2 = db2_fetch_assoc($stmt)) {
    $rows2[] = $row2;
}
$kode_obat_label = $rows2[0]['KODE_OBAT'] ?? '';
$nama_obat_label = $rows2[0]['NAMA_OBAT'] ?? '';

echo "<h4><strong>" . htmlspecialchars($kode_obat_label) . " - " . htmlspecialchars($nama_obat_label) . "</strong></h4>";
// if ($stmt) { 
        echo "<table class='table table-bordered table-striped' id='detailmasukTable'>";
        echo "<thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>QTY (gr)</th>
                    <th>Template</th>
                    <th>Keterangan</th>                
                </tr>
            </thead>";
        echo "<tbody>";
        foreach ($rows2 as $row) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['TGL_WAKTU'] ?? '') . "</td>";
            echo "<td>" . number_format((float) ($row['QTY_MASUK'] ?? 0), 2) . "</td>";
            echo "<td>" . htmlspecialchars($row['TEMPLATECODE'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['KETERANGAN'] ?? '') . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    // } else {
    //     echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
    // }


?>