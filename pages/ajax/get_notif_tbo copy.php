<?php
// pages/ajax/get_notif_tbo.php
require_once '../../koneksi.php';
require_once '../lib/revisi_compare.php';

header('Content-Type: application/json; charset=utf-8');

/* ===================== UTIL ===================== */
function norm_code($c) { return strtoupper(trim((string)$c)); }
function unique_codes($arr) {
    $u = [];
    foreach ((array)$arr as $c) {
        $c = norm_code($c);
        if ($c !== '') $u[$c] = true;
    }
    return array_keys($u);
}
$limit = isset($_GET['limit']) ? max(0, (int)$_GET['limit']) : 0;

/* ===================== BON ORDER BARU (non-revisi) ===================== */
function get_new_tbo_codes($conn1, $con) {
    // Ambil code yang SUDAH pernah di-approve (is_revision = 0) dari MySQL
    $approvedCodes = [];
    $res = mysqli_query($con, "SELECT code FROM approval_bon_order WHERE is_revision = 0");
    while ($r = mysqli_fetch_assoc($res)) {
        $approvedCodes[] = "'" . mysqli_real_escape_string($con, $r['code']) . "'";
    }
    $codeList = implode(",", $approvedCodes);

    // Kandidat siap approve dari DB2
    $sql = "
        SELECT *
        FROM (
            SELECT DISTINCT
                isa.CODE AS CODE,
                ip.LANGGANAN || ip.BUYER AS CUSTOMER,
                isa.TGL_APPROVEDRMP AS TGL_APPROVE_RMP,
                a.VALUETIMESTAMP AS ApprovalRMPDateTime
            FROM ITXVIEW_SALESORDER_APPROVED isa
            LEFT JOIN SALESORDER s
                   ON s.CODE = isa.CODE
            LEFT JOIN ITXVIEW_PELANGGAN ip
                   ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
                  AND ip.CODE = s.CODE
            LEFT JOIN ADSTORAGE a
                   ON a.UNIQUEID = s.ABSUNIQUEID
                  AND a.FIELDNAME = 'ApprovalRMPDateTime'
            WHERE isa.APPROVEDRMP IS NOT NULL
              AND DATE(s.CREATIONDATETIME) > DATE('2025-06-01')
        ) i
        WHERE i.ApprovalRMPDateTime IS NOT NULL
          AND i.CUSTOMER IS NOT NULL
    ";
    if (!empty($codeList)) {
        $sql .= " AND i.CODE NOT IN ($codeList)";
    }

    $stmt = db2_exec($conn1, $sql, ['cursor' => DB2_SCROLLABLE]);
    if ($stmt === false) return [];

    $codes = [];
    while ($row = db2_fetch_assoc($stmt)) {
        if (!empty($row['CODE'])) $codes[] = $row['CODE'];
    }
    return unique_codes($codes);
}

/* ===================== REVISI BON ORDER ===================== */
/* (bagian ini identik dengan file Anda sebelumnya, hanya dipaketkan jadi fungsi) */

