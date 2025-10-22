<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../koneksi.php";

$userLAB = $_SESSION['userLAB'] ?? '';
$ipUser  = $_SESSION['ip']      ?? '';
session_write_close(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_code'])) {
    // --- sanitasi input ---
    $orderCode    = trim($_POST['order_code'] ?? '');
    $orderCodeEsc = str_replace("'", "''", $orderCode); 

    // ======================
    // QUERY UTAMA (DB2)
    // ======================
    $query = "
    SELECT DISTINCT 
        SALESORDERCODE, ORDERLINE, LEGALNAME1, AKJ, JENIS_KAIN,
        LISTAGG(DISTINCT ITEMCODE, ', ') AS ITEMCODE,
        LISTAGG(DISTINCT NOTETAS, ', ') AS NOTETAS,
        NO_PO, GRAMASI, LEBAR, COLOR_STANDARD, WARNA, KODE_WARNA, COLORREMARKS,
        SUBCODE01, SUBCODE02, SUBCODE03, SUBCODE04,
        LISTAGG(DISTINCT TRIM(SUBCODE04_FIXED), ', ') AS SUBCODE04_FIXED,
        SUBCODE05, SUBCODE06, SUBCODE07, SUBCODE08, SUBCODE09, SUBCODE10,

        /* Ringkas Revisi per ORDERLINE */
        MAX(RevisiC)  AS RevisiC,
        MAX(RevisiC1) AS RevisiC1,
        MAX(RevisiC2) AS RevisiC2,
        MAX(RevisiC3) AS RevisiC3,
        MAX(RevisiC4) AS RevisiC4,
        MAX(Revisid)  AS Revisid,
        MAX(Revisi2)  AS Revisi2,
        MAX(Revisi3)  AS Revisi3,
        MAX(Revisi4)  AS Revisi4,
        MAX(Revisi5)  AS Revisi5,
        MAX(Revisi1Date) AS Revisi1Date,
        MAX(Revisi2Date) AS Revisi2Date,
        MAX(Revisi3Date) AS Revisi3Date,
        MAX(Revisi4Date) AS Revisi4Date,
        MAX(Revisi5Date) AS Revisi5Date
    FROM (
        SELECT
            i.SALESORDERCODE,
            i.ORDERLINE,
            CASE WHEN i.ITEMTYPEAFICODE = 'KFF' THEN i.RESERVATION_SUBCODE04 ELSE i.SUBCODE04 END AS SUBCODE04_FIXED,
            i.LEGALNAME1,
            i.AKJ,
            p.LONGDESCRIPTION AS JENIS_KAIN,
            i.NOTETAS_KGF || '/' || TRIM(i.SUBCODE01) || '-' || TRIM(i.SUBCODE02) || '-' || TRIM(i.SUBCODE03) || '-' || TRIM(i.SUBCODE04) AS ITEMCODE,
            i.NOTETAS,
            i.EXTERNALREFERENCE AS NO_PO,
            CASE 
                WHEN REGEXP_LIKE(i2.GRAMASI_KFF, '^\d+(\.\d+)?$') THEN CAST(i2.GRAMASI_KFF AS DECFLOAT)
                WHEN REGEXP_LIKE(i2.GRAMASI_FKF, '^\d+(\.\d+)?$') THEN CAST(i2.GRAMASI_FKF AS DECFLOAT)
                ELSE NULL
            END AS GRAMASI,
            i3.LEBAR,

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
            TRIM(i.SUBCODE10) AS SUBCODE10,

            /* RevisiC* (label dari OPTIONS) */
            CASE WHEN sc.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || sc.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || sc.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC,
            CASE WHEN sc1.VALUESTRING IS NOT NULL AND adC1.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC1.OPTIONS, '(?:^|;)' || sc1.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC1.OPTIONS,'(?:^|;)' || sc1.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC1,
            CASE WHEN sc2.VALUESTRING IS NOT NULL AND adC2.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC2.OPTIONS, '(?:^|;)' || sc2.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC2.OPTIONS,'(?:^|;)' || sc2.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC2,
            CASE WHEN sc3.VALUESTRING IS NOT NULL AND adC3.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC3.OPTIONS, '(?:^|;)' || sc3.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC3.OPTIONS,'(?:^|;)' || sc3.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC3,
            CASE WHEN sc4.VALUESTRING IS NOT NULL AND adC4.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC4.OPTIONS, '(?:^|;)' || sc4.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC4.OPTIONS,'(?:^|;)' || sc4.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC4,

            /* Revisi detail (D*) langsung VALUESTRING */
            sd.VALUESTRING  AS Revisid,
            sd1.VALUESTRING AS Revisi2,
            sd2.VALUESTRING AS Revisi3,
            sd3.VALUESTRING AS Revisi4,
            sd4.VALUESTRING AS Revisi5,

            sdt1.VALUEDATE AS Revisi1Date,
            sdt2.VALUEDATE AS Revisi2Date,
            sdt3.VALUEDATE AS Revisi3Date,
            sdt4.VALUEDATE AS Revisi4Date,
            sdt5.VALUEDATE AS Revisi5Date

        FROM ITXVIEWBONORDER i
        LEFT JOIN PRODUCT p
               ON p.ITEMTYPECODE = i.ITEMTYPEAFICODE
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
        LEFT JOIN ITXVIEWLEBAR   i3 ON i3.SALESORDERCODE = i.SALESORDERCODE AND i3.ORDERLINE = i.ORDERLINE 

        LEFT JOIN ADSTORAGE a  ON a.UNIQUEID  = i.ABSUNIQUEID_SALESORDERLINE AND a.FIELDNAME  = 'ColorStandard'
        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND a2.FIELDNAME = 'ColorRemarks'

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

        LEFT JOIN ADSTORAGE sd  ON sd.UNIQUEID  = i.ABSUNIQUEID_SALESORDERLINE AND sd.FIELDNAME  = 'Revisid'
        LEFT JOIN ADSTORAGE sd1 ON sd1.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd1.FIELDNAME = 'Revisi2'
        LEFT JOIN ADSTORAGE sd2 ON sd2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd2.FIELDNAME = 'Revisi3'
        LEFT JOIN ADSTORAGE sd3 ON sd3.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd3.FIELDNAME = 'Revisi4'
        LEFT JOIN ADSTORAGE sd4 ON sd4.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd4.FIELDNAME = 'Revisi5'

        LEFT JOIN ADSTORAGE sdt1 ON sdt1.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt1.FIELDNAME = 'Revisi1Date'
        LEFT JOIN ADSTORAGE sdt2 ON sdt2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt2.FIELDNAME = 'Revisi2Date'
        LEFT JOIN ADSTORAGE sdt3 ON sdt3.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt3.FIELDNAME = 'Revisi3Date'
        LEFT JOIN ADSTORAGE sdt4 ON sdt4.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt4.FIELDNAME = 'Revisi4Date'
        LEFT JOIN ADSTORAGE sdt5 ON sdt5.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt5.FIELDNAME = 'Revisi5Date'

        WHERE TRIM(i.SALESORDERCODE) = '$orderCodeEsc'
    )
    GROUP BY
        SALESORDERCODE, ORDERLINE, LEGALNAME1, AKJ, JENIS_KAIN,
        NO_PO, GRAMASI, LEBAR, COLOR_STANDARD, WARNA, KODE_WARNA, COLORREMARKS,
        SUBCODE01, SUBCODE02, SUBCODE03, SUBCODE04, SUBCODE05, SUBCODE06,
        SUBCODE07, SUBCODE08, SUBCODE09, SUBCODE10
    ORDER BY ORDERLINE ASC
    ";

    $stmt = db2_exec($conn1, $query, ['cursor' => DB2_SCROLLABLE]);

    // === RENDER TABLE (header) ===
    $html = '
    <table class="table table-sm table-bordered mb-0">
        <thead class="bg-warning text-white">
            <tr>
                <th>WARNA</th>
                <th>Kode Warna</th>
                <th>Color Remarks</th>
                <th>AKJ</th>
                <th>Kode Item</th>
                <th style="width: 40%;">BENANG</th>
                <th>PO GREIGE</th>
                <th>PIC Check</th>
                <th>Status Bon Order</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>';

    if (!$stmt) {
        // gagal eksekusi query -> tampilkan pesan error & tutup tabel
        $html .= '<tr><td colspan="9" class="text-danger">'
              . htmlspecialchars('DB2 error: ' . db2_stmt_errormsg(), ENT_QUOTES, 'UTF-8')
              . '</td></tr>';
        $html .= '</tbody></table>';
        echo $html;
        exit;
    }

    // ======================
    // LOOP DATA
    // ======================

    $optionPICBase = '<option value="">-- Pilih PIC --</option>';
    $resPIC = mysqli_query($con, "SELECT username FROM tbl_user WHERE pic_bonorder=1 ORDER BY id ASC");
    $picList = [];
    while ($rp = mysqli_fetch_assoc($resPIC)) {
        $u = htmlspecialchars($rp['username'], ENT_QUOTES, 'UTF-8');
        $picList[] = $u;
    }

    while ($row = db2_fetch_assoc($stmt)) {
        // ---- Ambil baris dasar ITXVIEWBONORDER untuk field tambahan ----
        $q_itxviewkk = db2_exec($conn1, "SELECT * FROM ITXVIEWBONORDER i
                                         WHERE TRIM(SALESORDERCODE) = '{$row['SALESORDERCODE']}'
                                           AND ORDERLINE = '{$row['ORDERLINE']}'");
        $d_itxviewkk = db2_fetch_assoc($q_itxviewkk);

        $subcode04 = ($d_itxviewkk['ITEMTYPEAFICODE'] === 'KFF')
            ? $d_itxviewkk['RESERVATION_SUBCODE04'] : $d_itxviewkk['SUBCODE04'];

        // ---- Rajut ----
        $skipRajut = ($d_itxviewkk['AKJ'] === 'AKJ' || $d_itxviewkk['AKJ'] === 'AKW'
                      || !empty($d_itxviewkk['ADDITIONALDATA']) || !empty($d_itxviewkk['LEGACYORDER']));
        if ($skipRajut) {
            $d_rajut = ['BENANG'=>'','PO_GREIGE'=>''];
        } else {
            $q_rajut = db2_exec($conn1, "SELECT SUMMARIZEDDESCRIPTION AS BENANG, CODE AS PO_GREIGE
                                         FROM ITXVIEW_RAJUT
                                         WHERE SUBCODE01='{$d_itxviewkk['SUBCODE01']}'
                                           AND SUBCODE02='{$d_itxviewkk['SUBCODE02']}'
                                           AND SUBCODE03='{$d_itxviewkk['SUBCODE03']}'
                                           AND SUBCODE04='$subcode04'
                                           AND ORIGDLVSALORDLINESALORDERCODE='{$row['SALESORDERCODE']}'
                                           AND (ITEMTYPEAFICODE='KGF' OR ITEMTYPEAFICODE='FKG')");
            $d_rajut = db2_fetch_assoc($q_rajut) ?: ['BENANG'=>'','PO_GREIGE'=>''];
        }

        // ---- Ready ----
        $skipReady = ($d_itxviewkk['AKJ'] === 'AKJ' || $d_itxviewkk['AKJ'] === 'AKW' || !empty($d_itxviewkk['ADDITIONALDATA']));
        if ($skipReady) {
            $d_booking_new = ['BENANG'=>'','PO_GREIGE'=>''];
        } else {
            $q_booking_new = db2_exec($conn1, "SELECT PROJECTCODE AS PO_GREIGE, SUMMARIZEDDESCRIPTION AS BENANG
                                               FROM ITXVIEW_BOOKING_NEW
                                               WHERE SALESORDERCODE='{$row['SALESORDERCODE']}'
                                                 AND ORDERLINE='{$row['ORDERLINE']}'");
            $d_booking_new = db2_fetch_assoc($q_booking_new) ?: ['BENANG'=>'','PO_GREIGE'=>''];
        }

        // ---- Belum Ready 1..7 ----
        $blm = [];
        for ($i=1; $i<=7; $i++) {
            $field = ($i === 7) ? 'ADDITIONALDATA6A' : ($i === 1 ? 'ADDITIONALDATA' : 'ADDITIONALDATA'.$i);
            if ($d_itxviewkk['AKJ'] === 'AKJ' || $d_itxviewkk['AKJ'] === 'AKW' || empty($d_itxviewkk[$field])) {
                $blm[$i] = ['BENANG'=>'','PO_GREIGE'=>''];
            } else {
                $val = $d_itxviewkk[$field];
                $q = db2_exec($conn1, "SELECT ORIGDLVSALORDLINESALORDERCODE AS PO_GREIGE,
                                              COALESCE(SUMMARIZEDDESCRIPTION,'') || COALESCE(ORIGDLVSALORDLINESALORDERCODE,'') AS BENANG
                                        FROM ITXVIEW_BOOKING_BLM_READY
                                        WHERE SUBCODE01='{$d_itxviewkk['SUBCODE01']}'
                                          AND SUBCODE02='{$d_itxviewkk['SUBCODE02']}'
                                          AND SUBCODE03='{$d_itxviewkk['SUBCODE03']}'
                                          AND SUBCODE04='$subcode04'
                                          AND ORIGDLVSALORDLINESALORDERCODE='{$val}'
                                          AND (ITEMTYPEAFICODE='KGF' OR ITEMTYPEAFICODE='FKG')");
                $blm[$i] = db2_fetch_assoc($q) ?: ['BENANG'=>'','PO_GREIGE'=>''];
            }
        }

        // ---- Gabung BENANG/PO (RAW dulu) ----
        $benangList = array_values(array_filter([
            htmlspecialchars($d_rajut['BENANG'] ?? ''),
            htmlspecialchars($d_booking_new['BENANG'] ?? ''),
            htmlspecialchars($blm[1]['BENANG'] ?? ''),
            htmlspecialchars($blm[2]['BENANG'] ?? ''),
            htmlspecialchars($blm[3]['BENANG'] ?? ''),
            htmlspecialchars($blm[4]['BENANG'] ?? ''),
            htmlspecialchars($blm[5]['BENANG'] ?? ''),
            htmlspecialchars($blm[6]['BENANG'] ?? ''),
            htmlspecialchars($blm[7]['BENANG'] ?? '')
        ]));
        $poList = array_values(array_filter([
            htmlspecialchars($d_rajut['PO_GREIGE'] ?? ''),
            htmlspecialchars($d_booking_new['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[1]['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[2]['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[3]['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[4]['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[5]['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[6]['PO_GREIGE'] ?? ''),
            htmlspecialchars($blm[7]['PO_GREIGE'] ?? '')
        ]));
        $max = max(count($benangList), count($poList));

        // ==== Hitung lastC/lastD utk ORDERLINE ====
        $revC_candidates = [
            trim((string)($row['REVISIC4'] ?? '')),
            trim((string)($row['REVISIC3'] ?? '')),
            trim((string)($row['REVISIC2'] ?? '')),
            trim((string)($row['REVISIC1'] ?? '')),
            trim((string)($row['REVISIC']  ?? '')),
        ];
        $revD_candidates = [
            trim((string)($row['REVISI5'] ?? '')),
            trim((string)($row['REVISI4'] ?? '')),
            trim((string)($row['REVISI3'] ?? '')),
            trim((string)($row['REVISI2'] ?? '')),
            trim((string)($row['REVISID']  ?? '')),
        ];
        $lastC = ''; foreach ($revC_candidates as $v) { if ($v !== '') { $lastC = $v; break; } }
        $lastD = ''; foreach ($revD_candidates as $v) { if ($v !== '') { $lastD = $v; break; } }

        // data-* utk modal
        $dataAttrs = sprintf(
            'data-revisic="%s" data-revisi2="%s" data-revisi3="%s" data-revisi4="%s" data-revisi5="%s" ' .
            'data-revisin="%s" data-drevisi2="%s" data-drevisi3="%s" data-drevisi4="%s" data-drevisi5="%s" ' .
            'data-revisi1date="%s" data-revisi2date="%s" data-revisi3date="%s" data-revisi4date="%s" data-revisi5date="%s"',
            htmlspecialchars((string)($row['REVISIC']   ?? $row['RevisiC']   ?? ''), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string)($row['REVISIC1']  ?? $row['RevisiC1']  ?? ''), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string)($row['REVISIC2']  ?? $row['RevisiC2']  ?? ''), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string)($row['REVISIC3']  ?? $row['RevisiC3']  ?? ''), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string)($row['REVISIC4']  ?? $row['RevisiC4']  ?? ''), ENT_QUOTES, 'UTF-8'),

            htmlspecialchars((string)($row['REVISID']   ?? $row['Revisid']   ?? ''), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string)($row['REVISI2']  ?? $row['Revisi2']  ?? ''), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string)($row['REVISI3']  ?? $row['Revisi3']  ?? ''), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string)($row['REVISI4']  ?? $row['Revisi4']  ?? ''), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string)($row['REVISI5']  ?? $row['Revisi5']  ?? ''), ENT_QUOTES, 'UTF-8'),

            htmlspecialchars((string)($row['REVISI1DATE'] ?? $row['Revisi1Date'] ?? ''), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string)($row['REVISI2DATE'] ?? $row['Revisi2Date'] ?? ''), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string)($row['REVISI3DATE'] ?? $row['Revisi3Date'] ?? ''), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string)($row['REVISI4DATE'] ?? $row['Revisi4Date'] ?? ''), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string)($row['REVISI5DATE'] ?? $row['Revisi5Date'] ?? ''), ENT_QUOTES, 'UTF-8')
        );

        // ===== Cetak baris-baris PO; sisipkan revisi di baris pertama (td Kode Item) =====
        for ($i=0; $i<$max; $i++) {
            $benang = $benangList[$i] ?? '';
            $po     = $poList[$i] ?? '';

            $selectedPIC = ''; $selectedStatus = ''; $btnLabel = 'Simpan';
            $queryCheck = "
                SELECT pic_check, status_bonorder 
                  FROM status_matching_bon_order
                 WHERE salesorder = '{$row['SALESORDERCODE']}'
                   AND orderline  = '{$row['ORDERLINE']}'
                   AND warna      = '{$row['WARNA']}'
                   AND po_greige  = '$po'
                 ORDER BY id DESC
                 LIMIT 1
            ";
            $resultCheck = mysqli_query($con, $queryCheck);
            if ($resultCheck && mysqli_num_rows($resultCheck) > 0) {
                $dataCheck = mysqli_fetch_assoc($resultCheck);
                $selectedPIC    = htmlspecialchars($dataCheck['pic_check'] ?? '');
                $selectedStatus = htmlspecialchars($dataCheck['status_bonorder'] ?? '');
                $btnLabel = 'Edit';
            }

            // PIC options
            $queryPIC = "SELECT * FROM tbl_user WHERE pic_bonorder = 1 ORDER BY id ASC";
            $resultPIC = mysqli_query($con, $queryPIC);
            $optionPIC = '<option value="">-- Pilih PIC --</option>';
            while ($rowPIC = mysqli_fetch_assoc($resultPIC)) {
                $picValue = htmlspecialchars($rowPIC['username']);
                $sel = ($picValue === $selectedPIC) ? 'selected' : '';
                $optionPIC .= "<option value=\"$picValue\" $sel>$picValue</option>";
            }

            // Status options
            $statuses = ['OK', 'Matching Ulang'];
            $optionStatus = "<option value=''>--Pilih--</option>";
            foreach ($statuses as $st) {
                $sel = ($st === $selectedStatus) ? 'selected' : '';
                $optionStatus .= "<option value=\"$st\" $sel>$st</option>";
            }

            // === Blok revisi (hanya pada baris pertama & kalau ada revisi) ===
            $revBlock = '';
            if ($i === 0 && ($lastC !== '' || $lastD !== '')) {
                $reviN = htmlspecialchars($lastD, ENT_QUOTES, 'UTF-8');
                $reviC = htmlspecialchars($lastC, ENT_QUOTES, 'UTF-8');
                $revBlock = "
                    <div class='rev-wrap' style='margin-top:6px; background:#ffecec; display:flex; align-items:center; justify-content:space-between; gap:8px;'>
                        <div class='rev-left' style='display:flex; gap:50px; font-weight:700;'>
                            <span>{$reviN}</span>
                            <span>{$reviC}</span>
                        </div>
                        <button type='button' class='btn btn-outline-purple btn-xs revisi-btn' {$dataAttrs}>Detail Revisi</button>
                    </div>
                ";
            }

            $html .= "
                <tr class=\"row-item\">
                    <td hidden class=\"td-salesorder\">{$row['SALESORDERCODE']}</td>
                    <td hidden class=\"td-orderline\">{$row['ORDERLINE']}</td>

                    <td class=\"td-warna\">{$row['WARNA']}</td>
                    <td class=\"td-kode-warna\">{$row['KODE_WARNA']}</td>
                    <td class=\"td-color-remarks\">{$row['COLORREMARKS']}</td>
                    <td class=\"td-akj\">{$row['AKJ']}</td>

                    <td class=\"td-item-code\">{$row['ITEMCODE']}</td>

                    <td class=\"td-benang\">$benang {$revBlock}</td>
                    <td class=\"td-po\">$po</td>

                    <td>
                        <select class=\"form-control form-control-sm pic-check\">$optionPIC</select>
                    </td>
                    <td>
                        <select class=\"form-control form-control-sm status-bonorder\">$optionStatus</select>
                    </td>
                    <td>
                        <button type=\"button\" class=\"btn btn-primary btn-sm btn-simpan-row\">
                            <span class=\"btn-text\">$btnLabel</span>
                            <span class=\"spinner-border spinner-border-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                        </button>
                    </td>
                </tr>
            ";
        }
    }

    $html .= '</tbody></table>';

    echo $html;
}
?>

