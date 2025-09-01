<?php
// koneksi ke DB
include "../../koneksi.php";

$code1 = $_POST['code1'];
$code2 = $_POST['code2'];
$code3 = $_POST['code3'];
$tgl1 = $_POST['tgl1'];
$tgl2 = $_POST['tgl2'];
$warehouse = $_POST['warehouse'];

// echo "<pre>";
// print_r($_POST); // Debug POST value
// echo "</pre>";

$query = "SELECT 
                    *
                    from
                    (SELECT s.TRANSACTIONDATE,
					s.TRANSACTIONNUMBER,
                    CASE 
                    	WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                    	ELSE s.TEMPLATECODE
                    END  as tempalte,
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
                    AND s.TRANSACTIONDATE BETWEEN '$tgl1' AND '$tgl2'
                    AND s.TEMPLATECODE IN ('201','203','303')
                    AND s.LOGICALWAREHOUSECODE $warehouse
                    AND s.DECOSUBCODE01 = '$code1' 
                    AND s.DECOSUBCODE02 = '$code2' 
                    AND s.DECOSUBCODE03 = '$code3'
                    )
                    -- WHERE tempalte <> '303'
                    ";
// echo "<pre>$query</pre>";

$stmt = db2_exec($conn1, $query);
// if (!$stmt) {
//     echo "<p class='text-danger'>Query gagal: " . db2_stmt_errormsg() . "</p>";
//     exit;
// }
$no = 1;
$kode_obat_label = '';
$nama_obat_label = '';
$rows2 = [];
while ($row2 = db2_fetch_assoc($stmt)) {
    $rows2[] = $row2;
}
$kode_obat_label = $rows2[0]['KODE_OBAT'] ?? '';
$nama_obat_label = $rows2[0]['NAMA_OBAT'] ?? '';

echo "<h4><strong>" . htmlspecialchars($kode_obat_label) . " - " . htmlspecialchars($nama_obat_label) . "</strong></h4>";
// if ($stmt) { 
echo "<table class='table table-bordered table-striped' id='detailTransferTable'>";
echo "<thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>QTY (gr)</th>
                    <th>Template</th>
                    <th>Lotcode</th>
                    <th>Keterangan</th>                
                </tr>
            </thead>";
echo "<tbody>";
foreach ($rows2 as $row) {
    echo "<tr>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . htmlspecialchars($row['TRANSACTIONDATE'] ?? '') . "</td>";    
    echo "<td>" . number_format((float) ($row['QTY_TRANSFER'] ?? 0), 2) . "</td>";
    echo "<td>" . htmlspecialchars($row['TEMPLATECODE'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['LOTCODE'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['KETERANGAN'] ?? '') . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";
// } else {
//     echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
// }


?>