<?php
include "../../koneksi.php";

$code = $_POST['code'];

$query = "SELECT DISTINCT 
                    SALESORDERCODE, ORDERLINE, LEGALNAME1, AKJ, JENIS_KAIN,
                    LISTAGG(DISTINCT ITEMCODE, ', ') AS ITEMCODE,
                    LISTAGG(DISTINCT NOTETAS, ', ') AS NOTETAS,
                    NO_PO, GRAMASI, LEBAR, COLOR_STANDARD, WARNA, KODE_WARNA, COLORREMARKS,
                    SUBCODE01, SUBCODE02, SUBCODE03, SUBCODE04,
                    LISTAGG(DISTINCT SUBCODE04_FIXED, ', ') AS SUBCODE04_FIXED,
                    SUBCODE05, SUBCODE06, SUBCODE07, SUBCODE08, SUBCODE09, SUBCODE10,
                    MAX(RevisiC)  AS RevisiC,
                    MAX(RevisiC1) AS RevisiC1,
                    MAX(RevisiC2) AS RevisiC2,
                    MAX(RevisiC3) AS RevisiC3,
                    MAX(RevisiC4) AS RevisiC4,
                    MAX(Revisid1) AS Revisid1,
                    MAX(Revisid)  AS Revisid,
                    MAX(Revisid2) AS Revisid2,
                    MAX(Revisid3) AS Revisid3,
                    MAX(Revisid4) AS Revisid4,
                    MAX(Revisi1Date) AS Revisi1Date,
                    MAX(Revisi2Date) AS Revisi2Date,
                    MAX(Revisi3Date) AS Revisi3Date,
                    MAX(Revisi4Date) AS Revisi4Date,
                    MAX(Revisi5Date) AS Revisi5Date
                FROM 
                (SELECT
                    i.SALESORDERCODE,
                    i.ORDERLINE,
                    CASE
                        WHEN i.ITEMTYPEAFICODE = 'KFF' THEN i.RESERVATION_SUBCODE04
                        ELSE i.SUBCODE04
                    END AS SUBCODE04_FIXED,
                    i.LEGALNAME1,
                    i.AKJ,
                    p.LONGDESCRIPTION AS JENIS_KAIN,
                    i.NOTETAS_KGF || '/' || TRIM(i.SUBCODE01) || '-' || TRIM(i.SUBCODE02) || '-' || TRIM(i.SUBCODE03) || '-' || TRIM(i.SUBCODE04) AS ITEMCODE,
                    i.NOTETAS,
                    i.EXTERNALREFERENCE AS NO_PO,
                    COALESCE(i2.GRAMASI_KFF, i2.GRAMASI_FKF) AS GRAMASI,
                    i3.LEBAR,
                    /* RevisiC* ambil label dari OPTIONS */
                    CASE
                      WHEN sc.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                        AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || sc.VALUESTRING || '=')
                      THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || sc.VALUESTRING || '=([^;]*)',1,1,'',1)
                    END AS RevisiC,
                    CASE
                      WHEN sc1.VALUESTRING IS NOT NULL AND adC1.OPTIONS IS NOT NULL
                        AND REGEXP_LIKE(adC1.OPTIONS, '(?:^|;)' || sc1.VALUESTRING || '=')
                      THEN REGEXP_SUBSTR(adC1.OPTIONS,'(?:^|;)' || sc1.VALUESTRING || '=([^;]*)',1,1,'',1)
                    END AS RevisiC1,
                    CASE
                      WHEN sc2.VALUESTRING IS NOT NULL AND adC2.OPTIONS IS NOT NULL
                        AND REGEXP_LIKE(adC2.OPTIONS, '(?:^|;)' || sc2.VALUESTRING || '=')
                      THEN REGEXP_SUBSTR(adC2.OPTIONS,'(?:^|;)' || sc2.VALUESTRING || '=([^;]*)',1,1,'',1)
                    END AS RevisiC2,
                    CASE
                      WHEN sc3.VALUESTRING IS NOT NULL AND adC3.OPTIONS IS NOT NULL
                        AND REGEXP_LIKE(adC3.OPTIONS, '(?:^|;)' || sc3.VALUESTRING || '=')
                      THEN REGEXP_SUBSTR(adC3.OPTIONS,'(?:^|;)' || sc3.VALUESTRING || '=([^;]*)',1,1,'',1)
                    END AS RevisiC3,
                    CASE
                      WHEN sc4.VALUESTRING IS NOT NULL AND adC4.OPTIONS IS NOT NULL
                        AND REGEXP_LIKE(adC4.OPTIONS, '(?:^|;)' || sc4.VALUESTRING || '=')
                      THEN REGEXP_SUBSTR(adC4.OPTIONS,'(?:^|;)' || sc4.VALUESTRING || '=([^;]*)',1,1,'',1)
                    END AS RevisiC4,
                    /* Revisi detail (D-group) langsung valuestring */
                    sd.VALUESTRING  AS Revisid,
                    sd1.VALUESTRING AS Revisid1,
                    sd2.VALUESTRING AS Revisid2,
                    sd3.VALUESTRING AS Revisid3,
                    sd4.VALUESTRING AS Revisid4,

                    sdt1.VALUEDATE AS Revisi1Date,
                    sdt2.VALUEDATE AS Revisi2Date,
                    sdt3.VALUEDATE AS Revisi3Date,
                    sdt4.VALUEDATE AS Revisi4Date,
                    sdt5.VALUEDATE AS Revisi5Date,
                    CASE
                        a.VALUESTRING
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
                LEFT JOIN PRODUCT p ON
                    p.ITEMTYPECODE = i.ITEMTYPEAFICODE
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
                LEFT JOIN ITXVIEWGRAMASI i2 ON
                    i2.SALESORDERCODE = i.SALESORDERCODE
                    AND i2.ORDERLINE = i.ORDERLINE
                LEFT JOIN ITXVIEWLEBAR i3 ON
                    i3.SALESORDERCODE = i.SALESORDERCODE
                    AND i3.ORDERLINE = i.ORDERLINE
                LEFT JOIN ADSTORAGE a ON
                    a.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE
                    AND a.FIELDNAME = 'ColorStandard'
                LEFT JOIN ADSTORAGE a2 ON
                    a2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE
                    AND a2.FIELDNAME = 'ColorRemarks'
                LEFT JOIN ADSTORAGE sc   ON sc.UNIQUEID  = i.ABSUNIQUEID_SALESORDERLINE AND sc.FIELDNAME  = 'RevisiC'
                LEFT JOIN ADADDITIONALDATA adC  ON adC.NAME  = sc.FIELDNAME
                LEFT JOIN ADSTORAGE sc1  ON sc1.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sc1.FIELDNAME = 'RevisiC1'
                LEFT JOIN ADADDITIONALDATA adC1 ON adC1.NAME = sc1.FIELDNAME
                LEFT JOIN ADSTORAGE sc2  ON sc2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sc2.FIELDNAME = 'RevisiC2'
                LEFT JOIN ADADDITIONALDATA adC2 ON adC2.NAME = sc2.FIELDNAME
                LEFT JOIN ADSTORAGE sc3  ON sc3.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sc3.FIELDNAME = 'RevisiC3'
                LEFT JOIN ADADDITIONALDATA adC3 ON adC3.NAME = sc3.FIELDNAME
                LEFT JOIN ADSTORAGE sc4  ON sc4.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sc4.FIELDNAME = 'RevisiC4'
                LEFT JOIN ADADDITIONALDATA adC4 ON adC4.NAME = sc4.FIELDNAME
                LEFT JOIN ADSTORAGE sd   ON sd.UNIQUEID  = i.ABSUNIQUEID_SALESORDERLINE AND sd.FIELDNAME  = 'Revisid'
                LEFT JOIN ADSTORAGE sd1  ON sd1.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd1.FIELDNAME = 'Revisid1'
                LEFT JOIN ADSTORAGE sd2  ON sd2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd2.FIELDNAME = 'Revisid2'
                LEFT JOIN ADSTORAGE sd3  ON sd3.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd3.FIELDNAME = 'Revisid3'
                LEFT JOIN ADSTORAGE sd4  ON sd4.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd4.FIELDNAME = 'Revisid4'

                LEFT JOIN ADSTORAGE sdt1 ON sdt1.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt1.FIELDNAME = 'Revisi1Date'
                LEFT JOIN ADSTORAGE sdt2 ON sdt2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt2.FIELDNAME = 'Revisi2Date'
                LEFT JOIN ADSTORAGE sdt3 ON sdt3.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt3.FIELDNAME = 'Revisi3Date'
                LEFT JOIN ADSTORAGE sdt4 ON sdt4.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt4.FIELDNAME = 'Revisi4Date'
                LEFT JOIN ADSTORAGE sdt5 ON sdt5.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt5.FIELDNAME = 'Revisi5Date'

                WHERE i.SALESORDERCODE = '$code')
                GROUP BY
                    SALESORDERCODE, ORDERLINE, LEGALNAME1, AKJ, JENIS_KAIN,
                    NO_PO, GRAMASI, LEBAR, COLOR_STANDARD, WARNA, KODE_WARNA, COLORREMARKS,
                    SUBCODE01, SUBCODE02, SUBCODE03, SUBCODE04, SUBCODE05, SUBCODE06,
                    SUBCODE07, SUBCODE08, SUBCODE09, SUBCODE10
                ORDER BY ORDERLINE ASC";