<script>
$(document).ready(function(){
    const currentUser = "<?= htmlspecialchars($userLAB, ENT_QUOTES, 'UTF-8'); ?>";
    const ip_user     = "<?= htmlspecialchars($ipUser,  ENT_QUOTES, 'UTF-8'); ?>";

    if (window.toastr) {
        toastr.options = {
            positionClass: "toast-top-right",
            closeButton: true,
            progressBar: true,
            timeOut: "3000",
            extendedTimeOut: "1000",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };
    }

    // lock dropdown bila sudah ada prefill
    $('.row-item').each(function() {
        const row = $(this);
        const picSelect = row.find('.pic-check');
        const statusSelect = row.find('.status-bonorder');
        const hasData = (picSelect.val() && statusSelect.val());

        if (hasData) {
            picSelect.prop('disabled', true);
            statusSelect.prop('disabled', true);
            row.find('.btn-simpan-row .btn-text').text('Edit');
        } else {
            picSelect.prop('disabled', false);
            statusSelect.prop('disabled', false);
            row.find('.btn-simpan-row .btn-text').text('Simpan');
        }
    });

    // cegah bubbling
    $(document).on('click', '.row-item, .row-item *', function(e){ e.stopPropagation(); });

    // handler tombol simpan/edit/update
    $(document).off('click', '.btn-simpan-row').on('click', '.btn-simpan-row', function() {
        const btn = $(this);
        const row = btn.closest('.row-item');
        const btnText = btn.find('.btn-text').text().trim();

        const salesorder = row.find('.td-salesorder').text().trim();
        const orderline  = row.find('.td-orderline').text().trim();
        const warna      = row.find('.td-warna').text().trim();
        const benang     = row.find('.td-benang').text().trim();
        const po         = row.find('.td-po').text().trim();

        const picSelect    = row.find('.pic-check');
        const statusSelect = row.find('.status-bonorder');

        // Mode Edit -> buka dropdown -> jadi Update
        if (btnText === 'Edit') {
            // contoh validasi user, silakan sesuaikan
            if (currentUser?.toLowerCase?.() !== 'riyan') { toastr.warning('Hanya user Riyan yang dapat melakukan edit'); return; }
            picSelect.prop('disabled', false);
            statusSelect.prop('disabled', false);
            btn.find('.btn-text').text('Update');
            return;
        }

        // Mode Simpan/Update
        const pic = picSelect.val();
        const status = statusSelect.val();
        if (!pic || !status) { alert('PIC dan Status Bon Order wajib dipilih!'); return; }

        // Lock saat kirim
        picSelect.prop('disabled', true);
        statusSelect.prop('disabled', true);
        btn.prop('disabled', true).addClass('btn-loading');
        btn.find('.btn-text').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
        btn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: 'pages/ajax/simpan_status_matching_bonorder.php',
            type: 'POST',
            data: {
                salesorder,
                orderline,
                warna,
                benang,
                ip: ip_user,
                user: currentUser,
                po_greige: po,
                pic_check: pic,
                status_bonorder: status
            },
            success: function(response) {
                // sukses -> kunci & ubah tombol ke Edit
                btn.removeClass('btn-loading').prop('disabled', false);
                btn.find('.spinner-border').addClass('d-none');
                btn.find('.btn-text').text('Edit');
                picSelect.prop('disabled', true);
                statusSelect.prop('disabled', true);
                if (window.toastr) toastr.success(response || 'Tersimpan');
            },
            error: function(xhr, status, error) {
                // gagal -> buka lagi supaya bisa diperbaiki
                btn.removeClass('btn-loading').prop('disabled', false);
                btn.find('.spinner-border').addClass('d-none');
                btn.find('.btn-text').text('Update');
                picSelect.prop('disabled', false);
                statusSelect.prop('disabled', false);
                if (window.toastr) toastr.error("Terjadi kesalahan: " + error);
            }
        });

    });
});
</script>
