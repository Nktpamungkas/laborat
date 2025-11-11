<?php
// koneksi ke DB
include "../../koneksi.php";

$code = $_POST['code'];
$tgl1 = $_POST['tgl1'];
$tgl2 = $_POST['tgl2'];
$warehouse = $_POST['warehouse'];


date_default_timezone_set('Asia/Jakarta');
$tglInput = $_POST['tgl1'] ?? '';

if (!empty($tglInput) && strtotime($tglInput) !== false) {
    $awaltanggal = date('Y-m-d 23:01:00', strtotime($tglInput));
} else {
    $awaltanggal = date('Y-m-01 23:01:00');
}


// Tanggal awal = 1 hari sebelum tanggal 1 bulan berjalan
$awal = date('Y-m-d', strtotime('-1 day', strtotime($awaltanggal)));

// Tanggal akhir = tanggal terakhir bulan berjalan jam 23:00:00
$akhir = date('Y-m-t 23:00:00');

// echo "<pre>";
// print_r($_POST); // Debug POST value
// echo "</pre>";

if ($warehouse == "in('M101')") {
    $detailtype = '1';
} else {
    $detailtype = '2';
    
}

if ($warehouse == "in('M101')") {
    $templatewarehouse = "IN ('303M510')";
} else {
    $templatewarehouse = "IN ('303M510', '303M101')";
}

    if ($warehouse == "in('M101')" || $warehouse == "in('M510')") {
    $wheretemplate = "";
                                } else {
    $wheretemplate = "!= '303'";}

if ($warehouse == "in('M101')") {
    $wheretemplate2 =  "AND s.TEMPLATECODE IN ('201','203','303','304')";
} else {
    $wheretemplate2 = "AND s.TEMPLATECODE IN ('201','203','303')";
}

if ($warehouse == "in('M101')" || $warehouse == "in('M510')") {
    $wherewarehouse2 =  "";
} else {
    $wherewarehouse2 = "AND NOT s3.LOGICALWAREHOUSECODE IN ('M510','M101')";

}
$query = "SELECT
                p.SUBCODE01,
                p.SUBCODE02,
                p.SUBCODE03,
				TRIM(p.SUBCODE01) || '-' || TRIM(p.SUBCODE02) || '-' || TRIM(p.SUBCODE03) AS KODE_OBAT,                
                CASE 
                	WHEN sum(s.USERPRIMARYQUANTITY) IS NULL THEN 0 
                	ELSE sum(s.USERPRIMARYQUANTITY)
                END  AS QTY_TRANSFER,              
            	CASE 
                	WHEN s.USERPRIMARYUOMCODE IS NULL AND p.BASEPRIMARYUNITCODE IN ('kg','t') THEN 'g'
                	ELSE s.USERPRIMARYUOMCODE
                END AS SATUAN_TRANSFER,
                u.LONGDESCRIPTION AS DESC_CATEGORY,
                p.LONGDESCRIPTION as NAMA_OBAT
            FROM
                 PRODUCT p
            LEFT JOIN 
            (
				SELECT    s.TRANSACTIONDATE,
                                            s.LOGICALWAREHOUSECODE,
                                            s.ITEMTYPECODE,
                                            s.DECOSUBCODE01,
                                            s.DECOSUBCODE02,
                                            s.DECOSUBCODE03,
                                            s3.TEMPLATECODE,                               
                                            CASE 
                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN s.USERPRIMARYQUANTITY * 1000000
                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN s.USERPRIMARYQUANTITY * 1000
                                                ELSE s.USERPRIMARYQUANTITY
                                            END AS USERPRIMARYQUANTITY,
                                            CASE 
                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                                ELSE s.USERPRIMARYUOMCODE
                                            END AS USERPRIMARYUOMCODE
                                        FROM
                                            STOCKTRANSACTION s
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME ='KeteranganDYC' 
                                        LEFT JOIN STOCKTRANSACTION s3 ON s3.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER $wherewarehouse2 AND s3.DETAILTYPE =  $detailtype  
                                        WHERE
                                            s.ITEMTYPECODE = 'DYC'
                                            -- AND TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) BETWEEN '$awal 23:01:00' AND '$tgl2 23:00:00'
                                            AND s.TRANSACTIONDATE BETWEEN '$awal' AND '$tgl2'
                                            AND (
                                                (s.TRANSACTIONDATE > '$awal' OR (s.TRANSACTIONDATE = '$awal' AND s.TRANSACTIONTIME >= '23:01:00'))
                                                AND (s.TRANSACTIONDATE < '$tgl2' OR (s.TRANSACTIONDATE = '$tgl2' AND s.TRANSACTIONTIME <= '23:00:00'))
                                            )
                                            $wheretemplate2 
                                            AND s.LOGICALWAREHOUSECODE $warehouse
                                            AND (CASE 
                                                WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                                                ELSE s.TEMPLATECODE
                                            END ) $wheretemplate
                                            AND s.DECOSUBCODE01 = '$code'               
                ) s ON  s.ITEMTYPECODE =  p.ITEMTYPECODE          
                AND  s.DECOSUBCODE01 = p.SUBCODE01
                AND  s.DECOSUBCODE02 = p.SUBCODE02
                AND  s.DECOSUBCODE03 = p.SUBCODE03 
            LEFT JOIN USERGENERICGROUP u ON u.CODE = p.SUBCODE01 AND u.USERGENERICGROUPTYPECODE ='S09'
            LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = p.ABSUNIQUEID AND a3.FIELDNAME ='ShowChemical'
            WHERE
                p.ITEMTYPECODE = 'DYC'
                AND a3.VALUEBOOLEAN = '1'
                and p.SUBCODE01 = '$code' 
            GROUP BY 
                p.SUBCODE01,
                p.SUBCODE02,
                p.SUBCODE03,
                s.USERPRIMARYUOMCODE,
                p.BASEPRIMARYUNITCODE,
                u.LONGDESCRIPTION,
                p.LONGDESCRIPTION 
                order by 
                TRIM(p.SUBCODE01) || '-' || TRIM(p.SUBCODE02) || '-' || TRIM(p.SUBCODE03) asc";
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
$kode_obat_label = $rows2[0]['SUBCODE01'] ?? '';
$nama_obat_label = $rows2[0]['DESC_CATEGORY'] ?? '';

echo "<h4><strong>" . htmlspecialchars($kode_obat_label) . " - " . htmlspecialchars($nama_obat_label) . "</strong></h4>";
// if ($stmt) { 
echo "<table class='table table-bordered table-striped' id='detailtransferTable'>";
echo "<thead>
             <tr>
                <th class='text-center'>No</th>
                <th class='text-center'>Kode Obat </th>
                <th class='text-center'>Nama Obat </th>
                <th class='text-center'>QTY (gr)</th>                
            </tr>
    </thead>";
echo "<tbody>";
foreach ($rows2 as $row) {
    echo "<tr>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . htmlspecialchars($row['KODE_OBAT'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['NAMA_OBAT'] ?? '') . "</td>";
    echo "<td>" . number_format((float) ($row['QTY_TRANSFER'] ?? 0), 2) . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";
// } else {
//     echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
// }


?>