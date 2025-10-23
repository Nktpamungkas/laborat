<?php
// pages/ajax/get_notif_tbo.php
require_once '../../koneksi.php';

// query untk ambil code yang baru
$newCodes = 0;
$revdCodes = 0;
$revCodes = 0;
$countRev = 0;
$newListed = [];
$revisi1 = [];
$revisi2 = [];
$head = [];
$line = [];
$gabDb2 = [];
$gabRevisi = [];
$gabRevisiD = [];
$q_code_baru = "SELECT
	                t.code as total_new
                from
                    tbl_header_bonorder t
                left join approval_bon_order a on
                    a.code = t.CODE
                where
                    buyer is not null
                    and DATE_RMP is not null
                    and APPROVED_RMP_DATETIME is not null
                    and a.code is null";

$stmt_baru = mysqli_query($con, $q_code_baru);
while ($data_baru =mysqli_fetch_array($stmt_baru)){
    $newListed[] = $data_baru['total_new'];
    $newCodes += 1;
}

// Query untuk revisi.
$query_revisi1 = "SELECT a.*
    FROM approval_bon_order a
    JOIN (
    SELECT code, MAX(id) AS max_id
    FROM approval_bon_order
    WHERE is_revision = 1
    GROUP BY code
    ) m ON m.max_id = a.id
    WHERE a.is_revision = 1
";
$stmt_revisi1 = mysqli_query($con, $query_revisi1);
$revisi1 = [];
while ($data_revisi1 = mysqli_fetch_assoc($stmt_revisi1)) {
    $revisi1[] = strtoupper(trim($data_revisi1['code']));
}
// QUERY REVISI 2 (line_revision)
$query_revisi2 = "SELECT DISTINCT code
    FROM line_revision
";
$stmt_revisi2 = mysqli_query($con, $query_revisi2);
$revisi2 = [];
while ($data_revisi2 = mysqli_fetch_assoc($stmt_revisi2)) {
    $revisi2[] = strtoupper(trim($data_revisi2['code']));
}


$gabRevisi = array_values(array_unique(array_merge($revisi1, $revisi2)));

// QUERY HEADER DB2
$query_head = "SELECT DISTINCT CODE
    FROM tbl_header_bonorder t
    WHERE COALESCE(
        t.REV_DEPT1, t.REV_DEPT2, t.REV_DEPT3, t.REV_DEPT4, t.REV_DEPT5,
        t.REV_COMN1, t.REV_COMN2, t.REV_COMN3, t.REV_COMN4, t.REV_COMN5,
        t.REV_DATE1, t.REV_DATE2, t.REV_DATE3, t.REV_DATE4, t.REV_DATE5
    ) IS NOT NULL
    AND t.BUYER IS NOT NULL
    AND t.IS_ACTIIVE = 1
    AND t.APPROVED_RMP_DATETIME IS NOT NULL
";
$stmt_revisi_head = mysqli_query($con, $query_head);
$head = [];
while ($data_head = mysqli_fetch_assoc($stmt_revisi_head)) {
    $head[] = strtoupper(trim($data_head['CODE']));
}

// QUERY LINE DB2
$query_line = "SELECT DISTINCT t.CODE
    FROM tbl_line_bonorder t
    LEFT JOIN tbl_header_bonorder h ON h.CODE = t.CODE
    WHERE COALESCE(
        t.REV_DEPT1, t.REV_DEPT2, t.REV_DEPT3, t.REV_DEPT4, t.REV_DEPT5,
        t.REV_COMN1, t.REV_COMN2, t.REV_COMN3, t.REV_COMN4, t.REV_COMN5,
        t.REV_DATE1, t.REV_DATE2, t.REV_DATE3, t.REV_DATE4, t.REV_DATE5
    ) IS NOT NULL
    AND t.BUYER IS NOT NULL
    AND t.IS_ACTIVE = 1
    AND h.APPROVED_RMP_DATETIME IS NOT NULL
";
$stmt_revisi_line = mysqli_query($con, $query_line);
$line = [];
while ($data_line = mysqli_fetch_assoc($stmt_revisi_line)) {
    $line[] = strtoupper(trim($data_line['CODE']));
}


$gabRevisiD = array_values(array_unique(array_merge($head, $line)));

$gabRevisi = array_filter($gabRevisi);
$gabRevisiD = array_filter($gabRevisiD);
$arrayBaru = array_values(array_diff($gabRevisiD, $gabRevisi));

// Query yang data rejected 
$query_approved = "SELECT DISTINCT 
                            code
                        from
                            approval_bon_order
                        where
                            is_revision = 0
                        and status = 'Rejected'
";
$stmt_rejected = mysqli_query($con, $query_approved);
$rejected = [];
while ($data_reject = mysqli_fetch_assoc($stmt_rejected)) {
    $rejected[] = strtoupper(trim($data_reject['code']));
}

$revisiListed = array_values(array_diff($arrayBaru, $rejected));
foreach($revisiListed as $data_revisi){
    $countRev += 1;
}

$response = [
    'new'    => ['count' => $newCodes,    'codes' => $newListed],
    'revisi' => ['count' => $countRev, 'codes' => $revisiListed],
    'total'  => $newCodes + $countRev,
];

echo json_encode($response);
