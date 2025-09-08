<!-- (Opsional) Font Awesome 4 untuk ikon tombol -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
  .toolbar { margin:8px 0; display:flex; gap:6px; flex-wrap:wrap; }
  .toolbar .btn { padding:4px 8px; font-size:12px; line-height:1.2; }
  .toolbar .form-control{ display:inline-block; width:auto; min-width:130px; }

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
</style>

<h4 class="summary-title" style="text-align:center;font-weight:700;margin:4px 0 6px;">SUMMARY DISPENSING</h4>
<div class="toolbar">
  <button id="addRowDisp" class="btn btn-primary">+ Tambah Baris</button>
  <input type="date" id="fromDate" class="form-control" style="height:24px;padding:2px 6px;font-size:12px;">
  <span style="align-self:center;">s/d</span>
  <input type="date" id="toDate" class="form-control" style="height:24px;padding:2px 6px;font-size:12px;">
  <button id="applyFilter" class="btn btn-default">Filter</button>
  <button id="resetFilter" class="btn btn-default">Reset</button>
  <button id="exportXlsDisp" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</button>
  <button id="exportPdfDisp" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</button>
</div>

<div id="gridDisp"></div>

<!-- Modal SUFFIX -->
<div id="suffixModalBackdrop" class="modal-backdrop"></div>
<div id="suffixModal" class="modal-shell" role="dialog" aria-modal="true" aria-labelledby="suffixTitle">
  <div class="modal-header" id="suffixTitle">Detail SUFFIX</div>
  <div class="modal-body">
    <div class="suffix-grid">
      <label>POLY</label>
      <div>
        <div id="taPoly"   class="token-area" contenteditable="true" data-key="suffix_poly"></div>
        <div class="hint">Jumlah: <span id="countPoly">0</span></div>
      </div>
      <label>COTTON</label>
      <div>
        <div id="taCotton" class="token-area" contenteditable="true" data-key="suffix_cotton"></div>
        <div class="hint">Jumlah: <span id="countCotton">0</span></div>
      </div>
      <label>WHITE</label>
      <div>
        <div id="taWhite"  class="token-area" contenteditable="true" data-key="suffix_white"></div>
        <div class="hint">Jumlah: <span id="countWhite">0</span></div>
      </div>
    </div>
    <div class="hint" style="margin-top:8px;">Pisahkan item dengan koma (<code>,</code>), semicolon (<code>;</code>), <b>spasi</b>, atau baris baru.</div>
  </div>
  <div class="modal-footer">
    <button id="suffixCancel" class="btn btn-default">Batal</button>
    <button id="suffixSave" class="btn btn-primary">Simpan</button>
  </div>
</div>

