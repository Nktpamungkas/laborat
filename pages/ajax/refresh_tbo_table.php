<?php
include "../../koneksi.php";

$approvedCodes = [];
$res = mysqli_query($con, "SELECT code FROM approval_bon_order WHERE is_revision = 0");
while ($r = mysqli_fetch_assoc($res)) {
    $approvedCodes[] = "'" . mysqli_real_escape_string($con, $r['code']) . "'";
}
$codeList = implode(",", $approvedCodes);

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

$seen = [];
while ($row = db2_fetch_assoc($resultTBO)) {
    $code = strtoupper(trim($row['CODE']));
    if (in_array($code, $seen)) continue;
    $seen[] = $code;
    $customer = trim($row['CUSTOMER']);
    $tgl = trim($row['TGL_APPROVE_RMP']);

    echo "<tr>
        <td>$customer</td>
        <td>
            <a href='#' class='btn btn-primary btn-sm open-detail' data-code='$code' data-toggle='modal' data-target='#detailModal'>
                $code
            </a>
        </td>
        <td>$tgl</td>
        <td>
            <div class='d-flex align-items-center gap-2'>
                <select class='form-control form-control-sm pic-select' data-code='$code'>
                    <option value=''>-- Pilih PIC --</option>";

    $queryPIC = "SELECT * FROM tbl_user WHERE pic_bonorder = 1 ORDER BY id ASC";
    $resultPIC = mysqli_query($con, $queryPIC);
    while ($rowPIC = mysqli_fetch_assoc($resultPIC)) {
        $username = htmlspecialchars($rowPIC['username']);
        echo "<option value='$username'>$username</option>";
    }

    echo "      </select>
                <button class='btn btn-success btn-sm approve-btn' data-code='$code'>Approve</button>
                <button class='btn btn-danger btn-sm reject-btn' data-code='$code'>Reject</button>
            </div>
        </td>
    </tr>";
}
?>
