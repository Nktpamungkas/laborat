<?php
// koneksi ke DB
include "../../koneksi.php";

$code = $_POST['code'];
$tgl1 = $_POST['tgl1'];
$tgl2 = $_POST['tgl2'];
$warehouse = $_POST['warehouse'];

// echo "<pre>";
// print_r($_POST); // Debug POST value
// echo "</pre>";

$query = "SELECT 
                *
                from
                (SELECT    s.TRANSACTIONDATE,
                    s.TRANSACTIONNUMBER,
                    s3.TEMPLATECODE,
                    s.LOTCODE,
                    s.ORDERCODE,
                    s.ORDERLINE,
                    CASE 
                    WHEN s.LOGICALWAREHOUSECODE = 'M101' THEN s3.TEMPLATECODE
                    ELSE s.TEMPLATECODE
                    END AS TEMPLATECODE,
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
                    	WHEN  s.TEMPLATECODE = '203' AND i2.DESTINATIONWAREHOUSECODE = 'P101' THEN 'Transfer ke Yarn Dye'
                    	WHEN  s.TEMPLATECODE = '303' AND s3.LOGICALWAREHOUSECODE = 'M512' THEN 'Transfer ke Finishing'
                        WHEN  s.TEMPLATECODE = '303' AND s3.LOGICALWAREHOUSECODE = 'P101' THEN 'Transfer ke Yarn Dye'
                        WHEN  s3.TEMPLATECODE = '304' THEN 'Transfer ke Laborat'
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
                LEFT JOIN STOCKTRANSACTION s3 ON s3.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER AND s3.DETAILTYPE = 2
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
                    AND s.LOGICALWAREHOUSECODE = '$warehouse'
                    AND s.DECOSUBCODE01 = '$code')
                    WHERE KODE_OBAT <> 'E-1-000'";
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
$kode_obat_label = $rows2[0]['DECOSUBCODE01'] ?? '';
$nama_obat_label = $rows2[0]['DESC_USERGENERIC'] ?? '';

echo "<h4><strong>" . htmlspecialchars($kode_obat_label) . " - " . htmlspecialchars($nama_obat_label) . "</strong></h4>";
// if ($stmt) { 
echo "<table class='table table-bordered table-striped' id='detailtransferTable'>";
echo "<thead>
             <tr>
                <th class='text-center'>No</th>
                <th class='text-center'>Tanggal</th>
                <th class='text-center'>Kode Obat </th>
                <th class='text-center'>Nama Obat </th>
                <th class='text-center'>QTY (gr)</th> 
                <th>Lotcode</th>
                <th class='text-center'>Keterangan</th>                
            </tr>
    </thead>";
echo "<tbody>";
foreach ($rows2 as $row) {
    echo "<tr>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . htmlspecialchars($row['TRANSACTIONDATE'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['KODE_OBAT'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['NAMA_OBAT'] ?? '') . "</td>";
    echo "<td>" . number_format((float) ($row['QTY_TRANSFER'] ?? 0), 2) . "</td>";
    echo "<td>" . htmlspecialchars($row['LOTCODE'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['KETERANGAN'] ?? '') . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";
// } else {
//     echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
// }


?>