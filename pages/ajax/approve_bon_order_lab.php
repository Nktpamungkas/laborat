<?php
require_once '../../koneksi.php';

function esc($con, $s) {
    return mysqli_real_escape_string($con, (string)$s);
}
function to_sql_date_or_null($con, $s) {
    $s = trim((string)$s);
    if ($s === '') return "NULL";
    // Normalisasi umum, DB2 sering sudah "YYYY-MM-DD"
    $ts = strtotime(str_replace(['.', '/', 'T'], ['-', '-', ' '], $s));
    if ($ts === false) return "NULL";
    return "'" . esc($con, date('Y-m-d', $ts)) . "'";
}

$code            = esc($con, $_POST['code']            ?? '');
$customer        = esc($con, $_POST['customer']        ?? '');
$tgl_approve_rmp = esc($con, $_POST['tgl_approve_rmp'] ?? '');
$pic_lab         = esc($con, $_POST['pic_lab']         ?? '');
$status          = esc($con, $_POST['status']          ?? '');
$is_revision     = (int)($_POST['is_revision']         ?? 0);

// Revisi* (string)
$revisic  = esc($con, $_POST['revisic']  ?? '');
$revisi2  = esc($con, $_POST['revisi2']  ?? '');
$revisi3  = esc($con, $_POST['revisi3']  ?? '');
$revisi4  = esc($con, $_POST['revisi4']  ?? '');
$revisi5  = esc($con, $_POST['revisi5']  ?? '');
$revisin  = esc($con, $_POST['revisin']  ?? '');
$drevisi2 = esc($con, $_POST['drevisi2'] ?? '');
$drevisi3 = esc($con, $_POST['drevisi3'] ?? '');
$drevisi4 = esc($con, $_POST['drevisi4'] ?? '');
$drevisi5 = esc($con, $_POST['drevisi5'] ?? '');

// Revisi*Date (DATE/NULL)
$revisi1date = to_sql_date_or_null($con, $_POST['revisi1date'] ?? '');
$revisi2date = to_sql_date_or_null($con, $_POST['revisi2date'] ?? '');
$revisi3date = to_sql_date_or_null($con, $_POST['revisi3date'] ?? '');
$revisi4date = to_sql_date_or_null($con, $_POST['revisi4date'] ?? '');
$revisi5date = to_sql_date_or_null($con, $_POST['revisi5date'] ?? '');

$sql = "
INSERT INTO approval_bon_order
(
    code, customer, tgl_approve_rmp, pic_lab, status, is_revision,
    revisic, revisi2, revisi3, revisi4, revisi5,
    revisin, drevisi2, drevisi3, drevisi4, drevisi5,
    revisi1date, revisi2date, revisi3date, revisi4date, revisi5date,
    tgl_approve_lab
)
VALUES
(
    '{$code}', '{$customer}', '{$tgl_approve_rmp}', '{$pic_lab}', '{$status}', {$is_revision},
    '{$revisic}', '{$revisi2}', '{$revisi3}', '{$revisi4}', '{$revisi5}',
    '{$revisin}', '{$drevisi2}', '{$drevisi3}', '{$drevisi4}', '{$drevisi5}',
    {$revisi1date}, {$revisi2date}, {$revisi3date}, {$revisi4date}, {$revisi5date},
    NOW()
)
";

if (!mysqli_query($con, $sql)) {
    http_response_code(500);
    echo "Gagal menyimpan data: " . mysqli_error($con);
    exit;
}

echo "Data approval berhasil disimpan.";
