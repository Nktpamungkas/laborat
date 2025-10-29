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

if ($warehouse = "in('M101')") {
    $templateCodes = "'QCT','OPN','204'";
} else {
    $templateCodes = "'QCT','304','OPN','204'";
}

$query = "SELECT 
			p.SUBCODE01,
                p.SUBCODE02,
                p.SUBCODE03,
				TRIM(p.SUBCODE01) || '-' || TRIM(p.SUBCODE02) || '-' || TRIM(p.SUBCODE03) AS KODE_OBAT,                
                CASE 
                	WHEN sum(s.USERPRIMARYQUANTITY) IS NULL THEN 0 
                	ELSE sum(s.USERPRIMARYQUANTITY)
                END  AS STOCK_BALANCE, 
                CASE 
                	WHEN s.BASEPRIMARYUNITCODE IS NULL AND p.BASEPRIMARYUNITCODE IN ('kg','t') THEN 'g'
                	ELSE s.BASEPRIMARYUNITCODE
                END AS SATUAN,
                u.LONGDESCRIPTION AS DESC_CATEGORY,
                p.LONGDESCRIPTION as NAMA_OBAT
			FROM 
            PRODUCT p 
            LEFT JOIN 
                (SELECT 
                    b.ITEMTYPECODE,
                    b.DECOSUBCODE01,
                    b.DECOSUBCODE02,
                    b.DECOSUBCODE03,
                    CASE 
                        WHEN b.BASEPRIMARYUNITCODE = 'kg' THEN sum(b.BASEPRIMARYQUANTITYUNIT)*1000
                        WHEN b.BASEPRIMARYUNITCODE = 't' THEN sum(b.BASEPRIMARYQUANTITYUNIT)*1000000
                        ELSE sum(b.BASEPRIMARYQUANTITYUNIT)
                    END  AS USERPRIMARYQUANTITY,
                    CASE 
                        WHEN b.BASEPRIMARYUNITCODE = 'kg' THEN 'g'
                        WHEN b.BASEPRIMARYUNITCODE = 't' THEN 'g'
                        ELSE b.BASEPRIMARYUNITCODE
                    END  AS BASEPRIMARYUNITCODE
                    FROM 
                    BALANCE b                 
                    WHERE 
                    b.ITEMTYPECODE ='DYC'
                    AND b.DETAILTYPE = 1
                    and b.LOGICALWAREHOUSECODE $warehouse
                    AND b.DECOSUBCODE01 = '$code'
                    GROUP BY 
                    b.ITEMTYPECODE,
                    b.DECOSUBCODE01,
                    b.DECOSUBCODE02,
                    b.DECOSUBCODE03,
                    b.BASEPRIMARYUNITCODE,
                    b.LOGICALWAREHOUSECODE
                ) s ON p.ITEMTYPECODE = s.ITEMTYPECODE            
                AND p.SUBCODE01 = s.DECOSUBCODE01
                AND p.SUBCODE02 = s.DECOSUBCODE02
                AND p.SUBCODE03 = s.DECOSUBCODE03
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
                s.BASEPRIMARYUNITCODE,
                p.BASEPRIMARYUNITCODE,
                u.LONGDESCRIPTION,
                p.LONGDESCRIPTION 
                order by 
                TRIM(p.SUBCODE01) || '-' || TRIM(p.SUBCODE02) || '-' || TRIM(p.SUBCODE03) asc";
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
$nama_obat_label = $rows2[0]['DESC_CATEGORY'] ?? '';

echo "<h4><strong>" . htmlspecialchars($kode_obat_label) . " - " . htmlspecialchars($nama_obat_label) . "</strong></h4>";
// if ($stmt) { 
echo "<table class='table table-bordered table-striped' id='detailbalanceTable'>";
echo "<thead>
            <tr>
                    <th class='text-center'>No</th>
                    <th class='text-center'>Code</th>
                    <th class='text-center'>Nama Obat</th>
                    <th class='text-center'>QTY (gr)</th>                
                </tr> 
    </thead>";
echo "<tbody>";
foreach ($rows2 as $row) {
    echo "<tr>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . htmlspecialchars($row['KODE_OBAT'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['NAMA_OBAT'] ?? '') . "</td>";
    echo "<td>" . number_format((float) ($row['STOCK_BALANCE'] ?? 0), 2) . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";
// } else {
//     echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
// }


?>