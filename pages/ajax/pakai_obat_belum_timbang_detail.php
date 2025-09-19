<?php
// koneksi ke DB
include "../../koneksi.php";

$code1 = $_POST['code1'];
$code2 = $_POST['code2'];
$code3 = $_POST['code3'];
$tgl1 = $_POST['tgl1'];
$tgl2 = $_POST['tgl2'];
$time = $_POST['time'];
$time2 = $_POST['time2'];
$warehouse = $_POST['warehouse'];

// echo "<pre>";
// print_r($_POST); // Debug POST value
// echo "</pre>";

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

$query = "SELECT 
        v.WAREHOUSECODE AS LOGICALWAREHOUSECODE,
        p.PRODUCTIONORDERCOUNTERCODE AS COUNTERCODE,
        v.PRODUCTIONORDERCODE AS ISTANCECODE,
        TRIM(v.PRODUCTIONORDERCODE) || '-' || TRIM(v.GROUPLINE)AS NOMOR_RESEP,
        TRIM(v.SUBCODE01) || '-' || TRIM(v.SUBCODE02) || '-' || TRIM(v.SUBCODE03) AS KODE_OBAT,
        p2.LONGDESCRIPTION AS NAMA_OBAT,
        v.GROUPLINE,
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
        LEFT JOIN PRODUCT p2 ON 
        p2.ITEMTYPECODE = v.ITEMTYPEAFICODE 
        AND p2.SUBCODE01 = v.SUBCODE01
        AND p2.SUBCODE02 = v.SUBCODE02
        AND p2.SUBCODE03 = v.SUBCODE03
        WHERE 
            v.ITEMTYPEAFICODE ='DYC'
		    AND v.PROGRESSSTATUS = 0
            AND p.STATUS = 0
            AND p.PRODUCTIONORDERCOUNTERCODE = '640'
            AND v.WAREHOUSECODE  $warehouse
            AND v.SUBCODE01 = '$code1' 
            AND v.SUBCODE02 = '$code2' 
            AND v.SUBCODE03 = '$code3'
            AND v.ISSUEDATE BETWEEN '$tgl1 ' AND '$tgl2'
                    ";


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
$kode_obat_label = $rows2[0]['KODE_OBAT'] ?? '';
$nama_obat_label = $rows2[0]['NAMA_OBAT'] ?? '';

echo "<h4><strong>" . htmlspecialchars($kode_obat_label) . " - " . htmlspecialchars($nama_obat_label) . "</strong></h4>";
// if ($stmt) { 
echo "<table class='table table-bordered table-striped' id='detailpakaibelumtimbang'>";
echo "<thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>No Bon Resep</th>
                <th>QTY (gr)</th>              
            </tr>
        </thead>";
echo "<tbody>";
foreach ($rows2 as $row) {
    echo "<tr>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . htmlspecialchars($row['DUEDATE'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['NOMOR_RESEP'] ?? '') . "</td>";
    echo "<td>" . number_format((float) ($row['BASEPRIMARYQUANTITY'] ?? 0), 2) . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";
// } else {
//     echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
// }


?>