<?php
/**
 * Helper: generate secondary qty for a balance element
 *
 * Usage:
 *   include 'includes/generate_balance_secondary_qty.php';
 *   $res = generate_balance_secondary_qty($con, $element_id);
 *   if ($res['success']) { $secondary = $res['secondary_qty']; }
 *
 * Returns array: [
 *   'success' => bool,
 *   'secondary_qty' => float, // computed (base * factor)
 *   'factor' => float,        // conversion factor used
 *   'base_qty' => float,      // base qty (kg)
 *   'message' => string|null
 * ]
 */

function generate_balance_secondary_qty($con, $element_id)
{
    if (empty($element_id)) {
        return ['success' => false, 'message' => 'element_id required'];
    }

    // 1) Get base qty and subcodes from balance
    $sql = "SELECT BASEPRIMARYQUANTITYUNIT AS base_qty, SUBCODE01, SUBCODE02, SUBCODE03, SUBCODE04
            FROM balance WHERE NUMBERID = ? LIMIT 1";

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        return ['success' => false, 'message' => 'Prepare failed (balance): ' . mysqli_error($con)];
    }
    mysqli_stmt_bind_param($stmt, 's', $element_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if (!$row) {
        return ['success' => false, 'message' => 'Balance not found for element_id: ' . $element_id];
    }

    $base_qty = floatval($row['base_qty'] ?? 0);
    $sub1 = $row['SUBCODE01'] ?? '';
    $sub2 = $row['SUBCODE02'] ?? '';
    $sub3 = $row['SUBCODE03'] ?? '';
    $sub4 = $row['SUBCODE04'] ?? '';

    // 2) Lookup conversion factor from PRODUCT
    $sql2 = "SELECT SECONDARYUNSTEADYCVSFACTOR FROM PRODUCT
             WHERE ITEMTYPECODE = 'KGF'
               AND SUBCODE01 = ?
               AND SUBCODE02 = ?
               AND SUBCODE03 = ?
               AND SUBCODE04 = ?
             LIMIT 1";

    $stmt2 = mysqli_prepare($con, $sql2);
    if (!$stmt2) {
        return ['success' => false, 'message' => 'Prepare failed (product): ' . mysqli_error($con)];
    }
    mysqli_stmt_bind_param($stmt2, 'ssss', $sub1, $sub2, $sub3, $sub4);
    mysqli_stmt_execute($stmt2);
    $res2 = mysqli_stmt_get_result($stmt2);
    $prow = mysqli_fetch_assoc($res2);
    mysqli_stmt_close($stmt2);

    if (!$prow || !isset($prow['SECONDARYUNSTEADYCVSFACTOR'])) {
        return ['success' => false, 'message' => 'Conversion factor not found for product (subcodes): ' . implode(', ', [$sub1, $sub2, $sub3, $sub4])];
    }

    $factor = floatval($prow['SECONDARYUNSTEADYCVSFACTOR']);
    // compute secondary qty
    $secondary_qty = $base_qty * $factor;

    return [
        'success' => true,
        'secondary_qty' => $secondary_qty,
        'factor' => $factor,
        'base_qty' => $base_qty,
        'message' => null
    ];
}

?>
