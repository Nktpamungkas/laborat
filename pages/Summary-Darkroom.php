<link rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.5.2/dist/css/tabulator.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>
<script src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>

<style>
  .toolbar { margin:8px 0; display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
  .toolbar .btn { padding:4px 8px; font-size:12px; line-height:1.2; }
  .toolbar .form-control{ display:inline-block; width:auto; min-width:130px; height:24px; padding:2px 6px; font-size:12px; }

  /* Tabulator compact + garis (seragam) */
  .tabulator{ font-size:11px; border:1px solid #dcdcdc; background:#fff !important; box-sizing:border-box; }
  .tabulator .tabulator-header{ border-bottom:1px solid #dcdcdc; background:#fff !important; }
  .tabulator .tabulator-col, .tabulator .tabulator-col-group{ padding:0 !important; box-sizing:border-box; }
  .tabulator .tabulator-col .tabulator-col-content{ padding:2px 4px !important; line-height:1.2; text-align:center; }
  .tabulator .tabulator-col .tabulator-col-title{ display:block; width:100%; white-space:normal; word-break:break-word; }
  .tabulator .tabulator-cell{ padding:2px 4px !important; line-height:1.2; background:#fff !important; }
  .tabulator .tabulator-col,.tabulator .tabulator-cell{ border-right:1px solid #dcdcdc; }
  .tabulator .tabulator-row{ border-top:1px solid #dcdcdc; }
  .tabulator .tabulator-row:last-child{ border-bottom:1px solid #dcdcdc; }
  .tabulator .tabulator-col:first-child,.tabulator .tabulator-cell:first-child{ border-left:0 !important; }
  .tabulator .tabulator-col .tabulator-arrow{ display:none !important; }
  .tabulator .tabulator-col{ cursor: default !important; }
  .tabulator .tabulator-header .tabulator-col { justify-content:center; }

  /* highlight error required */
  .tabulator .cell-error{ background:#ffecec !important; }

  /* Modal detail */
  .modal-backdrop{ position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:100000; display:none; }
  .modal-shell{ position:fixed; left:50%; top:50%; transform:translate(-50%,-50%);
    width:min(520px,92vw); max-height:86vh; overflow:auto; background:#fff; border:1px solid #d0d0d0;
    border-radius:10px; box-shadow:0 10px 30px rgba(0,0,0,.25); z-index:100001; display:none; }
  .modal-header{ padding:12px 16px; border-bottom:1px solid #eee; font-weight:700; }
  .modal-body{ padding:12px 16px; }
  .modal-footer{ padding:12px 16px; border-top:1px solid #eee; display:flex; gap:8px; justify-content:flex-end; }

  /* Tabel mini untuk modal */
  .table { width:100%; max-width:100%; border-collapse:collapse; }
  .table th, .table td{ border:1px solid #dcdcdc; padding:6px 8px; vertical-align:middle !important; }
  .table thead th{ text-align:center; background:#f5f7fa; }
  .table tfoot th{ font-weight:700; background:#fafafa; }
  .text-muted-small{ color:#888; font-size:12px; }
  .suffix-cell{ max-width:520px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
</style>

<h4 class="summary-title" style="text-align:center;font-weight:700;margin:4px 0 6px;">SUMMARY DARKROOM</h4>
<div class="toolbar">
  <button id="addRow" class="btn btn-primary">+ Tambah Baris</button>

  <input type="date" id="fromDate" class="form-control">
  <span>s/d</span>
  <input type="date" id="toDate" class="form-control">
  <button id="applyFilter" class="btn btn-default">Filter</button>
  <button id="resetFilter" class="btn btn-default">Reset</button>

  <button id="exportXls" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</button>
  <button id="exportPdf" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</button>
</div>

<div id="grid"></div>

<!-- Modal DETAIL SUFFIX -->
<div id="dkBackdrop" class="modal-backdrop"></div>
<div id="dkModal" class="modal-shell" role="dialog" aria-modal="true" aria-labelledby="dkTitle">
  <div class="modal-header" id="dkTitle">Detail SUFFIX</div>
  <div class="modal-body">
    <div class="text-muted-small" id="dkRange"></div>
    <table class="table" style="margin-top:8px;">
      <thead>
        <tr><th style="width:60px;">NO</th><th>suffix</th></tr>
      </thead>
      <tbody id="dkTbody">
        <tr><td colspan="2" class="text-center" style="color:#777;">Tidak ada data</td></tr>
      </tbody>
    </table>
  </div>
  <div class="modal-footer"><button id="dkClose" class="btn btn-default">Tutup</button></div>
</div>

<script>
  /* ========= Editor tanggal mengambang ========= */
  function overlayEditorFactory(inputType, minW){
    return function(cell, onRendered, success, cancel){
      var rect = cell.getElement().getBoundingClientRect();
      var wrap = document.createElement('div');
      wrap.style.position='fixed'; wrap.style.left=rect.left+'px'; wrap.style.top=(rect.bottom+2)+'px';
      wrap.style.zIndex='99999'; wrap.style.background='#fff'; wrap.style.border='1px solid #aaa';
      wrap.style.boxShadow='0 2px 8px rgba(0,0,0,.15)'; wrap.style.padding='4px';
      var input=document.createElement('input'); input.type=inputType; input.value=cell.getValue()||'';
      input.style.width='100%'; input.style.minWidth=(minW||180)+'px'; wrap.appendChild(input);
      document.body.appendChild(wrap);
      setTimeout(function(){ try{ input.focus(); if(input.select) input.select(); }catch(e){} }, 0);
      function cleanup(commit){
        try{ wrap.parentNode.removeChild(wrap); }catch(e){}
        window.removeEventListener('scroll', onScroll, true);
        document.removeEventListener('mousedown', onDown, true);
        document.removeEventListener('keydown', onKey, true);
        commit ? success(input.value) : cancel();
      }
      function onScroll(){ cleanup(true); }
      function onDown(e){ if(!wrap.contains(e.target)) cleanup(true); }
      function onKey(e){ if(e.key==='Escape'){ e.preventDefault(); cleanup(false); } if(e.key==='Enter'){ e.preventDefault(); cleanup(true); } }
      input.addEventListener('change', function(){ cleanup(true); });
      input.addEventListener('blur',   function(){ cleanup(true); });
      window.addEventListener('scroll', onScroll, true);
      document.addEventListener('mousedown', onDown, true);
      document.addEventListener('keydown', onKey, true);
      var dummy=document.createElement('span'); dummy.style.display='none'; return dummy;
    };
  }
  var dateEditor = overlayEditorFactory('date', 110);

  /* ========= Helpers ========= */
  function intValidator(cell){ var v=cell.getValue(); if (v==null||v==='') return true; return (/^-?\d+$/).test(String(v)); }
  function w(px){ return px; }
  function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
  function splitToList(text){ if (!text) return []; return String(text).split(/[,\s;]+/).map(s=>s.trim()).filter(Boolean); }

  /* ========= Kolom ========= */
  function suffixCellFormatter(){ return '<button class="btn btn-info btn-xs">Detail</button>'; }

  var columns = [
    { title:"ID", field:"id", visible:false, download:false },

    { title:"TGL",   field:"tgl",   editor:dateEditor, width:w(160), headerHozAlign:"center" },

    { title:"SHIFT", field:"shift",
      editor:"select", editorParams:{values:{"1":"1","2":"2","3":"3"}},
      width:w(120), headerHozAlign:"center"
    },

    { title:"JUMLAH", field:"jumlah", editor:"number", validator:intValidator, width:w(120) },

    { title:"SUFFIX", field:"_suffix_btn", width:w(140), headerSort:false, hozAlign:"center",
      formatter: suffixCellFormatter,
      cellClick: function(e, cell){ openDetail(cell.getRow()); },
      download:false
    },

    /* simpan list suffix (hidden) */
    { title:"suffix", field:"suffix", visible:false, download:false },

    { title:"KET", field:"ket", editor:"input", width:w(240), headerHozAlign:"center" },

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
          saveRow(cell.getRow(), t);
        }else if (t.classList.contains('act-del') || (t.closest('.act-del') && (t = t.closest('.act-del')))){
          deleteRow(cell.getRow(), t);
        }
      }
    }
  ];

  /* Matikan sort semua level */
  function disableSorting(cols){
    for (var i=0;i<cols.length;i++){
      cols[i].headerSort = false;
      if (cols[i].columns && cols[i].columns.length) disableSorting(cols[i].columns);
      if (cols[i].sorter) cols[i].sorter = null;
    }
    return cols;
  }
  columns = disableSorting(columns);

  /* ========= Table ========= */
  var table = new Tabulator("#grid", {
    layout:'fitData',
    columnMinWidth:40,
    columnDefaults:{ hozAlign:'center', vertAlign:'middle' },
    headerSort:false,
    reactiveData:true,
    movableColumns:true,
    resizableRows:false,
    columns:columns,
    clipboard:true,
    clipboardPasteAction:'insert',
    selectable:true,
    placeholder:'',
    pagination:"local",
    paginationSize:20,
    paginationSizeSelector:[10,20,50,100,true],
    addRowPos:"top",
    paginationAddRow:"table",
  });

  /* redraw guards */
  setTimeout(function(){ table.redraw(true); }, 0);
  window.addEventListener('resize', function(){ table.redraw(true); });
  table.on('pageLoaded', ()=> requestAnimationFrame(()=> table.redraw(true)));
  table.on('columnResized', ()=> table.redraw(true));
  table.on('columnMoved',   ()=> table.redraw(true));

  /* DPI watcher */
  (function(tbl){
    var last = window.devicePixelRatio || 1;
    var mq = window.matchMedia('(resolution: ' + last + 'dppx)');
    function onChange(){
      requestAnimationFrame(function(){ tbl.redraw(true); });
      try { mq.removeEventListener('change', onChange); } catch(e){ if (mq.removeListener) mq.removeListener(onChange); }
      last = window.devicePixelRatio || 1;
      mq = window.matchMedia('(resolution: ' + last + 'dppx)');
      try { mq.addEventListener('change', onChange); } catch(e){ if (mq.addListener) mq.addListener(onChange); }
    }
    try { mq.addEventListener('change', onChange); } catch(e){ if (mq.addListener) mq.addListener(onChange); }
    var iv = setInterval(function(){
      var cur = window.devicePixelRatio || 1;
      if (Math.abs(cur - last) > 0.001){ last = cur; tbl.redraw(true); }
    }, 400);
    window.addEventListener('beforeunload', function(){ clearInterval(iv); });
  })(table);

  /* ========= Modal DETAIL ========= */
  var dkBackdrop = document.getElementById('dkBackdrop');
  var dkModal    = document.getElementById('dkModal');
  var dkRange    = document.getElementById('dkRange');
  var dkTbody    = document.getElementById('dkTbody');

  function onEscClose(e){ if (e.key === 'Escape'){ e.preventDefault(); closeDetail(); } }
  function showDetail(){ dkBackdrop.style.display='block'; dkModal.style.display='block'; document.addEventListener('keydown', onEscClose, true); }
  function closeDetail(){ dkBackdrop.style.display='none'; dkModal.style.display='none'; document.removeEventListener('keydown', onEscClose, true); }
  document.getElementById('dkClose').addEventListener('click', closeDetail);
  dkBackdrop.addEventListener('click', closeDetail);

  function renderDetail(list){
    var arr = list || [];
    if (!arr.length){
      dkTbody.innerHTML = '<tr><td colspan="2" class="text-center" style="color:#777;">Tidak ada data</td></tr>';
      return;
    }
    var rows = arr.map(function(s, i){
      return '<tr><td class="text-center">'+(i+1)+'</td><td class="suffix-cell" title="'+esc(s)+'">'+esc(s)+'</td></tr>';
    }).join('');
    dkTbody.innerHTML = rows;
  }

  async function openDetail(row){
    var d = row.getData();
    if (!d.tgl || !/^[123]$/.test(String(d.shift||''))){
      alert('Isi TGL dan pilih SHIFT dulu.');
      return;
    }
    try{
      var url = 'pages/ajax/suggest_summary_darkroom.php?date=' + encodeURIComponent(d.tgl) + '&shift=' + encodeURIComponent(d.shift);
      var res = await fetch(url);
      var json = await res.json();
      if (!json || !json.ok){ alert(json && json.message ? json.message : 'Gagal mengambil detail'); return; }

      dkRange.textContent = 'Periode: ' + json.range_start + ' s/d ' + json.range_end;

      var list = [];
      if (Array.isArray(json.list)) list = json.list;
      else if (typeof json.suffix === 'string') list = splitToList(json.suffix);
      if (!list.length && d.suffix) list = splitToList(d.suffix);

      renderDetail(list);
      showDetail();
    }catch(e){
      alert('Gagal memuat detail.');
    }
  }

  /* ========= Toolbar / Filter tanggal ========= */
  function normRange(){
    var f = document.getElementById('fromDate').value || '';
    var t = document.getElementById('toDate').value   || '';
    if (f && t && f > t){ var x=f; f=t; t=x; }
    return {from:f,to:t};
  }
  document.getElementById('applyFilter').addEventListener('click', function(){ var r=normRange(); loadData(r.from, r.to); });
  document.getElementById('resetFilter').addEventListener('click', function(){
    document.getElementById('fromDate').value=''; document.getElementById('toDate').value='';
    loadData();
  });

  /* ========= Tambah Baris: pindah page 1 + langsung buat (tanpa auto-edit) ========= */
  document.getElementById('addRow').addEventListener('click', function(e){
    const btn = e.currentTarget;
    btn.disabled = true;
    table.setPage(1);
    setTimeout(function(){
      const res = table.addRow({}, true); // prepend to table
      function after(row){
        requestAnimationFrame(function(){
          table.redraw(true);
          if (row) table.scrollToRow(row, "top", true);
          btn.disabled = false;  // tidak membuka editor TGL
        });
      }
      if (res && typeof res.then === 'function'){ res.then(after).catch(function(){ btn.disabled=false; }); }
      else { after(null); }
    }, 0);
  });

  /* ========= Spinner ========= */
  function setBtnSpinner(btn, on){
    if (!btn) return;
    var icon = btn.querySelector('i'); if (!icon) return;
    if (on){ icon.setAttribute('data-prev', icon.className || 'fa fa-spinner'); icon.className='fa fa-spinner fa-spin'; btn.disabled=true; }
    else   { var p=icon.getAttribute('data-prev')||'fa fa-floppy-o'; icon.className=p; icon.removeAttribute('data-prev'); btn.disabled=false; }
  }

  /* ========= AJAX helpers ========= */
  function rowToParams(rowData){
    var p = new URLSearchParams();
    for (var key in rowData){ if (!rowData.hasOwnProperty(key)) continue; p.append(key, (rowData[key]==null)? '' : String(rowData[key]));
    }
    return p;
  }
  async function postForm(url, params){
    var res = await fetch(url, { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8'}, body: params.toString() });
    var text = await res.text();
    try{ return JSON.parse(text); }
    catch(e){
      alert("Server mengirim non-JSON (cek Network → Response):\n\n" + text.slice(0, 1000));
      return { ok:false, message:'Respons tidak valid' };
    }
  }

  /* ========= REQUIRED: TGL & SHIFT ========= */
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
    var cell = row.getCell(field); if (!cell) return;
    var el = cell.getElement(); el.classList.add('cell-error'); if (msg) el.title = msg;
  }
  function validateRequiredRow(row){
    clearRowErrors(row);
    var d = row.getData();
    var missing = [];
    REQUIRED_FIELDS.forEach(function(req){
      var v = d[req.field];
      if (v==null || String(v).trim()===''){ missing.push(req.label); markCellError(row, req.field, req.label + ' wajib diisi'); }
    });
    if (!missing.includes('SHIFT')){
      var ok = {"1":true,"2":true,"3":true}[String(d.shift||'').trim()];
      if (!ok){ missing.push('SHIFT (hanya 1/2/3)'); markCellError(row, 'shift', 'SHIFT hanya boleh 1/2/3'); }
    }
    return { ok: missing.length===0, missing: missing };
  }

  /* ========= Auto-suggest setelah TGL & SHIFT valid ========= */
  async function fetchSuggest(dateStr, shiftStr){
    if(!dateStr || !/^[123]$/.test(String(shiftStr||''))) return null;
    var url = 'pages/ajax/suggest_summary_darkroom.php?date='+encodeURIComponent(dateStr)+'&shift='+shiftStr;
    try{
      const res  = await fetch(url);
      const json = await res.json();
      if (!json || !json.ok){ console.warn('Suggest failed:', json && json.message); return null; }
      return json;
    }catch(e){ console.warn('Suggest error:', e); return null; }
  }
  async function suggestForRow(row){
    var d = row.getData();
    if (!d.tgl) return;
    var s = String(d.shift||'').trim();
    if (!/^[123]$/.test(s)) return;

    var sug = await fetchSuggest(d.tgl, s);
    if (!sug) return;

    var list = [];
    if (Array.isArray(sug.list)) list = sug.list;
    else if (typeof sug.suffix === 'string') list = splitToList(sug.suffix);

    row.update({
      suffix: list.join(' '),
      jumlah: list.length
    });
    row.reformat();
  }

  table.on('cellEdited', function(cell){
    var f = cell.getField();
    if (f === 'tgl' || f === 'shift'){ suggestForRow(cell.getRow()); }
    if (f === 'tgl' || f === 'shift'){
      var el = cell.getElement(); el.classList.remove('cell-error'); el.removeAttribute('title');
    }
  });

  /* ========= Save / Delete ========= */
  async function saveRow(row, btn){
    var data = row.getData();

    // Wajib: TGL & SHIFT
    var req = validateRequiredRow(row);
    if (!req.ok){
      try{
        table.scrollToRow(row, "center", false);
        for (var i=0;i<REQUIRED_FIELDS.length;i++){
          var f = REQUIRED_FIELDS[i].field;
          var c = row.getCell(f);
          if (c && c.getElement().classList.contains('cell-error')){ c.edit(); break; }
        }
      }catch(e){}
      alert('Kolom "' + req.missing.join(', ') + '" WAJIB DI ISI!');
      return;
    }

    var isUpdate = !!data.id;
    var url = isUpdate ? 'pages/ajax/update_row_summary_darkroom.php'
                       : 'pages/ajax/save_row_summary_darkroom.php';
    if (btn) setBtnSpinner(btn, true);
    var resp = await postForm(url, rowToParams(data));
    if (btn) setBtnSpinner(btn, false);
    alert((resp && resp.message) ? resp.message : (isUpdate ? 'Update selesai' : 'Tersimpan'));
    if (!isUpdate && resp && resp.ok && resp.id){ row.update({ id: resp.id }); }
    row.reformat();
  }

  async function deleteRow(row, btn){
    var data = row.getData();
    if (!confirm('Hapus baris ini?')) return;
    if (data.id){
      if (btn) setBtnSpinner(btn, true);
      var p = new URLSearchParams(); p.append('id', String(data.id));
      var resp = await postForm('pages/ajax/delete_row_summary_darkroom.php', p);
      if (btn) setBtnSpinner(btn, false);
      if (!resp || !resp.ok){ alert((resp && resp.message) ? resp.message : 'Gagal hapus di DB'); return; }
    }
    row.delete();
  }

  /* ========= Load data ========= */
  async function loadData(from, to){
    try{
      var qs = [];
      if (from) qs.push('from='+encodeURIComponent(from));
      if (to)   qs.push('to='+encodeURIComponent(to));
      var url = 'pages/ajax/get_summary_darkroom.php' + (qs.length? ('?'+qs.join('&')) : '');
      var res = await fetch(url);
      var json = await res.json();
      if (json && json.ok){
        var rows = json.data || [];
        table.setData(rows);

        // filter klien (tanggal saja)
        table.clearFilter(true);
        if (from || to){
          table.setFilter(function(d){
            var tgl = (d.tgl || '').trim();
            return (!from || (tgl && tgl >= from)) && (!to || (tgl && tgl <= to));
          });
        }

        if (!rows.length){
          table.setPage(1);
          setTimeout(function(){
            table.addRow({}, true);
            requestAnimationFrame(()=> table.redraw(true));
          },0);
        }
      }else{
        table.clearData();
        table.setPage(1);
        setTimeout(function(){ table.addRow({}, true); },0);
        alert(json && json.message ? json.message : 'Gagal ambil data');
      }
    }catch(e){
      table.clearData();
      table.setPage(1);
      setTimeout(function(){ table.addRow({}, true); },0);
      alert('Gagal ambil data');
    }
  }

  /* ========= Export ========= */
  document.getElementById('exportXls').addEventListener('click', function () {
    var rows = table.getRows('active').map(r => r.getData());
    var head = ['TGL','SHIFT','JUMLAH','KET'];
    var body = rows.map(function(r){ return [r.tgl||'', r.shift||'', r.jumlah||'', r.ket||'']; });
    var aoa = [head].concat(body);
    var wb = XLSX.utils.book_new();
    var ws = XLSX.utils.aoa_to_sheet(aoa);
    ws['!cols'] = [{wch:14},{wch:12},{wch:10},{wch:30}];
    XLSX.utils.book_append_sheet(wb, ws, 'Summary');
    XLSX.writeFile(wb, 'Summary-Darkroom.xlsx');
  });

  document.getElementById('exportPdf').addEventListener('click', function(){
    const { jsPDF } = window.jspdf;
    var doc = new jsPDF({ orientation:'landscape', unit:'pt', format:'a4' });
    doc.setFontSize(14); doc.setFont(undefined,'bold');
    doc.text('SUMMARY DARKROOM', 40, 40);
    doc.setFontSize(9); doc.setFont(undefined,'normal');

    var rows = table.getRows('active').map(r => r.getData());
    var body = rows.map(function(r){ return [r.tgl||'', r.shift||'', r.jumlah||'', r.ket||'']; });

    doc.autoTable({
      startY: 56,
      head: [[ 'TGL','SHIFT','JUMLAH','KET' ]],
      body: body,
      styles:{ fontSize:9, halign:'center', valign:'middle', lineWidth:0.3, lineColor:[170,170,170] },
      headStyles:{ fillColor:[224,232,241], textColor:[0,0,0], fontStyle:'bold' },
      alternateRowStyles:{ fillColor:[248,250,252] },
      margin:{ left:40, right:40 },
      tableWidth:'auto'
    });

    doc.save('Summary-Darkroom.pdf');
  });

  /* ========= First load ========= */
  loadData();
</script>