<script>
  /* ===== Overlay editor untuk tanggal ===== */
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

  /* ===== Helpers ===== */
  function intValidator(cell){
    var v = cell.getValue();
    if (v === null || v === '' || typeof v === 'undefined') return true;
    return (/^-?\d+$/).test(String(v));
  }

  /* >> Perubahan: tidak ada penyusutan 50%, pakai nilai apa adanya */
  function w(px){ return px; }

  /* ===== Token area (badge) ===== */
  function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

  function renderTokens(el, tokens){
    if (!tokens || !tokens.length){
      el.innerHTML = '<span class="token-placeholder">Ketik item lalu pisahkan dengan koma/semicolon/spasi…</span>';
      return;
    }
    var html = tokens.map(function(t){
      return '<span class="label label-info" contenteditable="false" data-token="'+encodeURIComponent(t)+'">'+esc(t)+'<span class="token-x" title="hapus">&times;</span></span>';
    }).join(' ');
    el.innerHTML = html;
  }

  function getTokens(el){
    var arr = [];
    el.querySelectorAll('[data-token]').forEach(function(b){
      arr.push(decodeURIComponent(b.getAttribute('data-token')||''));
    });
    return arr;
  }

  // kumpulkan teks mentah dari text node di luar badge, lalu bersihkan node tsb
  function extractTypedText(el){
    var raw = [];
    (function walk(node){
      if (node.nodeType === 1){
        if (node.hasAttribute && node.hasAttribute('data-token')) return; // skip badge
        var child = node.firstChild;
        while(child){
          var next = child.nextSibling;
          if (child.nodeType === 3){
            var txt = (child.textContent||'').replace(/\u200B/g,'').trim();
            if (txt){ raw.push(txt); }
            if (child.parentNode) child.parentNode.removeChild(child);
          }else{
            walk(child);
          }
          child = next;
        }
      }
    })(el);
    return raw.join(' ');
  }

  function splitToList(text){
    if (!text) return [];
    return String(text).split(/[,\s;]+/).map(function(s){ return s.trim(); }).filter(Boolean);
  }

  function mergeAndRender(el, tokens, counterEl){
    var exists = getTokens(el);
    var set = {};
    exists.concat(tokens).forEach(function(t){ set[t] = true; });
    var merged = Object.keys(set);
    renderTokens(el, merged);
    if (counterEl) counterEl.textContent = merged.length;
  }

  function attachTokenArea(el, counterEl){
    el.addEventListener('focus', function(){
      if (el.querySelector('.token-placeholder')) el.innerHTML = '';
    });
    el.addEventListener('click', function(e){
      if (e.target && e.target.classList.contains('token-x')){
        var parent = e.target.closest('[data-token]');
        if (parent){ parent.parentNode.removeChild(parent); }
        if (counterEl) counterEl.textContent = getTokens(el).length;
      }
    });
    el.addEventListener('paste', function(e){
      e.preventDefault();
      var text = (e.clipboardData || window.clipboardData).getData('text');
      mergeAndRender(el, splitToList(text), counterEl);
    });
    el.addEventListener('keydown', function(e){
      if (e.key === 'Enter' || e.key === ' ' || e.key === ',' || e.key === ';'){
        e.preventDefault();
        var typed = extractTypedText(el);
        if (typed){ mergeAndRender(el, splitToList(typed), counterEl); }
        setTimeout(function(){
          var sel = window.getSelection(); var r = document.createRange();
          el.focus(); r.selectNodeContents(el); r.collapse(false); sel.removeAllRanges(); sel.addRange(r);
        },0);
      }
      if (e.key === 'Backspace' && !el.innerText.trim()){
        var last = el.querySelector('[data-token]:last-of-type');
        if (last){ last.parentNode.removeChild(last); if (counterEl) counterEl.textContent = getTokens(el).length; }
        e.preventDefault();
      }
    });
    el.addEventListener('blur', function(){
      var typed = extractTypedText(el);
      if (typed){ mergeAndRender(el, splitToList(typed), counterEl); }
    });
  }

  function joinTokens(arr){ return (arr && arr.length) ? arr.join(' ') : ''; }

  /* ===== Kolom ===== */
  function suffixCount(d){
    var f = function(x){ return x? String(x).split(/[,\s;]+/).map(s=>s.trim()).filter(Boolean).length : 0; };
    return f(d.suffix_poly)+f(d.suffix_cotton)+f(d.suffix_white);
  }
  function suffixCellFormatter(cell){
    var d = cell.getRow().getData();
    var n = suffixCount(d);
    return '<button class="btn btn-info btn-xs">Detail ('+ n +')</button>';
  }

  var columnsDisp = [
    { title:"ID", field:"id", visible:false, download:false },
    { title:"TGL",   field:"tgl",   editor:dateEditor, width:w(160), headerHozAlign:"center" },
    { title:"SHIFT", field:"shift", editor:"input",    width:w(140), headerHozAlign:"center" },
    { title:"TOTAL KLOTER", headerHozAlign:"center", columns:[
      { title:"POLY",   field:"ttl_kloter_poly",   editor:"number", validator:intValidator, width:w(140) },
      { title:"COTTON", field:"ttl_kloter_cotton", editor:"number", validator:intValidator, width:w(140) },
      { title:"WHITE",  field:"ttl_kloter_white",  editor:"number", validator:intValidator, width:w(140) }
    ]},
    { title:"SUFFIX", field:"_suffix_btn", width:w(180), headerSort:false, hozAlign:"center",
      formatter: suffixCellFormatter,
      cellClick: function(e, cell){ openSuffixModal(cell.getRow()); },
      download:false
    },
    { title:"suffix_poly",   field:"suffix_poly",   visible:false, download:false },
    { title:"suffix_cotton", field:"suffix_cotton", visible:false, download:false },
    { title:"suffix_white",  field:"suffix_white",  visible:false, download:false },
    { title:"BOTOL", field:"botol",
      editor:"number",
      validator:function(cell){
        var v = cell.getValue();
        if (v === null || v === '' || typeof v === 'undefined') return true; // boleh kosong saat ketik
        return (/^\d+$/).test(String(v)); // hanya 0..9 (unsigned)
      },
      width:w(180),
      hozAlign:"center"
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

  /* ===== Modal SUFFIX ===== */
  var suffixModal      = document.getElementById('suffixModal');
  var suffixBackdrop   = document.getElementById('suffixModalBackdrop');
  var taPoly           = document.getElementById('taPoly');
  var taCotton         = document.getElementById('taCotton');
  var taWhite          = document.getElementById('taWhite');
  var cPoly            = document.getElementById('countPoly');
  var cCotton          = document.getElementById('countCotton');
  var cWhite           = document.getElementById('countWhite');
  var currentSuffixRow = null;

  attachTokenArea(taPoly, cPoly);
  attachTokenArea(taCotton, cCotton);
  attachTokenArea(taWhite, cWhite);

  function onEscClose(e){ if (e.key === 'Escape'){ e.preventDefault(); closeSuffixModal(); } }
  function openSuffixModal(row){
    currentSuffixRow = row;
    var d = row.getData();
    renderTokens(taPoly,   splitToList(d.suffix_poly));
    renderTokens(taCotton, splitToList(d.suffix_cotton));
    renderTokens(taWhite,  splitToList(d.suffix_white));
    cPoly.textContent   = getTokens(taPoly).length;
    cCotton.textContent = getTokens(taCotton).length;
    cWhite.textContent  = getTokens(taWhite).length;
    suffixBackdrop.style.display = 'block';
    suffixModal.style.display = 'block';
    setTimeout(function(){ taPoly.focus(); }, 0);
    document.addEventListener('keydown', onEscClose, true);
  }
  function closeSuffixModal(){
    suffixBackdrop.style.display = 'none';
    suffixModal.style.display = 'none';
    document.removeEventListener('keydown', onEscClose, true);
    currentSuffixRow = null;
  }
  document.getElementById('suffixCancel').addEventListener('click', closeSuffixModal);
  document.getElementById('suffixSave').addEventListener('click', function(){
    if (!currentSuffixRow) return;
    var valPoly   = joinTokens(getTokens(taPoly));
    var valCotton = joinTokens(getTokens(taCotton));
    var valWhite  = joinTokens(getTokens(taWhite));
    currentSuffixRow.update({ suffix_poly:valPoly, suffix_cotton:valCotton, suffix_white:valWhite });
    currentSuffixRow.reformat(); // refresh label "Detail (N)"
    closeSuffixModal();
  });
  suffixBackdrop.addEventListener('click', closeSuffixModal);

  /* ===== Toolbar ===== */
  function normRange(){
    var f = document.getElementById('fromDate').value || '';
    var t = document.getElementById('toDate').value   || '';
    if (f && t && f > t){ var x=f; f=t; t=x; }
    return {from:f,to:t};
  }
  document.getElementById('addRowDisp').addEventListener('click', function(){ tableDisp.addRow({}); });
  document.getElementById('applyFilter').addEventListener('click', function(){ var r=normRange(); loadData(r.from, r.to); });
  document.getElementById('resetFilter').addEventListener('click', function(){ fromDate.value=''; toDate.value=''; loadData(); });

  /* ===== Spinner ===== */
  function setBtnSpinner(btn, on){
    if (!btn) return;
    var icon = btn.querySelector('i');
    if (!icon) return;
    if (on){
      icon.setAttribute('data-prev', icon.className || 'fa fa-spinner');
      icon.className = 'fa fa-spinner fa-spin';
      btn.disabled = true;
    }else{
      var prev = icon.getAttribute('data-prev') || 'fa fa-floppy-o';
      icon.className = prev;
      icon.removeAttribute('data-prev');
      btn.disabled = false;
    }
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

  /* ===== Save / Delete ===== */
  async function saveRowDisp(row, btn){
    var data = row.getData();
    var isUpdate = !!data.id;
    var url = isUpdate ? 'pages/ajax/update_row_summary_dispensing.php'
                       : 'pages/ajax/save_row_summary_dispensing.php';
    if (btn) setBtnSpinner(btn, true);
    var resp = await postForm(url, rowToParams(data));
    if (btn) setBtnSpinner(btn, false);
    alert((resp && resp.message) ? resp.message : (isUpdate ? 'Update selesai' : 'Tersimpan'));
    if (!isUpdate && resp && resp.ok && resp.id){ row.update({ id: resp.id }); }
    row.reformat(); // refresh tombol "Detail (N)"
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
        if (!rows.length) tableDisp.addRow({});
      }else{
        tableDisp.clearData(); tableDisp.addRow({});
        alert(json && json.message ? json.message : 'Gagal ambil data');
      }
    }catch(e){
      tableDisp.clearData(); tableDisp.addRow({});
      alert('Gagal ambil data');
    }
  }

  /* ===== Export Excel: header bertingkat ===== */
  document.getElementById('exportXlsDisp').addEventListener('click', function () {
    var rows = tableDisp.getRows('active').map(r => r.getData());
    function _len(t){ return t ? String(t).split(/[,\s;]+/).map(s=>s.trim()).filter(Boolean).length : 0; }

    var row1 = ['TGL','SHIFT','TOTAL KLOTER','','','JUMLAH SUFFIX','BOTOL'];
    var row2 = ['',   '',     'POLY',        'COTTON','WHITE',   '',            ''   ];

    var body = rows.map(function(r){
      return [
        r.tgl || '',
        r.shift || '',
        r.ttl_kloter_poly || '',
        r.ttl_kloter_cotton || '',
        r.ttl_kloter_white || '',
        (_len(r.suffix_poly)+_len(r.suffix_cotton)+_len(r.suffix_white)) || '',
        r.botol || ''
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

  /* ===== Export PDF: header bertingkat ===== */
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
        r.ttl_kloter_poly || '',
        r.ttl_kloter_cotton || '',
        r.ttl_kloter_white || '',
        (_len(r.suffix_poly)+_len(r.suffix_cotton)+_len(r.suffix_white)) || '',
        r.botol || ''
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
