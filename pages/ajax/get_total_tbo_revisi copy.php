<?php
include "../../koneksi.php";

$approvedCodes = [];
$res = mysqli_query($con, "SELECT code FROM approval_bon_order WHERE is_revision = 1");
while ($r = mysqli_fetch_assoc($res)) {
    $approvedCodes[] = "'" . mysqli_real_escape_string($con, $r['code']) . "'";
}

// Bentuk list code (untuk IN (...))
$codeList = implode(",", $approvedCodes);

// Ambil data siap approve
$notIn = !empty($codeList) ? " AND isa.CODE NOT IN ($codeList)" : "";

$sqlTBO = "WITH base AS (
                SELECT
                    isa.CODE                                AS CODE,
                    ip.LANGGANAN || ip.BUYER                AS CUSTOMER,
                    isa.TGL_APPROVEDRMP                     AS TGL_APPROVE_RMP,
                    /* --- Grup RevisiC/Revisi2/... dari ad*.OPTIONS --- */
                    CASE
                        WHEN aC.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || aC.VALUESTRING || '=')
                        THEN REGEXP_SUBSTR(
                            adC.OPTIONS,
                            '(?:^|;)' || aC.VALUESTRING || '=([^;]*)',
                            1, 1, '', 1
                            )
                    END AS RevisiC,
                    CASE
                        WHEN a2.VALUESTRING IS NOT NULL AND ad2.OPTIONS IS NOT NULL
                            AND REGEXP_LIKE(ad2.OPTIONS, '(?:^|;)' || a2.VALUESTRING || '=')
                        THEN REGEXP_SUBSTR(
                            ad2.OPTIONS,
                            '(?:^|;)' || a2.VALUESTRING || '=([^;]*)',
                            1, 1, '', 1
                            )
                    END AS Revisi2,
                    CASE
                        WHEN a3.VALUESTRING IS NOT NULL AND ad3.OPTIONS IS NOT NULL
                            AND REGEXP_LIKE(ad3.OPTIONS, '(?:^|;)' || a3.VALUESTRING || '=')
                        THEN REGEXP_SUBSTR(
                            ad3.OPTIONS,
                            '(?:^|;)' || a3.VALUESTRING || '=([^;]*)',
                            1, 1, '', 1
                            )
                    END AS Revisi3,
                    CASE
                        WHEN a4.VALUESTRING IS NOT NULL AND ad4.OPTIONS IS NOT NULL
                            AND REGEXP_LIKE(ad4.OPTIONS, '(?:^|;)' || a4.VALUESTRING || '=')
                        THEN REGEXP_SUBSTR(
                            ad4.OPTIONS,
                            '(?:^|;)' || a4.VALUESTRING || '=([^;]*)',
                            1, 1, '', 1
                            )
                    END AS Revisi4,
                    CASE
                        WHEN a5.VALUESTRING IS NOT NULL AND ad5.OPTIONS IS NOT NULL
                            AND REGEXP_LIKE(ad5.OPTIONS, '(?:^|;)' || a5.VALUESTRING || '=')
                        THEN REGEXP_SUBSTR(
                            ad5.OPTIONS,
                            '(?:^|;)' || a5.VALUESTRING || '=([^;]*)',
                            1, 1, '', 1
                            )
                    END AS Revisi5,
                    /* --- Grup RevisiN/DRevisi* langsung VALUESTRING --- */
                    n1.VALUESTRING AS RevisiN,
                    n2.VALUESTRING AS DRevisi2,
                    n3.VALUESTRING AS DRevisi3,
                    n4.VALUESTRING AS DRevisi4,
                    n5.VALUESTRING AS DRevisi5
                FROM ITXVIEW_SALESORDER_APPROVED isa
                LEFT JOIN SALESORDER s
                    ON s.CODE = isa.CODE
                /* Grup C */
                LEFT JOIN ADSTORAGE aC  ON aC.UNIQUEID = s.ABSUNIQUEID AND aC.FIELDNAME = 'RevisiC'
                LEFT JOIN ADADDITIONALDATA adC ON adC.NAME = aC.NAMENAME
                LEFT JOIN ADSTORAGE a2   ON a2.UNIQUEID = s.ABSUNIQUEID AND a2.FIELDNAME = 'Revisi2'
                LEFT JOIN ADADDITIONALDATA ad2 ON ad2.NAME = a2.NAMENAME
                LEFT JOIN ADSTORAGE a3   ON a3.UNIQUEID = s.ABSUNIQUEID AND a3.FIELDNAME = 'Revisi3'
                LEFT JOIN ADADDITIONALDATA ad3 ON ad3.NAME = a3.NAMENAME
                LEFT JOIN ADSTORAGE a4   ON a4.UNIQUEID = s.ABSUNIQUEID AND a4.FIELDNAME = 'Revisi4'
                LEFT JOIN ADADDITIONALDATA ad4 ON ad4.NAME = a4.NAMENAME
                LEFT JOIN ADSTORAGE a5   ON a5.UNIQUEID = s.ABSUNIQUEID AND a5.FIELDNAME = 'Revisi5'
                LEFT JOIN ADADDITIONALDATA ad5 ON ad5.NAME = a5.NAMENAME
                /* Grup N/DRevisi* */
                LEFT JOIN ADSTORAGE n1 ON n1.UNIQUEID = s.ABSUNIQUEID AND n1.FIELDNAME = 'RevisiN'
                LEFT JOIN ADSTORAGE n2 ON n2.UNIQUEID = s.ABSUNIQUEID AND n2.FIELDNAME = 'DRevisi2'
                LEFT JOIN ADSTORAGE n3 ON n3.UNIQUEID = s.ABSUNIQUEID AND n3.FIELDNAME = 'DRevisi3'
                LEFT JOIN ADSTORAGE n4 ON n4.UNIQUEID = s.ABSUNIQUEID AND n4.FIELDNAME = 'DRevisi4'
                LEFT JOIN ADSTORAGE n5 ON n5.UNIQUEID = s.ABSUNIQUEID AND n5.FIELDNAME = 'DRevisi5'
                LEFT JOIN ITXVIEW_PELANGGAN ip
                    ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
                AND ip.CODE = s.CODE
                WHERE
                    isa.APPROVEDRMP IS NOT NULL
                    AND DATE(s.CREATIONDATETIME) > DATE('2025-06-01')
                    $notIn
                ),
                ranked AS (
                SELECT
                    b.*,
                    ROW_NUMBER() OVER (
                    PARTITION BY b.CODE
                    ORDER BY (b.TGL_APPROVE_RMP IS NULL) ASC,
                            b.TGL_APPROVE_RMP DESC
                    ) AS rn
                FROM base b
                )
                SELECT
                CODE,
                CUSTOMER,
                TGL_APPROVE_RMP,
                RevisiC, Revisi2, Revisi3, Revisi4, Revisi5,
                RevisiN, DRevisi2, DRevisi3, DRevisi4, DRevisi5,
                COALESCE(
                    NULLIF(TRIM(DRevisi5), ''),
                    NULLIF(TRIM(DRevisi4), ''),
                    NULLIF(TRIM(DRevisi3), ''),
                    NULLIF(TRIM(DRevisi2), ''),
                    NULLIF(TRIM(RevisiN),  '')
                ) AS RevisiN_last,
                COALESCE(
                    NULLIF(TRIM(Revisi5), ''),
                    NULLIF(TRIM(Revisi4), ''),
                    NULLIF(TRIM(Revisi3), ''),
                    NULLIF(TRIM(Revisi2), ''),
                    NULLIF(TRIM(RevisiC), '')
                ) AS RevisiC_last
                FROM ranked
                WHERE rn = 1
                AND COALESCE(
                        NULLIF(TRIM(RevisiC),  ''),
                        NULLIF(TRIM(Revisi2), ''),
                        NULLIF(TRIM(Revisi3), ''),
                        NULLIF(TRIM(Revisi4), ''),
                        NULLIF(TRIM(Revisi5), '')
                    ) IS NOT NULL
                AND COALESCE(
                        NULLIF(TRIM(RevisiN),   ''),
                        NULLIF(TRIM(DRevisi2), ''),
                        NULLIF(TRIM(DRevisi3), ''),
                        NULLIF(TRIM(DRevisi4), ''),
                        NULLIF(TRIM(DRevisi5), '')
                    ) IS NOT NULL
";

// Eksekusi query DB2
$resultTBO = db2_exec($conn1, $sqlTBO, ['cursor' => DB2_SCROLLABLE]);

if ($resultTBO === false) {
    echo json_encode(["success" => false, "message" => db2_stmt_errormsg()]);
    exit;
}

$totalRows = db2_num_rows($resultTBO);

// echo json_encode([
//     "success" => true,
//     "total" => $totalRows
// ]);

echo json_encode($totalRows);