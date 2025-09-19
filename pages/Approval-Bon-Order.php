<?php
include "koneksi.php";

$approvedCodes = [];
$res = mysqli_query($con, "SELECT code FROM approval_bon_order WHERE is_revision = 0");
while ($r = mysqli_fetch_assoc($res)) {
    $approvedCodes[] = "'" . mysqli_real_escape_string($con, $r['code']) . "'";
}

// Bentuk list code (untuk IN (...))
$codeList = implode(",", $approvedCodes);

// Ambil data siap approve
$sqlTBO = "SELECT DISTINCT 
            isa.CODE AS CODE,
            ip.LANGGANAN || ip.BUYER AS CUSTOMER,
            isa.TGL_APPROVEDRMP AS TGL_APPROVE_RMP
        FROM
            ITXVIEW_SALESORDER_APPROVED isa 
        LEFT JOIN SALESORDER s ON s.CODE = isa.CODE 
        LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s.CODE
        WHERE 
            isa.APPROVEDRMP IS NOT NULL
            AND CAST(s.CREATIONDATETIME AS DATE) > '2025-06-01'
";

if (!empty($codeList)) {
    $sqlTBO .= " AND isa.CODE NOT IN ($codeList)";
}

$resultTBO = db2_exec($conn1, $sqlTBO, ['cursor' => DB2_SCROLLABLE]);

// Ambil data yang sudah pernah di-approve
$sqlApproved = "SELECT * FROM approval_bon_order WHERE is_revision = 0 ORDER BY id DESC";
$resultApproved = mysqli_query($con, $sqlApproved);
?>

<style>
    .modal-full {
        width: 98%;
        max-width: 98%;
    }