/* --- ambil detail line untuk cek perubahan --- */
function get_db2_lines($conn1, $codeUpper) {
    $sql = "
    SELECT
        ORDERLINE,
        MAX(RevisiC)     AS RevisiC,
        MAX(RevisiC1)    AS RevisiC1,
        MAX(RevisiC2)    AS RevisiC2,
        MAX(RevisiC3)    AS RevisiC3,
        MAX(RevisiC4)    AS RevisiC4,
        MAX(Revisid)     AS Revisid,
        MAX(Revisi2)     AS Revisi2,
        MAX(Revisi3)     AS Revisi3,
        MAX(Revisi4)     AS Revisi4,
        MAX(Revisi5)     AS Revisi5,
        MAX(Revisi1Date) AS Revisi1Date,
        MAX(Revisi2Date) AS Revisi2Date,
        MAX(Revisi3Date) AS Revisi3Date,
        MAX(Revisi4Date) AS Revisi4Date,
        MAX(Revisi5Date) AS Revisi5Date
    FROM (
        SELECT
            i.SALESORDERCODE,
            i.ORDERLINE,

            CASE WHEN sc.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || sc.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || sc.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC,
            CASE WHEN sc1.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || sc1.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || sc1.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC1,
            CASE WHEN sc2.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || sc2.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || sc2.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC2,
            CASE WHEN sc3.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || sc3.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || sc3.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC3,
            CASE WHEN sc4.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || sc4.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || sc4.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC4,

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
        LEFT JOIN ADSTORAGE sd1  ON sd1.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd1.FIELDNAME = 'Revisi2'
        LEFT JOIN ADSTORAGE sd2  ON sd2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd2.FIELDNAME = 'Revisi3'
        LEFT JOIN ADSTORAGE sd3  ON sd3.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd3.FIELDNAME = 'Revisi4'
        LEFT JOIN ADSTORAGE sd4  ON sd4.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd4.FIELDNAME = 'Revisi5'

        LEFT JOIN ADSTORAGE sdt1 ON sdt1.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt1.FIELDNAME = 'Revisi1Date'
        LEFT JOIN ADSTORAGE sdt2 ON sdt2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt2.FIELDNAME = 'Revisi2Date'
        LEFT JOIN ADSTORAGE sdt3 ON sdt3.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt3.FIELDNAME = 'Revisi3Date'
        LEFT JOIN ADSTORAGE sdt4 ON sdt4.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt4.FIELDNAME = 'Revisi4Date'
        LEFT JOIN ADSTORAGE sdt5 ON sdt5.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt5.FIELDNAME = 'Revisi5Date'

        WHERE i.SALESORDERCODE = ?
    ) X
    GROUP BY ORDERLINE
    HAVING COALESCE(
        NULLIF(TRIM(MAX(RevisiC)) , ''), NULLIF(TRIM(MAX(RevisiC1)), ''),
        NULLIF(TRIM(MAX(RevisiC2)), ''), NULLIF(TRIM(MAX(RevisiC3)), ''),
        NULLIF(TRIM(MAX(RevisiC4)), ''), NULLIF(TRIM(MAX(Revisid)) , ''),
        NULLIF(TRIM(MAX(Revisi2)), ''), NULLIF(TRIM(MAX(Revisi3)), ''),
        NULLIF(TRIM(MAX(Revisi4)), ''), NULLIF(TRIM(MAX(Revisi5)), '')
    ) IS NOT NULL
    ORDER BY ORDERLINE
    ";

    $stmt = db2_prepare($conn1, $sql);
    $codeVar = $codeUpper;
    db2_bind_param($stmt, 1, "codeVar", DB2_PARAM_IN);
    $ok = db2_execute($stmt);

    $lines = [];
    if ($ok) {
        while ($r = db2_fetch_assoc($stmt)) {
            $lines[] = [
                'orderline'   => (string)($r['ORDERLINE'] ?? ''),
                'revisic'     => (string)($r['REVISIC']  ?? ''),
                'revisic1'    => (string)($r['REVISIC1'] ?? ''),
                'revisic2'    => (string)($r['REVISIC2'] ?? ''),
                'revisic3'    => (string)($r['REVISIC3'] ?? ''),
                'revisic4'    => (string)($r['REVISIC4'] ?? ''),
                'revisid'     => (string)($r['REVISID']  ?? ''),
                'revisi2'     => (string)($r['REVISI2'] ?? ''),
                'revisi3'     => (string)($r['REVISI3'] ?? ''),
                'revisi4'     => (string)($r['REVISI4'] ?? ''),
                'revisi5'     => (string)($r['REVISI5'] ?? ''),
                'revisi1date' => (string)($r['REVISI1DATE'] ?? ''),
                'revisi2date' => (string)($r['REVISI2DATE'] ?? ''),
                'revisi3date' => (string)($r['REVISI3DATE'] ?? ''),
                'revisi4date' => (string)($r['REVISI4DATE'] ?? ''),
                'revisi5date' => (string)($r['REVISI5DATE'] ?? ''),
            ];
        }
    }
    return $lines;
}

