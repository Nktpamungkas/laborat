<?php
include "../../koneksi.php";

$orderCode = isset($_POST['order_code']) ? trim($_POST['order_code']) : '';

$sql = "
    SELECT 
        i.SALESORDERCODE,
        i.ORDERLINE,
        COALESCE(
            TRIM(pg.PO_GREIGE) ||
            COALESCE(', ' || TRIM(i.ADDITIONALDATA), '') ||
            COALESCE(', ' || TRIM(i.ADDITIONALDATA2), '') ||
            COALESCE(', ' || TRIM(i.ADDITIONALDATA3), '') ||
            COALESCE(', ' || TRIM(i.ADDITIONALDATA4), '') ||
            COALESCE(', ' || TRIM(i.ADDITIONALDATA5), '') ||
            COALESCE(', ' || TRIM(i.ADDITIONALDATA6), '') ||
            COALESCE(', ' || TRIM(i.PROD_ORDER_AKJ), '') ||
            COALESCE(', ' || TRIM(i.PROD_ORDER_AKJ2), '') ||
            COALESCE(', ' || TRIM(i.PROD_ORDER_AKJ3), '') ||
            COALESCE(', ' || TRIM(i.PROD_ORDER_AKJ4), '') ||
            COALESCE(', ' || TRIM(i.PROD_ORDER_AKJ5), '') ||
            COALESCE(', ' || TRIM(i.SALESORDER_AKJ), '') ||
            COALESCE(', ' || TRIM(i.SALESORDER_AKJ2), '') ||
            COALESCE(', ' || TRIM(i.SALESORDER_AKJ3), '') ||
            COALESCE(', ' || TRIM(i.SALESORDER_AKJ4), '') ||
            COALESCE(', ' || TRIM(i.SALESORDER_AKJ5), '') ||
            COALESCE(', ' || TRIM(ibn.PROJECTCODE), ''),
            COALESCE(TRIM(i.ADDITIONALDATA), '') ||
            COALESCE(', ' || TRIM(i.ADDITIONALDATA2), '') ||
            COALESCE(', ' || TRIM(i.ADDITIONALDATA3), '') ||
            COALESCE(', ' || TRIM(i.ADDITIONALDATA4), '') ||
            COALESCE(', ' || TRIM(i.ADDITIONALDATA5), '') ||
            COALESCE(', ' || TRIM(i.ADDITIONALDATA6), '') ||
            COALESCE(', ' || TRIM(i.PROD_ORDER_AKJ), '') ||
            COALESCE(', ' || TRIM(i.PROD_ORDER_AKJ2), '') ||
            COALESCE(', ' || TRIM(i.PROD_ORDER_AKJ3), '') ||
            COALESCE(', ' || TRIM(i.PROD_ORDER_AKJ4), '') ||
            COALESCE(', ' || TRIM(i.PROD_ORDER_AKJ5), '') ||
            COALESCE(', ' || TRIM(i.SALESORDER_AKJ), '') ||
            COALESCE(', ' || TRIM(i.SALESORDER_AKJ2), '') ||
            COALESCE(', ' || TRIM(i.SALESORDER_AKJ3), '') ||
            COALESCE(', ' || TRIM(i.SALESORDER_AKJ4), '') ||
            COALESCE(', ' || TRIM(i.SALESORDER_AKJ5), '') ||
            COALESCE(', ' || TRIM(ibn.PROJECTCODE), '')
        ) AS PO_GREIGE,
        TRIM(ibn.PROJECTCODE) AS PROJECT,
        i.SALESORDERCODE,
        i.ORDERLINE
    FROM ITXVIEWBONORDER i
    LEFT JOIN (
        SELECT SALESORDERCODE, ORDERLINE, LISTAGG(DEMAND_KGF, ', ') WITHIN GROUP (ORDER BY DEMAND_KGF) AS PO_GREIGE
        FROM (
            SELECT SALESORDERCODE, ORDERLINE, DEMAND_KGF FROM ITXVIEWPOGREIGENEW
            UNION
            SELECT SALESORDERCODE, ORDERLINE, DEMAND_KGF FROM ITXVIEWPOGREIGENEW2
            UNION
            SELECT SALESORDERCODE, ORDERLINE, DEMAND_KGF FROM ITXVIEWPOGREIGENEW3
        ) all_data
        WHERE DEMAND_KGF IS NOT NULL
        GROUP BY SALESORDERCODE, ORDERLINE
    ) pg ON pg.SALESORDERCODE = i.SALESORDERCODE AND pg.ORDERLINE = i.ORDERLINE
    LEFT JOIN ITXVIEW_BOOKING_NEW ibn ON ibn.SALESORDERCODE = i.SALESORDERCODE AND ibn.ORDERLINE = i.ORDERLINE
    WHERE i.SALESORDERCODE = ?
