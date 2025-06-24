<?php
include "../../koneksi.php";

$code = $_POST['code'];

// Query utama
$query = "SELECT
        i.SALESORDERCODE,
        i.ORDERLINE,
        i.LEGALNAME1,
        p.LONGDESCRIPTION AS JENIS_KAIN,
        i.NOTETAS_KGF || '/' || TRIM(i.SUBCODE01) || '-' || TRIM(i.SUBCODE02) || '-' || TRIM(i.SUBCODE03) || '-' || TRIM(i.SUBCODE04) AS ITEMCODE,
        i.NOTETAS,
        i.EXTERNALREFERENCE AS NO_PO,
        COALESCE(i2.GRAMASI_KFF, i2.GRAMASI_FKF) AS GRAMASI,
        i3.LEBAR,
        COALESCE(
            TRIM(pg.PO_GREIGE) ||
            CASE WHEN i.ADDITIONALDATA IS NOT NULL THEN ', ' || TRIM(i.ADDITIONALDATA) ELSE '' END ||
            CASE WHEN i.ADDITIONALDATA2 IS NOT NULL THEN ', ' || TRIM(i.ADDITIONALDATA2) ELSE '' END ||
            CASE WHEN i.ADDITIONALDATA3 IS NOT NULL THEN ', ' || TRIM(i.ADDITIONALDATA3) ELSE '' END ||
            CASE WHEN i.ADDITIONALDATA4 IS NOT NULL THEN ', ' || TRIM(i.ADDITIONALDATA4) ELSE '' END ||
            CASE WHEN i.ADDITIONALDATA5 IS NOT NULL THEN ', ' || TRIM(i.ADDITIONALDATA5) ELSE '' END ||
            CASE WHEN i.ADDITIONALDATA6 IS NOT NULL THEN ', ' || TRIM(i.ADDITIONALDATA6) ELSE '' END,
            
            TRIM(i.ADDITIONALDATA) ||
            CASE WHEN i.ADDITIONALDATA2 IS NOT NULL THEN ', ' || TRIM(i.ADDITIONALDATA2) ELSE '' END ||
            CASE WHEN i.ADDITIONALDATA3 IS NOT NULL THEN ', ' || TRIM(i.ADDITIONALDATA3) ELSE '' END ||
            CASE WHEN i.ADDITIONALDATA4 IS NOT NULL THEN ', ' || TRIM(i.ADDITIONALDATA4) ELSE '' END ||
            CASE WHEN i.ADDITIONALDATA5 IS NOT NULL THEN ', ' || TRIM(i.ADDITIONALDATA5) ELSE '' END ||
            CASE WHEN i.ADDITIONALDATA6 IS NOT NULL THEN ', ' || TRIM(i.ADDITIONALDATA6) ELSE '' END
        ) AS PO_GREIGE,
        CASE a.VALUESTRING
            WHEN '1' THEN 'L/D'
            WHEN '2' THEN 'First Lot'
            WHEN '3' THEN 'Original'
            WHEN '4' THEN 'Previous Order'
            WHEN '5' THEN 'Master Color'
            WHEN '6' THEN 'Lampiran Buyer'
            WHEN '7' THEN 'Body'
            ELSE ''
        END AS COLOR_STANDARD,
        i.WARNA,
        TRIM(i.SUBCODE05) || ' (' || TRIM(i.COLORGROUP) || ')' AS KODE_WARNA,
        a2.VALUESTRING AS COLORREMARKS,
        TRIM(i.SUBCODE01) AS SUBCODE01,
        TRIM(i.SUBCODE02) AS SUBCODE02,
        TRIM(i.SUBCODE03) AS SUBCODE03,
        TRIM(i.SUBCODE04) AS SUBCODE04,
        TRIM(i.SUBCODE05) AS SUBCODE05,
        TRIM(i.SUBCODE06) AS SUBCODE06,
        TRIM(i.SUBCODE07) AS SUBCODE07,
        TRIM(i.SUBCODE08) AS SUBCODE08,
        TRIM(i.SUBCODE09) AS SUBCODE09,
        TRIM(i.SUBCODE10) AS SUBCODE10
    FROM
        ITXVIEWBONORDER i
    LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = i.ITEMTYPEAFICODE 
                        AND p.SUBCODE01 = i.SUBCODE01 
                        AND p.SUBCODE02 = i.SUBCODE02 
                        AND p.SUBCODE03 = i.SUBCODE03 
                        AND p.SUBCODE04 = i.SUBCODE04 
                        AND p.SUBCODE05 = i.SUBCODE05 
                        AND p.SUBCODE06 = i.SUBCODE06 
                        AND p.SUBCODE07 = i.SUBCODE07 
                        AND p.SUBCODE08 = i.SUBCODE08 
                        AND p.SUBCODE09 = i.SUBCODE09 
                        AND p.SUBCODE10 = i.SUBCODE10
    LEFT JOIN ITXVIEWGRAMASI i2 ON i2.SALESORDERCODE = i.SALESORDERCODE AND i2.ORDERLINE = i.ORDERLINE 
    LEFT JOIN ITXVIEWLEBAR i3 ON i3.SALESORDERCODE = i.SALESORDERCODE AND i3.ORDERLINE = i.ORDERLINE 
    LEFT JOIN ADSTORAGE a ON a.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND a.FIELDNAME = 'ColorStandard'
    LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND a2.FIELDNAME = 'ColorRemarks'
    LEFT JOIN (
        SELECT 
            ORIGDLVSALORDLINESALORDERCODE AS SALESORDERCODE,
            ORIGDLVSALORDERLINEORDERLINE AS ORDERLINE,
            LISTAGG(CODE, ', ') WITHIN GROUP (ORDER BY CODE) AS PO_GREIGE
        FROM ITXVIEW_RAJUT
        WHERE TGLPOGREIGE IS NOT NULL
        GROUP BY ORIGDLVSALORDLINESALORDERCODE, ORIGDLVSALORDERLINEORDERLINE
    ) pg ON pg.SALESORDERCODE = i.SALESORDERCODE AND pg.ORDERLINE = i.ORDERLINE
    WHERE i.SALESORDERCODE = '$code'";

