<?php
include "koneksi.php";
require_once __DIR__."/lib/revisi_compare.php";

// $sqlApproved = "SELECT * FROM approval_bon_order WHERE is_revision = 1 ORDER BY id DESC";
$sqlApproved = "SELECT a.*
                FROM approval_bon_order a
                JOIN (
                SELECT code, MAX(id) AS max_id
                FROM approval_bon_order
                WHERE is_revision = 1
                GROUP BY code
                ) m ON m.max_id = a.id
                WHERE a.is_revision = 1
                ORDER BY a.id DESC";

$resultApproved = mysqli_query($con, $sqlApproved);

// Snapshot terakhir per code
$lastMySQLByCode = [];
if ($resultApproved) {
    mysqli_data_seek($resultApproved, 0);
    while ($r = mysqli_fetch_assoc($resultApproved)) {
        $codeKey = strtoupper(trim($r['code']));
        if (!isset($lastMySQLByCode[$codeKey])) {
            $lastMySQLByCode[$codeKey] = $r; // baris terbaru utk code tsb
        }
    }
    mysqli_data_seek($resultApproved, 0);
}

/* === Ambil data DB2 utk tabel "Siap Approval" (tanpa NOT IN) === */
$sqlTBO = "
WITH base AS (
    SELECT
        isa.CODE                                AS CODE,
        ip.LANGGANAN || ip.BUYER                AS CUSTOMER,
        isa.TGL_APPROVEDRMP                     AS TGL_APPROVE_RMP,

        CASE WHEN aC.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || aC.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || aC.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC,
        CASE WHEN a2.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a2.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a2.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi2,
        CASE WHEN a3.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a3.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a3.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi3,
        CASE WHEN a4.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a4.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a4.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi4,
        CASE WHEN a5.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a5.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a5.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi5,

        n1.VALUESTRING AS RevisiN,
        n2.VALUESTRING AS DRevisi2,
        n3.VALUESTRING AS DRevisi3,
        n4.VALUESTRING AS DRevisi4,
        n5.VALUESTRING AS DRevisi5,

        dt1.VALUEDATE AS Revisi1Date,
        dt2.VALUEDATE AS Revisi2Date,
        dt3.VALUEDATE AS Revisi3Date,
        dt4.VALUEDATE AS Revisi4Date,
        dt5.VALUEDATE AS Revisi5Date

    FROM ITXVIEW_SALESORDER_APPROVED isa
    LEFT JOIN SALESORDER s ON s.CODE = isa.CODE

    LEFT JOIN ADSTORAGE aC ON aC.UNIQUEID = s.ABSUNIQUEID AND aC.FIELDNAME = 'RevisiC'
    LEFT JOIN ADADDITIONALDATA adC ON adC.NAME = aC.FIELDNAME
    LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = s.ABSUNIQUEID AND a2.FIELDNAME = 'Revisi2'
    LEFT JOIN ADADDITIONALDATA ad2 ON ad2.NAME = a2.FIELDNAME
    LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = s.ABSUNIQUEID AND a3.FIELDNAME = 'Revisi3'
    LEFT JOIN ADADDITIONALDATA ad3 ON ad3.NAME = a3.FIELDNAME
    LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s.ABSUNIQUEID AND a4.FIELDNAME = 'Revisi4'
    LEFT JOIN ADADDITIONALDATA ad4 ON ad4.NAME = a4.FIELDNAME
    LEFT JOIN ADSTORAGE a5 ON a5.UNIQUEID = s.ABSUNIQUEID AND a5.FIELDNAME = 'Revisi5'
    LEFT JOIN ADADDITIONALDATA ad5 ON ad5.NAME = a5.FIELDNAME

    LEFT JOIN ADSTORAGE n1 ON n1.UNIQUEID = s.ABSUNIQUEID AND n1.FIELDNAME = 'RevisiN'
    LEFT JOIN ADSTORAGE n2 ON n2.UNIQUEID = s.ABSUNIQUEID AND n2.FIELDNAME = 'DRevisi2'
    LEFT JOIN ADSTORAGE n3 ON n3.UNIQUEID = s.ABSUNIQUEID AND n3.FIELDNAME = 'DRevisi3'
    LEFT JOIN ADSTORAGE n4 ON n4.UNIQUEID = s.ABSUNIQUEID AND n4.FIELDNAME = 'DRevisi4'
    LEFT JOIN ADSTORAGE n5 ON n5.UNIQUEID = s.ABSUNIQUEID AND n5.FIELDNAME = 'DRevisi5'

    LEFT JOIN ADSTORAGE dt1 ON dt1.UNIQUEID = s.ABSUNIQUEID AND dt1.FIELDNAME = 'Revisi1Date'
    LEFT JOIN ADSTORAGE dt2 ON dt2.UNIQUEID = s.ABSUNIQUEID AND dt2.FIELDNAME = 'Revisi2Date'
    LEFT JOIN ADSTORAGE dt3 ON dt3.UNIQUEID = s.ABSUNIQUEID AND dt3.FIELDNAME = 'Revisi3Date'
    LEFT JOIN ADSTORAGE dt4 ON dt4.UNIQUEID = s.ABSUNIQUEID AND dt4.FIELDNAME = 'Revisi4Date'
    LEFT JOIN ADSTORAGE dt5 ON dt5.UNIQUEID = s.ABSUNIQUEID AND dt5.FIELDNAME = 'Revisi5Date'

    LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s.CODE

    WHERE isa.APPROVEDRMP IS NOT NULL
      AND DATE(s.CREATIONDATETIME) > DATE('2025-06-01')
),
ranked AS (
    SELECT b.*, ROW_NUMBER() OVER (PARTITION BY b.CODE ORDER BY (b.TGL_APPROVE_RMP IS NULL) ASC, b.TGL_APPROVE_RMP DESC) AS rn
    FROM base b
)
SELECT
    CODE, CUSTOMER, TGL_APPROVE_RMP,
    RevisiC, Revisi2, Revisi3, Revisi4, Revisi5,
    RevisiN, DRevisi2, DRevisi3, DRevisi4, DRevisi5,
    Revisi1Date, Revisi2Date, Revisi3Date, Revisi4Date, Revisi5Date,
    COALESCE(NULLIF(TRIM(DRevisi5),''),NULLIF(TRIM(DRevisi4),''),NULLIF(TRIM(DRevisi3),''),NULLIF(TRIM(DRevisi2),''),NULLIF(TRIM(RevisiN),'')) AS REVISIN_LAST,
    COALESCE(NULLIF(TRIM(Revisi5),''),NULLIF(TRIM(Revisi4),''),NULLIF(TRIM(Revisi3),''),NULLIF(TRIM(Revisi2),''),NULLIF(TRIM(RevisiC),'')) AS REVISIC_LAST
