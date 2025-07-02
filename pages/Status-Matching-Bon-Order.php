<?php
    ini_set("error_reporting", 1);
    session_start();
    include "koneksi.php";   
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <!-- <p><strong>Total Bon Order: <?= $totalRows ?></strong></p> -->
                <h3 class="box-title">Status Matching Bon order</h3><br>
                <table id="tboTable" class="display table table-bordered table-striped" style="width:100%">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Nomer Bon Order</th>
                            <th>Customer</th>
                            <th>Tgl Approved RMP</th>
                            <th>Tgl Approved LAB</th>
                            <th>PIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sqlTBO = "SELECT * FROM approval_bon_order";
                            $stmtTBO = mysqli_query($con, $sqlTBO);
                        ?>
                        <?php while ($rowTBO = mysqli_fetch_array($stmtTBO)): ?>
                            <tr data-order="<?= $rowTBO['code'] ?>">
                                <td><?= $rowTBO['code'] ?></td>
                                <td><?= $rowTBO['customer'] ?></td>
                                <td><?= $rowTBO['tgl_approve_rmp'] ?></td>
                                <td><?= $rowTBO['tgl_approve_lab'] ?></td>
                                <td><?= $rowTBO['pic_lab'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>        
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#tboTable').DataTable();

        $('#tboTable tbody').on('click', 'tr', function (e) {
            if (!$(e.target).closest('td').length || $(e.target).is('select, option, button, input')) {
            return;
        }

        const tr = $(this);
        const orderCode = tr.data('order');

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
            data: { order_code: orderCode },
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

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

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
                if (response === 'saved' || response === 'updated') {
                    toastr.success(response === 'saved' ? 'Data berhasil disimpan.' : 'Data berhasil diperbarui.');
                    btn.removeClass('btn-primary').addClass('btn-success');

                    setTimeout(() => {
                        btn.text('Perbarui').prop('disabled', false);
                        btn.removeClass('btn-success').addClass('btn-primary');
                    }, 1000);
                } else {
                    toastr.error('Gagal menyimpan data. Respons: ' + response);
                    btn.html('Simpan').prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', status, error);
                toastr.error('Terjadi kesalahan saat mengirim data.');
                btn.html('Simpan').prop('disabled', false);
            }
        });

    });
});
</script>

