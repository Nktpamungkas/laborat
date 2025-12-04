<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../koneksi.php';

$results = [];

// Require all 4 decosub codes
$decosubcode01 = isset($_GET['decosubcode01']) ? trim($_GET['decosubcode01']) : '';
$decosubcode02 = isset($_GET['decosubcode02']) ? trim($_GET['decosubcode02']) : '';
$decosubcode03 = isset($_GET['decosubcode03']) ? trim($_GET['decosubcode03']) : '';
$decosubcode04 = isset($_GET['decosubcode04']) ? trim($_GET['decosubcode04']) : '';

// If any decosub code is missing, return empty array
if (empty($decosubcode01) || empty($decosubcode02) || empty($decosubcode03) || empty($decosubcode04)) {
    echo json_encode($results);
    exit;
}

// Accept search parameter (from select2) via GET 'search' or 'term'
$search = '';
if (isset($_GET['search'])) $search = trim($_GET['search']);
if (empty($search) && isset($_GET['term'])) $search = trim($_GET['term']);

// basic sanitization for DB2 single-quoted literals
$decosubcode01_esc = str_replace("'", "''", $decosubcode01);
$decosubcode02_esc = str_replace("'", "''", $decosubcode02);
$decosubcode03_esc = str_replace("'", "''", $decosubcode03);
$decosubcode04_esc = str_replace("'", "''", $decosubcode04);
$search_esc = str_replace("'", "''", $search);

// Build SQL: filter by all 4 decosub codes; optionally search by CODE or DESCRIPTION; limit to 10 rows
if ($search_esc !== '') {
    $like = "%" . $search_esc . "%";
    $sql = "SELECT 
        TRIM(LONGDESCRIPTION) AS LONGDESCRIPTION,
        TRIM(DECOSUBCODE01) AS DECOSUBCODE01,
        TRIM(DECOSUBCODE02) AS DECOSUBCODE02,
        TRIM(DECOSUBCODE03) AS DECOSUBCODE03,
        TRIM(DECOSUBCODE04) AS DECOSUBCODE04
      FROM LOT
      WHERE TRIM(ITEMTYPECODE) = 'KGF' 
        AND TRIM(DECOSUBCODE01) = '$decosubcode01_esc'
        AND TRIM(DECOSUBCODE02) = '$decosubcode02_esc'
        AND TRIM(DECOSUBCODE03) = '$decosubcode03_esc'
        AND TRIM(DECOSUBCODE04) = '$decosubcode04_esc'
        AND TRIM(LONGDESCRIPTION) LIKE '$like'
      FETCH FIRST 10 ROWS ONLY";
} else {
    $sql = "SELECT 
        TRIM(CODE) AS CODE, 
        TRIM(DECOSUBCODE01) AS DECOSUBCODE01, 
        TRIM(DECOSUBCODE02) AS DECOSUBCODE02, 
        TRIM(DECOSUBCODE03) AS DECOSUBCODE03, 
        TRIM(DECOSUBCODE04) AS DECOSUBCODE04 
        FROM LOT 
        WHERE TRIM(ITEMTYPECODE) = 'KGF' 
            AND TRIM(DECOSUBCODE01) = '$decosubcode01_esc' 
            AND TRIM(DECOSUBCODE02) = '$decosubcode02_esc' 
            AND TRIM(DECOSUBCODE03) = '$decosubcode03_esc' 
            AND TRIM(DECOSUBCODE04) = '$decosubcode04_esc' 
        FETCH FIRST 10 ROWS ONLY";
}

$res = db2_exec($conn1, $sql);
if ($res) {
    while ($row = db2_fetch_assoc($res)) {
        $code = $row['CODE'] ?? '';
        // Build label showing code and description; use code as id
        $label = trim($code);
        $id = htmlspecialchars($code, ENT_QUOTES);
        $results[] = [ 'id' => $id, 'text' => $label, 'code' => $code ];
    }
} else {
    // Log error for debugging
    $error = db2_conn_error($conn1) . ' - ' . db2_conn_errormsg($conn1);
    error_log("DB2 Error in get_lot_codes.php: " . $error);
}

echo json_encode($results);

?>
