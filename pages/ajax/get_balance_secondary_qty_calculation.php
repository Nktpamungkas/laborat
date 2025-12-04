<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
require_once __DIR__ . '/../../koneksi.php';

$itemType = isset($_REQUEST['itemtypecode']) ? $_REQUEST['itemtypecode'] : 'KGF';
$s1 = isset($_REQUEST['subcode01']) ? $_REQUEST['subcode01'] : '';
$s2 = isset($_REQUEST['subcode02']) ? $_REQUEST['subcode02'] : '';
$s3 = isset($_REQUEST['subcode03']) ? $_REQUEST['subcode03'] : '';
$s4 = isset($_REQUEST['subcode04']) ? $_REQUEST['subcode04'] : '';

// sanitize for DB2: escape single quotes by doubling them
$itemType = strtoupper(trim(str_replace("'", "''", $itemType)));
$s1 = trim(str_replace("'", "''", $s1));
$s2 = trim(str_replace("'", "''", $s2));
$s3 = trim(str_replace("'", "''", $s3));
$s4 = trim(str_replace("'", "''", $s4));

$default = 1;

if ($s1 === '' && $s2 === '' && $s3 === '' && $s4 === '') {
    echo json_encode(['success' => false, 'message' => 'No subcodes provided', 'factor' => $default]);
    exit;
}

// DB2: use FETCH FIRST 1 ROW ONLY
$sql = "SELECT SECONDARYUNSTEADYCVSFACTOR FROM PRODUCT p WHERE TRIM(ITEMTYPECODE) = '$itemType' ";
$sql .= "AND TRIM(SUBCODE01) = '$s1' AND TRIM(SUBCODE02) = '$s2' AND TRIM(SUBCODE03) = '$s3' AND TRIM(SUBCODE04) = '$s4' FETCH FIRST 1 ROW ONLY";

$res = db2_exec($conn1, $sql);
if ($res) {
    $row = db2_fetch_assoc($res);
    if ($row && isset($row['SECONDARYUNSTEADYCVSFACTOR']) && $row['SECONDARYUNSTEADYCVSFACTOR'] !== null && $row['SECONDARYUNSTEADYCVSFACTOR'] !== '') {
        $factor = floatval($row['SECONDARYUNSTEADYCVSFACTOR']);
        echo json_encode(['success' => true, 'factor' => $factor]);
        exit;
    }
}

// fallback
echo json_encode(['success' => false, 'message' => 'Factor not found', 'factor' => $default]);

?>
