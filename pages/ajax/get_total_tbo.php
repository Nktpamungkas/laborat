<?php   
include "../../koneksi.php";

$approvedCodes = [];
$res = mysqli_query($con, "SELECT code FROM approval_bon_order");
while ($r = mysqli_fetch_assoc($res)) {
    $approvedCodes[] = "'" . mysqli_real_escape_string($con, $r['code']) . "'";
}

// Bentuk list code (untuk IN (...))
$codeList = implode(",", $approvedCodes);

// Ambil data siap approve
$sqlTBO = "WITH APPROVED_RMP AS (
    SELECT DISTINCT 
        isa.CODE,
        isa.APPROVERMP,
        isa.APPROVEDRMP,
        isa.TGL_APPROVEDRMP
    FROM ITXVIEW_SALESORDER_APPROVED isa
)
SELECT 
    i.SALESORDERCODE AS code,
    i.LEGALNAME1 AS customer,
    MAX(AR.TGL_APPROVEDRMP) AS tgl_approve_rmp
FROM 
    ITXVIEWBONORDER i 
LEFT JOIN APPROVED_RMP AR ON AR.CODE = i.SALESORDERCODE 
WHERE 
    AR.APPROVERMP IS NOT NULL 
    AND AR.APPROVEDRMP IS NOT NULL
    AND CAST(i.CREATIONDATETIME_SALESORDER AS DATE) > '2025-06-01'
";

if (!empty($codeList)) {
    $sqlTBO .= " AND i.SALESORDERCODE NOT IN ($codeList)";
}

$sqlTBO .= " GROUP BY i.SALESORDERCODE, i.LEGALNAME1";

$resultTBO = db2_exec($conn1, $sqlTBO, ['cursor' => DB2_SCROLLABLE]);

echo db2_num_rows($resultTBO);
