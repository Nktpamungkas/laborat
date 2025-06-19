<?php
include "../../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_code'])) {
    $orderCode = $_POST['order_code'];
    $orderLine = $_POST['order_line'];

    $sql = "SELECT DEMAND_KGF, SALESORDERCODE, ORDERLINE
            FROM ITXVIEWPOGREIGENEW
            WHERE SALESORDERCODE = ? AND ORDERLINE = ? AND DEMAND_KGF IS NOT NULL

            UNION

            SELECT DEMAND_KGF, SALESORDERCODE, ORDERLINE
            FROM ITXVIEWPOGREIGENEW2
            WHERE SALESORDERCODE = ? AND ORDERLINE = ? AND DEMAND_KGF IS NOT NULL

            UNION

            SELECT DEMAND_KGF, SALESORDERCODE, ORDERLINE
            FROM ITXVIEWPOGREIGENEW3
            WHERE SALESORDERCODE = ? AND ORDERLINE = ? AND DEMAND_KGF IS NOT NULL";
    $stmt = db2_prepare($conn1, $sql);
    db2_bind_param($stmt, 1, "orderCode", DB2_PARAM_IN);
    db2_bind_param($stmt, 2, "orderLine", DB2_PARAM_IN);
    db2_bind_param($stmt, 3, "orderCode", DB2_PARAM_IN);
    db2_bind_param($stmt, 4, "orderLine", DB2_PARAM_IN);
    db2_bind_param($stmt, 5, "orderCode", DB2_PARAM_IN);
    db2_bind_param($stmt, 6, "orderLine", DB2_PARAM_IN);
    db2_execute($stmt);

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

    $hasData = false;

    while ($row = db2_fetch_assoc($stmt)) {
        $hasData = true;

        $po = htmlspecialchars($row['DEMAND_KGF'] ?? '');
        $project = htmlspecialchars($row['SALESORDERCODE'] ?? '');

        // Cek apakah sudah ada data di MySQL
        $poSafe = mysqli_real_escape_string($con, $po);
        $projectSafe = mysqli_real_escape_string($con, $project);
        $check = mysqli_query($con, "SELECT * FROM status_matching_bon_order WHERE sales_order_code = '$projectSafe' AND po_greige = '$poSafe'");
        $existing = mysqli_fetch_assoc($check);

        $selectedPIC = $existing['pic_check'] ?? '';
        $selectedStatus = $existing['status_bon_order'] ?? '';

        $html .= "<tr>
            <td>$po</td>
            <td>$project</td>
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

        $btnText = $existing ? 'Perbarui' : 'Simpan';

        $html .= "</select>
            </td>
            <td>
                <button class='btn btn-sm btn-primary save-status-btn' data-order='$project' data-po='$po'>$btnText</button>
            </td>
        </tr>";
    }

    if (!$hasData) {
        $html .= "<tr><td colspan='5' class='text-center text-muted'>No data</td></tr>";
    }

    $html .= '</tbody></table>';
    echo $html;
}
