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
    WHERE status = 'Approved'
";
$rsMy = mysqli_query($con, $sqlTBO);

$approvedRows = [];
// $codes = [];
// if ($rsMy) {
//     while ($r = mysqli_fetch_assoc($rsMy)) {
//         $r['code'] = trim((string)$r['code']);
//         $approvedRows[] = $r;
//         if ($r['code'] !== '') {
//             $codes[] = "'" . mysqli_real_escape_string($con, $r['code']) . "'";
//         }
//     }
// }
$tempMap = [];
$codes = [];
if ($rsMy) {
    while ($r = mysqli_fetch_assoc($rsMy)) {
        $r['code'] = trim((string)$r['code']);
        $code = $r['code'];

        if ($code === '') continue;

        // cek apakah sudah ada code ini
        if (!isset($tempMap[$code])) {
            $tempMap[$code] = $r;
        } else {
            if ((int)$r['is_revision'] === 1 && (int)$existing['is_revision'] === 0) {
                // utamakan revisi
                $tempMap[$code] = $r;
            } elseif ((int)$r['is_revision'] === (int)$existing['is_revision']) {
                // kalau sama-sama revisi / sama-sama bukan revisi → pilih yang tanggal lab terbaru
                if (strtotime($r['tgl_approve_lab']) > strtotime($existing['tgl_approve_lab'])) {
                    $tempMap[$code] = $r;
                }
            }
        }
    }

    // hasil akhir, sudah unik per code
    $approvedRows = array_values($tempMap);

    // buat list untuk query DB2
    foreach ($approvedRows as $r) {
        $codes[] = "'" . mysqli_real_escape_string($con, $r['code']) . "'";
    }
}

