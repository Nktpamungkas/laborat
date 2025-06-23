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
$sqlTBO = "SELECT DISTINCT 
            isa.CODE AS CODE,
            ip.LANGGANAN || ip.BUYER AS CUSTOMER,
            isa.TGL_APPROVEDRMP AS TGL_APPROVE_RMP
        FROM
            ITXVIEW_SALESORDER_APPROVED isa 
        LEFT JOIN SALESORDER s ON s.CODE = isa.CODE 
        LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s.CODE 
        WHERE 
            isa.APPROVEDRMP IS NOT NULL
            AND CAST(s.CREATIONDATETIME AS DATE) > '2025-06-01'
";

if (!empty($codeList)) {
    $sqlTBO .= " AND isa.CODE NOT IN ($codeList)";
}

$resultTBO = db2_exec($conn1, $sqlTBO, ['cursor' => DB2_SCROLLABLE]);

echo db2_num_rows($resultTBO);
