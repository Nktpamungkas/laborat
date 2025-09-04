<!-- Tabulator CSS -->
<link href="https://unpkg.com/tabulator-tables@5.5.2/dist/css/tabulator.min.css" rel="stylesheet">
<!-- SheetJS utk export Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<style>
  .toolbar { margin:8px 0; display:flex; gap:6px; flex-wrap:wrap; }
  .toolbar .btn { padding:4px 8px; font-size:12px; line-height:1.2; }
  .toolbar .form-control{ display:inline-block; width:auto; min-width:130px; }

  /* Tabulator compact + garis lurus */
  .tabulator{
    font-size:11px;
    border:1px solid #dcdcdc;
    background:#fff !important;
  }

  .tabulator { box-sizing: border-box; }
  .tabulator .tabulator-col,
  .tabulator .tabulator-cell { box-sizing: border-box; }

  .tabulator .tabulator-header{
    border-bottom:1px solid #dcdcdc;
    background:#fff !important;
  }

  /* header wrapper jangan pakai padding (bikin width meleset) */
  .tabulator .tabulator-col,
  .tabulator .tabulator-col-group{ padding:0 !important; }

  /* isi header & cell kecil */
  .tabulator .tabulator-col .tabulator-col-content{ padding:2px 4px !important; line-height:1.2; text-align:center; }
  .tabulator .tabulator-col .tabulator-col-title{ display:block; width:100%; }
  .tabulator .tabulator-cell{ padding:2px 4px !important; line-height:1.2; background:#fff !important; }

  /* grid lines */
  .tabulator .tabulator-col{ border-right:1px solid #dcdcdc; }
  .tabulator .tabulator-cell{ border-right:1px solid #dcdcdc; }
  .tabulator .tabulator-row{ border-top:1px solid #dcdcdc; }
  .tabulator .tabulator-row:last-child{ border-bottom:1px solid #dcdcdc; }

  /* hindari garis dobel di kiri; rapikan batas kolom frozen */
  .tabulator .tabulator-col:first-child,
  .tabulator .tabulator-cell:first-child{ border-left:0 !important; }
  .tabulator .tabulator-frozen-left{ border-right:1px solid #dcdcdc; }
  .tabulator .tabulator-frozen-left .tabulator-col:last-child,
  .tabulator .tabulator-frozen-left .tabulator-cell:last-child{ border-right:0 !important; }

  /* baris terpilih & hover */
  /* .tabulator .tabulator-row:hover{ background:#f3f9ff !important; }
  .tabulator .tabulator-row.tabulator-selected{ background:#fff4bf !important; outline:2px solid #f6c744; }
  .tabulator .tabulator-row.tabulator-selected .tabulator-cell{ background:transparent !important; } */

  /* sembunyikan panah sort */
  .tabulator .tabulator-col .tabulator-arrow{ display:none !important; }
  .tabulator .tabulator-col{ cursor: default !important; }

  /* editor kecil & spinner number dikecilkan */
  /* .tabulator .tabulator-cell input,
  .tabulator .tabulator-cell select{ height:22px; line-height:22px; text-align:center; padding:1px 4px; font-size:11px; }
  .tabulator .tabulator-cell input[type=number]::-webkit-outer-spin-button,
  .tabulator .tabulator-cell input[type=number]::-webkit-inner-spin-button{ -webkit-appearance: inner-spin-button; transform: scale(0.75); margin:0; } */
  /* number spinner hilang */
  .tabulator .tabulator-cell input[type=number]::-webkit-outer-spin-button,
  .tabulator .tabulator-cell input[type=number]::-webkit-inner-spin-button{ -webkit-appearance: none; margin: 0; }
  .tabulator .tabulator-cell input[type=number]{ -moz-appearance: textfield; }

  /* header boleh multi-baris */
  .tabulator .tabulator-col .tabulator-col-title{
    white-space: normal;       /* allow wrap */
    word-break: break-word;
  }
  .tabulator .tabulator-header .tabulator-col { justify-content: center; }
</style>

<h4 class="summary-title" style="text-align: center; font-weight:700; margin:4px 0 6px;">SUMMARY PRELIMINARY</h4>
<div class="toolbar">
  <button id="addRow" class="btn btn-primary">+ Tambah Baris</button>
  <!-- <button id="delSelected" class="btn btn-danger">Hapus Baris Terpilih</button>
  <button id="clearAll" class="btn btn-default">Bersihkan Semua</button>
  <button id="reload" class="btn btn-default">Muat Ulang</button> -->
  <input type="date" id="fromDate" class="form-control" style="height:24px;padding:2px 6px;font-size:12px;">
  <span style="align-self:center;">s/d</span>
  <input type="date" id="toDate"   class="form-control" style="height:24px;padding:2px 6px;font-size:12px;">
  <button id="applyFilter" class="btn btn-default">Filter</button>
  <button id="resetFilter" class="btn btn-default">Reset</button>

  <button id="exportXls" class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button>
  <button id="exportPdf" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Export PDF</button>
</div>

<div id="grid"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>
<script src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>

<script>
  /* ===== editor overlay: date & time ===== */
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
      input.type = inputType;
      if (inputType === 'time') input.step = '60';
      input.value = cell.getValue() || '';
      input.style.width = '100%';
      input.style.minWidth = (minW || 180) + 'px';
      wrap.appendChild(input);
      document.body.appendChild(wrap);
      // === tambahan supaya klik area kosong juga munculin picker ===
      function openPicker(){
        if (typeof input.showPicker === "function") {
          try { input.showPicker(); } catch(e){}
        }
      }
      input.addEventListener("click", openPicker);
      wrap.addEventListener("click", function(e){
        if (e.target === wrap) { 
          input.focus();
          openPicker();
        }
      });
      // ===============================================
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
  var timeEditor = overlayEditorFactory('time', 110);

  /* ===== helper width 50% ===== */
  function w(px){ var s = Math.round(px * 0.5); return (s < 40 ? 40 : s); }

  /* ===== validator integer ===== */
  function intValidator(cell){
    var v = cell.getValue();
    if (v === null || v === '' || typeof v === 'undefined') return true;
    return (/^-?\d+$/).test(String(v));
  }

  /* ===== helper angka & JML ===== */
  function toInt(x){ var n = parseInt(x,10); return isNaN(n) ? 0 : n; }

  // ambil data baris AKTIF (terfilter). fallback ke semua data bila tidak ada filter
  function getActiveData(){
    var actRows = table.getRows('active');
    if (actRows && actRows.length) return actRows.map(r => r.getData());
    return table.getData();
  }

  // kolom tail yg ikut JML
  var JML_TAIL_FIELDS = ['resep_asal','x6','t_report','t_ulang','t_gabung','warna_ctrl','resep_lain'];

  // daftar nama tetap
  var N = ['hendrik','gunawan','ferdinan','gugum','ganang','citra','joni'];

  function allLdBulkFields(){
    var out = [];
    ['visual','color'].forEach(function(pref){
      N.forEach(function(n){
        out.push(pref+'_'+n+'_ld', pref+'_'+n+'_bulk');
      });
    });
    return out;
  }
  var JML_TRIGGERS = JML_TAIL_FIELDS.concat(allLdBulkFields());

  function computeJml(d){
    var total = 0;
    var lbs = allLdBulkFields();
    for (var i=0;i<lbs.length;i++){ total += toInt(d[lbs[i]]); }
    for (var j=0;j<JML_TAIL_FIELDS.length;j++){ total += toInt(d[JML_TAIL_FIELDS[j]]); }
    return total;
  }

  // tampilkan kosong kalau 0 (untuk LD/BULK & tail)
  function zeroBlankFormatter(cell){
    var v = cell.getValue();
    return (v === 0 || v === '0') ? '' : v;
  }
  // saat export excel, juga kosongkan 0
  function zeroBlankDownload(value){ return (value===0 || value==='0') ? '' : value; }

  /* ===== opsi kain ===== */
  var kainValues = { 'POLY':'POLY', 'COTTON':'COTTON', 'WHITE':'WHITE' };

  function buildNamePairCols(prefix, titleGroup){
    var group = { title: titleGroup, headerHozAlign:'center', columns: [] };
    for (var i=0; i<N.length; i++){
      var name = N[i];
      group.columns.push({
        title: name.toUpperCase(),
        headerHozAlign:'center',
        columns: [
          { title:'LD',   field: prefix+'_'+name+'_ld',   width:w(80), editor:'number',
            headerHozAlign:'center', hozAlign:'center', validator:intValidator,
            formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload }, // <<<
          { title:'BULK', field: prefix+'_'+name+'_bulk', width:w(80), editor:'number',
            headerHozAlign:'center', hozAlign:'center', validator:intValidator,
            formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload }  // <<<
        ]
      });
    }
    return group;
  }

  /* ===== definisi kolom ===== */
  var columns = [
    { title:'ID', field:'id', visible:false, download:false }, // jangan ikut export

    /* Freeze kolom-kolom ini */
    { title:'TGL',        field:'tgl',        editor:dateEditor,  width:w(120), headerHozAlign:'center' },
    { title:'JAM',        field:'jam',        editor:timeEditor,  width:w(100), headerHozAlign:'center' },
    // { title:'SHIFT',      field:'shift',      editor:'input',     width:w(100), headerHozAlign:'center' },
    { 
      title:'SHIFT',
      field:'shift',
      editor:'select',
      editorParams:{ values:{ "1":"1", "2":"2", "3":"3" } },
      width:w(100),  
      headerHozAlign:'center'
    },

    { title:'KLOTER',     field:'kloter',     editor:'number', validator:intValidator, width:w(90),  hozAlign:'center', headerHozAlign:'center' },
    { title:'Jenis<br>Kain', field:'jenis_kain', editor:'select', editorParams:{values:kainValues}, titleFormatter:"html", titleDownload:'Jenis Kain', width:w(120), headerHozAlign:'center' },

    buildNamePairCols('visual','RESEP VISUAL'),
    buildNamePairCols('color','RESEP DATA COLOR'),

    { title:'RESEP<br>ASAL', field:'resep_asal', titleFormatter:"html", titleDownload:'Jenis Kain',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(110), hozAlign:'center', headerHozAlign:'center' },

    { title:'X6', field:'x6',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(80), hozAlign:'center', headerHozAlign:'center' },

    { title:'T.<br>REPORT',  field:'t_report', titleFormatter:"html", titleDownload:'Jenis Kain',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(110), hozAlign:'center', headerHozAlign:'center' },

    { title:'T.<br>ULANG',   field:'t_ulang', titleDownload:'Jenis Kain',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(100), hozAlign:'center', headerHozAlign:'center' },

    { title:'T.<br>GABUNG',  field:'t_gabung', titleFormatter:"html", titleDownload:'Jenis Kain',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(110), hozAlign:'center', headerHozAlign:'center' },

    { title:'WARNA<br>CTRL', field:'warna_ctrl', titleFormatter:"html", titleDownload:'Jenis Kain',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(120), hozAlign:'center', headerHozAlign:'center' },

    { title:'RESEP<br>LAIN', field:'resep_lain', titleFormatter:"html", titleDownload:'Jenis Kain',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(110), hozAlign:'center', headerHozAlign:'center' },

    // JML otomatis (tidak bisa diketik)
    { title:'JML', field:'jml',
      editor:false, width:w(90), hozAlign:'center', headerHozAlign:'center' },

    /* Kolom AKSI: tombol per-baris */
    { title:'Aksi', field:'_aksi',
      headerSort:false, headerHozAlign:'center', hozAlign:'center',
      width:87, minWidth:87, download:false, /* exclude dari export */       // <<<
      formatter:function(cell){
        return '<div class="btn-group btn-group-xs">' +
                 '<button class="btn btn-primary btn-xs act-save"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>' +
                 '<button class="btn btn-danger btn-xs act-del" style="margin-left: 2px;"><i class="fa fa-trash-o" aria-hidden="true"></i></button>' +
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

  /* ===== matikan sorting di semua kolom & sub-kolom ===== */
  function disableSorting(cols){
    for (var i=0;i<cols.length;i++){
      cols[i].headerSort = false;
      if (cols[i].columns && cols[i].columns.length){ disableSorting(cols[i].columns); }
      if (cols[i].sorter){ cols[i].sorter = null; }
    }
    return cols;
  }
  columns = disableSorting(columns);

  /* ===== init tabel ===== */
  var table = new Tabulator('#grid', {
    layout:'fitData',
    columnMinWidth:40,
    headerSort:false,
    columnDefaults: { vertAlign:'middle', hozAlign:'center' },
    reactiveData:true,
    movableColumns:true,
    resizableRows:false,
    columns:columns,
    clipboard:true,
    clipboardPasteAction:'insert',
    selectable:true,
    placeholder:'',

    // --- pagination ---
    pagination: "local",
    paginationSize: 20,
    paginationSizeSelector: [10,20,50,100,true],
  });
  setTimeout(function(){ table.redraw(true); }, 0);
  window.addEventListener('resize', function(){ table.redraw(true); });
  
  table.on('pageLoaded', function(){
    requestAnimationFrame(function(){ table.redraw(true); });
  });
  table.on('columnResized',  ()=> table.redraw(true));
  table.on('columnMoved',    ()=> table.redraw(true));

  (function(tbl){
    var last = window.devicePixelRatio || 1;
    var mq = window.matchMedia('(resolution: ' + last + 'dppx)');

    function onChange(){
      requestAnimationFrame(function(){ tbl.redraw(true); });
      // re-subscribe ke DPI terbaru
      try { mq.removeEventListener('change', onChange); } catch(e){ if (mq.removeListener) mq.removeListener(onChange); }
      last = window.devicePixelRatio || 1;
      mq = window.matchMedia('(resolution: ' + last + 'dppx)');
      try { mq.addEventListener('change', onChange); } catch(e){ if (mq.addListener) mq.addListener(onChange); }
    }

    try { mq.addEventListener('change', onChange); } catch(e){ if (mq.addListener) mq.addListener(onChange); }

    // fallback polling kalau event di atas tidak nembak
    var iv = setInterval(function(){
      var cur = window.devicePixelRatio || 1;
      if (Math.abs(cur - last) > 0.001){
        last = cur;
        tbl.redraw(true);
      }
    }, 400);

    // optional: bersihkan interval saat pindah halaman
    window.addEventListener('beforeunload', function(){ clearInterval(iv); });
  })(table);

  function normRange(){
    var f = document.getElementById('fromDate').value || '';
    var t = document.getElementById('toDate').value   || '';
    if (f && t && f > t){ var tmp=f; f=t; t=tmp; } // tukar kalau terbalik
    return {from:f, to:t};
  }

  document.getElementById('applyFilter').addEventListener('click', function(){
    var r = normRange();
    loadData(r.from, r.to);
  });

  document.getElementById('resetFilter').addEventListener('click', function(){
    document.getElementById('fromDate').value = '';
    document.getElementById('toDate').value   = '';
    loadData();
  });


  /* ===== tombol toolbar ===== */
  document.getElementById('addRow').addEventListener('click', function(){ table.addRow({}); });
  //   document.getElementById('delSelected').addEventListener('click', function(){
  //     var rows = table.getSelectedRows(); if (!rows.length){ alert('Pilih minimal satu baris.'); return; }
  //     for (var i=0;i<rows.length;i++){ rows[i].delete(); }
  //   });
  //   document.getElementById('clearAll').addEventListener('click', function(){ table.clearData(); });
  //   document.getElementById('reload').addEventListener('click', loadData);

  // EXPORT EXCEL
//   document.getElementById('exportXls').addEventListener('click', function(){
//     // pastikan JML up-to-date & nol jadi kosong saat export
//     var rows = table.getData();
//     rows.forEach(function(r){ r.jml = computeJml(r); });
//     table.updateData(rows); // refresh tampilan JML (tidak wajib, tapi aman)

//     table.download("xlsx", "Summary-Preliminary.xlsx", {sheetName:"Summary"});
//   });
    document.getElementById('exportXls').addEventListener('click', function(){
        // pastikan JML up-to-date utk baris aktif
        var rows = getActiveData();
        rows.forEach(function(r){ r.jml = computeJml(r); });
        // (opsional) sync tampilan JML pada baris yang terlihat
        table.getRows('active').forEach(function(r){ r.update({ jml: computeJml(r.getData()) }); });

        table.download("xlsx", "Summary-Preliminary.xlsx", {
            sheetName: "Summary",
            columnGroups: true,
            rowRange: "active"
        });
    });

  /* ===== validasi integer ===== */
  var N_OP = ['hendrik','gunawan','ferdinan','gugum','ganang','citra','joni'];
  function buildIntKeys(){
    var keys = ['kloter','resep_asal','x6','t_report','t_ulang','t_gabung','warna_ctrl','resep_lain','jml'];
    for (var j=0;j<N_OP.length;j++){
      var n = N_OP[j];
      keys.push('visual_'+n+'_ld','visual_'+n+'_bulk','color_'+n+'_ld','color_'+n+'_bulk');
    }
    return keys;
  }
  function validateIntRow(rowData){
    var ints = buildIntKeys();
    for (var k=0;k<ints.length;k++){
      var key = ints[k], val = rowData[key];
      if (val !== null && val !== '' && typeof val !== 'undefined'){
        if (!/^-?\d+$/.test(String(val))){ alert("Kolom '"+key+"' harus integer."); return false; }
      }
    }
    return true;
  }

  /* ===== helper HTTP & tombol spinner ===== */
  function rowToParams(rowData){
    var p = new URLSearchParams();
    for (var key in rowData){ if (!rowData.hasOwnProperty(key)) continue; p.append(key, (rowData[key]==null)? '' : String(rowData[key])); }
    return p;
  }
  async function postForm(url, params){
    var res = await fetch(url, { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8'}, body: params.toString() });
    var json = null; try{ json = await res.json(); }catch(e){}
    return json || { ok:false, message:'Respons tidak valid' };
  }
  function setBtnSpinner(btn, on){
    if (!btn) return;
    var icon = btn.querySelector('i');
    if (!icon) return;
    if (on){
      icon.setAttribute('data-prev', icon.className || 'fa fa-spinner');
      icon.className = 'fa fa-spinner fa-spin';   /* FA4 */
    }else{
      var prev = icon.getAttribute('data-prev') || 'fa fa-floppy-o';
      icon.className = prev;
      icon.removeAttribute('data-prev');
    }
  }

  /* ===== simpan/update per-baris ===== */
  async function saveRow(row, clickedBtn){
    var data = row.getData();

    // hitung JML dari semua LD/BULK + tail  // <<<
    data.jml = computeJml(data);
    row.update({ jml: data.jml });

    if (!validateIntRow(data)) return;

    var isUpdate = !!data.id;
    var url = isUpdate ? 'pages/ajax/update_row_summary_preliminary.php'
                       : 'pages/ajax/save_row_summary_preliminary.php';

    if (clickedBtn){ clickedBtn.disabled = true; setBtnSpinner(clickedBtn, true); }

    var resp = await postForm(url, rowToParams(data));

    /* spinner OFF */
    if (clickedBtn){ setBtnSpinner(clickedBtn, false); clickedBtn.disabled = false; }

    /* notifikasi */
    alert((resp && resp.message) ? resp.message : (isUpdate ? 'Update selesai' : 'Tersimpan'));

    /* efek check 600ms */
    if (clickedBtn && resp && resp.ok){
      var icon = clickedBtn.querySelector('i');
      if (icon){
        var prev = icon.className;
        icon.className = 'fa fa-check';
        setTimeout(function(){ icon.className = prev; }, 600);
      }
    }

    /* jika INSERT, set id baru & refresh tombol (jadi Update) */
    if (!isUpdate && resp && resp.ok && resp.id){
      row.update({ id: resp.id });
      row.reformat();
    }
  }

  /* ===== hapus per-baris ===== */
  async function deleteRow(row, clickedBtn){
    var data = row.getData();
    if (!confirm('Hapus baris ini?')) return;

    if (data.id){
      if (clickedBtn){ clickedBtn.disabled = true; setBtnSpinner(clickedBtn, true); }
      var p = new URLSearchParams(); p.append('id', String(data.id));
      var resp = await postForm('pages/ajax/delete_row_summary_preliminary.php', p);
      if (clickedBtn){ setBtnSpinner(clickedBtn, false); clickedBtn.disabled = false; }

      if (!resp || !resp.ok){ alert((resp && resp.message) ? resp.message : 'Gagal hapus di DB'); return; }

      /* efek check 600ms di tombol hapus */
      if (clickedBtn){
        var icon = clickedBtn.querySelector('i');
        if (icon){
          var prev = icon.className;
          icon.className = 'fa fa-check';
          setTimeout(function(){ /* row akan dihapus */ }, 600);
        }
      }
    }
    row.delete();
  }

  /* ===== muat data tersimpan ===== */
    async function loadData(from, to){
        try{
            // bangun query ke server (kalau backend support, dia akan filter di SQL)
            var qs = [];
            if (from) qs.push('from='+encodeURIComponent(from));
            if (to)   qs.push('to='+encodeURIComponent(to));
            var url = 'pages/ajax/get_summary_preliminary.php' + (qs.length ? ('?'+qs.join('&')) : '');

            var res = await fetch(url);
            var json = await res.json();
            if (json && json.ok){
            var rows = json.data || [];
            // hitung JML utk setiap row
            for (var i=0;i<rows.length;i++){ rows[i].jml = computeJml(rows[i]); }
            table.setData(rows);

            // Fallback filter sisi-klien jika backend belum terapkan ?from/&to
            table.clearFilter(true);
            if (from || to){
                table.setFilter(function(data){
                var t = data.tgl || '';
                return (!from || t >= from) && (!to || t <= to);
                });
            }

            if (!rows.length) table.addRow({});
            }else{
            table.clearData(); table.addRow({}); alert(json && json.message ? json.message : 'Gagal ambil data');
            }
        }catch(e){
            table.clearData(); table.addRow({}); alert('Gagal ambil data');
        }
    }

  /* Recompute JML setiap selesai edit kolom pemicu */ // <<<
  table.on('cellEdited', function(cell){
    var f = cell.getField();
    if (JML_TRIGGERS.indexOf(f) !== -1){
      var row = cell.getRow();
      var d = row.getData();
      row.update({ jml: computeJml(d) });
    }
  });

  /* pertama kali: muat data */
  loadData();
</script>
<script>
    document.getElementById('exportPdf').addEventListener('click', function(){
        // --- setup data & helper ---
        const { jsPDF } = window.jspdf;
        var N = ['hendrik','gunawan','ferdinan','gugum','ganang','citra','joni'];
        var N_UP = N.map(n=>n.toUpperCase());

        function toInt(x){ var n=parseInt(x,10); return isNaN(n)?0:n; }
        var TAIL = ['resep_asal','x6','t_report','t_ulang','t_gabung','warna_ctrl','resep_lain'];
        function computeJmlLocal(d){
            var tot = 0;
            N.forEach(function(n){
            tot += toInt(d['visual_'+n+'_ld']);  tot += toInt(d['visual_'+n+'_bulk']);
            tot += toInt(d['color_'+n+'_ld']);   tot += toInt(d['color_'+n+'_bulk']);
            });
            TAIL.forEach(f=> tot += toInt(d[f]));
            return tot;
        }
        // kolom yg nol kita kosongkan saat export
        var zeroFields = new Set();
        N.forEach(function(n){
            zeroFields.add('visual_'+n+'_ld'); zeroFields.add('visual_'+n+'_bulk');
            zeroFields.add('color_'+n+'_ld');  zeroFields.add('color_'+n+'_bulk');
        });
        TAIL.forEach(f=>zeroFields.add(f));

        // urutan kolom seperti di grid:
        var leftCols = [
            {field:'tgl',        title:'TGL'},
            {field:'jam',        title:'JAM'},
            {field:'shift',      title:'SHIFT'},
            {field:'kloter',     title:'KLOTER'},
            {field:'jenis_kain', title:'Jenis Kain'}
        ];
        var tailCols = [
            {field:'resep_asal', title:'RESEP ASAL'},
            {field:'x6',         title:'X6'},
            {field:'t_report',   title:'T. REPORT'},
            {field:'t_ulang',    title:'T. ULANG'},
            {field:'t_gabung',   title:'T. GABUNG'},
            {field:'warna_ctrl', title:'WARNA CTRL'},
            {field:'resep_lain', title:'RESEP LAIN'},
            {field:'jml',        title:'JML'}
        ];

        // --- bangun 3 baris header ---
        var row1 = [], row2 = [], row3 = [];
        // kiri (rowSpan 3)
        leftCols.forEach(c => row1.push({content:c.title, rowSpan:3, styles:{halign:'center', valign:'middle'}}));
        // grup besar
        row1.push({content:'RESEP VISUAL',     colSpan:N.length*2, styles:{halign:'center'}});
        row1.push({content:'RESEP DATA COLOR', colSpan:N.length*2, styles:{halign:'center'}});
        // tail (rowSpan 3)
        tailCols.forEach(c => row1.push({content:c.title, rowSpan:3, styles:{halign:'center', valign:'middle'}}));
        // baris-2: nama (masing2 colSpan 2), dua kali (visual & color)
        N_UP.forEach(n => row2.push({content:n, colSpan:2, styles:{halign:'center'}}));
        N_UP.forEach(n => row2.push({content:n, colSpan:2, styles:{halign:'center'}}));
        // baris-3: LD, BULK per nama x2 (visual & color)
        for (var pass=0; pass<2; pass++){
            for (var i=0;i<N.length;i++){
            row3.push({content:'LD',   styles:{halign:'center'}});
            row3.push({content:'BULK', styles:{halign:'center'}});
            }
        }

        // --- body sesuai urutan header di atas ---
        // var rows = table.getData();
        var rows = getActiveData();
        var body = rows.map(function(r){
            r.jml = computeJmlLocal(r); // pastikan terbaru
            var line = [];
            // kiri
            leftCols.forEach(c => line.push(r[c.field] ?? ''));
            // visual (LD,BULK) per nama
            N.forEach(function(n){
            var f1='visual_'+n+'_ld', f2='visual_'+n+'_bulk';
            var v1=r[f1], v2=r[f2];
            if (zeroFields.has(f1) && (v1===0 || v1==='0' || v1==null)) v1='';
            if (zeroFields.has(f2) && (v2===0 || v2==='0' || v2==null)) v2='';
            line.push(v1==null?'':v1); line.push(v2==null?'':v2);
            });
            // color (LD,BULK) per nama
            N.forEach(function(n){
            var f1='color_'+n+'_ld', f2='color_'+n+'_bulk';
            var v1=r[f1], v2=r[f2];
            if (zeroFields.has(f1) && (v1===0 || v1==='0' || v1==null)) v1='';
            if (zeroFields.has(f2) && (v2===0 || v2==='0' || v2==null)) v2='';
            line.push(v1==null?'':v1); line.push(v2==null?'':v2);
            });
            // tail + JML
            tailCols.forEach(function(c){
            var v = (c.field==='jml') ? r.jml : r[c.field];
            if (c.field!=='jml' && zeroFields.has(c.field) && (v===0 || v==='0' || v==null)) v='';
            line.push(v==null?'':v);
            });
            return line;
        });

        // --- render PDF ---
        var doc = new jsPDF({ orientation:'landscape', unit:'pt', format:'a2' });
        doc.setFontSize(16); doc.setFont(undefined,'bold');
        doc.text('SUMMARY PRELIMINARY', 40, 40);

        var r = normRange();
        var period = (r.from || r.to) ? ('Periode: ' + (r.from || '–') + ' s/d ' + (r.to || '–')) : '';
        if (period){
            doc.setFontSize(11); doc.setFont(undefined,'normal');
            doc.text(period, 40, 56);
        }

        doc.setFontSize(8); doc.setFont(undefined,'normal');
        doc.autoTable({
            startY: 60,
            head: [row1, row2, row3],
            body: body,

            // garis & teks lebih jelas
            styles: {
                fontSize: 9,
                halign: 'center',
                valign: 'middle',
                lineWidth: 0.3,
                lineColor: [170,170,170]
            },

            // header lebih kontras
            headStyles: {
                fillColor: [224,232,241],   // biru-abu terang
                textColor: [0,0,0],
                fontStyle: 'bold',
                lineWidth: 0.6,
                lineColor: [120,120,120]
            },

            // zebra row supaya body kebaca
            alternateRowStyles: { fillColor: [248,250,252] },

            // bingkai tabel tebal
            tableLineWidth: 0.8,
            tableLineColor: [120,120,120],

            margin: { left: 40, right: 40 },
            tableWidth: 'auto',
            rowPageBreak: 'auto',

            // beda shading untuk 3 baris header (lebih kentara)
            didParseCell: function (data) {
                if (data.section === 'head') {
                if (data.row.index === 0) data.cell.styles.fillColor = [200,200,200]; // baris 1
                else if (data.row.index === 1) data.cell.styles.fillColor = [220,220,220]; // baris 2
                else data.cell.styles.fillColor = [235,235,235]; // baris 3
                }
            }
        });

        doc.save('Summary-Preliminary.pdf');
        });
</script>
