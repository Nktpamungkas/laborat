<?php
include "../../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_code'])) {
    $orderCode = $_POST['order_code'];
    $orderLine = $_POST['order_line'];

    // Step 1: Ambil semua DEMAND_KGF dari 3 tabel
    $sql = "
        SELECT DEMAND_KGF FROM ITXVIEWPOGREIGENEW WHERE SALESORDERCODE = ? AND DEMAND_KGF IS NOT NULL
        UNION
        SELECT DEMAND_KGF FROM ITXVIEWPOGREIGENEW2 WHERE SALESORDERCODE = ? AND DEMAND_KGF IS NOT NULL
        UNION
        SELECT DEMAND_KGF FROM ITXVIEWPOGREIGENEW3 WHERE SALESORDERCODE = ? AND DEMAND_KGF IS NOT NULL
    ";
    $stmt = db2_prepare($conn1, $sql);
    db2_bind_param($stmt, 1, "orderCode", DB2_PARAM_IN);
    db2_bind_param($stmt, 2, "orderCode", DB2_PARAM_IN);
    db2_bind_param($stmt, 3, "orderCode", DB2_PARAM_IN);
    db2_execute($stmt);

    $poList = [];
    while ($row = db2_fetch_assoc($stmt)) {
        if (!empty($row['DEMAND_KGF'])) {
            $poList[] = trim($row['DEMAND_KGF']);
        }
    }

    // Step 2: Ambil ADDITIONALDATA1–6 dari ITXVIEWBONORDER
    $sqlAdd = "SELECT 
                ibo.ADDITIONALDATA, ibo.ADDITIONALDATA2, ibo.ADDITIONALDATA3, 
                ibo.ADDITIONALDATA4, ibo.ADDITIONALDATA5, ibo.ADDITIONALDATA6,
                ibo.PROD_ORDER_AKJ, ibo.PROD_ORDER_AKJ2, ibo.PROD_ORDER_AKJ3, 
                ibo.PROD_ORDER_AKJ4, ibo.PROD_ORDER_AKJ5,
                ibo.SALESORDER_AKJ, ibo.SALESORDER_AKJ2, ibo.SALESORDER_AKJ3, 
                ibo.SALESORDER_AKJ4, ibo.SALESORDER_AKJ5,
                ibn.PROJECTCODE
            FROM ITXVIEWBONORDER ibo
            LEFT JOIN ITXVIEW_BOOKING_NEW ibn 
                ON ibo.SALESORDERCODE = ibn.SALESORDERCODE
            WHERE ibo.SALESORDERCODE = ?";
                
    $stmtAdd = db2_prepare($conn1, $sqlAdd);
    db2_bind_param($stmtAdd, 1, "orderCode", DB2_PARAM_IN);
    db2_execute($stmtAdd);
    if ($addRow = db2_fetch_assoc($stmtAdd)) {
        $extraColumns = [
            'ADDITIONALDATA', 'ADDITIONALDATA2', 'ADDITIONALDATA3', 'ADDITIONALDATA4', 'ADDITIONALDATA5', 'ADDITIONALDATA6',
            'PROD_ORDER_AKJ', 'PROD_ORDER_AKJ2', 'PROD_ORDER_AKJ3', 'PROD_ORDER_AKJ4', 'PROD_ORDER_AKJ5',
            'SALESORDER_AKJ', 'SALESORDER_AKJ2', 'SALESORDER_AKJ3', 'SALESORDER_AKJ4', 'SALESORDER_AKJ5', 'PROJECTCODE'
        ];

        foreach ($extraColumns as $col) {
            if (!empty($addRow[$col])) {
                $poList[] = trim($addRow[$col]);
            }
        }
    }

    // Buang duplikat PO dan sort
    $poList = array_unique($poList);
    sort($poList);

    // Step 3: Buat HTML tabel
    $html = '<table class="table table-sm table-bordered mb-0">';
    $html .= '<thead class="bg-warning text-white">
        <tr>
            <th>PO GREIGE</th>
            <th>PROJECT</th>
            <th>PIC Check</th>
            <th>Status Bon Order</th>
            <th>Aksi</th>
        </tr>
    </thead><tbody>';

    if (empty($poList)) {
        $html .= "<tr><td colspan='5' class='text-center text-muted'>No data</td></tr>";
    } else {
        foreach ($poList as $po) {
            $poSafe = mysqli_real_escape_string($con, $po);
            $projectSafe = mysqli_real_escape_string($con, $orderCode);

            // Cek apakah data sudah ada di MySQL
            $check = mysqli_query($con, "SELECT * FROM status_matching_bon_order WHERE sales_order_code = '$projectSafe' AND po_greige = '$poSafe'");
            $existing = mysqli_fetch_assoc($check);

            $selectedPIC = $existing['pic_check'] ?? '';
            $selectedStatus = $existing['status_bon_order'] ?? '';
            $btnText = $existing ? 'Perbarui' : 'Simpan';

            $html .= "<tr>
                <td>$po</td>
                <td>$orderCode</td>
                <td>
                    <select class='form-control form-control-sm pic-select'>
                        <option value=''>--Pilih--</option>";
                        foreach (['Cecen', 'Ridho', 'Riyan', 'Flavia'] as $pic) {
                            $selected = ($pic === $selectedPIC) ? 'selected' : '';
                            $html .= "<option value='$pic' $selected>$pic</option>";
                        }
            $html .= "</select>
                </td>
                <td>
                    <select class='form-control form-control-sm status-select'>
                        <option value=''>--Pilih--</option>";
                        foreach (['OK', 'Matching Ulang'] as $status) {
                            $selected = ($status === $selectedStatus) ? 'selected' : '';
                            $html .= "<option value='$status' $selected>$status</option>";
                        }
            $html .= "</select>
                </td>
                <td>
                    <button class='btn btn-sm btn-primary save-status-btn' data-order='$orderCode' data-po='$po'>$btnText</button>
                </td>
            </tr>";
        }
    }

    $html .= '</tbody></table>';
    echo $html;
}
