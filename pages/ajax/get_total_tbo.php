<?php
include "../../koneksi.php";

$approvedCodes = [];
$res = mysqli_query($con, "SELECT code FROM approval_bon_order WHERE is_revision = 0");
while ($r = mysqli_fetch_assoc($res)) {
    $approvedCodes[] = "'" . mysqli_real_escape_string($con, $r['code']) . "'";
}
$codeList = implode(",", $approvedCodes);

$sqlTBO = "SELECT DISTINCT isa.CODE AS CODE
           FROM ITXVIEW_SALESORDER_APPROVED isa
           LEFT JOIN SALESORDER s ON s.CODE = isa.CODE
           LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
                AND ip.CODE = s.CODE
           LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID
                AND a.FIELDNAME = 'ApprovalRMPDateTime'
           WHERE isa.APPROVEDRMP IS NOT NULL
             AND CAST(s.CREATIONDATETIME AS DATE) > '2025-06-01'
             AND a.VALUETIMESTAMP IS NOT NULL
             AND ip.LANGGANAN IS NOT NULL";

if (!empty($codeList)) {
    $sqlTBO .= " AND isa.CODE NOT IN ($codeList)";
}

$resultTBO = db2_exec($conn1, $sqlTBO, ['cursor' => DB2_SCROLLABLE]);

$codes = [];
while ($row = db2_fetch_assoc($resultTBO)) {
    $codes[] = $row['CODE'];
}

echo json_encode([
    'count' => count($codes),
    'codes' => $codes
]);
