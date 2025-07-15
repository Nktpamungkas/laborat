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
                s.TRANSACTIONDATE,
                s.TEMPLATECODE,
                s.ITEMTYPECODE,
                s.DECOSUBCODE01,
                s.DECOSUBCODE02,
                s.DECOSUBCODE03,
                TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03) AS KODE_OBAT,
                p.LONGDESCRIPTION as NAMA_OBAT,
                s2.LONGDESCRIPTION, 
                u.LONGDESCRIPTION AS DESC_USERGENERIC,
                CASE 
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
                LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = s.ITEMTYPECODE
                AND p.SUBCODE01 = s.DECOSUBCODE01
                AND p.SUBCODE02 = s.DECOSUBCODE02
                AND p.SUBCODE03 = s.DECOSUBCODE03 
                LEFT JOIN STOCKTRANSACTIONTEMPLATE s2 ON s2.CODE = s.TEMPLATECODE 
                LEFT JOIN USERGENERICGROUP u ON u.CODE = s.DECOSUBCODE01 AND u.USERGENERICGROUPTYPECODE ='S09'
            WHERE
                s.ITEMTYPECODE = 'DYC'
                AND s.TRANSACTIONDATE BETWEEN '$tgl1' AND '$tgl2'
                AND s.TEMPLATECODE IN ('QCT','304','OPN','204')
                AND s.LOGICALWAREHOUSECODE = '$warehouse'
                and s.DECOSUBCODE01 = '$code1' AND
                s.DECOSUBCODE02 = '$code2' AND
                s.DECOSUBCODE03 = '$code3' ";
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

echo "<h4><strong>" . htmlspecialchars($kode_obat_label) . " ( " . htmlspecialchars($nama_obat_label) .")". "</strong></h4>";
// if ($stmt) { 
echo "<table class='table table-bordered table-striped' id='detailTransferTable'>";
echo "<thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Obat</th>
                    <th>QTY (gr)</th>
                    <th>Template</th>
                    <th>Keterangan</th>                
                </tr>
            </thead>";
echo "<tbody>";
foreach ($rows2 as $row) {
    echo "<tr>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . htmlspecialchars($row['TRANSACTIONDATE'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['NAMA_OBAT'] ?? '') . "</td>";
    echo "<td>" . number_format((float) ($row['QTY_MASUK'] ?? 0), 2) . "</td>";
    echo "<td>" . htmlspecialchars($row['TEMPLATECODE'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['LONGDESCRIPTION'] ?? '') . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";
// } else {
//     echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
// }


?>