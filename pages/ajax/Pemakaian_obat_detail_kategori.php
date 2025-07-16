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
    TRANSACTIONDATE,
    DECOSUBCODE01,
    DECOSUBCODE02,
    DECOSUBCODE03,
    KODE_OBAT,
    NAMA_OBAT,
    DESC_USERGENERIC,
    SUM(CASE WHEN KET_STEPTYPE = 'Normal' THEN NORMAL_QTY ELSE 0 END) AS NORMAL_QTY,
    SUM(CASE WHEN KET_STEPTYPE = 'Additional' THEN ADITIONAL_QTY ELSE 0 END) AS ADITIONAL_QTY,
    SUM(CASE WHEN KET_STEPTYPE = 'Tambah Obat' THEN TAMBAH_OBAT_QTY ELSE 0 END) AS TAMBAH_OBAT_QTY
FROM (
  SELECT 
                TRANSACTIONDATE,
                DECOSUBCODE01,
                DECOSUBCODE02,
                DECOSUBCODE03,
                KODE_OBAT,
                LONGDESCRIPTION As NAMA_OBAT,
                DESC_USERGENERIC,
                sum(NORMAL_QTY) AS NORMAL_QTY,
                sum(ADITIONAL_QTY) AS ADITIONAL_QTY,
                sum(TAMBAH_OBAT_QTY) AS TAMBAH_OBAT_QTY,
                TEMPLATECODE,
                KETERANGAN,
                KET_STEPTYPE
            FROM 
            (SELECT
                s.TRANSACTIONDATE,
                s.DECOSUBCODE01,
                s.DECOSUBCODE02,
                s.DECOSUBCODE03,
                CASE
                    WHEN s.TEMPLATECODE = '120' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)                   
                END AS KODE_OBAT,
                CASE 
                    WHEN  s.USERPRIMARYUOMCODE = 't' AND n2.STEPTYPE = 0  THEN s.USERPRIMARYQUANTITY * 1000000
                    WHEN  s.USERPRIMARYUOMCODE = 'kg' AND n2.STEPTYPE = 0 THEN s.USERPRIMARYQUANTITY * 1000
                    WHEN n2.STEPTYPE = 0 THEN s.USERPRIMARYQUANTITY
                    ELSE  0
                END AS NORMAL_QTY,
                CASE 
                    WHEN  s.USERPRIMARYUOMCODE = 't' AND n2.STEPTYPE = 1  THEN s.USERPRIMARYQUANTITY * 1000000
                    WHEN  s.USERPRIMARYUOMCODE = 'kg' AND n2.STEPTYPE = 1 THEN s.USERPRIMARYQUANTITY * 1000
                    WHEN n2.STEPTYPE = 1 THEN s.USERPRIMARYQUANTITY 
                    ELSE  0
                END AS ADITIONAL_QTY,
                CASE 
                    WHEN  s.USERPRIMARYUOMCODE = 't' AND n2.STEPTYPE = 3  THEN s.USERPRIMARYQUANTITY * 1000000
                    WHEN  s.USERPRIMARYUOMCODE = 'kg' AND n2.STEPTYPE = 3 THEN s.USERPRIMARYQUANTITY * 1000
                    WHEN  n2.STEPTYPE = 3  THEN s.USERPRIMARYQUANTITY
                    ELSE 0
                END AS TAMBAH_OBAT_QTY,
                CASE 
                    WHEN  s.USERPRIMARYUOMCODE = 't'THEN 'g  '
                    WHEN  s.USERPRIMARYUOMCODE = 'kg'THEN 'g  '
                    ELSE  s.USERPRIMARYUOMCODE
                END AS SATUAN,
                 u.LONGDESCRIPTION AS DESC_USERGENERIC,
                s.LOGICALWAREHOUSECODE,
                p.LONGDESCRIPTION,
                s.TEMPLATECODE,
                n2.KETERANGAN,
                n2.STEPTYPE,
                CASE 
                	WHEN n2.STEPTYPE = 0 THEN 'Normal'
                	WHEN n2.STEPTYPE = 1 THEN 'Additional'
--                	WHEN n2.STEPTYPE = 2 THEN 'Normal'/
                	WHEN n2.STEPTYPE = 3 THEN 'Tambah Obat'
                END AS KET_STEPTYPE
            FROM
                STOCKTRANSACTION s
            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = s.ITEMTYPECODE
                AND p.SUBCODE01 = s.DECOSUBCODE01
                AND p.SUBCODE02 = s.DECOSUBCODE02
                AND p.SUBCODE03 = s.DECOSUBCODE03
            LEFT JOIN INTERNALDOCUMENT i ON i.PROVISIONALCODE = s.ORDERCODE
            LEFT JOIN ORDERPARTNER o ON o.CUSTOMERSUPPLIERCODE = i.ORDPRNCUSTOMERSUPPLIERCODE
            LEFT JOIN LOGICALWAREHOUSE l ON l.CODE = o.CUSTOMERSUPPLIERCODE
            LEFT JOIN STOCKTRANSACTION s2 ON s2.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER AND s2.DETAILTYPE = 2
            LEFT JOIN LOGICALWAREHOUSE l2 ON l2.CODE = s2.LOGICALWAREHOUSECODE
            LEFT JOIN USERGENERICGROUP u ON u.CODE = s.DECOSUBCODE01 AND u.USERGENERICGROUPTYPECODE ='S09'
            LEFT JOIN ( SELECT DISTINCT 
                        p.PRODUCTIONORDERCODE,
                        p.GROUPLINE,
                        p3.STEPTYPE,
                        CASE
                            WHEN p2.CODE LIKE '%T1%' OR p2.CODE LIKE '%T2%' OR p2.CODE LIKE '%T3%' OR p2.CODE LIKE '%T4%' OR p2.CODE LIKE '%T5%' OR p2.CODE LIKE '%T6%' OR p2.CODE LIKE '%T7%' THEN 'Tambah Obat'
                            WHEN p2.CODE LIKE '%R1%' OR p2.CODE LIKE '%R2%' OR p2.CODE LIKE '%R3%' OR p2.CODE LIKE '%R4%' OR p2.CODE LIKE '%R5%' OR p2.CODE LIKE '%R6%' OR p2.CODE LIKE '%R7%' THEN 'Perbaikan'
                            -- ELSE 'Normal'
                            -- ELSE p.PRODRESERVATIONLINKGROUPCODE
                            ELSE 
                                CASE
                                    WHEN p.PRODRESERVATIONLINKGROUPCODE IS NULL THEN COALESCE(p3.OPERATIONCODE, p.PRODRESERVATIONLINKGROUPCODE)
                                    ELSE p.PRODRESERVATIONLINKGROUPCODE
                                END
                        END AS KETERANGAN
                    FROM
                        PRODUCTIONRESERVATION p
                    LEFT JOIN PRODRESERVATIONLINKGROUP p2 ON p2.CODE = p.PRODRESERVATIONLINKGROUPCODE 
                    LEFT JOIN PRODUCTIONDEMANDSTEP p3 ON p3.STEPNUMBER = p.GROUPSTEPNUMBER AND p3.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                            ) n2 ON n2.PRODUCTIONORDERCODE = s.PRODUCTIONORDERCODE
                            AND n2.GROUPLINE = s.ORDERLINE
            WHERE
                s.ITEMTYPECODE = 'DYC'
                AND s.TRANSACTIONDATE  BETWEEN '$tgl1' AND '$tgl2'
                AND s.TEMPLATECODE IN ('120','201','203')
                AND (s.DETAILTYPE = 1 OR s.DETAILTYPE = 0)
                AND s.LOGICALWAREHOUSECODE = '$warehouse'
                and s.DECOSUBCODE01 = '$code1' AND
            s.DECOSUBCODE02 = '$code2' AND
            s.DECOSUBCODE03 = '$code3'
            ORDER BY
                s.PRODUCTIONORDERCODE ASC)                
                GROUP BY 
                TRANSACTIONDATE,
                DECOSUBCODE01,               
                DECOSUBCODE02,
                DECOSUBCODE03,
                LONGDESCRIPTION,
                DESC_USERGENERIC,
                KODE_OBAT,
                TEMPLATECODE,
                KETERANGAN,
                KET_STEPTYPE
            HAVING 
            COALESCE(SUM(NORMAL_QTY), 0) > 0 
            OR COALESCE(SUM(ADITIONAL_QTY), 0) > 0 
            OR COALESCE(SUM(TAMBAH_OBAT_QTY), 0) > 0
) AS sub
GROUP BY 
    TRANSACTIONDATE,
    DECOSUBCODE01,
    DECOSUBCODE02,
    DECOSUBCODE03,
    KODE_OBAT,
    NAMA_OBAT,
    DESC_USERGENERIC
ORDER BY TRANSACTIONDATE ASC";
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
echo "<table class='table table-bordered table-striped' id='detailPakaiTabel'>";
echo "<thead>
             <tr>
                <th class='text-center'>No</th>
                <th class='text-center'>Tanggal</th>
                <th class='text-center'>Normal (gr)</th>
                <th class='text-center'>Tambah Obat (gr)</th>
                <th class='text-center's>perbaikan (gr)</th>                
            </tr>
    </thead>";
echo "<tbody>";
foreach ($rows2 as $row) {
    echo "<tr>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . htmlspecialchars($row['TRANSACTIONDATE'] ?? '') . "</td>";
    echo "<td>" . number_format((float) ($row['NORMAL_QTY'] ?? 0), 2) . "</td>";
    echo "<td>" . number_format((float) ($row['TAMBAH_OBAT_QTY'] ?? 0), 2) . "</td>";
    echo "<td>" . number_format((float) ($row['ADITIONAL_QTY'] ?? 0), 2) . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";
// } else {
//     echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
// }


?>