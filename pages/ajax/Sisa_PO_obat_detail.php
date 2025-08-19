<?php
// koneksi ke DB
include "../../koneksi.php";

$code1 = $_POST['code1'];
$code2 = $_POST['code2'];
$code3 = $_POST['code3'];
$tgl_sebelumnya = $_POST['tgl_sebelumnya'];
$tgl2 = $_POST['tgl2'];
$warehouse = $_POST['warehouse'];

// echo "<pre>";
// print_r($_POST); // Debug POST value
// echo "</pre>";

// if ($warehouse == 'M101') {
//     $templateCodes = "'QCT','OPN','204'";
// } else {
//     $templateCodes = "'QCT','304','OPN','204'";
// }

// if ($warehouse == 'M101') {
//     $detailtype = '2';
// } else {
//     $detailtype = '1';
// }

$query = "SELECT * FROM(SELECT 
            v.ISTANCECODE,
            v.COUNTERCODE,
            r.HEADERCODE AS PR_CODE,                                           
            v.DECOSUBCODE01,
            v.DECOSUBCODE02,
            v.DECOSUBCODE03,
            v.DECOSUBCODE04,
            v.DECOSUBCODE05,
            TRIM(v.DECOSUBCODE01) || '-' || TRIM(v.DECOSUBCODE02) || '-' || TRIM(v.DECOSUBCODE03) AS KODE_OBAT,
            r.LONGDESCRIPTION as NAMA_OBAT,
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
            p.CREATIONDATETIME AS CREATIONDATE_PO,
                date(r.CREATIONDATETIME) AS CREATIONDATE_PR
            FROM 
            VIEWAVANALYSISPART1 v 
            LEFT JOIN PURCHASEORDERLINE p ON p.PURCHASEORDERCODE = v.ISTANCECODE AND p.ORDERLINE = v.ISTANCELINE 
            AND p.SUBCODE01 = v.DECOSUBCODE01 
            AND p.SUBCODE02 = v.DECOSUBCODE02 
            AND p.SUBCODE03 = v.DECOSUBCODE03 
            LEFT JOIN REPLENISHMENTREQUISITION r ON v.ISTANCECODE = r.LINEPURCHASEORDERCODE AND r.LINEORDERLINE = v.ISTANCELINE 
            AND r.SUBCODE01 = v.DECOSUBCODE01 
            AND r.SUBCODE02 = v.DECOSUBCODE02 
            AND r.SUBCODE03 = v.DECOSUBCODE03 
            WHERE 
            v.ISTANCETYPE = '6'
                AND v.LOGICALWAREHOUSECODE IN ('M510','M101')
            AND date(p.CREATIONDATETIME) BETWEEN '$tgl_sebelumnya' AND '$tgl2'
            and v.DECOSUBCODE01 = '$code1'  AND
            v.DECOSUBCODE02 = '$code2'  AND
            v.DECOSUBCODE03 = '$code3' 
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
            p.CREATIONDATETIME,
            r.HEADERCODE,
            r.LONGDESCRIPTION,
            r.CREATIONDATETIME)
            ORDER BY KODE_OBAT ASC";
// echo "<pre>$query</pre>";

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
        echo "<table class='table table-bordered table-striped' id='detailsisaPOTable'>";
        echo "<thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal PO</th>
                    <th>Purchase Order</th>
                    <th>QTY (gr)</th>
                    <th>PR</th>
                    <th>Tanggal PR</th>                
                </tr>
            </thead>";
        echo "<tbody>";
        foreach ($rows2 as $row) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['PO_DATE'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['ISTANCECODE'] ?? '') . "</td>";
            echo "<td>" . number_format((float) ($row['QTY'] ?? 0), 2) . "</td>";
            echo "<td>" . htmlspecialchars($row['PR_CODE'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['CREATIONDATE_PR'] ?? '') . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    // } else {
    //     echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
    // }


?>