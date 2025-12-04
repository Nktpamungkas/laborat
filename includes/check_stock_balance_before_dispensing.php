<?php
function checkStockAvailability($con, $assignments)
{
    // 1️⃣ Ambil unique no_resep
    $uniqueResep = [];
    foreach ($assignments as $item) {
        $no_resep = trim($item['no_resep']);
        if ($no_resep) {
            $uniqueResep[$no_resep] = true;
        }
    }
    $uniqueResep = array_keys($uniqueResep);

    // 2️⃣ Query SUM qty per no_resep
    $resepGroups = [];
    $elementMap  = [];

    $sql = " SELECT 
            ps.no_resep,
            pse.element_id,
            SUM(pse.qty) as total_qty
        FROM tbl_preliminary_schedule ps
        LEFT JOIN tbl_preliminary_schedule_element pse
               ON ps.id = pse.tbl_preliminary_schedule_id
        WHERE ps.no_resep = ?
        GROUP BY ps.no_resep, pse.element_id
    ";

    $stmt = $con->prepare($sql);

    foreach ($uniqueResep as $no_resep) {
        $stmt->bind_param("s", $no_resep);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if ($res) {
            $elementMap[$no_resep]  = intval($res['element_id']);
            $resepGroups[$no_resep] = floatval($res['total_qty']);
        }
    }

    $stmt->close();

    // 3️⃣ Cek stok balance
    $insufficient = [];

    $sqlBal = "SELECT BASEPRIMARYQUANTITYUNIT FROM balance WHERE NUMBERID = ? LIMIT 1";
    $stmtBal = $con->prepare($sqlBal);

    foreach ($resepGroups as $no_resep => $need) {
        $element_id = $elementMap[$no_resep];

        // Ambil stok
        $stmtBal->bind_param("i", $element_id);
        $stmtBal->execute();
        $row = $stmtBal->get_result()->fetch_assoc();

        $qty_before_kg = $row ? floatval($row['BASEPRIMARYQUANTITYUNIT']) : 0;
        $qty_before_gr = $qty_before_kg * 1000;

        // Cek
        if ($qty_before_gr < $need) {
            $insufficient[] = [
                'no_resep' => $no_resep,
                'element_id' => $element_id,
                'stock_available_gr' => $qty_before_gr,
                'needed_gr' => $need
            ];
        }
    }

    $stmtBal->close();

    // 4️⃣ Return hasil
    if (!empty($insufficient)) {
        return [
            'ok' => false,
            'message' => "Stok tidak mencukupi",
            'failed' => $insufficient,
            'grouped_qty' => $resepGroups
        ];
    }

    return [
        'ok' => true,
        'message' => "Semua stok cukup",
        'grouped_qty' => $resepGroups
    ];
}