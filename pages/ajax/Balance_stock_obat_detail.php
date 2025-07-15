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
                                           KODE_OBAT,
                                           TRANSACTIONDATE,
                                            TEMPLATECODE,
                                            ITEMTYPECODE,
                                            ORDERLINE,
                                            PRODUCTIONORDERCODE,
                                            LOGICALWAREHOUSECODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            QTY_TRANSFER,
                                            LONGDESCRIPTION, 
                                            NAMA_OBAT
                                           from
                                            (SELECT
                                            TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03) AS KODE_OBAT,
                                            s.TRANSACTIONDATE,
                                            s.TEMPLATECODE,
                                            s.ITEMTYPECODE,
                                            s.ORDERLINE,
                                            CASE
                                                WHEN s.PRODUCTIONORDERCODE IS NULL THEN COALESCE(s.ORDERCODE, s.LOTCODE)
                                                ELSE s.PRODUCTIONORDERCODE
                                            END AS PRODUCTIONORDERCODE,
                                            s.LOGICALWAREHOUSECODE,
                                            s.DECOSUBCODE01,
                                            s.DECOSUBCODE02,
                                            s.DECOSUBCODE03,
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
                                            s2.LONGDESCRIPTION, 
                                            p.LONGDESCRIPTION as NAMA_OBAT
                                        FROM
                                            STOCKTRANSACTION s
                                            LEFT JOIN STOCKTRANSACTIONTEMPLATE s2 ON s2.CODE = s.TEMPLATECODE 
                                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = s.ITEMTYPECODE
                                            AND p.SUBCODE01 = s.DECOSUBCODE01
                                            AND p.SUBCODE02 = s.DECOSUBCODE02
                                            AND p.SUBCODE03 = s.DECOSUBCODE03
                                        WHERE
                                        s.ITEMTYPECODE = 'DYC'
                                        AND s.TEMPLATECODE IN ('201','203'))
                                        where KODE_OBAT = '$code' 
                                        and TRANSACTIONDATE BETWEEN '$tgl1' AND '$tgl2'
                                        and LOGICALWAREHOUSECODE = '$warehouse'";
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
echo "<table class='table table-bordered table-striped' id='detailbalanceTable'>";
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
    echo "<td>" . htmlspecialchars($row['TRANSACTIONDATE'] ?? '') . "</td>";
    echo "<td>" . number_format((float) ($row['QTY_TRANSFER'] ?? 0), 2) . "</td>";
    echo "<td>" . htmlspecialchars($row['TEMPLATECODE'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['LONGDESCRIPTION'] ?? '') . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";
// } else {
//     echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
// }


?>