$stmt = db2_exec($conn1, $query);

if (!$stmt) {
    echo "<p class='text-danger'>Query gagal dijalankan.</p>";
    exit;
}

$data = [];
while ($row = db2_fetch_assoc($stmt)) {
    $data[] = $row;
}

// Fungsi untuk prioritas baris PO_GREIGE berdasar NOTETAS tidak null
function sortByNotetas($a, $b) {
    $notaA = trim($a['NOTETAS'] ?? '');
    $notaB = trim($b['NOTETAS'] ?? '');

    if ($notaA === '' && $notaB !== '') {
        return 1;
    }
    if ($notaA !== '' && $notaB === '') {
        return -1;
    }
    return 0;
}

// Urutkan data berdasar SALESORDERCODE + ORDERLINE, lalu prioritaskan NOTETAS tidak null
usort($data, 'sortByNotetas');

// Kita ingin ambil satu record unik per SALESORDERCODE + ORDERLINE
$finalData = [];
foreach ($data as $row) {
    $key = $row['SALESORDERCODE'] . '_' . $row['ORDERLINE'];
    if (!isset($finalData[$key])) {
        $finalData[$key] = $row;
    }
}

// Tampilkan tabel
echo "<table class='table table-bordered table-striped' id='detailApprovedTable'>";
echo "<thead>
        <tr>
            <th>No</th>
            <th>No PO</th>
            <th>Nama Buyer</th>
            <th>Jenis Kain</th>
            <th>Itemcode</th>
            <th>Notetas</th>
            <th>Gramasi</th>
            <th>Lebar</th>
            <th>Color Standard</th>
            <th>Warna</th>
            <th>Kode Warna</th>
            <th>Color Remarks</th>
            <th>Benang</th>
            <th>Po Greige</th>
        </tr>
      </thead>";
echo "<tbody>";
$no = 1;
foreach ($finalData as $row) {
    echo "<tr>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . htmlspecialchars($row['NO_PO'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['LEGALNAME1'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['JENIS_KAIN'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['ITEMCODE'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['NOTETAS'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars(number_format($row['GRAMASI'] ?? 0, 2)) . "</td>";
    echo "<td>" . htmlspecialchars(number_format($row['LEBAR'] ?? 0, 2)) . "</td>";
    echo "<td>" . htmlspecialchars($row['COLOR_STANDARD'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['WARNA'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['KODE_WARNA'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['COLORREMARKS'] ?? '') . "</td>";
    // Kolom Benang bisa kamu tambahkan sesuai kebutuhan, sementara kosong
    echo "<td></td>";
    echo "<td>" . htmlspecialchars($row['PO_GREIGE'] ?? '') . "</td>";
    echo "</tr>";
}
echo "</tbody></table>";
?>
