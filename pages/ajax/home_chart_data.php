<?php
ini_set('error_reporting', E_ALL);
header('Content-Type: application/json; charset=utf-8');

include "../../koneksi.php";

// Param: berapa hari ke belakang (default 12, dibatasi max 120 utk safety)
$days = isset($_GET['days']) ? (int)$_GET['days'] : 12;
$days = max(0, min(120, $days));

// Window waktu [start, end)
// start = jam 00:00:00 N hari lalu, endExclusive = besok 00:00:00
$start = (new DateTime("today -$days day"))->format('Y-m-d 00:00:00');
$endExclusive = (new DateTime('tomorrow'))->format('Y-m-d 00:00:00');

// Siapkan categories (urutan sama seperti kode lama: hari ini, kemarin, ... )
$xCategories = [];
$ymdKeys = [];
for ($i = 0; $i <= $days; $i++) {
  $dObj = new DateTime("today -$i day");
  $xCategories[] = $dObj->format('d M');
  $ymdKeys[] = $dObj->format('Y-m-d');
}

// Helper ambil hasil group-by jadi map: 'YYYY-MM-DD' => count
function grouped_map(mysqli $con, string $sql, array $params): array {
  $stmt = $con->prepare($sql);
  if (!$stmt) {
    return [];
  }
  if (!empty($params)) {
    $types = str_repeat('s', count($params)); // semua as string tanggal
    $stmt->bind_param($types, ...$params);
  }
  $stmt->execute();
  $res = $stmt->get_result();
  $out = [];
  while ($row = $res->fetch_assoc()) {
    $out[$row['d']] = (int)$row['cnt'];
  }
  $stmt->close();
  return $out;
}

// Timeline: Approved (status=selesai & approve=TRUE) by approve_at
$mapSelesai = grouped_map(
  $con,
  "SELECT DATE(approve_at) AS d, COUNT(*) AS cnt
   FROM tbl_status_matching
   WHERE status='selesai' AND approve='TRUE'
     AND approve_at IS NOT NULL
     AND approve_at >= ? AND approve_at < ?
   GROUP BY DATE(approve_at)",
  [$start, $endExclusive]
);

// Timeline: Rejected (status=tutup) by tutup_at
$mapClosed = grouped_map(
  $con,
  "SELECT DATE(tutup_at) AS d, COUNT(*) AS cnt
   FROM tbl_status_matching
   WHERE status='tutup'
     AND tutup_at IS NOT NULL
     AND tutup_at >= ? AND tutup_at < ?
   GROUP BY DATE(tutup_at)",
  [$start, $endExclusive]
);

// Timeline: Arsip (distinct idm pada hari log arsip)
$mapArsip = grouped_map(
  $con,
  "SELECT DATE(b.do_at) AS d, COUNT(DISTINCT a.idm) AS cnt
   FROM tbl_status_matching a
   JOIN log_status_matching b ON a.idm = b.ids
   WHERE a.status='arsip' AND b.status='arsip'
     AND b.do_at IS NOT NULL
     AND b.do_at >= ? AND b.do_at < ?
   GROUP BY DATE(b.do_at)",
  [$start, $endExclusive]
);

// Susun array seri per hari (isi 0 kalau tidak ada)
$seriSelesai = [];
$seriClosed  = [];
$seriArsip   = [];
foreach ($ymdKeys as $ymd) {
  $seriSelesai[] = $mapSelesai[$ymd] ?? 0;
  $seriClosed[]  = $mapClosed[$ymd]  ?? 0;
  $seriArsip[]   = $mapArsip[$ymd]   ?? 0;
}

// PIE (dalam 1 query)
$pieRow = ['row_selesai' => 0, 'row_tutup' => 0, 'row_arsip' => 0];
$qPie = "
  SELECT
    SUM(CASE WHEN status='selesai' AND approve='TRUE' THEN 1 ELSE 0 END) AS row_selesai,
    SUM(CASE WHEN status='tutup' THEN 1 ELSE 0 END) AS row_tutup,
    SUM(CASE WHEN status='arsip' THEN 1 ELSE 0 END) AS row_arsip
  FROM tbl_status_matching
";
if ($res = $con->query($qPie)) {
  $pieRow = $res->fetch_assoc();
  $res->free();
}

echo json_encode([
  'xCategories' => $xCategories,
  'series' => [
    'selesai' => $seriSelesai,
    'closed'  => $seriClosed,
    'arsip'   => $seriArsip,
  ],
  'pie' => [
    'aktif'    => (int)$pieRow['row_selesai'],
    'rejected' => (int)$pieRow['row_tutup'],
    'arsip'    => (int)$pieRow['row_arsip'],
  ],
], JSON_UNESCAPED_UNICODE);
