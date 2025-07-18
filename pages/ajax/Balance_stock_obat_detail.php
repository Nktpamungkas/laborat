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

if ($warehouse == 'M101') {
    $templateCodes = "'QCT','OPN','204'";
} else {
    $templateCodes = "'QCT','304','OPN','204'";
}

$query = "SELECT 
                b.ITEMTYPECODE,
                b.DECOSUBCODE01,
                b.DECOSUBCODE02,
                b.DECOSUBCODE03,
                TRIM(b.DECOSUBCODE01) ||'-'|| TRIM(b.DECOSUBCODE02) ||'-'|| TRIM(b.DECOSUBCODE03) AS KODE_OBAT,
                p.LONGDESCRIPTION AS NAMA_OBAT,
                b.LOGICALWAREHOUSECODE,
                TRIM(b.WHSLOCATIONWAREHOUSEZONECODE) ||'-'|| TRIM(b.WAREHOUSELOCATIONCODE) AS ZONE_LOCATION,
                u.LONGDESCRIPTION AS DESC_USERGENERIC,
                CASE 
                    WHEN b.BASEPRIMARYUNITCODE = 'kg' THEN sum(b.BASEPRIMARYQUANTITYUNIT)*1000
                    WHEN b.BASEPRIMARYUNITCODE = 't' THEN sum(b.BASEPRIMARYQUANTITYUNIT)*1000000
                    ELSE sum(b.BASEPRIMARYQUANTITYUNIT)
                END  AS STOCK_BALANCE,
                CASE 
                    WHEN b.BASEPRIMARYUNITCODE = 'kg' THEN 'g'
                    WHEN b.BASEPRIMARYUNITCODE = 't' THEN 'g'
                    ELSE b.BASEPRIMARYUNITCODE
                END  AS BASEPRIMARYUNITCODE
                FROM 
                BALANCE b 
                    LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = b.ITEMTYPECODE
                    AND p.SUBCODE01 = b.DECOSUBCODE01
                    AND p.SUBCODE02 = b.DECOSUBCODE02
                    AND p.SUBCODE03 = b.DECOSUBCODE03
                    LEFT JOIN USERGENERICGROUP u ON u.CODE = b.DECOSUBCODE01 AND u.USERGENERICGROUPTYPECODE ='S09'
                WHERE 
                b.ITEMTYPECODE ='DYC'
                AND b.LOGICALWAREHOUSECODE = '$warehouse'
                AND b.DETAILTYPE = 1
                AND b.DECOSUBCODE01 = '$code' 
                GROUP BY 
                b.ITEMTYPECODE,
                b.DECOSUBCODE01,
                b.DECOSUBCODE02,
                b.DECOSUBCODE03,
                b.BASEPRIMARYUNITCODE,
                b.LOGICALWAREHOUSECODE,
                b.WHSLOCATIONWAREHOUSEZONECODE,
                b.WAREHOUSELOCATIONCODE,
                p.LONGDESCRIPTION,
                u.LONGDESCRIPTION";
// echo "<pre>$query</pre>";

$stmt = db2_exec($conn1, $query);
if (!$stmt) {
    echo "<p class='text-danger'>Query gagal: " . db2_stmt_errormsg() . "</p>";
    exit;
}
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
echo "<table class='table table-bordered table-striped' id='detailbalanceTable'>";
echo "<thead>
            <tr>
                    <th class='text-center'>No</th>
                    <th class='text-center'>Code</th>
                    <th class='text-center'>Nama Obat</th>
                    <th class='text-center'>QTY (gr)</th>
                    <th class='text-center'>Warehouse</th>
                    <th class='text-center'>Zone - Location</th>                
                </tr> 
    </thead>";
echo "<tbody>";
foreach ($rows2 as $row) {
    echo "<tr>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . htmlspecialchars($row['KODE_OBAT'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['NAMA_OBAT'] ?? '') . "</td>";
    echo "<td>" . number_format((float) ($row['STOCK_BALANCE'] ?? 0), 2) . "</td>";
    echo "<td>" . htmlspecialchars($row['LOGICALWAREHOUSECODE'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['ZONE_LOCATION'] ?? '') . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";
// } else {
//     echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
// }


?>