";

$stmt = db2_prepare($conn1, $sql);
if (!$stmt) {
    echo "Prepare statement gagal: " . db2_stmt_errormsg();
    exit;
}

$result = db2_execute($stmt, [$orderCode]);
if (!$result) {
    echo "Eksekusi query gagal: " . db2_stmt_errormsg($stmt);
    exit;
}

echo "<table class='table table-bordered'>";
echo "
<thead class='bg-warning text-white'>
    <tr>
        <th>PO GREIGE</th>
        <th>PROJECT</th>
        <th>PIC Check</th>
        <th>Status Bon Order</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
";

while ($row = db2_fetch_assoc($stmt)) {
    // Amanin nilai null untuk htmlspecialchars dengan ?? ''
    $po_greige = isset($row['PO_GREIGE']) ? ltrim($row['PO_GREIGE'], ', ') : '';
    $project = $row['SALESORDERCODE'] ?? '';
    
    // Escape agar aman dari XSS, pastikan string
    $po_greige_html = htmlspecialchars($po_greige ?? '');
    $project_html = htmlspecialchars($project ?? '');

    // Untuk cek status di MySQL (status_matching_bon_order)
    $poSafe = mysqli_real_escape_string($con, $po_greige);
    $projectSafe = mysqli_real_escape_string($con, $orderCode);

    $check = mysqli_query($con, "SELECT * FROM status_matching_bon_order WHERE sales_order_code = '$projectSafe' AND po_greige = '$poSafe'");
    $existing = mysqli_fetch_assoc($check);

    $selectedPIC = $existing['pic_check'] ?? '';
    $selectedStatus = $existing['status_bon_order'] ?? '';
    $btnText = $existing ? 'Perbarui' : 'Simpan';

    // Buat opsi PIC
    $picOptions = "";
    foreach (['Cecen', 'Ridho', 'Riyan', 'Flavia'] as $pic) {
        $selected = ($pic === $selectedPIC) ? 'selected' : '';
        $picOptions .= "<option value='$pic' $selected>$pic</option>";
    }

    // Buat opsi Status Bon Order
    $statusOptions = "";
    foreach (['OK', 'Matching Ulang'] as $status) {
        $selected = ($status === $selectedStatus) ? 'selected' : '';
        $statusOptions .= "<option value='$status' $selected>$status</option>";
    }

    echo "<tr>";
    echo "<td>$po_greige_html</td>";
    echo "<td>$project_html</td>";

    echo "<td>
            <select class='form-control form-control-sm pic-select'>
                <option value=''>--Pilih--</option>
                $picOptions
            </select>
          </td>";

    echo "<td>
            <select class='form-control form-control-sm status-select'>
                <option value=''>--Pilih--</option>
                $statusOptions
            </select>
          </td>";

    echo "<td>
            <button class='btn btn-sm btn-primary save-status-btn' data-order='" . htmlspecialchars($orderCode) . "' data-po='$po_greige_html'>$btnText</button>
          </td>";

    echo "</tr>";
}

echo "</tbody></table>";
?>

<script>
document.querySelectorAll('.save-status-btn').forEach(button => {
    button.addEventListener('click', () => {
        const row = button.closest('tr');
        const picSelect = row.querySelector('.pic-select');
        const statusSelect = row.querySelector('.status-select');

        const pic = picSelect.value;
        const status = statusSelect.value;
        const po = button.getAttribute('data-po');
        const order = button.getAttribute('data-order');

        if (!pic || !status) {
            alert('PIC dan Status harus dipilih!');
            return;
        }

        // Kirim data ke server via AJAX untuk simpan/perbarui
        fetch('save_status.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                sales_order_code: order,
                po_greige: po,
                pic_check: pic,
                status_bon_order: status
            })
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
        })
        .catch(error => {
            alert('Error: ' + error);
        });
    });
});
</script>