</style>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <!-- ✅ TABEL 1: Data Siap Approval -->
                <div class="card mb-4">
                    <div class="card-header text-white">
                        <h3 class="card-title">Data Siap Approval</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm" id="tboTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Customer</th>
                                    <th>Nomer Bon Order</th>
                                    <th>Tgl Approved RMP</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // $seen = [];
                                while ($row = db2_fetch_assoc($resultTBO)) {
                                    $code = strtoupper(trim($row['CODE']));
                                    // if (in_array($code, $seen)) continue;
                                    // $seen[] = $code;
                                    $customer = trim($row['CUSTOMER']);
                                    $tgl = trim($row['TGL_APPROVE_RMP']);
                                ?>
                                <tr>
                                    <td><?= $customer ?></td>
                                    <!-- <td><?= $code ?></td> -->
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm open-detail" data-code="<?= $code ?>" data-toggle="modal" data-target="#detailModal">
                                            <?= $code ?>
                                        </a>
                                    </td>
                                    <td><?= $tgl ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <select class="form-control form-control-sm pic-select" data-code="<?= $code ?>">
                                                <option value="">-- Pilih PIC --</option>
                                                <?php
                                                    // Daftar PIC yang bisa dipilih
                                                    $queryPIC = "SELECT * FROM tbl_user WHERE pic_bonorder = 1 ORDER BY id ASC";
                                                    $resultPIC = mysqli_query($con, $queryPIC);
                                                ?>
                                                <?php while ($rowPIC = mysqli_fetch_assoc($resultPIC)) : ?>
                                                    <option value="<?= $rowPIC['username'] ?>"><?= $rowPIC['username'] ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                            <button class="btn btn-success btn-sm approve-btn" data-code="<?= $code ?>">Approve</button>
                                            <button class="btn btn-danger btn-sm reject-btn" data-code="<?= $code ?>">Reject</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>  
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <!-- ✅ TABEL 2: Riwayat Approval -->
                <div class="card">
                    <div class="card-header text-white">
                        <h3 class="card-title">Tabel Approval Bon Order</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm" id="approvedTable">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th style="display: none;">ID</th>
                                    <th>Customer</th>
                                    <th>No Bon Order</th>
                                    <th>Tgl Approved RMP</th>
                                    <th>Tgl Approved Lab</th>
                                    <th>Tgl Rejected Lab</th>
                                    <th>PIC Lab</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($resultApproved)) { ?>
                                <tr>
                                    <td style="display: none;"><?= $row['id'] ?></td>
                                    <td><?= $row['customer'] ?></td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm open-detail" data-code="<?= $row['code'] ?>" data-toggle="modal" data-target="#detailModal">
                                            <?= $row['code'] ?>
                                        </a>
                                    </td>
                                    <td><?= $row['tgl_approve_rmp'] ?></td>
                                    <td><?= $row['tgl_approve_lab'] ?></td>
                                    <td><?= $row['tgl_rejected_lab'] ?></td>
                                    <td><?= $row['pic_lab'] ?></td>
                                    <td><strong class="<?= $row['status'] == 'Approved' ? 'text-success' : 'text-danger' ?>"><?= $row['status'] ?></strong></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>     
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Detail Order</h4>
        </div>
        
        <div class="modal-body" id="modal-content">
            <p>Loading data...</p>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
        
        </div>
    </div>
</div>


<script>
    $(document).on('click', '.open-detail', function() {
        var code = $(this).data('code');

        $('#modal-content').html('<p>Loading data...</p>');

        $.ajax({
        url: 'pages/ajax/Approved_get_order_detail.php',
        type: 'POST',
        data: { code: code },
        success: function(response) {
            console.log('Response received');
            $('#modal-content').html(response);

            if ($.fn.DataTable.isDataTable('#detailApprovedTable')) {
                console.log('Destroying existing DataTable');
                $('#detailApprovedTable').DataTable().destroy();
            }
            console.log('Initializing DataTable');
            $('#detailApprovedTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                order: [[0, 'asc']]
            });
        },
        error: function() {
            $('#modal-content').html('<p class="text-danger">Gagal memuat data.</p>');
        }
        });
    });

    $(document).ready(function () {
        // Inisialisasi kedua tabel dengan DataTables
        const tboTable = $('#tboTable').DataTable();
        const approvedTable = $('#approvedTable').DataTable({
                                    "order": [[0, "desc"]],
                                    "columnDefs": [
                                        { "targets": 0, "visible": false }
                                    ]
                                });

        function getPIC(code) {
            return $("select.pic-select[data-code='" + code + "']").val();
        }

        function getCustomer(code) {
            return $("tr:has(button[data-code='" + code + "']) td:first").text();
        }

        function getTglApproveRMP(code) {
            return $("tr:has(button[data-code='" + code + "']) td:eq(2)").text();
        }

        function submitApproval(code, action) {
            const pic = getPIC(code);
            const customer = getCustomer(code);
            const tgl_approve_rmp = getTglApproveRMP(code);

            // Disable semua tombol approve/reject untuk kode yang sama
            const buttons = $("button[data-code='" + code + "']");
            buttons.prop('disabled', true);

            if (!pic) {
                Swal.fire({
                    icon: 'warning',
                    title: 'PIC belum dipilih',
                    text: 'Silakan pilih PIC Lab terlebih dahulu.'
                });
                buttons.prop('disabled', false); // Re-enable jika PIC belum dipilih
                return;
            }

            Swal.fire({
                title: `${action} Bon Order?`,
                text: `Kode: ${code} | PIC: ${pic}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: action,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar.',
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        allowOutsideClick: false
                    });

                    $.post("pages/ajax/approve_bon_order_lab.php", {
                        code: code,
                        customer: customer,
                        tgl_approve_rmp: tgl_approve_rmp,
                        pic_lab: pic,
                        status: action
                    }, function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response
                        });

                        // Refresh tabel, tombol akan hilang karena data berubah
                        reloadApprovedTable();
                        reloadTboTable();
                        // refreshTBOCount();
                        // refreshTBORCount();
                    }).fail(function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyimpan data.'
                        });
                        buttons.prop('disabled', false); // Re-enable jika gagal
                    });
                } else {
                    // Re-enable jika batal
                    buttons.prop('disabled', false);
                }
            });
        }

        function reloadApprovedTable() {
            $.get("pages/ajax/refresh_approved_table.php", function (data) {
                approvedTable.clear();
                approvedTable.rows.add($(data)).draw();
            });
        }

        function reloadTboTable() {
            $.get("pages/ajax/refresh_tbo_table.php", function (data) {
                tboTable.clear();
                tboTable.rows.add($(data)).draw();

                // Re-bind tombol setelah data baru dimuat
                // bindApproveRejectButtons();
            });
        }

        function bindApproveRejectButtons() {
            $('.approve-btn').off().on('click', function () {
                const code = $(this).data('code');
                submitApproval(code, 'Approved');
            });

            $('.reject-btn').off().on('click', function () {
                const code = $(this).data('code');
                submitApproval(code, 'Rejected');
            });
        }

        // ✅ Gunakan event delegation agar tetap berfungsi setelah redraw (pengganti bindApproveRejectButtons())
        $('#tboTable tbody').on('click', '.approve-btn', function () {
            const code = $(this).data('code');
            submitApproval(code, 'Approved');
        });

        $('#tboTable tbody').on('click', '.reject-btn', function () {
            const code = $(this).data('code');
            submitApproval(code, 'Rejected');
        });

        // Bind tombol awal saat halaman pertama kali diload
        // bindApproveRejectButtons();
        
        let tboCount = 0;
        let tboRevisiCount = 0;

        // helper parsing angka aman
        function toInt(x) {
            try {
                if (typeof x === 'string' && x.trim().startsWith('{')) {
                    const obj = JSON.parse(x);
                    for (const k in obj) {
                        if (Object.hasOwn(obj, k) && !isNaN(parseInt(obj[k], 10))) {
                            return parseInt(obj[k], 10);
                        }
                    }
                }
            } catch(e) {}
            // fallback: ambil digit saja
            const n = parseInt(String(x).replace(/[^\d-]/g, ''), 10);
            return isNaN(n) ? 0 : n;
        }

        // function updateBadge() {
        //     const total = tboCount + tboRevisiCount;
        //     // badge pada icon (gabungan)
        //     $('#notifTBO').text(total);

        //     $('#notifTBOText').text(tboCount);
        //     $('#notifTBOText_revisi').text(tboRevisiCount);
        // }

        // function refreshTBOCount() {
        //     $.ajax({
        //         url: 'pages/ajax/get_total_tbo.php',
        //         method: 'GET',
        //         success: function (data) {
        //             tboCount = toInt(data);
        //             updateBadge();
        //         },
        //         error: function () {
        //             tboCount = 0;
        //             updateBadge();
        //         }
        //     });
        // }

        // function refreshTBORCount() {
        //     $.ajax({
        //         url: 'pages/ajax/get_total_tbo_revisi.php',
        //         method: 'GET',
        //         success: function (data) {
        //             tboRevisiCount = toInt(data);
        //             updateBadge();
        //         },
        //         error: function () {
        //             tboRevisiCount = 0;
        //             updateBadge();
        //         }
        //     });
        // }

        // refreshTBOCount();
        // refreshTBORCount();
    });
</script>