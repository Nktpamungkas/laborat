<!-- Tabulator CSS -->
<link rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.5.2/dist/css/tabulator.min.css">
<!-- SheetJS (Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<!-- jsPDF + autotable (PDF) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>
<!-- Tabulator JS -->
<script src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>

<style>
  .toolbar { margin:8px 0; display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
  .toolbar .btn { padding:4px 8px; font-size:12px; line-height:1.2; }
  .toolbar .form-control{ display:inline-block; width:auto; min-width:130px; height:24px; padding:2px 6px; font-size:12px; }

  /* Tabulator compact + garis */
  .tabulator{ font-size:11px; border:1px solid #dcdcdc; background:#fff !important; }
  .tabulator .tabulator-header{ border-bottom:1px solid #dcdcdc; background:#fff !important; }
  .tabulator .tabulator-col, .tabulator .tabulator-col-group{ padding:0 !important; }
  .tabulator .tabulator-col .tabulator-col-content{ padding:2px 4px !important; line-height:1.2; text-align:center; }
  .tabulator .tabulator-cell{ padding:2px 4px !important; line-height:1.2; background:#fff !important; }
  .tabulator .tabulator-col,.tabulator .tabulator-cell{ border-right:1px solid #dcdcdc; }
  .tabulator .tabulator-row{ border-top:1px solid #dcdcdc; }
  .tabulator .tabulator-row:last-child{ border-bottom:1px solid #dcdcdc; }
  .tabulator .tabulator-col:first-child,.tabulator .tabulator-cell:first-child{ border-left:0 !important; }
  .tabulator .tabulator-col .tabulator-arrow{ display:none !important; }
  .tabulator .tabulator-col{ cursor: default !important; }

   /* Modal sederhana */
  .modal-backdrop{ position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:100000; display:none; }
  .modal-shell{ position:fixed; left:50%; top:50%; transform:translate(-50%,-50%);
    width:min(900px,92vw); max-height:85vh; overflow:auto; background:#fff; border:1px solid #d0d0d0;
    border-radius:10px; box-shadow:0 10px 30px rgba(0,0,0,.25); z-index:100001; display:none; }
  .modal-header{ padding:12px 16px; border-bottom:1px solid #eee; font-weight:700; }
  .modal-body{ padding:12px 16px; }
  .modal-footer{ padding:12px 16px; border-top:1px solid #eee; display:flex; gap:8px; justify-content:flex-end; }

  .suffix-grid{ display:grid; grid-template-columns:110px 1fr; gap:10px 12px; align-items:start; }
  .suffix-grid label{ font-weight:600; align-self:center; }

  /* Contenteditable “textarea” dengan badge (Bootstrap 3 .label) */
  .token-area{
    border:1px solid #d0d0d0; border-radius:6px; min-height:90px; padding:6px 8px;
    font-size:12px; line-height:1.35; cursor:text; background:#fff;
  }
  .token-area:focus{ outline:none; border-color:#80bdff; box-shadow:0 0 4px rgba(0,123,255,.25); }
  .token-area .label{ display:inline-block; margin:2px 3px; }
  .token-area [data-token]{ -webkit-user-select:none; user-select:none; }
  .token-area .token-x{ margin-left:4px; cursor:pointer; font-weight:bold; }
  .token-placeholder{ color:#999; }
  .hint{ font-size:12px; color:#666; margin-top:4px; }

  /* Highlight sel error required */
  .tabulator .cell-error{ background:#ffecec !important; }
</style>

<h4 class="summary-title" style="text-align:center;font-weight:700;margin:4px 0 6px;">SUMMARY DISPENSING</h4>
<div class="toolbar">
  <button id="addRowDisp" class="btn btn-primary">+ Tambah Baris</button>

  <input type="date" id="fromDate" class="form-control">
  <span>s/d</span>
  <input type="date" id="toDate" class="form-control">
  <button id="applyFilter" class="btn btn-default">Filter</button>
  <button id="resetFilter" class="btn btn-default">Reset</button>

  <button id="exportXlsDisp" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</button>
  <button id="exportPdfDisp" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</button>
</div>

<div id="gridDisp"></div>

<!-- Modal DETAIL SUFFIX (3 tabel) -->
<div id="suffixModalBackdrop" class="modal-backdrop" style="display:none;"></div>
<div id="suffixModal" class="modal-shell" role="dialog" aria-modal="true" aria-labelledby="suffixTitle" style="display:none;">
  <div class="modal-header" id="suffixTitle">Detail SUFFIX</div>
  <div class="modal-body">
    <div class="text-muted-small" id="suffixRange"></div>
    <div class="detail-grid" style="display:flex;gap:16px;align-items:flex-start;margin-top:8px;">
      <!-- POLY -->
      <div class="detail-col panel-like" style="flex:1 1 0;min-width:260px;border:1px solid #dcdcdc;border-radius:6px;overflow:hidden;">
        <div class="panel-head" style="background:#f5f7fa;border-bottom:1px solid #e5e7eb;padding:6px 10px;font-weight:600;text-transform:capitalize;text-align:center;">poly</div>
        <div class="panel-body" style="padding:6px;">
          <table class="table table-bordered table-condensed" id="tblPoly">
            <thead>
              <tr>
                <th style="width:40px;">NO</th>
                <th>suffix</th>
                <th style="width:80px;">jml btl</th>
              </tr>
            </thead>
            <tbody id="tbodyPoly">
              <tr><td colspan="3" class="text-center text-muted">Tidak ada data</td></tr>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="2" class="text-right">Total</th>
                <th id="totalPoly">0</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- COTTON -->
      <div class="detail-col panel-like" style="flex:1 1 0;min-width:260px;border:1px solid #dcdcdc;border-radius:6px;overflow:hidden;">
        <div class="panel-head" style="background:#f5f7fa;border-bottom:1px solid #e5e7eb;padding:6px 10px;font-weight:600;text-transform:capitalize;text-align:center;">cotton</div>
        <div class="panel-body" style="padding:6px;">
          <table class="table table-bordered table-condensed" id="tblCotton">
            <thead>
              <tr>
                <th style="width:40px;">NO</th>
                <th>suffix</th>
                <th style="width:80px;">jml btl</th>
              </tr>
            </thead>
            <tbody id="tbodyCotton">
              <tr><td colspan="3" class="text-center text-muted">Tidak ada data</td></tr>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="2" class="text-right">Total</th>
                <th id="totalCotton">0</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- WHITE -->
      <div class="detail-col panel-like" style="flex:1 1 0;min-width:260px;border:1px solid #dcdcdc;border-radius:6px;overflow:hidden;">
        <div class="panel-head" style="background:#f5f7fa;border-bottom:1px solid #e5e7eb;padding:6px 10px;font-weight:600;text-transform:capitalize;text-align:center;">white</div>
        <div class="panel-body" style="padding:6px;">
          <table class="table table-bordered table-condensed" id="tblWhite">
            <thead>
              <tr>
                <th style="width:40px;">NO</th>
                <th>suffix</th>
                <th style="width:80px;">jml btl</th>
              </tr>
            </thead>
            <tbody id="tbodyWhite">
              <tr><td colspan="3" class="text-center text-muted">Tidak ada data</td></tr>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="2" class="text-right">Total</th>
                <th id="totalWhite">0</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

    </div>
  </div>
  <div class="modal-footer" style="display:flex;gap:8px;justify-content:flex-end;padding:12px 16px;border-top:1px solid #eee;">
    <button id="suffixClose" class="btn btn-default">Tutup</button>
  </div>
</div>

<script>
  /* ===== Helpers ===== */
  function intValidator(cell){
    var v = cell.getValue();
    if (v === null || v === '' || typeof v === 'undefined') return true;
    return (/^-?\d+$/).test(String(v));
  }
  function w(px){ return px; }
  function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

  // 0 → tampil kosong (grid & export)
  function zeroBlankFormatter(cell){
    var v = cell.getValue();
    return (v === 0 || v === '0') ? '' : v;
  }
  function zeroBlankDownload(value){
    return (value === 0 || value === '0') ? '' : value;
  }
  function _zb(v){ return (v === 0 || v === '0') ? '' : (v ?? ''); }

  /* ===== Overlay editor tanggal ===== */
  function overlayEditorFactory(inputType, minW){
    return function(cell, onRendered, success, cancel){
      var rect = cell.getElement().getBoundingClientRect();
      var wrap = document.createElement('div');
      wrap.style.position = 'fixed';
      wrap.style.left = rect.left + 'px';
      wrap.style.top  = (rect.bottom + 2) + 'px';
      wrap.style.zIndex = '99999';
      wrap.style.background = '#fff';
      wrap.style.border = '1px solid #aaa';
      wrap.style.boxShadow = '0 2px 8px rgba(0,0,0,.15)';
      wrap.style.padding = '4px';
      var input = document.createElement('input');
      input.type = inputType; // 'date'
      input.value = cell.getValue() || '';
      input.style.width = '100%';
      input.style.minWidth = (minW || 180) + 'px';
      wrap.appendChild(input);
      document.body.appendChild(wrap);
      setTimeout(function(){ try{ input.focus(); if(input.select) input.select(); }catch(e){} }, 0);
      function cleanup(commit){
        try{ if (wrap && wrap.parentNode) wrap.parentNode.removeChild(wrap); }catch(e){}
        window.removeEventListener('scroll', onScroll, true);
        document.removeEventListener('mousedown', onDown, true);
        document.removeEventListener('keydown', onKey, true);
        commit ? success(input.value) : cancel();
      }
      function onScroll(){ cleanup(true); }
      function onDown(e){ if (!wrap.contains(e.target)) cleanup(true); }
      function onKey(e){ if (e.key === 'Escape'){ e.preventDefault(); cleanup(false); } if (e.key === 'Enter'){ e.preventDefault(); cleanup(true); } }
      input.addEventListener('change', function(){ cleanup(true); });
      input.addEventListener('blur',   function(){ cleanup(true); });
      window.addEventListener('scroll', onScroll, true);
      document.addEventListener('mousedown', onDown, true);
      document.addEventListener('keydown', onKey, true);
      var dummy = document.createElement('span'); dummy.style.display = 'none'; return dummy;
    };
  }
  var dateEditor = overlayEditorFactory('date', 110);

  /* ===== Kolom ===== */
  function suffixCellFormatter(){ return '<button class="btn btn-info btn-xs">Detail</button>'; }

  var columnsDisp = [
    { title:"ID", field:"id", visible:false, download:false },
    { title:"TGL",   field:"tgl",   editor:dateEditor, width:w(160), headerHozAlign:"center" },
    { title:"SHIFT", field:"shift",
      editor:"select", editorParams:{values:{"1":"1","2":"2","3":"3"}},
      width:w(120), headerHozAlign:"center"
    },
    { title:"TOTAL KLOTER", headerHozAlign:"center", columns:[
      { title:"POLY",   field:"ttl_kloter_poly",   editor:"number", validator:intValidator, width:w(140),
        formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload },
      { title:"COTTON", field:"ttl_kloter_cotton", editor:"number", validator:intValidator, width:w(140),
        formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload },
      { title:"WHITE",  field:"ttl_kloter_white",  editor:"number", validator:intValidator, width:w(140),
        formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload }
    ]},
    { title:"SUFFIX", field:"_suffix_btn", width:w(160), headerSort:false, hozAlign:"center",
      formatter: suffixCellFormatter,
      cellClick: function(e, cell){ openSuffixModal(cell.getRow()); },
      download:false
    },
    { title:"suffix_poly",   field:"suffix_poly",   visible:false, download:false },
    { title:"suffix_cotton", field:"suffix_cotton", visible:false, download:false },
    { title:"suffix_white",  field:"suffix_white",  visible:false, download:false },

    { title:"BOTOL", field:"botol",
      editor:"number",
      validator:function(cell){ var v=cell.getValue(); if (v==null||v==='') return true; return (/^\d+$/).test(String(v)); },
      width:w(140), hozAlign:"center",
      formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload
    },

    { title:"Aksi", field:"_aksi", width:w(120), headerSort:false, headerHozAlign:"center", hozAlign:"center",
      download:false,
      formatter:function(){
        return '<div class="btn-group btn-group-xs">' +
                 '<button class="btn btn-primary btn-xs act-save"><i class="fa fa-floppy-o"></i></button>' +
                 '<button class="btn btn-danger btn-xs act-del" style="margin-left:6px;"><i class="fa fa-trash-o"></i></button>' +
               '</div>';
      },
      cellClick:function(e, cell){
        var t = e.target;
        if (t.classList.contains('act-save') || (t.closest('.act-save') && (t = t.closest('.act-save')))){
          saveRowDisp(cell.getRow(), t);
        }else if (t.classList.contains('act-del') || (t.closest('.act-del') && (t = t.closest('.act-del')))){
          deleteRowDisp(cell.getRow(), t);
        }
      }
    }
  ];

  /* ===== Table ===== */
  var tableDisp = new Tabulator("#gridDisp", {
    layout:'fitData',
    columnMinWidth:40,
    columnDefaults:{ hozAlign:'center', vertAlign:'middle' },
    headerSort:false,
    reactiveData:true,
    addRowPos:"top",
    movableColumns:true,
    resizableRows:false,
    columns:columnsDisp,
    clipboard:true,
    clipboardPasteAction:'insert',
    selectable:true,
    placeholder:'',
    pagination:"local",
    paginationSize:20,
    paginationSizeSelector:[10,20,50,100,true]
  });

  /* ===== Modal DETAIL (pakai suggest_summary_dispensing.php) ===== */
  var suffixModalBackdrop = document.getElementById('suffixModalBackdrop');
  var suffixModal         = document.getElementById('suffixModal');
  var suffixRangeEl       = document.getElementById('suffixRange');
  function onEscClose(e){ if (e.key === 'Escape'){ e.preventDefault(); closeSuffixModal(); } }
  function closeSuffixModal(){ suffixBackdropHide(); document.removeEventListener('keydown', onEscClose, true); }
  function suffixBackdropShow(){ suffixModalBackdrop.style.display='block'; suffixModal.style.display='block'; }
  function suffixBackdropHide(){ suffixModalBackdrop.style.display='none'; suffixModal.style.display='none'; }
  document.getElementById('suffixClose').addEventListener('click', closeSuffixModal);
  suffixModalBackdrop.addEventListener('click', closeSuffixModal);

  function renderDetailBlock(list, tbodyId, totalId){
    var tb = document.getElementById(tbodyId);
    var tot = 0;
    if (!list || !list.length){
      tb.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tidak ada data</td></tr>';
      document.getElementById(totalId).textContent = '0';
      return;
    }
    var rows = list.map(function(it, i){
      var qty = parseInt(it.qty,10) || 0; tot += qty;
      return '<tr>' +
               '<td class="text-center">'+(i+1)+'</td>' +
               '<td class="suffix-cell" title="'+esc(it.suffix)+'">'+esc(it.suffix)+'</td>' +
               '<td class="text-center">'+qty+'</td>' +
             '</tr>';
    }).join('');
    tb.innerHTML = rows;
    document.getElementById(totalId).textContent = String(tot);
  }

  async function openSuffixModal(row){
    var d = row.getData();
    if (!d.tgl || !/^[123]$/.test(String(d.shift||''))){
      alert('Isi TGL dan pilih SHIFT dulu.');
      return;
    }
    try{
      var url = 'pages/ajax/suggest_summary_dispensing.php?date='
                + encodeURIComponent(d.tgl) + '&shift=' + encodeURIComponent(d.shift);
      var res = await fetch(url);
      var json = await res.json();
      if (!json || !json.ok){ alert(json && json.message ? json.message : 'Gagal mengambil detail'); return; }

      suffixRangeEl.textContent = 'Periode: ' + json.range_start + ' s/d ' + json.range_end;
      var det = json.detail || {poly:[], cotton:[], white:[]};
      renderDetailBlock(det.poly,   'tbodyPoly',   'totalPoly');
      renderDetailBlock(det.cotton, 'tbodyCotton', 'totalCotton');
      renderDetailBlock(det.white,  'tbodyWhite',  'totalWhite');

      suffixBackdropShow();
      document.addEventListener('keydown', onEscClose, true);
    }catch(e){
      alert('Gagal memuat detail.');
    }
  }

  /* ===== Required validation: TGL & SHIFT ===== */
  var REQUIRED_FIELDS = [
    { field:'tgl',   label:'TGL' },
    { field:'shift', label:'SHIFT' },
  ];
  function clearRowErrors(row){
    REQUIRED_FIELDS.forEach(function(req){
      var c = row.getCell(req.field);
      if (c){
        var el = c.getElement();
        el.classList.remove('cell-error');
        el.removeAttribute('title');
      }
    });
  }
  function markCellError(row, field, msg){
    var cell = row.getCell(field);
    if (!cell) return;
    var el = cell.getElement();
    el.classList.add('cell-error');
    if (msg) el.title = msg;
  }
  function validateRequiredRow(row){
    clearRowErrors(row);
    var d = row.getData();
    var missing = [];

    REQUIRED_FIELDS.forEach(function(req){
      var v = d[req.field];
      if (v === null || typeof v === 'undefined' || String(v).trim() === ''){
        missing.push(req.label);
        markCellError(row, req.field, req.label + ' wajib diisi');
      }
    });

    if (!missing.includes('SHIFT')){
      var s = String(d.shift||'').trim();
      if (!/^[123]$/.test(s)){
        missing.push('SHIFT (hanya 1/2/3)');
        markCellError(row, 'shift', 'SHIFT hanya boleh 1/2/3');
      }
    }

    return { ok: missing.length === 0, missing: missing };
  }

  tableDisp.on('cellEdited', function(cell){
    var f = cell.getField();
    if (f === 'tgl' || f === 'shift'){
      var el = cell.getElement(); el.classList.remove('cell-error'); el.removeAttribute('title');
    }
  });

  /* ===== Toolbar ===== */
  function normRange(){
    var f = document.getElementById('fromDate').value || '';
    var t = document.getElementById('toDate').value   || '';
    if (f && t && f > t){ var x=f; f=t; t=x; }
    return {from:f,to:t};
  }
  document.getElementById('addRowDisp').addEventListener('click', function(){
    tableDisp.setPage(1);
    requestAnimationFrame(function(){ tableDisp.addRow({}, true); });
  });
  document.getElementById('applyFilter').addEventListener('click', function(){ var r=normRange(); loadData(r.from, r.to); });
  document.getElementById('resetFilter').addEventListener('click', function(){
    document.getElementById('fromDate').value=''; document.getElementById('toDate').value=''; loadData();
  });

  /* ===== Spinner ===== */
  function setBtnSpinner(btn, on){
    if (!btn) return;
    var icon = btn.querySelector('i'); if (!icon) return;
    if (on){ icon.setAttribute('data-prev', icon.className || 'fa fa-spinner'); icon.className='fa fa-spinner fa-spin'; btn.disabled=true; }
    else   { var p=icon.getAttribute('data-prev')||'fa fa-floppy-o'; icon.className=p; icon.removeAttribute('data-prev'); btn.disabled=false; }
  }

  /* ===== AJAX helpers ===== */
  function rowToParams(rowData){
    var p = new URLSearchParams();
    for (var key in rowData){ if (!rowData.hasOwnProperty(key)) continue; p.append(key, (rowData[key]==null)? '' : String(rowData[key])); }
    return p;
  }
  async function postForm(url, params){
    var res = await fetch(url, {
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8'},
      body: params.toString()
    });
    var text = await res.text();
    try{ return JSON.parse(text); }
    catch(e){
      alert("Server mengirim non-JSON (cek Network → Response):\n\n" + text.slice(0, 1000));
      return { ok:false, message:'Respons tidak valid' };
    }
  }

  /* ===== Suggest (auto-isi setelah TGL & SHIFT valid) ===== */
  async function fetchSuggest(dateStr, shiftStr){
    if(!dateStr || !/^[123]$/.test(String(shiftStr||''))) return null;
    var url = 'pages/ajax/suggest_summary_dispensing.php?date='+encodeURIComponent(dateStr)+'&shift='+shiftStr;
    try{
      const res  = await fetch(url);
      const json = await res.json();
      if (!json || !json.ok){ console.warn('Suggest failed:', json && json.message); return null; }
      return json;
    }catch(e){
      console.warn('Suggest error:', e);
      return null;
    }
  }
  async function suggestForRow(row){
    var d = row.getData();
    if (!d.tgl) return;
    var s = String(d.shift||'').trim();
    if (!/^[123]$/.test(s)) return;
    var sug = await fetchSuggest(d.tgl, s);
    if (!sug) return;
    row.update({
      suffix_poly:   sug.suffix_poly   || '',
      suffix_cotton: sug.suffix_cotton || '',
      suffix_white:  sug.suffix_white  || '',
      botol:         sug.botol || 0
    });
    row.reformat();
  }
  tableDisp.on('cellEdited', function(cell){
    var f = cell.getField();
    if (f === 'tgl' || f === 'shift'){ suggestForRow(cell.getRow()); }
  });

  /* ===== Save / Delete ===== */
  async function saveRowDisp(row, btn){
    var req = validateRequiredRow(row);
    if (!req.ok){
      try{
        tableDisp.scrollToRow(row, "center", false);
        for (var i=0;i<REQUIRED_FIELDS.length;i++){
          var f = REQUIRED_FIELDS[i].field;
          var c = row.getCell(f);
          if (c && c.getElement().classList.contains('cell-error')){ c.edit(); break; }
        }
      }catch(e){}
      alert('Kolom "' + req.missing.join(', ') + '" WAJIB DI ISI!');
      return;
    }

    var data = row.getData();
    var isUpdate = !!data.id;
    var url = isUpdate ? 'pages/ajax/update_row_summary_dispensing.php'
                       : 'pages/ajax/save_row_summary_dispensing.php';
    if (btn) setBtnSpinner(btn, true);
    var resp = await postForm(url, rowToParams(data));
    if (btn) setBtnSpinner(btn, false);
    alert((resp && resp.message) ? resp.message : (isUpdate ? 'Update selesai' : 'Tersimpan'));
    if (!isUpdate && resp && resp.ok && resp.id){ row.update({ id: resp.id }); }
    row.reformat();
  }
  async function deleteRowDisp(row, btn){
    var data = row.getData();
    if (!confirm('Hapus baris ini?')) return;
    if (data.id){
      if (btn) setBtnSpinner(btn, true);
      var p = new URLSearchParams(); p.append('id', String(data.id));
      var resp = await postForm('pages/ajax/delete_row_summary_dispensing.php', p);
      if (btn) setBtnSpinner(btn, false);
      if (!resp || !resp.ok){ alert((resp && resp.message) ? resp.message : 'Gagal hapus di DB'); return; }
    }
    row.delete();
  }

  /* ===== Load data (default 30 hari terakhir di backend) ===== */
  async function loadData(from, to){
    try{
      var qs = [];
      if (from) qs.push('from='+encodeURIComponent(from));
      if (to)   qs.push('to='+encodeURIComponent(to));
      var url = 'pages/ajax/get_summary_dispensing.php' + (qs.length? ('?'+qs.join('&')) : '');
      var res = await fetch(url);
      var json = await res.json();
      if (json && json.ok){
        var rows = json.data || [];
        tableDisp.setData(rows);
        tableDisp.clearFilter(true);
        if (from || to){
          tableDisp.setFilter(function(d){
            var t = d.tgl || '';
            return (!from || t >= from) && (!to || t <= to);
          });
        }
        if (!rows.length){ tableDisp.addRow({}, true); tableDisp.setPage(1); }
      }else{
        tableDisp.clearData(); tableDisp.addRow({}, true); tableDisp.setPage(1);
        alert(json && json.message ? json.message : 'Gagal ambil data');
      }
    }catch(e){
      tableDisp.clearData(); tableDisp.addRow({}, true); tableDisp.setPage(1);
      alert('Gagal ambil data');
    }
  }

  /* ===== Export Excel (0 → kosong) ===== */
  document.getElementById('exportXlsDisp').addEventListener('click', function () {
    var rows = tableDisp.getRows('active').map(r => r.getData());
    function _len(t){ return t ? String(t).split(/[,\s;]+/).map(s=>s.trim()).filter(Boolean).length : 0; }

    var row1 = ['TGL','SHIFT','TOTAL KLOTER','','','JUMLAH SUFFIX','BOTOL'];
    var row2 = ['',   '',     'POLY',        'COTTON','WHITE',   '',            ''   ];

    var body = rows.map(function(r){
      return [
        r.tgl || '',
        r.shift || '',
        _zb(r.ttl_kloter_poly),
        _zb(r.ttl_kloter_cotton),
        _zb(r.ttl_kloter_white),
        (_len(r.suffix_poly)+_len(r.suffix_cotton)+_len(r.suffix_white)) || '',
        _zb(r.botol)
      ];
    });

    var aoa = [row1, row2].concat(body);
    var wb = XLSX.utils.book_new();
    var ws = XLSX.utils.aoa_to_sheet(aoa);

    ws['!merges'] = [
      XLSX.utils.decode_range('A1:A2'),
      XLSX.utils.decode_range('B1:B2'),
      XLSX.utils.decode_range('C1:E1'),
      XLSX.utils.decode_range('F1:F2'),
      XLSX.utils.decode_range('G1:G2')
    ];
    ws['!cols'] = [{wch:14},{wch:12},{wch:14},{wch:14},{wch:14},{wch:16},{wch:18}];

    XLSX.utils.book_append_sheet(wb, ws, 'Summary');
    XLSX.writeFile(wb, 'Summary-Dispensing.xlsx');
  });

  /* ===== Export PDF (0 → kosong) ===== */
  document.getElementById('exportPdfDisp').addEventListener('click', function(){
    const { jsPDF } = window.jspdf;
    var doc = new jsPDF({ orientation:'landscape', unit:'pt', format:'a4' });

    doc.setFontSize(14); doc.setFont(undefined,'bold');
    doc.text('SUMMARY DISPENSING', 40, 40);
    doc.setFontSize(9); doc.setFont(undefined,'normal');

    var rows = tableDisp.getRows('active').map(r => r.getData());
    function _len(t){ return t ? String(t).split(/[,\s;]+/).map(s=>s.trim()).filter(Boolean).length : 0; }

    var rowTop = [
      {content:'TGL', rowSpan:2, styles:{halign:'center', valign:'middle'}},
      {content:'SHIFT', rowSpan:2, styles:{halign:'center', valign:'middle'}},
      {content:'TOTAL KLOTER', colSpan:3, styles:{halign:'center'}},
      {content:'JUMLAH SUFFIX', rowSpan:2, styles:{halign:'center', valign:'middle'}},
      {content:'BOTOL', rowSpan:2, styles:{halign:'center', valign:'middle'}}
    ];
    var rowSub = [
      {content:'POLY', styles:{halign:'center'}},
      {content:'COTTON', styles:{halign:'center'}},
      {content:'WHITE', styles:{halign:'center'}}
    ];

    var body = rows.map(function(r){
      return [
        r.tgl || '',
        r.shift || '',
        _zb(r.ttl_kloter_poly),
        _zb(r.ttl_kloter_cotton),
        _zb(r.ttl_kloter_white),
        (_len(r.suffix_poly)+_len(r.suffix_cotton)+_len(r.suffix_white)) || '',
        _zb(r.botol)
      ];
    });

    doc.autoTable({
      startY: 56,
      head: [rowTop, rowSub],
      body: body,
      styles:{ fontSize:9, halign:'center', valign:'middle', lineWidth:0.3, lineColor:[170,170,170] },
      headStyles:{ fillColor:[224,232,241], textColor:[0,0,0], fontStyle:'bold' },
      alternateRowStyles:{ fillColor:[248,250,252] },
      margin:{ left:40, right:40 },
      tableWidth:'auto',
      didParseCell: function (data) {
        if (data.section === 'head') {
          if (data.row.index === 0) data.cell.styles.fillColor = [210,210,210];
          if (data.row.index === 1) data.cell.styles.fillColor = [230,230,230];
        }
      }
    });

    doc.save('Summary-Dispensing.pdf');
  });

  /* ===== First load ===== */
  loadData();
</script>
