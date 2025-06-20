<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";   

$approvedCodes = [];
// $res = mysqli_query($con, "SELECT code FROM approval_bon_order");
$res = mysqli_query($con, "
            SELECT code 
            FROM approval_bon_order abo
            WHERE NOT EXISTS (
                SELECT 1 FROM approval_bon_order abo2 
                WHERE abo2.code = abo.code AND abo2.id > abo.id
            )
        ");

while ($r = mysqli_fetch_assoc($res)) {
    $approvedCodes[] = "'" . mysqli_real_escape_string($con, $r['code']) . "'";
}
$codeList = implode(",", $approvedCodes);

// Query utama dengan join untuk ColorRemarks
$sqlTBO = "
WITH APPROVED_RMP AS (
    SELECT DISTINCT 
        isa.CODE,
        isa.APPROVERMP,
        isa.APPROVEDRMP,
        isa.TGL_APPROVEDRMP
    FROM ITXVIEW_SALESORDER_APPROVED isa
)
SELECT 
    i.SALESORDERCODE,
    MAX(i.ORDERLINE) AS ORDERLINE,
    MAX(i.LEGALNAME1) AS LEGALNAME1,
    MAX(REPLACE(i.SUBCODE02, ' ', '')) AS SUBCODE02,
    MAX(REPLACE(i.SUBCODE03, ' ', '')) AS SUBCODE03,
    MAX(i.WARNA) AS WARNA,
    MAX(i.SUBCODE05) AS SUBCODE05,
    MAX(i.COLORGROUP) AS COLORGROUP,
    MAX(i.ABSUNIQUEID_SALESORDERLINE) AS ABSUNIQUEID_SALESORDERLINE,
    MAX(a2.VALUESTRING) AS COLORREMARKS
FROM ITXVIEWBONORDER i
LEFT JOIN APPROVED_RMP AR ON AR.CODE = i.SALESORDERCODE
LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND a2.FIELDNAME = 'ColorRemarks'
WHERE 
    AR.APPROVERMP IS NOT NULL 
    AND AR.APPROVEDRMP IS NOT NULL
    AND CAST(i.CREATIONDATETIME_SALESORDER AS DATE) > '2025-06-01'
";

if (!empty($codeList)) {
    $sqlTBO .= " AND i.SALESORDERCODE IN ($codeList)";
} else {
    $sqlTBO .= " AND 1 = 0";
}

$sqlTBO .= " GROUP BY i.SALESORDERCODE";

$stmtTBO = db2_exec($conn1, $sqlTBO, array('cursor' => DB2_SCROLLABLE));
// $totalRows = db2_num_rows($stmtTBO);
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <!-- <p><strong>Total Bon Order: <?= $totalRows ?></strong></p> -->
                <table id="tboTable" class="display table table-bordered table-striped" style="width:100%">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Customer</th>
                            <th>Nomer Bon Order</th>
                            <th>No. Item</th>
                            <th>Warna</th>
                            <th>No. Warna</th>
                            <th>Color Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($rowTBO = db2_fetch_assoc($stmtTBO)): ?>
                            <?php 
                                $orderCode = htmlspecialchars($rowTBO['SALESORDERCODE']);
                                $customer = htmlspecialchars($rowTBO['LEGALNAME1']);
                                $noItem = htmlspecialchars(trim($rowTBO['SUBCODE02'] . $rowTBO['SUBCODE03']));
                                $warna = htmlspecialchars($rowTBO['WARNA']);
                                $NoWarna = htmlspecialchars(trim($rowTBO['SUBCODE05'] . ' (' . $rowTBO['COLORGROUP'] . ')'));
                                $colorRemarks = htmlspecialchars($rowTBO['COLORREMARKS'] ?? '');
                                $orderLine = htmlspecialchars($rowTBO['ORDERLINE'] ?? '');
                            ?>
                            <tr data-orderline="<?= $orderLine ?>">
                                <td><?= $customer ?></td>
                                <td><?= $orderCode  ?></td>
                                <td><?= $noItem ?></td>
                                <td><?= $warna ?></td>
                                <td><?= $NoWarna ?></td>
                                <td><?= $colorRemarks ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>        
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#tboTable').DataTable();

        $('#tboTable tbody').on('click', 'tr', function (e) {
            if (!$(e.target).closest('td').length || $(e.target).is('select, option, button, input')) {
            return;
        }

        const tr = $(this);
        const orderCode = tr.find('td:eq(1)').text().trim();
        const orderLine = tr.data('orderline');

        const nextRow = tr.next('.greige-row');
        if (nextRow.length) {
            nextRow.remove(); 
            return;
        }

        $('.greige-row').remove();

        const colspan = tr.children('td').length;
        const loadingRow = $('<tr class="greige-row"><td colspan="' + colspan + '">Loading PO Greige...</td></tr>');
        tr.after(loadingRow);

        $.ajax({
            url: 'pages/ajax/get_po_greige.php',
            type: 'POST',
            data: { order_code: orderCode, order_line: orderLine },
            success: function (html) {
                loadingRow.html('<td colspan="' + colspan + '">' + html + '</td>');
            },
            error: function () {
                loadingRow.html('<td colspan="' + colspan + '">Gagal mengambil data PO Greige.</td>');
            }
        });
    });

    $(document).on('click', '.save-status-btn', function () {
        const row = $(this).closest('tr');
        const salesOrderCode = $(this).data('order');
        const poGreige = $(this).data('po');
        const pic = row.find('.pic-select').val();
        const status = row.find('.status-select').val();

        if (!pic || !status) {
            toastr.warning("PIC dan Status Bon Order harus dipilih.");
            return;
        }

        $.ajax({
            url: 'pages/ajax/save_status_matching_bon_order.php',
            type: 'POST',
            data: {
                sales_order_code: salesOrderCode,
                po_greige: poGreige,
                pic: pic,
                status: status
            },
            success: function(response) {
                console.log('Server Response:', response);
                if (response === 'saved') {
                    toastr.success('Data berhasil disimpan.');
                } else if (response === 'updated') {
                    toastr.success('Data berhasil diperbarui.');
                } else {
                    toastr.error('Gagal menyimpan data. Respons: ' + response);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', status, error);
                toastr.error('Terjadi kesalahan saat mengirim data.');
            }
        });
    });
});
</script>

