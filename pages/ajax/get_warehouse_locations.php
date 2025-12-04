<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../koneksi.php';

$results = [];

// get search term from select2
$search = '';
if (isset($_GET['search'])) $search = trim($_GET['search']);
if (empty($search) && isset($_GET['term'])) $search = trim($_GET['term']);

// escape single quotes for DB2 literal
$search_esc = str_replace("'", "''", $search);

if ($search_esc !== '') {
  $like = "%" . $search_esc . "%";
  $sql = "SELECT 
      TRIM(zl.location) AS location_code, 
      TRIM(zl.zone) AS location_zone 
    FROM tbl_master_zone_location zl 
    WHERE 
      TRIM(zl.location) LIKE '$like' LIMIT 10";
} else {
  $sql = "SELECT 
      TRIM(zl.location) AS location_code, 
      TRIM(zl.zone) AS location_zone 
    FROM tbl_master_zone_location zl 
    LIMIT 10";
}

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if ($res) {
  while ($row = mysqli_fetch_assoc($res)) {
    $code = $row['location_code'] ?? '';
    $zone = $row['location_zone'] ?? '';
    $text = trim($code . ' (' . $zone . ')');
    $results[] = ['id' => $code, 'text' => $text, 'location_code' => $code, 'location_zone' => $zone];
  }
}

echo json_encode($results);