function has_line_diff($conn1, $con, $codeUpper) {
    $db2Lines = get_db2_lines($conn1, $codeUpper);
    $codeEsc  = mysqli_real_escape_string($con, $codeUpper);
    $res = mysqli_query($con, "
        SELECT lr.*
        FROM line_revision lr
        JOIN approval_bon_order a ON a.id = lr.approval_id
        JOIN (
          SELECT code, MAX(id) AS max_id
          FROM approval_bon_order
          WHERE is_revision = 1
          GROUP BY code
        ) m ON m.max_id = a.id
        WHERE a.is_revision = 1 AND UPPER(lr.code) = '{$codeEsc}'
        ORDER BY lr.orderline
    ");
    $mysqlLines = [];
    if ($res) while ($r = mysqli_fetch_assoc($res)) $mysqlLines[] = $r;
    if (empty($mysqlLines)) return false;
    return linesDiffer($db2Lines, $mysqlLines);
}

function has_header_revisi($row) {
    $keys = ['RevisiC','Revisi2','Revisi3','Revisi4','Revisi5','RevisiN','DRevisi2','DRevisi3','DRevisi4','DRevisi5'];
    foreach ($keys as $k) {
        if (isset($row[$k]) && trim((string)$row[$k]) !== '') return true;
    }
    return false;
}

function get_revisi_codes($conn1, $con) {
    // Snapshot header MySQL terakhir per code (is_revision = 1)
    $sqlSnap = "
        SELECT a.*
        FROM approval_bon_order a
        JOIN (
          SELECT code, MAX(id) AS max_id
          FROM approval_bon_order
          WHERE is_revision = 1
          GROUP BY code
        ) m ON m.max_id = a.id
        WHERE a.is_revision = 1
    ";
    $resSnap = mysqli_query($con, $sqlSnap);
    $lastMySQLByCode = [];
    if ($resSnap) while ($r = mysqli_fetch_assoc($resSnap)) {
        $lastMySQLByCode[strtoupper(trim($r['code']))] = $r;
    }

    // Kandidat siap approval dari DB2 (header)
    $sqlTBO = "
    WITH base AS (
        SELECT
            isa.CODE                                AS CODE,
            ip.LANGGANAN || ip.BUYER                AS CUSTOMER,
            isa.TGL_APPROVEDRMP                     AS TGL_APPROVE_RMP,

            CASE WHEN aC.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || aC.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || aC.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC,
            CASE WHEN a2.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a2.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a2.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi2,
            CASE WHEN a3.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a3.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a3.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi3,
            CASE WHEN a4.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a4.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a4.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi4,
            CASE WHEN a5.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                 AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a5.VALUESTRING || '=')
                 THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a5.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi5,

            n1.VALUESTRING AS RevisiN,
            n2.VALUESTRING AS DRevisi2,
            n3.VALUESTRING AS DRevisi3,
            n4.VALUESTRING AS DRevisi4,
            n5.VALUESTRING AS DRevisi5,

            dt1.VALUEDATE AS Revisi1Date,
            dt2.VALUEDATE AS Revisi2Date,
            dt3.VALUEDATE AS Revisi3Date,
            dt4.VALUEDATE AS Revisi4Date,
            dt5.VALUEDATE AS Revisi5Date

        FROM ITXVIEW_SALESORDER_APPROVED isa
        LEFT JOIN SALESORDER s ON s.CODE = isa.CODE
        LEFT JOIN ADSTORAGE aC  ON aC.UNIQUEID = s.ABSUNIQUEID AND aC.FIELDNAME = 'RevisiC'
        LEFT JOIN ADADDITIONALDATA adC ON adC.NAME = aC.FIELDNAME
        LEFT JOIN ADSTORAGE a2  ON a2.UNIQUEID = s.ABSUNIQUEID AND a2.FIELDNAME = 'Revisi2'
        LEFT JOIN ADADDITIONALDATA ad2 ON ad2.NAME = a2.FIELDNAME
        LEFT JOIN ADSTORAGE a3  ON a3.UNIQUEID = s.ABSUNIQUEID AND a3.FIELDNAME = 'Revisi3'
        LEFT JOIN ADADDITIONALDATA ad3 ON ad3.NAME = a3.FIELDNAME
        LEFT JOIN ADSTORAGE a4  ON a4.UNIQUEID = s.ABSUNIQUEID AND a4.FIELDNAME = 'Revisi4'
        LEFT JOIN ADADDITIONALDATA ad4 ON ad4.NAME = a4.FIELDNAME
        LEFT JOIN ADSTORAGE a5  ON a5.UNIQUEID = s.ABSUNIQUEID AND a5.FIELDNAME = 'Revisi5'
        LEFT JOIN ADADDITIONALDATA ad5 ON ad5.NAME = a5.FIELDNAME
        LEFT JOIN ADSTORAGE n1 ON n1.UNIQUEID = s.ABSUNIQUEID AND n1.FIELDNAME = 'RevisiN'
        LEFT JOIN ADSTORAGE n2 ON n2.UNIQUEID = s.ABSUNIQUEID AND n2.FIELDNAME = 'DRevisi2'
        LEFT JOIN ADSTORAGE n3 ON n3.UNIQUEID = s.ABSUNIQUEID AND n3.FIELDNAME = 'DRevisi3'
        LEFT JOIN ADSTORAGE n4 ON n4.UNIQUEID = s.ABSUNIQUEID AND n4.FIELDNAME = 'DRevisi4'
        LEFT JOIN ADSTORAGE n5 ON n5.UNIQUEID = s.ABSUNIQUEID AND n5.FIELDNAME = 'DRevisi5'
        LEFT JOIN ADSTORAGE dt1 ON dt1.UNIQUEID = s.ABSUNIQUEID AND dt1.FIELDNAME = 'Revisi1Date'
        LEFT JOIN ADSTORAGE dt2 ON dt2.UNIQUEID = s.ABSUNIQUEID AND dt2.FIELDNAME = 'Revisi2Date'
        LEFT JOIN ADSTORAGE dt3 ON dt3.UNIQUEID = s.ABSUNIQUEID AND dt3.FIELDNAME = 'Revisi3Date'
        LEFT JOIN ADSTORAGE dt4 ON dt4.UNIQUEID = s.ABSUNIQUEID AND dt4.FIELDNAME = 'Revisi4Date'
        LEFT JOIN ADSTORAGE dt5 ON dt5.UNIQUEID = s.ABSUNIQUEID AND dt5.FIELDNAME = 'Revisi5Date'
        LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s.CODE
        WHERE isa.APPROVEDRMP IS NOT NULL
          AND DATE(s.CREATIONDATETIME) > DATE('2025-06-01')
    ),
    ranked AS (
        SELECT b.*, ROW_NUMBER() OVER
          (PARTITION BY b.CODE ORDER BY (b.TGL_APPROVE_RMP IS NULL) ASC, b.TGL_APPROVE_RMP DESC) AS rn
        FROM base b
    )
    SELECT
        CODE, CUSTOMER, TGL_APPROVE_RMP,
        RevisiC, Revisi2, Revisi3, Revisi4, Revisi5,
        RevisiN, DRevisi2, DRevisi3, DRevisi4, DRevisi5,
        Revisi1Date, Revisi2Date, Revisi3Date, Revisi4Date, Revisi5Date
    FROM ranked
    WHERE rn = 1
    ";

    $resultTBO = db2_exec($conn1, $sqlTBO, ['cursor' => DB2_SCROLLABLE]);
    if ($resultTBO === false) return [];

     $pending = [];
        while ($row = db2_fetch_assoc($resultTBO)) {
            $codeUpper = norm_code($row['CODE'] ?? '');
            if ($codeUpper === '') continue;

            $snap         = $lastMySQLByCode[$codeUpper] ?? null;
            $hasHeaderRev = has_header_revisi($row);
            $need = false;

            if ($snap === null) {
                if ($hasHeaderRev) {
                    $need = true;
                } else {
                    $hasLineRev = !empty(get_db2_lines($conn1, $codeUpper));
                    $need = $hasLineRev;
                }
            } else {
                $headerDiff = $hasHeaderRev ? revisionsDiffer($row, $snap) : false;
                $lineDiff   = has_line_diff($conn1, $con, $codeUpper);
                $need = $headerDiff || $lineDiff;
            }

            if ($need) $pending[$codeUpper] = true;
        }

        return array_keys($pending);
}

/* ===================== EKSEKUSI ===================== */
$newCodes    = get_new_tbo_codes($conn1, $con);
$revisiCodes = get_revisi_codes($conn1, $con);

if ($limit > 0) {
    $newListed    = array_slice($newCodes, 0, $limit);
    $revisiListed = array_slice($revisiCodes, 0, $limit);
} else {
    $newListed    = $newCodes;
    $revisiListed = $revisiCodes;
}

$response = [
    'new'    => ['count' => count($newCodes),    'codes' => $newListed],
    'revisi' => ['count' => count($revisiCodes), 'codes' => $revisiListed],
    'total'  => count($newCodes) + count($revisiCodes),
];

echo json_encode($response);
