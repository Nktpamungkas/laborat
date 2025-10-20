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
                p.SUBCODE01,
                p.SUBCODE02,
                p.SUBCODE03,
				TRIM(p.SUBCODE01) || '-' || TRIM(p.SUBCODE02) || '-' || TRIM(p.SUBCODE03) AS KODE_OBAT,                
                CASE 
                	WHEN sum(s.USERPRIMARYQUANTITY) IS NULL THEN 0 
                	ELSE sum(s.USERPRIMARYQUANTITY)
                END  AS USED_QTY, 
                CASE 
                	WHEN s.USERPRIMARYUOMCODE IS NULL AND p.BASEPRIMARYUNITCODE IN ('kg','t') THEN 'g'
                	ELSE s.USERPRIMARYUOMCODE
                END AS SATUAN,
                u.LONGDESCRIPTION AS DESC_CATEGORY,
                p.LONGDESCRIPTION as NAMA_OBAT
            FROM
                 PRODUCT p
            LEFT JOIN 
            (
				SELECT 
                    s.TRANSACTIONDATE,
               		s.ITEMTYPECODE,
                    s.DECOSUBCODE01,
                    s.DECOSUBCODE02,
                    s.DECOSUBCODE03,
                    s.TEMPLATECODE,
                    s.LOGICALWAREHOUSECODE,
                    CASE 
                        when s.CREATIONUSER = 'azwani.najwa' AND  s.TEMPLATECODE = '098' and  s.TRANSACTIONDATE ='2025-10-05' AND s.LOGICALWAREHOUSECODE ='M510' then 0
                        WHEN s.USERPRIMARYUOMCODE = 't' THEN sum(s.USERPRIMARYQUANTITY) * 1000000
                        WHEN s.USERPRIMARYUOMCODE = 'kg' THEN sum(s.USERPRIMARYQUANTITY)* 1000
                        ELSE sum(s.USERPRIMARYQUANTITY)
                    END AS USERPRIMARYQUANTITY,
                    CASE 
                        WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                        WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                        ELSE s.USERPRIMARYUOMCODE
                    END AS USERPRIMARYUOMCODE
                FROM
                    STOCKTRANSACTION s
                WHERE
                    s.ITEMTYPECODE = 'DYC'
                    AND s.TRANSACTIONDATE BETWEEN '$tgl1' AND '$tgl2'
                    AND s.TEMPLATECODE  IN ('120','098')
                    AND s.LOGICALWAREHOUSECODE $warehouse
                    and s.DECOSUBCODE01 = '$code' 
                    AND NOT TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03) ='E-1-000' 
               GROUP BY
                    s.TRANSACTIONDATE,
                    s.CREATIONUSER,
               	    s.ITEMTYPECODE,
                    s.DECOSUBCODE01,
                    s.DECOSUBCODE02,
                    s.DECOSUBCODE03,
                    s.TEMPLATECODE,
                    s.USERPRIMARYUOMCODE,
                    s.LOGICALWAREHOUSECODE
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
$kode_obat_label = $rows2[0]['DECOSUBCODE01'] ?? '';
$nama_obat_label = $rows2[0]['DESC_CATEGORY'] ?? '';

echo "<h4><strong>" . htmlspecialchars($kode_obat_label) . " - " . htmlspecialchars($nama_obat_label) . "</strong></h4>";
// if ($stmt) { 
echo "<table class='table table-bordered table-striped' id='detailPakaiTabel'>";
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
    echo "<td>" . number_format((float) ($row['USED_QTY'] ?? 0), 2) . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";
// } else {
//     echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
// }


?>