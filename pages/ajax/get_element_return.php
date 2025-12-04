<?php
header('Content-Type: application/json');
require_once '../../koneksi.php'; // sesuaikan

$response = [
    'success' => false,
    'data' => null,
    'message' => 'Unknown error'
];

// --- Validate input ---
if (!isset($_POST['element_id']) || empty($_POST['element_id'])) {
    echo json_encode(['success' => false, 'data' => null, 'message' => 'element_id required']);
    exit;
}

$element_id = $_POST['element_id'];

// --- Ambil data element untuk direturn---
$queryElement = " SELECT DISTINCT
        b.NUMBERID as element_id, 
        b.ELEMENTSCODE as element_code,
        b.BASEPRIMARYQUANTITYUNIT as curr_qty,
        tre.no_resep,
        COALESCE(bt.used_stock, 0) AS used_stock,
        (b.BASEPRIMARYQUANTITYUNIT + COALESCE(bt.used_stock, 0) / 1000) AS initial_stock
    FROM balance b
    LEFT JOIN tbl_resep_element tre ON b.NUMBERID = tre.element_id
    LEFT JOIN (
        SELECT element_id, SUM(qty) AS used_stock
        FROM balance_transactions
        GROUP BY element_id
    ) bt ON bt.element_id = b.NUMBERID
    WHERE b.NUMBERID = ?
    GROUP BY b.NUMBERID
    LIMIT 1
";

$stmt = $con->prepare($queryElement);
if (!$stmt) {
    echo json_encode(['success' => false, 'data' => null, 'message' => 'Prepare failed: ' . $con->error]);
    exit;
}

$stmt->bind_param("s", $element_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // fetch associated resep list for this element
    $noResepList = [];
    $stmt2 = $con->prepare("SELECT DISTINCT no_resep FROM tbl_resep_element WHERE element_id = ?");
    if ($stmt2) {
        $stmt2->bind_param('s', $element_id);
        $stmt2->execute();
        $r2 = $stmt2->get_result();
        while ($rr = $r2->fetch_assoc()) {
            $noResepList[] = $rr['no_resep'];
        }
        $stmt2->close();
    }

    $response['success'] = true;
    $response['data'] = [
        'element_id' => $row['element_id'],
        'element_code' => $row['element_code'],
        'curr_qty' => floatval($row['curr_qty']),
        'initial_stock' => floatval($row['initial_stock']),
        'no_resep_list' => $noResepList
    ];
    $response['message'] = 'Success';
} else {
    $response['message'] = 'Data not found';
}

$stmt->close();
echo json_encode($response);
exit;