$stmt = db2_exec($conn1, $query);
$no = 1;

if ($stmt) {
    // CSS kecil agar baris ringkasan menyatu dengan baris utama
    echo "<style>
        .table.table-bordered > tbody > tr.has-revisi > td { border-bottom-color: transparent; }
        .table.table-bordered > tbody > tr.revisi-summary > td { border-top-color: transparent; }
        .table.table-bordered > tbody > tr.revisi-summary td:first-child { border-left: none; background:#fafafa; }
        .table > tbody > tr.has-revisi > td { padding-bottom:6px; }
        .table > tbody > tr.revisi-summary > td { padding-top:6px; }
        .btn-outline-purple{background-color:transparent;color:#6f42c1;border:1px solid #6f42c1}
        .btn-outline-purple:hover,.btn-outline-purple:focus{background:#6f42c1;color:#fff}
    </style>";

    echo "<table class='table table-bordered table-striped' id='detailApprovedTable'>";
    echo "<thead>
            <tr>
                <th>No</th>
                <th>Bon Order</th>
                <th>No PO</th>
                <th>Nama Buyer</th>
                <th>Jenis Kain</th>
                <th>AKJ</th>
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

    while ($row = db2_fetch_assoc($stmt)) {
        // Ambil data ITXVIEWBONORDER (untuk penentu subcode04 & data booking/rajut)
        $q_itxviewkk = db2_exec($conn1, "SELECT * FROM ITXVIEWBONORDER i 
                                         WHERE SALESORDERCODE = '{$row['SALESORDERCODE']}' 
                                           AND ORDERLINE = '{$row['ORDERLINE']}'");
        $d_itxviewkk = db2_fetch_assoc($q_itxviewkk);

        $subcode04 = ($d_itxviewkk['ITEMTYPEAFICODE'] === 'KFF')
            ? $d_itxviewkk['RESERVATION_SUBCODE04']
            : $d_itxviewkk['SUBCODE04'];

        // ---------- Rajut ----------
        $skipRajut = (
            $d_itxviewkk['AKJ'] === 'AKJ' ||
            $d_itxviewkk['AKJ'] === 'AKW' ||
            !empty($d_itxviewkk['ADDITIONALDATA']) ||
            !empty($d_itxviewkk['LEGACYORDER'])
        );
        if ($skipRajut) {
            $d_rajut = ['BENANG' => '', 'PO_GREIGE' => ''];
        } else {
            $q_rajut = db2_exec($conn1, "SELECT SUMMARIZEDDESCRIPTION AS BENANG, CODE AS PO_GREIGE
                                         FROM ITXVIEW_RAJUT
                                         WHERE SUBCODE01 = '{$d_itxviewkk['SUBCODE01']}'
                                           AND SUBCODE02 = '{$d_itxviewkk['SUBCODE02']}'
                                           AND SUBCODE03 = '{$d_itxviewkk['SUBCODE03']}'
                                           AND SUBCODE04 = '$subcode04'
                                           AND ORIGDLVSALORDLINESALORDERCODE = '{$row['SALESORDERCODE']}'
                                           AND (ITEMTYPEAFICODE = 'KGF' OR ITEMTYPEAFICODE = 'FKG')");
            $d_rajut = db2_fetch_assoc($q_rajut) ?: ['BENANG' => '', 'PO_GREIGE' => ''];
        }

        // ---------- Ready ----------
        $skipReady = (
            $d_itxviewkk['AKJ'] === 'AKJ' ||
            $d_itxviewkk['AKJ'] === 'AKW' ||
            !empty($d_itxviewkk['ADDITIONALDATA'])
        );
        if ($skipReady) {
            $d_booking_new = ['BENANG' => '', 'PO_GREIGE' => ''];
        } else {
            $q_booking_new = db2_exec($conn1, "SELECT PROJECTCODE AS PO_GREIGE, SUMMARIZEDDESCRIPTION AS BENANG
                                               FROM ITXVIEW_BOOKING_NEW
                                               WHERE SALESORDERCODE = '{$row['SALESORDERCODE']}'
                                                 AND ORDERLINE = '{$row['ORDERLINE']}'");
            $d_booking_new = db2_fetch_assoc($q_booking_new) ?: ['BENANG' => '', 'PO_GREIGE' => ''];
        }

        // ---------- Belum Ready 1..7 ----------
        // Helper kecil untuk query BLM_READY
        $blm = [];
        for ($i = 1; $i <= 7; $i++) {
            $field = ($i === 7) ? 'ADDITIONALDATA6A' : ($i === 1 ? 'ADDITIONALDATA' : 'ADDITIONALDATA'.$i);
            $skip = ($d_itxviewkk['AKJ'] === 'AKJ' || $d_itxviewkk['AKJ'] === 'AKW');
            if ($skip) {
                $blm[$i] = ['BENANG' => '', 'PO_GREIGE' => ''];
            } else {
                $val = $d_itxviewkk[$field] ?? '';
                if ($val === '') {
                    $blm[$i] = ['BENANG' => '', 'PO_GREIGE' => ''];
                } else {
                    $q = db2_exec($conn1, "SELECT ORIGDLVSALORDLINESALORDERCODE AS PO_GREIGE,
                                                   COALESCE(SUMMARIZEDDESCRIPTION, '') || COALESCE(ORIGDLVSALORDLINESALORDERCODE, '') AS BENANG
                                            FROM ITXVIEW_BOOKING_BLM_READY
                                            WHERE SUBCODE01 = '{$d_itxviewkk['SUBCODE01']}'
                                              AND SUBCODE02 = '{$d_itxviewkk['SUBCODE02']}'
                                              AND SUBCODE03 = '{$d_itxviewkk['SUBCODE03']}'
                                              AND SUBCODE04 = '$subcode04'
                                              AND ORIGDLVSALORDLINESALORDERCODE = '{$val}'
                                              AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
                    $blm[$i] = db2_fetch_assoc($q) ?: ['BENANG' => '', 'PO_GREIGE' => ''];
                }
            }
        }

        // Gabungkan BENANG & PO_GREIGE
        $benangList = [
            htmlspecialchars($d_rajut['BENANG'] ?? ''),
            htmlspecialchars($d_booking_new['BENANG'] ?? ''),
            htmlspecialchars($blm[1]['BENANG'] ?? ''),
            htmlspecialchars($blm[2]['BENANG'] ?? ''),
            htmlspecialchars($blm[3]['BENANG'] ?? ''),
            htmlspecialchars($blm[4]['BENANG'] ?? ''),
            htmlspecialchars($blm[5]['BENANG'] ?? ''),
            htmlspecialchars($blm[6]['BENANG'] ?? ''),
            htmlspecialchars($blm[7]['BENANG'] ?? ''),
        ];
        $benang = implode('<br><br>', array_filter($benangList));

        $po_greige_List = [
            htmlspecialchars($d_rajut['PO_GREIGE'] ?? ''),
            htmlspecialchars($d_booking_new['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[1]['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[2]['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[3]['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[4]['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[5]['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[6]['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[7]['PO_GREIGE'] ?? ''),
        ];
        $po_greige = implode('<br><br>', array_filter($po_greige_List));

        // ------------ Ambil "terakhir" dari C-group dan D-group -------------
        $revC_candidates = [
            trim((string)($row['REVISIC4'] ?? '')),
            trim((string)($row['REVISIC3'] ?? '')),
            trim((string)($row['REVISIC2'] ?? '')),
            trim((string)($row['REVISIC1'] ?? '')),
            trim((string)($row['REVISIC']  ?? '')),
        ];
        $revD_candidates = [
            trim((string)($row['REVISID4'] ?? '')),
            trim((string)($row['REVISID3'] ?? '')),
            trim((string)($row['REVISID2'] ?? '')),
            trim((string)($row['REVISID1'] ?? '')),
            trim((string)($row['REVISID']  ?? '')),
        ];

        $lastC = ''; foreach ($revC_candidates as $v) { if ($v !== '') { $lastC = $v; break; } }
        $lastD = ''; foreach ($revD_candidates as $v) { if ($v !== '') { $lastD = $v; break; } }

        $hasRevisi = ($lastC !== '' || $lastD !== '');
        $rowClass  = $hasRevisi ? 'has-revisi' : '';

        $lastC_esc = htmlspecialchars($lastC, ENT_QUOTES, 'UTF-8');
        $lastD_esc = htmlspecialchars($lastD, ENT_QUOTES, 'UTF-8');

        // ---------------- Cetak baris utama ----------------
        echo "<tr class='{$rowClass}'>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['SALESORDERCODE'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['NO_PO'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['LEGALNAME1'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['JENIS_KAIN'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['AKJ'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['ITEMCODE'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['NOTETAS'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars(number_format((float)($row['GRAMASI'] ?? 0), 2)) . "</td>";
            echo "<td>" . htmlspecialchars(number_format((float)($row['LEBAR'] ?? 0), 2)) . "</td>";
            echo "<td>" . htmlspecialchars($row['COLOR_STANDARD'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['WARNA'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['KODE_WARNA'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['COLORREMARKS'] ?? '') . "</td>";
            echo "<td>" . $benang . "</td>";
            echo "<td>" . $po_greige . "</td>";
        echo "</tr>";

        // ---------------- Baris ringkasan revisi (hanya jika ada) ----------------
        if ($hasRevisi) {
            // Data-attributes untuk tombol Detail Revisi (modal)
            $dataAttrs = sprintf(
                'data-revisic="%s" data-revisic1="%s" data-revisic2="%s" data-revisic3="%s" data-revisic4="%s" ' .
                'data-revisid="%s" data-revisid1="%s" data-revisid2="%s" data-revisid3="%s" data-revisid4="%s" ' .
                'data-revisi1date="%s" data-revisi2date="%s" data-revisi3date="%s" data-revisi4date="%s" data-revisi5date="%s"',
                htmlspecialchars((string)($row['REVISIC']   ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISIC1']  ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISIC2']  ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISIC3']  ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISIC4']  ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISID']   ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISID1']  ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISID2']  ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISID3']  ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISID4']  ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISI1DATE'] ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISI2DATE'] ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISI3DATE'] ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISI4DATE'] ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string)($row['REVISI5DATE'] ?? ''), ENT_QUOTES, 'UTF-8')
            );


            echo "<tr class='revisi-summary'>
                    <td></td>
                    <td colspan='15' style=\"background:#fafafa;\">
                      <div style=\"display:flex; align-items:center; gap: 50px; flex-wrap:wrap;\">
                        <div><strong><span>".($lastD_esc === '' ? '-' : $lastD_esc)."</span></strong></div>
                        <div><strong><span>".($lastC_esc === '' ? '-' : $lastC_esc)."</span></strong></div>
                        <button type='button' class='btn btn-outline-purple btn-xs revisi-btn' {$dataAttrs}
                                style='margin-left:auto;'>Detail Revisi</button>
                      </div>
                    </td>
                  </tr>";
        }
    }

    echo "</tbody></table>";
} else {
    echo "<p class='text-danger'>Data tidak ditemukan.</p>";
}
?>
