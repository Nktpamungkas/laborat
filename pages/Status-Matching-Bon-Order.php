<?php
    ini_set("error_reporting", 1);
    session_start();
    include "koneksi.php";

    $userLAB = $_SESSION['userLAB'] ?? '';
    $ipUser  = $_SESSION['ip']      ?? '';
    session_write_close(); 
?>

<style>
    .btn-loading{position:relative;pointer-events:none;opacity:.7}
    .btn-loading .spinner-border{position:absolute;top:50%;left:50%;width:1rem;height:1rem;margin-top:-.5rem;margin-left:-.5rem;border-width:.15em}

    .btn-outline-purple{background-color:transparent;color:#6f42c1;border:1px solid #6f42c1}
    .btn-outline-purple:hover,.btn-outline-purple:focus{background:#6f42c1;color:#fff}

    .rev-muted{margin-top:4px; font-size:12px; color:#6c757d}
    .rev-wrap{
        margin-top:4px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:8px;
    }
    .rev-left{
        display:flex;
        gap:50px;
        font-weight:700;
    }
    .rev-wrap .push-right{margin-left:auto}
    .rev-loading {
        font-style: italic;
        opacity: 0.6;
    }
</style>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <h3 class="box-title">Status Matching Bon order</h3><br>

<?php
/* 1) Ambil data Approved dari MySQL */
$sqlTBO = "
    SELECT code, customer, tgl_approve_rmp, tgl_approve_lab, pic_lab, is_revision, approvalrmpdatetime
    FROM approval_bon_order
    WHERE status = 'Approved' ORDER BY id DESC
";
$rsMy = mysqli_query($con, $sqlTBO);

$approvedRows = [];
$tempMap = [];

if ($rsMy) {
    while ($r = mysqli_fetch_assoc($rsMy)) {
        $r['code'] = trim((string)$r['code']);
        $code = $r['code'];
        if ($code === '') continue;

        if (!isset($tempMap[$code])) {
            $tempMap[$code] = $r;
        } else {
            $existing = $tempMap[$code];
            if ((int)$r['is_revision'] === 1 && (int)$existing['is_revision'] === 0) {
                $tempMap[$code] = $r; // utamakan revisi
            } elseif ((int)$r['is_revision'] === (int)$existing['is_revision']) {
                // sama level revisi → ambil Approved Lab terbaru
                if (strtotime($r['tgl_approve_lab']) > strtotime($existing['tgl_approve_lab'])) {
                    $tempMap[$code] = $r;
                }
            }
        }
    }

    // unik per code
    $approvedRows = array_values($tempMap);
}
?>

                <table id="tboTable" class="display table table-bordered table-striped" style="width:100%">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="30%">Nomer Bon Order</th>
                            <th>Customer</th>
                            <th>Tgl Approved RMP</th>
                            <th>Tgl Approved LAB</th>
                            <th>PIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($approvedRows as $rowTBO):
                            $code       = $rowTBO['code'];
                            $isRevision = (int)$rowTBO['is_revision'] === 1;
                        ?>
                            <tr class="<?= $isRevision ? 'has-revisi' : '' ?>"
                                data-order="<?= htmlspecialchars($code) ?>"
                                data-revision="<?= $isRevision ? '1' : '0' ?>"
                            >
                                <td class="cell-order" data-code="<?= htmlspecialchars($code) ?>">
                                    <?= htmlspecialchars($code) ?>
                                </td>
                                <td class="cell-customer" data-cust="<?= htmlspecialchars($rowTBO['customer']) ?>">
                                    <?= htmlspecialchars($rowTBO['customer']) ?>
                                </td>
                                <td>
                                    <?= !empty($rowTBO['approvalrmpdatetime']) 
                                        ? htmlspecialchars(date('Y-m-d', strtotime($rowTBO['approvalrmpdatetime']))) 
                                        : '' ?>
                                </td>
                                <td><?= htmlspecialchars($rowTBO['tgl_approve_lab']) ?></td>
                                <td><?= htmlspecialchars($rowTBO['pic_lab']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>        
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Revisi -->
<div class="modal fade" id="modalRevisi" tabindex="-1" role="dialog" aria-labelledby="modalRevisiLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalRevisiLabel">Detail Revisi</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th style="width:160px">Revisi Category</th>
                    <th>Detail Revisi</th>
                    <th style="width:140px">Tanggal Revisi</th>
                </tr>
            </thead>
            <tbody id="tbl-revisi-body">
                <!-- diisi via JS -->
            </tbody>
        </table>
        <div class="text-right">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
    // ----- VAR GLOBAL DARI PHP -----
    const currentUser = "<?= htmlspecialchars($userLAB ?? '', ENT_QUOTES, 'UTF-8'); ?>";
    const ip_user     = "<?= htmlspecialchars($ipUser  ?? '', ENT_QUOTES, 'UTF-8'); ?>";

    // ----- DATATABLES UTAMA -----
    const table = $('#tboTable').DataTable({
        pageLength: 10,
        lengthMenu: [10,25,50,100],
        order: [],
        autoWidth: false,
        deferRender: true
    });

    // ----- CACHE RINGKASAN REVISI (HEADER) -----
    const loadedSummary  = {}; // { CODE: {revin_last, revisic_last} }
    const pendingSummary = {}; // { CODE: true } kalau sedang di-request

    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }

    // Apply ringkasan revisi ke satu baris header
    function applySummaryToRow($tr, infoOpt) {
        const code = $tr.data('order');
        if (!code) return;

        const info = infoOpt || loadedSummary[code];
        if (!info) return;

        const wrap   = $tr.find('.rev-wrap');
        const $revin = wrap.find('.rev-revin');
        const $revic = wrap.find('.rev-revic');

        $revin
            .removeClass('rev-loading')
            .text(info.revin_last && info.revin_last.trim() !== '' ? info.revin_last : '-');
        $revic
            .removeClass('rev-loading')
            .text(info.revisic_last && info.revisic_last.trim() !== '' ? info.revisic_last : '-');
    }

    // Pastikan ringkasan revisi untuk 1 baris header ada (kalau belum, ambil via AJAX per-code)
    function ensureSummaryForRow($tr) {
        const code = $tr.data('order');
        if (!code) return;

        // sudah di-cache
        if (loadedSummary[code]) {
            applySummaryToRow($tr);
            return;
        }

        // lagi di-request
        if (pendingSummary[code]) return;
        pendingSummary[code] = true;

        const wrap   = $tr.find('.rev-wrap');
        const $revin = wrap.find('.rev-revin');
        const $revic = wrap.find('.rev-revic');

        // indikator loading
        $revin.addClass('rev-loading').text('loading...');
        $revic.addClass('rev-loading').text('loading...');

        $.ajax({
            url: 'pages/ajax/get_revisi_detail.php',
            type: 'GET',
            dataType: 'json',
            data: { code: code },
            success: function(res){
                if (!res || !res.ok) {
                    loadedSummary[code] = { revin_last: '', revisic_last: '' };
                } else {
                    loadedSummary[code] = {
                        revin_last:   res.revin_last   || '',
                        revisic_last: res.revisic_last || ''
                    };
                }
                applySummaryToRow($tr, loadedSummary[code]);
            },
            error: function(){
                loadedSummary[code] = { revin_last: '', revisic_last: '' };
                applySummaryToRow($tr, loadedSummary[code]);
            },
            complete: function(){
                pendingSummary[code] = false;
            }
        });
    }

    // Render sel Nomer Bon Order (kolom 1) di header
    function renderOrderCell(tr) {
        const $tr = $(tr);
        const isRev = $tr.data('revision');
        const $td  = $('td.cell-order', $tr);
        const code = $td.data('code') || $td.text();

        if (isRev === 1 || isRev === '1') {
            const html = `
            <div>
                <div>${escapeHtml(code)}</div>
                <div class="rev-wrap" style="background:#ffecec;">
                    <div class="rev-left" style="margin-left:4px;">
                        <span class="rev-muted rev-revin">-</span>
                        <span class="rev-muted rev-revic">-</span>
                    </div>
                    <button type="button"
                            class="btn btn-outline-purple btn-xs revisi-btn push-right"
                            data-code="${escapeHtml(code)}"
                            style="margin-right:4px;">
                        Detail Revisi
                    </button>
                </div>
            </div>`;
            $td.html(html);

            // pastikan ringkasan di-load
            ensureSummaryForRow($tr);
        } else {
            $td.text(code);
        }
    }

    function renderAllVisible() {
        table.rows({ page: 'current' }).every(function () {
            renderOrderCell(this.node());
        });
    }

    // panggil ensureSummaryForRow untuk semua header yang sedang tampil
    function loadRevisionSummaryForPage() {
        table.rows({ page: 'current' }).every(function(){
            const $tr = $(this.node());
            if (!$tr.hasClass('has-revisi')) return;
            ensureSummaryForRow($tr);
        });
    }

    // setiap draw (pindah page / search / sort)
    $('#tboTable').on('draw.dt', function(){
        renderAllVisible();
        loadRevisionSummaryForPage();
    });

    // initial load
    renderAllVisible();
    loadRevisionSummaryForPage();

    // Klik tombol Detail Revisi (header & detail PO Greige)
    $(document).on('click', '.revisi-btn', function(e){
        e.stopPropagation();

        const btn  = $(this);
        const code = btn.data('code'); // hanya ada di header

        // ====== CASE 1: TOMBOL DI HEADER (punya data-code) ======
        if (code) {
            const oldText = btn.text();
            // btn.addClass('btn-loading').prop('disabled', true).text('Loading...');

            $.ajax({
                url: 'pages/ajax/get_revisi_detail.php',
                type: 'GET',
                dataType: 'json',
                data: { code: code },
                success: function(res) {
                    if (!res || !res.ok) {
                        alert(res && res.message ? res.message : 'Gagal mengambil detail revisi.');
                        return;
                    }

                    const rows = (res.items || []).map(function(p){
                        return `
                            <tr>
                                <td>${escapeHtml(p.cat || '-')}</td>
                                <td>${escapeHtml(p.det || '-')}</td>
                                <td>${escapeHtml(p.dt  || '')}</td>
                            </tr>`;
                    });

                    $('#tbl-revisi-body').html(
                        rows.length ? rows.join('') :
                        `<tr><td colspan="3" class="text-center text-muted">Tidak ada detail revisi yang terisi.</td></tr>`
                    );

                    // sync ringkasan & cache di header
                    const info = {
                        revin_last:   res.revin_last   || '',
                        revisic_last: res.revisic_last || ''
                    };
                    loadedSummary[code] = info;

                    table.rows().every(function(){
                        const $tr = $(this.node());
                        const c   = $tr.data('order');
                        if (c != code) return;
                        applySummaryToRow($tr, info);
                    });

                    $('#modalRevisi').modal('show');
                },
                error: function() {
                    alert('Gagal mengambil detail revisi (AJAX error).');
                },
                complete: function() {
                    btn.removeClass('btn-loading').prop('disabled', false).text(oldText);
                }
            });

            return;
        }

        // ====== CASE 2: TOMBOL DI DALAM get_po_greige.php (TIDAK punya data-code) ======
        const d = this.dataset;
        const norm = (v) => (v === undefined || v === null) ? '' : String(v).trim();

        const pairs = [
            { cat: norm(d.revisic),  det: norm(d.revisin),  dt: norm(d.revisi1date) },
            { cat: norm(d.revisi2),  det: norm(d.drevisi2), dt: norm(d.revisi2date) },
            { cat: norm(d.revisi3),  det: norm(d.drevisi3), dt: norm(d.revisi3date) },
            { cat: norm(d.revisi4),  det: norm(d.drevisi4), dt: norm(d.revisi4date) },
            { cat: norm(d.revisi5),  det: norm(d.drevisi5), dt: norm(d.revisi5date) },
        ];

        const rows = pairs
            .filter(p => p.cat !== '' || p.det !== '' || p.dt !== '')
            .map(p => `
                <tr>
                    <td>${escapeHtml(p.cat || '-')}</td>
                    <td>${escapeHtml(p.det || '-')}</td>
                    <td>${escapeHtml(p.dt  || '')}</td>
                </tr>
            `);

        $('#tbl-revisi-body').html(
            rows.length ? rows.join('') :
            `<tr><td colspan="3" class="text-center text-muted">Tidak ada detail revisi yang terisi.</td></tr>`
        );

        $('#modalRevisi').modal('show');
    });

    // ====== FUNGSI BANTU UNTUK ROW DETAIL PO GREIGE ======
    function initGreigeRows($scope) {
        $scope.find('.row-item').each(function() {
            const row = $(this);
            const picSelect    = row.find('.pic-check');
            const statusSelect = row.find('.status-bonorder');
            const btnTextSpan  = row.find('.btn-simpan-row .btn-text');

            const hasData = (picSelect.val() && statusSelect.val());

            if (hasData) {
                picSelect.prop('disabled', true);
                statusSelect.prop('disabled', true);
                btnTextSpan.text('Edit');
            } else {
                picSelect.prop('disabled', false);
                statusSelect.prop('disabled', false);
                btnTextSpan.text('Simpan');
            }
        });
    }

    // Klik baris utama di header -> toggle baris PO Greige
    $('#tboTable tbody').on('click', '> tr:not(.greige-row)', function (e) {
        // Abaikan klik pada tombol / input / detail di dalam greige
        if ($(e.target).closest('.revisi-btn, .detail-wrap, .close-greige-row, .btn-simpan-row').length) return;
        if ($(e.target).is('select, option, button, input, a,label')) return;

        const tr = $(this);
        const orderCode = tr.data('order');

        // Toggle buka/tutup baris detail
        const nextRow = tr.next('.greige-row');
        if (nextRow.length) {
            tr.removeClass('expanded');
            nextRow.remove();
            return;
        }

        tr.addClass('expanded');
        const colspan = tr.children('td').length;
        const loadingRow = $('<tr class="greige-row"><td colspan="' + colspan + '">Loading PO Greige...</td></tr>');
        tr.after(loadingRow);

        $.ajax({
            url: 'pages/ajax/get_po_greige.php',
            type: 'POST',
            data: { order_code: orderCode },
            success: function (html) {
                loadingRow.html(`
                    <td colspan="${colspan}">
                        <div class="detail-wrap" style="position: relative;">
                            <button class="close-greige-row btn btn-sm btn-danger" style="position: absolute; top: 5px; right: 5px;">&times;</button>
                            ${html}
                        </div>
                    </td>
                `);

                // inisialisasi UI (label Simpan/Edit, disable dropdown)
                initGreigeRows(loadingRow);
            },
            error: function () {
                loadingRow.html('<td colspan="' + colspan + '">Gagal mengambil data PO Greige.</td>');
            }
        });
    });

    // jangan biarkan klik di dalam greige-row menutup row
    $(document).on('click', '.greige-row, .greige-row *', function (e) {
        e.stopPropagation();
    });

    // tombol close detail PO Greige
    $(document).on('click', '.close-greige-row', function (e) {
        e.stopPropagation();
        const greigeRow = $(this).closest('tr.greige-row');
        const mainRow = greigeRow.prev('tr');
        greigeRow.remove();
        mainRow.removeClass('expanded');
    });

    // cegah bubbling pada row-item (baris dalam table PO Greige)
    $(document).on('click', '.row-item, .row-item *', function(e){
        e.stopPropagation();
    });

    // ====== HANDLER TOMBOL SIMPAN / EDIT / UPDATE DI PO GREIGE ======
    if (window.toastr) {
        toastr.options = {
            positionClass: "toast-top-right",
            closeButton: true,
            progressBar: true,
            timeOut: "3000",
            extendedTimeOut: "1000",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };
    }

    $(document).on('click', '.btn-simpan-row', function(e){
        e.stopPropagation();

        const btn = $(this);
        const row = btn.closest('.row-item');
        const btnTextSpan = btn.find('.btn-text');
        const btnText = btnTextSpan.text().trim();

        const salesorder = row.find('.td-salesorder').text().trim();
        const orderline  = row.find('.td-orderline').text().trim();
        const warna      = row.find('.td-warna').text().trim();
        const benang     = row.find('.td-benang').text().trim();
        const po         = row.find('.td-po').text().trim();

        const picSelect    = row.find('.pic-check');
        const statusSelect = row.find('.status-bonorder');

        // Mode Edit -> buka dropdown -> jadi Update
        if (btnText === 'Edit') {
            if (currentUser && currentUser.toLowerCase() !== 'riyan') {
                if (window.toastr) toastr.warning('Hanya user Riyan yang dapat melakukan edit');
                else alert('Hanya user Riyan yang dapat melakukan edit');
                return;
            }
            picSelect.prop('disabled', false);
            statusSelect.prop('disabled', false);
            btnTextSpan.text('Update');
            return;
        }

        // Mode Simpan/Update
        const pic    = picSelect.val();
        const status = statusSelect.val();
        if (!pic || !status) {
            alert('PIC dan Status Bon Order wajib dipilih!');
            return;
        }

        // Lock saat kirim
        picSelect.prop('disabled', true);
        statusSelect.prop('disabled', true);
        btn.prop('disabled', true).addClass('btn-loading');
        btnTextSpan.html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
        btn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: 'pages/ajax/simpan_status_matching_bonorder.php',
            type: 'POST',
            data: {
                salesorder,
                orderline,
                warna,
                benang,
                ip: ip_user,
                user: currentUser,
                po_greige: po,
                pic_check: pic,
                status_bonorder: status
            },
            success: function(response) {
                btn.removeClass('btn-loading').prop('disabled', false);
                btn.find('.spinner-border').addClass('d-none');
                btnTextSpan.text('Edit');
                picSelect.prop('disabled', true);
                statusSelect.prop('disabled', true);
                if (window.toastr) toastr.success(response || 'Tersimpan');
                else alert(response || 'Tersimpan');
            },
            error: function(xhr, status, error) {
                btn.removeClass('btn-loading').prop('disabled', false);
                btn.find('.spinner-border').addClass('d-none');
                btnTextSpan.text('Update');
                picSelect.prop('disabled', false);
                statusSelect.prop('disabled', false);
                if (window.toastr) toastr.error("Terjadi kesalahan: " + error);
                else alert("Terjadi kesalahan: " + error);
            }
        });
    });
});
</script>