/* 2) Peta revisi dari DB2 utk code yang ada */
$revMap = [];
if (!empty($codes)) {
    $codeList = implode(",", $codes);
    $sqlDB2 = "WITH base AS (
                    SELECT
                        TRIM(isa.CODE)                     AS \"CODE\",
                        ip.LANGGANAN || ip.BUYER           AS \"CUSTOMER\",
                        isa.TGL_APPROVEDRMP                AS \"TGL_APPROVE_RMP\",

                        /* --- Grup RevisiC/Revisi2/... dari ad*.OPTIONS --- */
                        CASE WHEN aC.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || aC.VALUESTRING || '=')
                            THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || aC.VALUESTRING || '=([^;]*)',1,1,'',1) END AS \"RevisiC\",
                        CASE WHEN a2.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a2.VALUESTRING || '=')
                            THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a2.VALUESTRING || '=([^;]*)',1,1,'',1) END AS \"Revisi2\",
                        CASE WHEN a3.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a3.VALUESTRING || '=')
                            THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a3.VALUESTRING || '=([^;]*)',1,1,'',1) END AS \"Revisi3\",
                        CASE WHEN a4.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a4.VALUESTRING || '=')
                            THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a4.VALUESTRING || '=([^;]*)',1,1,'',1) END AS \"Revisi4\",
                        CASE WHEN a5.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
                            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a5.VALUESTRING || '=')
                            THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a5.VALUESTRING || '=([^;]*)',1,1,'',1) END AS \"Revisi5\",


                        /* --- Grup RevisiN/DRevisi* langsung VALUESTRING --- */
                        n1.VALUESTRING AS \"RevisiN\",
                        n2.VALUESTRING AS \"DRevisi2\",
                        n3.VALUESTRING AS \"DRevisi3\",
                        n4.VALUESTRING AS \"DRevisi4\",
                        n5.VALUESTRING AS \"DRevisi5\",

                        /* --- Grup Tanggal Revisi* --- */
                        dt1.VALUEDATE AS \"Revisi1Date\",
                        dt2.VALUEDATE AS \"Revisi2Date\",
                        dt3.VALUEDATE AS \"Revisi3Date\",
                        dt4.VALUEDATE AS \"Revisi4Date\",
                        dt5.VALUEDATE AS \"Revisi5Date\"

                    FROM ITXVIEW_SALESORDER_APPROVED isa
                    LEFT JOIN SALESORDER s ON s.CODE = isa.CODE

                    LEFT JOIN ADSTORAGE aC  ON aC.UNIQUEID = s.ABSUNIQUEID AND aC.FIELDNAME = 'RevisiC'
                    LEFT JOIN ADADDITIONALDATA adC ON adC.NAME = aC.FIELDNAME
                    LEFT JOIN ADSTORAGE a2  ON a2.UNIQUEID = s.ABSUNIQUEID AND a2.FIELDNAME = 'Revisi2'
                    LEFT JOIN ADADDITIONALDATA ad2 ON ad2.NAME = a2.FIELDNAME
                    LEFT JOIN ADSTORAGE a3  ON a3.UNIQUEID = s.ABSUNIQUEID AND a3.FIELDNAME = 'Revisi3'
                    LEFT JOIN ADADDITIONALDATA ad3 ON ad3.NAME = a3.FIELDNAME
                    LEFT JOIN ADSTORAGE a4  ON a4.UNIQUEID = s.ABSUNIQUEID AND a4.FIELDNAME = 'Revisi4'
                    LEFT JOIN ADADDITIONALDATA ad4 ON ad4.NAME = a4.FIELDNAME
                    LEFT JOIN ADSTORAGE a5  ON a5.UNIQUEID = s.ABSUNIQUEID AND a5.FIELDNAME = 'Revisi5'
                    LEFT JOIN ADADDITIONALDATA ad5 ON ad5.NAME = a5.FIELDNAME

                    /* nilai detail */
                    LEFT JOIN ADSTORAGE n1 ON n1.UNIQUEID = s.ABSUNIQUEID AND n1.FIELDNAME = 'RevisiN'
                    LEFT JOIN ADSTORAGE n2 ON n2.UNIQUEID = s.ABSUNIQUEID AND n2.FIELDNAME = 'DRevisi2'
                    LEFT JOIN ADSTORAGE n3 ON n3.UNIQUEID = s.ABSUNIQUEID AND n3.FIELDNAME = 'DRevisi3'
                    LEFT JOIN ADSTORAGE n4 ON n4.UNIQUEID = s.ABSUNIQUEID AND n4.FIELDNAME = 'DRevisi4'
                    LEFT JOIN ADSTORAGE n5 ON n5.UNIQUEID = s.ABSUNIQUEID AND n5.FIELDNAME = 'DRevisi5'

                    /* tanggal detail */
                    LEFT JOIN ADSTORAGE dt1 ON dt1.UNIQUEID = s.ABSUNIQUEID AND dt1.FIELDNAME = 'Revisi1Date'
                    LEFT JOIN ADSTORAGE dt2 ON dt2.UNIQUEID = s.ABSUNIQUEID AND dt2.FIELDNAME = 'Revisi2Date'
                    LEFT JOIN ADSTORAGE dt3 ON dt3.UNIQUEID = s.ABSUNIQUEID AND dt3.FIELDNAME = 'Revisi3Date'
                    LEFT JOIN ADSTORAGE dt4 ON dt4.UNIQUEID = s.ABSUNIQUEID AND dt4.FIELDNAME = 'Revisi4Date'
                    LEFT JOIN ADSTORAGE dt5 ON dt5.UNIQUEID = s.ABSUNIQUEID AND dt5.FIELDNAME = 'Revisi5Date'

                    LEFT JOIN ITXVIEW_PELANGGAN ip
                        ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
                    AND ip.CODE = s.CODE

                    WHERE isa.APPROVEDRMP IS NOT NULL AND isa.TGL_APPROVEDRMP IS NOT NULL
                    AND DATE(s.CREATIONDATETIME) > DATE('2025-06-01')
                    AND TRIM(isa.CODE) IN ($codeList)
                ),
                ranked AS (
                    SELECT b.*,
                        ROW_NUMBER() OVER (
                            PARTITION BY b.\"CODE\"
                            ORDER BY (b.\"TGL_APPROVE_RMP\" IS NULL) ASC, b.\"TGL_APPROVE_RMP\" DESC
                        ) AS rn
                    FROM base b
                )
                SELECT
                    \"CODE\",
                    \"CUSTOMER\",
                    \"TGL_APPROVE_RMP\",
                    \"RevisiC\",\"Revisi2\",\"Revisi3\",\"Revisi4\",\"Revisi5\",
                    \"RevisiN\",\"DRevisi2\",\"DRevisi3\",\"DRevisi4\",\"DRevisi5\",
                    \"Revisi1Date\",\"Revisi2Date\",\"Revisi3Date\",\"Revisi4Date\",\"Revisi5Date\",
                    COALESCE(
                        NULLIF(TRIM(\"DRevisi5\"), ''),
                        NULLIF(TRIM(\"DRevisi4\"), ''),
                        NULLIF(TRIM(\"DRevisi3\"), ''),
                        NULLIF(TRIM(\"DRevisi2\"), ''),
                        NULLIF(TRIM(\"RevisiN\"),  '')
                    ) AS \"REVISIN_LAST\",
                    COALESCE(
                        NULLIF(TRIM(\"Revisi5\"), ''),
                        NULLIF(TRIM(\"Revisi4\"), ''),
                        NULLIF(TRIM(\"Revisi3\"), ''),
                        NULLIF(TRIM(\"Revisi2\"), ''),
                        NULLIF(TRIM(\"RevisiC\"), '')
                    ) AS \"REVISIC_LAST\"
                FROM ranked
                WHERE rn = 1
                ";

    $resDB2 = db2_exec($conn1, $sqlDB2, ['cursor' => DB2_SCROLLABLE]);
    if ($resDB2) {
        while ($r = db2_fetch_assoc($resDB2)) {
            $codeKey = trim((string)$r['CODE']);
            $revMap[$codeKey] = [
                'REVISIN_LAST' => trim((string)($r['REVISIN_LAST'] ?? '')),
                'REVISIC_LAST' => trim((string)($r['REVISIC_LAST'] ?? '')),
                'REVISIC'  => $r['REVISIC']  ?? ($r['RevisiC'] ?? ''),
                'REVISI2'  => $r['REVISI2']  ?? ($r['Revisi2'] ?? ''),
                'REVISI3'  => $r['REVISI3']  ?? ($r['Revisi3'] ?? ''),
                'REVISI4'  => $r['REVISI4']  ?? ($r['Revisi4'] ?? ''),
                'REVISI5'  => $r['REVISI5']  ?? ($r['Revisi5'] ?? ''),
                'REVISIN'  => $r['REVISIN']  ?? ($r['RevisiN'] ?? ''),
                'DREVISI2' => $r['DREVISI2'] ?? ($r['DRevisi2'] ?? ''),
                'DREVISI3' => $r['DREVISI3'] ?? ($r['DRevisi3'] ?? ''),
                'DREVISI4' => $r['DREVISI4'] ?? ($r['DRevisi4'] ?? ''),
                'DREVISI5' => $r['DREVISI5'] ?? ($r['DRevisi5'] ?? ''),
                'REVISI1DATE'   => $r['REVISI1DATE'] ?? ($r['Revisi1Date'] ?? ''),
                'REVISI2DATE'   => $r['REVISI2DATE'] ?? ($r['Revisi2Date'] ?? ''),
                'REVISI3DATE'   => $r['REVISI3DATE'] ?? ($r['Revisi3Date'] ?? ''),
                'REVISI4DATE'   => $r['REVISI4DATE'] ?? ($r['Revisi4Date'] ?? ''),
                'REVISI5DATE'   => $r['REVISI5DATE'] ?? ($r['Revisi5Date'] ?? ''),
            ];
        }
    }
}
?>

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
                        <?php foreach ($approvedRows as $rowTBO):
                            $code       = $rowTBO['code'];
                            $isRevision = (int)$rowTBO['is_revision'] === 1;
                            $rev        = $revMap[$code] ?? [];
                            $reviN      = ($rev['REVISIN_LAST'] ?? '') !== '' ? $rev['REVISIN_LAST'] : '-';
                            $reviC      = ($rev['REVISIC_LAST'] ?? '') !== '' ? $rev['REVISIC_LAST'] : '-';

                            // data-* buat modal
                            $dataAttrs = sprintf(
                                'data-revisic="%s" data-revisi2="%s" data-revisi3="%s" data-revisi4="%s" data-revisi5="%s" ' .
                                'data-revisin="%s" data-drevisi2="%s" data-drevisi3="%s" data-drevisi4="%s" data-drevisi5="%s" ' .
                                'data-revisi1date="%s" data-revisi2date="%s" data-revisi3date="%s" data-revisi4date="%s" data-revisi5date="%s"',
                                htmlspecialchars((string)($rev['REVISIC']  ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['REVISI2']  ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['REVISI3']  ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['REVISI4']  ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['REVISI5']  ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['REVISIN']  ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['DREVISI2'] ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['DREVISI3'] ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['DREVISI4'] ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['DREVISI5'] ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['REVISI1DATE'] ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['REVISI2DATE'] ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['REVISI3DATE'] ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['REVISI4DATE'] ?? ''), ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars((string)($rev['REVISI5DATE'] ?? ''), ENT_QUOTES, 'UTF-8')
                            );
                        ?>
                            <tr class="<?= $isRevision ? 'has-revisi' : '' ?>"
                                data-order="<?= htmlspecialchars($code) ?>"
                                data-revision="<?= $isRevision ? '1' : '0' ?>"
                                data-revin-last="<?= htmlspecialchars($reviN) ?>"
                                data-revic-last="<?= htmlspecialchars($reviC) ?>"
                                <?= $dataAttrs ?>
                            >
                                <td class="cell-order" data-code="<?= htmlspecialchars($code) ?>">
                                    <?= htmlspecialchars($code) ?>
                                </td>
                                <td class="cell-customer" data-cust="<?= htmlspecialchars($rowTBO['customer']) ?>">
                                    <?= htmlspecialchars($rowTBO['customer']) ?>
                                </td>
                                <!-- <td><?= htmlspecialchars($rowTBO['tgl_approve_rmp']) ?></td> -->
                                 <td><?= !empty($rowTBO['approvalrmpdatetime']) ? date('Y-m-d', strtotime($rowTBO['approvalrmpdatetime'])) : htmlspecialchars($rowTBO['tgl_approve_rmp']) ?></td>
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
    const table = $('#tboTable').DataTable({
        pageLength: 10,
        lengthMenu: [10,25,50,100],
        order: [],
        autoWidth: false
    });

    // Render Nomer Bon Order cell (kolom 1) dgn revisi + tombol (hanya utk is_revision=1)
    function renderOrderCell(tr) {
        const $tr = $(tr);
        const isRev = $tr.data('revision');
        const $td = $('td.cell-order', $tr);
        const code = $td.data('code') || $td.text();

        if (isRev === 1 || isRev === '1') {
            const revin = $tr.data('revinLast') || '-';
            const revic = $tr.data('revicLast') || '-';

            // ⬇️ ambil semua atribut data-* langsung dari DOM
            const trEl = $tr.get(0);
            const dataAttrs = Array.from(trEl.attributes)
            .filter(a => a.name.startsWith('data-'))
            .map(a => `${a.name}="${String(a.value).replace(/"/g,'&quot;')}"`)
            .join(' ');

            const html = `
            <div>
                <div>${$('<div>').text(code).html()}</div>
                <div class="rev-wrap" style="background:#ffecec;">
                <div class="rev-left" style="margin-left:4px;">
                    <span class="rev-muted">${$('<div>').text(revin).html()}</span>
                    <span class="rev-muted">${$('<div>').text(revic).html()}</span>
                </div>
                <button type="button" class="btn btn-outline-purple btn-xs revisi-btn push-right" ${dataAttrs} style="margin-right:4px;">
                    Detail Revisi
                </button>
                </div>
            </div>`;
            $td.html(html);
        } else {
            $td.text(code);
        }
    }

    function renderAllVisible() {
        table.rows({ page: 'current' }).every(function () {
            renderOrderCell(this.node());
        });
    }
    $('#tboTable').on('draw.dt', renderAllVisible);
    renderAllVisible();

    // Klik tombol Detail Revisi -> isi tabel seperti screenshot
    $(document).on('click', '.revisi-btn', function(e){
        e.stopPropagation();
        const d = this.dataset;

        // helper: paksa ke string lalu trim
        const norm = (v) => (v === undefined || v === null) ? '' : String(v).trim();
        
        const pairs = [
            { cat: (d.revisic || '').trim(),  det: (d.revisin || '').trim(),  dt: (d.revisi1date || '').trim() },
            { cat: (d.revisi2 || '').trim(),  det: (d.drevisi2 || '').trim(), dt: (d.revisi2date || '').trim() },
            { cat: (d.revisi3 || '').trim(),  det: (d.drevisi3 || '').trim(), dt: (d.revisi3date || '').trim() },
            { cat: (d.revisi4 || '').trim(),  det: (d.drevisi4 || '').trim(), dt: (d.revisi4date || '').trim() },
            { cat: (d.revisi5 || '').trim(),  det: (d.drevisi5 || '').trim(), dt: (d.revisi5date || '').trim() },
        ];


        const rows = pairs
            .filter(p => p.det !== '')
            .map(p => `
            <tr>
                <td>${$('<div>').text(p.cat || '-').html()}</td>
                <td>${$('<div>').text(p.det || '-').html()}</td>
                <td>${$('<div>').text(p.dt  || '').html()}</td>
            </tr>
            `);

        $('#tbl-revisi-body').html(rows.length ? rows.join('') : `
            <tr><td colspan="3" class="text-center text-muted">Tidak ada detail revisi yang terisi.</td></tr>
        `);

        $('#modalRevisi').modal('show');
    });

    $('#tboTable tbody').on('click', '> tr:not(.greige-row)', function (e) {
        // Abaikan klik pada tombol / input
        if ($(e.target).closest('.revisi-btn, .detail-wrap, .close-greige-row').length) return;
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
            },
            error: function () {
                loadingRow.html('<td colspan="' + colspan + '">Gagal mengambil data PO Greige.</td>');
            }
        });
    });

    $(document).on('click', '.greige-row, .greige-row *', function (e) {
        e.stopPropagation();
    });

    $(document).on('click', '.close-greige-row', function (e) {
        e.stopPropagation();
        const greigeRow = $(this).closest('tr.greige-row');
        const mainRow = greigeRow.prev('tr');
        greigeRow.remove();
        mainRow.removeClass('expanded');
    });
});
</script>