FROM ranked
WHERE rn = 1
  AND COALESCE(NULLIF(TRIM(RevisiC),''),NULLIF(TRIM(Revisi2),''),NULLIF(TRIM(Revisi3),''),NULLIF(TRIM(Revisi4),''),NULLIF(TRIM(Revisi5),'')) IS NOT NULL
  AND COALESCE(NULLIF(TRIM(RevisiN),''),NULLIF(TRIM(DRevisi2),''),NULLIF(TRIM(DRevisi3),''),NULLIF(TRIM(DRevisi4),''),NULLIF(TRIM(DRevisi5),'')) IS NOT NULL
";
$resultTBO = db2_exec($conn1, $sqlTBO, ['cursor' => DB2_SCROLLABLE]);

// Filter hanya yang baru/berubah
$tboRows = [];
while ($row = db2_fetch_assoc($resultTBO)) {
    $code = strtoupper(trim($row['CODE']));
    $snap = $lastMySQLByCode[$code] ?? null;
    if ($snap === null || revisionsDiffer($row, $snap)) $tboRows[] = $row;
}

?>
<style>
.modal-full { width:98%; max-width:98%; }
.btn-outline-purple { background:transparent; color:#6f42c1; border:1px solid #6f42c1; }
.btn-outline-purple:hover,.btn-outline-purple:focus { background:#6f42c1; color:#fff; }
</style>

<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <!-- ✅ TABEL 1: Data Siap Approval -->
        <div class="card mb-4">
          <div class="card-header text-white"><h3 class="card-title">Data Siap Approval</h3></div>
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
              <?php foreach ($tboRows as $row):
                  $code = strtoupper(trim($row['CODE']));
                  $customer = trim($row['CUSTOMER']);
                  $tgl = trim($row['TGL_APPROVE_RMP']);
              ?>
                <tr>
                  <td style="padding:4px 8px;">
                    <div style="margin-bottom:2px; word-break:break-word;"><?= htmlspecialchars($customer) ?></div>
                    <div style="display:flex; align-items:center; font-weight:700;">
                      <span style="flex:1 1 auto; min-width:0; word-break:break-word;"><?= htmlspecialchars($row['REVISIN_LAST']) ?></span>
                      <span style="flex:0 0 auto; margin-left:auto;"><?= htmlspecialchars($row['REVISIC_LAST']) ?></span>
                    </div>
                  </td>
                  <td>
                    <a href="#" class="btn btn-primary btn-sm open-detail" data-code="<?= $code ?>" data-toggle="modal" data-target="#detailModal"><?= $code ?></a>
                  </td>
                  <td><?= htmlspecialchars($tgl) ?></td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <select class="form-control form-control-sm pic-select" data-code="<?= $code ?>">
                        <option value="">-- Pilih PIC --</option>
                        <?php $resultPIC = mysqli_query($con, "SELECT * FROM tbl_user WHERE pic_bonorder = 1 ORDER BY id ASC");
                        while ($rowPIC = mysqli_fetch_assoc($resultPIC)) : ?>
                          <option value="<?= htmlspecialchars($rowPIC['username']) ?>"><?= htmlspecialchars($rowPIC['username']) ?></option>
                        <?php endwhile; ?>
                      </select>

                      <button class="btn btn-success btn-sm approve-btn" data-code="<?= $code ?>">Approve</button>
                      <button class="btn btn-danger btn-sm reject-btn"  data-code="<?= $code ?>">Reject</button>

                      <button
                        class="btn btn-outline-purple btn-sm revisi-btn"
                        data-code="<?= $code ?>"
                        data-revisic="<?= htmlspecialchars($row['REVISIC']  ?? '', ENT_QUOTES) ?>"
                        data-revisi2="<?= htmlspecialchars($row['REVISI2']  ?? '', ENT_QUOTES) ?>"
                        data-revisi3="<?= htmlspecialchars($row['REVISI3']  ?? '', ENT_QUOTES) ?>"
                        data-revisi4="<?= htmlspecialchars($row['REVISI4']  ?? '', ENT_QUOTES) ?>"
                        data-revisi5="<?= htmlspecialchars($row['REVISI5']  ?? '', ENT_QUOTES) ?>"
                        data-revisin="<?= htmlspecialchars($row['REVISIN']  ?? '', ENT_QUOTES) ?>"
                        data-drevisi2="<?= htmlspecialchars($row['DREVISI2'] ?? '', ENT_QUOTES) ?>"
                        data-drevisi3="<?= htmlspecialchars($row['DREVISI3'] ?? '', ENT_QUOTES) ?>"
                        data-drevisi4="<?= htmlspecialchars($row['DREVISI4'] ?? '', ENT_QUOTES) ?>"
                        data-drevisi5="<?= htmlspecialchars($row['DREVISI5'] ?? '', ENT_QUOTES) ?>"
                        data-revisi1date="<?= htmlspecialchars($row['REVISI1DATE'] ?? '', ENT_QUOTES) ?>"
                        data-revisi2date="<?= htmlspecialchars($row['REVISI2DATE'] ?? '', ENT_QUOTES) ?>"
                        data-revisi3date="<?= htmlspecialchars($row['REVISI3DATE'] ?? '', ENT_QUOTES) ?>"
                        data-revisi4date="<?= htmlspecialchars($row['REVISI4DATE'] ?? '', ENT_QUOTES) ?>"
                        data-revisi5date="<?= htmlspecialchars($row['REVISI5DATE'] ?? '', ENT_QUOTES) ?>">
                        Detail Revisi
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>  
      </div>
    </div>

    <div class="box">
      <div class="box-body">
        <div class="card">
          <div class="card-header text-white"><h3 class="card-title">Tabel Approval Revisi Bon Order</h3></div>
          <div class="card-body">
            <table class="table table-bordered table-sm" id="approvedTable">
              <thead class="bg-success text-white">
                <tr>
                  <th style="display:none;">ID</th>
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
              <?php while ($row = mysqli_fetch_assoc($resultApproved)):
                    $codeApp = strtoupper(trim($row['code']));
                    $reviN_last = first_non_empty([$row['drevisi5'],$row['drevisi4'],$row['drevisi3'],$row['drevisi2'],$row['revisin']]);
                    $reviC_last = first_non_empty([$row['revisi5'],$row['revisi4'],$row['revisi3'],$row['revisi2'],$row['revisic']]);
              ?>
                <tr>
                  <td style="display:none;"><?= (int)$row['id'] ?></td>
                  <td>
                    <div style="margin-bottom:2px; word-break:break-word;"><?= htmlspecialchars($row['customer']) ?></div>
                    <div style="display:flex; align-items:center; font-weight:700;">
                      <span style="flex:1 1 auto; min-width:0; word-break:break-word;"><?= htmlspecialchars($reviN_last) ?></span>
                      <span style="flex:0 0 auto; margin-left:auto;"><?= htmlspecialchars($reviC_last) ?></span>
                    </div>
                  </td>
                  <td>
                    <a href="#" class="btn btn-primary btn-sm open-detail" data-code="<?= htmlspecialchars($row['code']) ?>" data-toggle="modal" data-target="#detailModal"><?= htmlspecialchars($row['code']) ?></a>
                  </td>
                  <td><?= htmlspecialchars($row['tgl_approve_rmp']) ?></td>
                  <td><?= htmlspecialchars($row['tgl_approve_lab']) ?></td>
                  <td><?= htmlspecialchars($row['tgl_rejected_lab']) ?></td>
                  <td><?= htmlspecialchars($row['pic_lab']) ?></td>
                  <td>
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                      <strong class="<?= ($row['status']==='Approved'?'text-success':'text-danger') ?>"><?= htmlspecialchars($row['status']) ?></strong>
                      <button class="btn btn-outline-purple btn-sm revisi-btn"
                        data-code="<?= $codeApp ?>"
                        data-revisic="<?= htmlspecialchars($row['revisic']  ?? '', ENT_QUOTES) ?>"
                        data-revisi2="<?= htmlspecialchars($row['revisi2']  ?? '', ENT_QUOTES) ?>"
                        data-revisi3="<?= htmlspecialchars($row['revisi3']  ?? '', ENT_QUOTES) ?>"
                        data-revisi4="<?= htmlspecialchars($row['revisi4']  ?? '', ENT_QUOTES) ?>"
                        data-revisi5="<?= htmlspecialchars($row['revisi5']  ?? '', ENT_QUOTES) ?>"
                        data-revisin="<?= htmlspecialchars($row['revisin']  ?? '', ENT_QUOTES) ?>"
                        data-drevisi2="<?= htmlspecialchars($row['drevisi2'] ?? '', ENT_QUOTES) ?>"
                        data-drevisi3="<?= htmlspecialchars($row['drevisi3'] ?? '', ENT_QUOTES) ?>"
                        data-drevisi4="<?= htmlspecialchars($row['drevisi4'] ?? '', ENT_QUOTES) ?>"
                        data-drevisi5="<?= htmlspecialchars($row['drevisi5'] ?? '', ENT_QUOTES) ?>"
                        data-revisi1date="<?= htmlspecialchars($row['revisi1date'] ?? '', ENT_QUOTES) ?>"
                        data-revisi2date="<?= htmlspecialchars($row['revisi2date'] ?? '', ENT_QUOTES) ?>"
                        data-revisi3date="<?= htmlspecialchars($row['revisi3date'] ?? '', ENT_QUOTES) ?>"
                        data-revisi4date="<?= htmlspecialchars($row['revisi4date'] ?? '', ENT_QUOTES) ?>"
                        data-revisi5date="<?= htmlspecialchars($row['revisi5date'] ?? '', ENT_QUOTES) ?>">
                        Detail Revisi
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
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
      <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Detail Order</h4></div>
      <div class="modal-body" id="modal-content"><p>Loading data...</p></div>
      <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button></div>
    </div>
  </div>
</div>

<div id="revisiModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Detail Revisi</h4></div>
      <div class="modal-body">
        <table class="table table-bordered table-striped table-condensed" id="revisionTable">
          <thead><tr><th>Revisi Category</th><th>Detail Revisi</th><th style="width:140px;">Tanggal Revisi</th></tr></thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button></div>
    </div>
  </div>
</div>

<script>
$(document).on('click', '.open-detail', function() {
  var code = $(this).data('code');
  $('#modal-content').html('<p>Loading data...</p>');
  $.ajax({
    url: 'pages/ajax/Approved_get_order_detail_revision.php',
    type: 'POST',
    data: { code: code },
    success: function(response) {
      $('#modal-content').html(response);
      if ($.fn.DataTable.isDataTable('#detailApprovedTable')) $('#detailApprovedTable').DataTable().destroy();
      $('#detailApprovedTable').DataTable({ paging:true, searching:true, ordering:true, order:[[0,'asc']] });
    },
    error: function() { $('#modal-content').html('<p class="text-danger">Gagal memuat data.</p>'); }
  });
});

$(document).ready(function(){
  const tboTable = $('#tboTable').DataTable();
  const approvedTable = $('#approvedTable').DataTable({ "order":[[0,"desc"]], "columnDefs":[{ "targets":0, "visible":false }] });

  function getPIC(code){ return $("select.pic-select[data-code='"+code+"']").val(); }
  function getCustomer(code){ return $("tr:has(button[data-code='"+code+"']) td:first").text(); }
  function getTglApproveRMP(code){ return $("tr:has(button[data-code='"+code+"']) td:eq(2)").text(); }

  function submitApproval(code, action){
    const pic = getPIC(code);
    const customer = getCustomer(code);
    const tgl_approve_rmp = getTglApproveRMP(code);
    const buttons = $("button[data-code='"+code+"']");
    const $revBtn = $("button.revisi-btn[data-code='"+code+"']");

    const revisiPayload = {
      revisic:     $revBtn.data('revisic')     || '',
      revisi2:     $revBtn.data('revisi2')     || '',
      revisi3:     $revBtn.data('revisi3')     || '',
      revisi4:     $revBtn.data('revisi4')     || '',
      revisi5:     $revBtn.data('revisi5')     || '',
      revisin:     $revBtn.data('revisin')     || '',
      drevisi2:    $revBtn.data('drevisi2')    || '',
      drevisi3:    $revBtn.data('drevisi3')    || '',
      drevisi4:    $revBtn.data('drevisi4')    || '',
      drevisi5:    $revBtn.data('drevisi5')    || '',
      revisi1date: $revBtn.data('revisi1date') || '',
      revisi2date: $revBtn.data('revisi2date') || '',
      revisi3date: $revBtn.data('revisi3date') || '',
      revisi4date: $revBtn.data('revisi4date') || '',
      revisi5date: $revBtn.data('revisi5date') || ''
    };

    buttons.prop('disabled', true);
    if(!pic){
      Swal.fire({icon:'warning', title:'PIC belum dipilih', text:'Silakan pilih PIC Lab terlebih dahulu.'});
      buttons.prop('disabled', false);
      return;
    }

    Swal.fire({title:`${action} Bon Order?`, text:`Kode: ${code} | PIC: ${pic}`, icon:'question', showCancelButton:true, confirmButtonText:action, cancelButtonText:'Batal'})
      .then((result)=>{
        if(!result.isConfirmed){ buttons.prop('disabled', false); return; }
        Swal.fire({title:'Memproses...', text:'Mohon tunggu sebentar.', didOpen:()=>Swal.showLoading(), allowOutsideClick:false});
        $.post("pages/ajax/approve_bon_order_lab.php", {
          code, customer, tgl_approve_rmp, pic_lab:pic, status:action, is_revision:1, ...revisiPayload
        }, function(resp){
          Swal.fire({icon:'success', title:'Berhasil', text:resp});
          reloadApprovedTable(1);
          reloadTboTable();
          refreshTBOCount();
          refreshTBORCount();
        }).fail(function(){
          Swal.fire({icon:'error', title:'Gagal', text:'Terjadi kesalahan saat menyimpan data.'});
          buttons.prop('disabled', false);
        });
      });
  }

  function reloadTboTable() {
    $.get("pages/ajax/refresh_tbo_table_revisi.php", function (html) {
        const $rows = $($.parseHTML(html)).filter('tr');
        tboTable.clear();
        if ($rows.length) tboTable.rows.add($rows);
        tboTable.draw(false);
    });
  }

  function reloadApprovedTable(isRevision = 1) {
    $.get("pages/ajax/refresh_approved_table_revisi.php", { is_revision: isRevision }, function (html) {
        const $rows = $($.parseHTML(html)).filter('tr');
        approvedTable.clear();
        if ($rows.length) approvedTable.rows.add($rows);
        approvedTable.draw(false);
    });
  }

  $('#tboTable tbody').on('click', '.approve-btn', function(){ submitApproval($(this).data('code'), 'Approved'); });
  $('#tboTable tbody').on('click', '.reject-btn',  function(){ submitApproval($(this).data('code'), 'Rejected'); });

  let tboCount=0, tboRevisiCount=0;
  function toInt(x){ try{ if(typeof x==='string' && x.trim().startsWith('{')){const o=JSON.parse(x);for(const k in o){if(Object.hasOwn(o,k)&&!isNaN(parseInt(o[k],10)))return parseInt(o[k],10);}} }catch(e){} const n=parseInt(String(x).replace(/[^\d-]/g,''),10); return isNaN(n)?0:n;}
  function updateBadge(){ const total=tboCount+tboRevisiCount; $('#notifTBO').text(total); $('#notifTBOText').text(tboCount); $('#notifTBOText_revisi').text(tboRevisiCount); }
  function refreshTBOCount(){ $.get('pages/ajax/get_total_tbo.php', (d)=>{ tboCount=toInt(d); updateBadge(); }).fail(()=>{ tboCount=0; updateBadge(); }); }
  function refreshTBORCount(){ $.get('pages/ajax/get_total_tbo_revisi.php', (d)=>{ tboRevisiCount=toInt(d); updateBadge(); }).fail(()=>{ tboRevisiCount=0; updateBadge(); }); }
  refreshTBOCount(); refreshTBORCount();
});

// ===== Modal Detail Revisi (tombol univ) =====
function openRevisionModalFromBtn($btn){
  var rows = [
    { cat: $btn.data('revisic')  || '', det: $btn.data('revisin')  || '', dt: $btn.data('revisi1date') || '' },
    { cat: $btn.data('revisi2')  || '', det: $btn.data('drevisi2') || '', dt: $btn.data('revisi2date') || '' },
    { cat: $btn.data('revisi3')  || '', det: $btn.data('drevisi3') || '', dt: $btn.data('revisi3date') || '' },
    { cat: $btn.data('revisi4')  || '', det: $btn.data('drevisi4') || '', dt: $btn.data('revisi4date') || '' },
    { cat: $btn.data('revisi5')  || '', det: $btn.data('drevisi5') || '', dt: $btn.data('revisi5date') || '' }
  ];
  var $tbody = $('#revisionTable tbody'); $tbody.empty();
  rows.forEach(function(r){
    var det = String(r.det||'').trim(); if(det==='') return;
    var $tr = $('<tr/>');
    $tr.append($('<td/>').text(String(r.cat||'').trim() || '-'));
    $tr.append($('<td/>').text(det));
    $tr.append($('<td/>').text(String(r.dt||'').trim()));
    $tbody.append($tr);
  });
  if ($tbody.children().length===0){
    $tbody.append($('<tr/>').append($('<td colspan="3" class="text-center text-muted"/>').text('Tidak ada detail revisi yang terisi.')));
  }
  $('#revisiModal').modal('show');
}
$(document).on('click', '#approvedTable tbody .revisi-btn', function(e){ e.preventDefault(); e.stopPropagation(); openRevisionModalFromBtn($(this)); });
$(document).on('click', '#tboTable tbody .revisi-btn',      function(e){ e.preventDefault(); e.stopPropagation(); openRevisionModalFromBtn($(this)); });

// ===== Modal Detail (table dalam modal) - handler Revisi spesifik =====
$(document).on('click', '#detailApprovedTable .revisi-btn', function(){
  var rows = [
    { cat: String($(this).data('revisic')||''),  det: String($(this).data('revisid') ||''), dt: $(this).data('revisi1date')||'' },
    { cat: String($(this).data('revisic1')||''), det: String($(this).data('revisid1')||''), dt: $(this).data('revisi2date')||'' },
    { cat: String($(this).data('revisic2')||''), det: String($(this).data('revisid2')||''), dt: $(this).data('revisi3date')||'' },
    { cat: String($(this).data('revisic3')||''), det: String($(this).data('revisid3')||''), dt: $(this).data('revisi4date')||'' },
    { cat: String($(this).data('revisic4')||''), det: String($(this).data('revisid4')||''), dt: $(this).data('revisi5date')||'' }
  ];
  var $tbody = $('#revisionTable tbody'); $tbody.empty();
  rows.forEach(function(r){ var d=String(r.det||'').trim(); if(d==='')return;
    var $tr=$('<tr/>'); $tr.append($('<td/>').text(String(r.cat||'').trim()||'-')); $tr.append($('<td/>').text(d)); $tr.append($('<td/>').text(String(r.dt||'').trim())); $tbody.append($tr);
  });
  if ($tbody.children().length===0){
    $tbody.append($('<tr/>').append($('<td colspan="3" class="text-center text-muted"/>').text('Tidak ada detail revisi yang terisi.')));
  }
  $('#revisiModal').modal('show');
});
</script>
