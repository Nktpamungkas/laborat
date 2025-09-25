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

  /* sembunyikan panah sort */
  .tabulator .tabulator-col .tabulator-arrow{ display:none !important; }
  .tabulator .tabulator-col{ cursor: default !important; }

  /* number spinner hilang */
  .tabulator .tabulator-cell input[type=number]::-webkit-outer-spin-button,
  .tabulator .tabulator-cell input[type=number]::-webkit-inner-spin-button{ -webkit-appearance: none; margin: 0; }
  .tabulator .tabulator-cell input[type=number]{ -moz-appearance: textfield; }

  /* header boleh multi-baris */
  .tabulator .tabulator-col .tabulator-col-title{
    white-space: normal;
    word-break: break-word;
  }
  .tabulator .tabulator-header .tabulator-col { justify-content: center; }

  /* === highlight sel error required === */
  .tabulator .cell-error{
    background: #ffecec !important;
  }
</style>

<h4 class="summary-title" style="text-align: center; font-weight:700; margin:4px 0 6px;">SUMMARY PRELIMINARY</h4>
<div class="toolbar">
  <button id="addRow" class="btn btn-primary">+ Tambah Baris</button>

  <input type="date" id="fromDate" class="form-control" style="height:24px;padding:2px 6px;font-size:12px;">
  <input type="time" id="fromTime" class="form-control" style="height:24px;padding:2px 6px;font-size:12px;" placeholder="HH:MM">
  <span style="align-self:center;">s/d</span>
  <input type="date" id="toDate"   class="form-control" style="height:24px;padding:2px 6px;font-size:12px;">
  <input type="time" id="toTime"   class="form-control" style="height:24px;padding:2px 6px;font-size:12px;" placeholder="HH:MM">

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
      wrap.style.position   = 'fixed';
      wrap.style.left       = rect.left + 'px';
      wrap.style.top        = (rect.bottom + 2) + 'px';
      wrap.style.zIndex     = '99999';
      wrap.style.background = '#fff';
      wrap.style.border     = '1px solid #aaa';
      wrap.style.boxShadow  = '0 2px 8px rgba(0,0,0,.15)';
      wrap.style.padding    = '6px';
      wrap.style.boxSizing  = 'border-box';
      wrap.style.overflow   = 'visible';

      var isTime = (inputType === 'time');

      var input  = document.createElement('input');
      if (isTime){
        input.type = 'text';
        input.placeholder = 'HH:MM';
        input.setAttribute('inputmode', 'numeric');
        input.setAttribute('maxlength', '5');
        input.style.fontFamily = 'monospace';
      } else {
        input.type = inputType;
        if (inputType === 'time') input.step = '60';
      }

      var cellVal = cell.getValue();
      if (cellVal) { input.value = String(cellVal); } else { input.value = ""; input.removeAttribute("value"); }
      input.style.width    = '100%';
      input.style.minWidth = (minW || 180) + 'px';
      wrap.appendChild(input);

      var panel = null;
      if (isTime){
        panel = document.createElement('div');
        panel.style.display = 'grid';
        panel.style.gridTemplateColumns = 'auto auto';
        panel.style.gap = '6px';
        panel.style.marginTop = '6px';

        var hoursBox = document.createElement('div');
        hoursBox.style.maxHeight = '144px';
        hoursBox.style.overflowY = 'auto';
        hoursBox.style.border = '1px solid ' + '#ddd';
        hoursBox.style.padding = '4px';
        hoursBox.style.width = '92px';
        var hTitle = document.createElement('div');
        hTitle.textContent = 'Jam';
        hTitle.style.fontSize = '12px';
        hTitle.style.textAlign = 'center';
        hTitle.style.marginBottom = '4px';
        hoursBox.appendChild(hTitle);

        for (let h=0; h<24; h++){
          let btn = document.createElement('button');
          btn.type = 'button';
          btn.textContent = (h<10? '0'+h : ''+h);
          btn.style.display = 'inline-block';
          btn.style.width = '40px';
          btn.style.margin = '2px';
          btn.style.fontSize = '12px';
          btn.addEventListener('click', function(ev){
            ev.preventDefault(); ev.stopPropagation();
            let cur = normalizeHHMMSoft(input.value);
            let mm  = (cur.m == null ? '' : String(cur.m));
            input.value = pad2(h) + ':' + mm;
            try { input.focus(); setCaretToEnd(input); } catch(e){}
          });
          hoursBox.appendChild(btn);
        }

        var minsBox = document.createElement('div');
        minsBox.style.maxHeight = '144px';
        minsBox.style.overflowY = 'auto';
        minsBox.style.border = '1px solid #ddd';
        minsBox.style.padding = '4px';
        minsBox.style.width = '188px';
        var mTitle = document.createElement('div');
        mTitle.textContent = 'Menit';
        mTitle.style.fontSize = '12px';
        mTitle.style.textAlign = 'center';
        mTitle.style.marginBottom = '4px';
        minsBox.appendChild(mTitle);

        for (let m=0; m<60; m++){
          let btn = document.createElement('button');
          btn.type = 'button';
          btn.textContent = pad2(m);
          btn.style.display = 'inline-block';
          btn.style.width = '40px';
          btn.style.margin = '2px';
          btn.style.fontSize = '12px';
          btn.addEventListener('click', function(ev){
            ev.preventDefault(); ev.stopPropagation();
            let cur = normalizeHHMMSoft(input.value);
            let h = (cur.h == null ? 0 : cur.h);
            input.value = pad2(h) + ':' + pad2(m);
            cleanup(true);
          });
          minsBox.appendChild(btn);
        }

        panel.appendChild(hoursBox);
        panel.appendChild(minsBox);
        wrap.appendChild(panel);
      }

      document.body.appendChild(wrap);

      function pad2(n){ n = parseInt(n,10); if (isNaN(n)) n = 0; return (n<10? '0'+n : ''+n); }
      function clamp(x, lo, hi){ if (isNaN(x)) return x; return Math.max(lo, Math.min(hi, x)); }
      function setCaretToEnd(el){ const v = el.value; try{ el.setSelectionRange(v.length, v.length); }catch(e){} }

      function normalizeHHMMSoft(v){
        v = (v || '').trim();
        if (!v) return {h:null, m:null};
        if (/^\d{1,2}:\d{0,2}$/.test(v)){
          let [h,m] = v.split(':');
          let H = (h===""? null : clamp(parseInt(h,10), 0, 23));
          let M = (m===""? null : clamp(parseInt(m,10), 0, 59));
          return {h: isNaN(H)? null:H, m: isNaN(M)? null:M};
        }
        let d = v.replace(/[^\d]/g,'').slice(0,4);
        if (d.length <= 2){
          let H = (d===""? null : clamp(parseInt(d,10),0,23));
          return {h:isNaN(H)?null:H, m:null};
        }else{
          let H = clamp(parseInt(d.slice(0,2),10),0,23);
          let M = clamp(parseInt(d.slice(2),10),0,59);
          return {h:isNaN(H)?null:H, m:isNaN(M)?null:M};
        }
      }

      function finalizeHHMM(v){
        v = (v || '').trim();
        if (!v) return '';
        const m = v.match(/^(\d{1,2})(?::(\d{1,2}))?$/);
        if (!m) return null;
        let h  = clamp(parseInt(m[1],10), 0, 23);
        let mm = (m[2] !== undefined) ? clamp(parseInt(m[2],10), 0, 59) : 0;
        if (isNaN(h) || isNaN(mm)) return null;
        return pad2(h) + ':' + pad2(mm);
      }

      setTimeout(function(){ try{ if (!isTime){ input.focus(); if (input.select) input.select(); } }catch(e){} }, 0);

      let allowClose = !isTime;
      function cleanup(commit){
        try { if (wrap && wrap.parentNode) wrap.parentNode.removeChild(wrap); } catch(e){}
        window.removeEventListener('scroll', onScroll, true);
        document.removeEventListener('mousedown', onDown, true);
        document.removeEventListener('keydown', onKey, true);

        if (commit){
          if (isTime){
            const fin = finalizeHHMM(input.value);
            if (fin === null){ cancel(); return; }
            success(fin);
          } else { success(input.value); }
        } else { cancel(); }
      }

      function onScroll(){ if (!isTime && allowClose) cleanup(true); }
      function onDown(e){
        if (!wrap.contains(e.target)){
          if (isTime){ cleanup(true); }
          else if (allowClose){ cleanup(true); }
        }
      }
      function onKey(e){
        if (e.key === 'Enter'){
          e.preventDefault();
          if (isTime){
            const fin = finalizeHHMM(input.value);
            if (fin !== null){ input.value = fin; cleanup(true); }
          } else { cleanup(true); }
        } else if (e.key === 'Escape'){ e.preventDefault(); cleanup(false); }
      }

      window.addEventListener('scroll', onScroll, true);
      document.addEventListener('mousedown', onDown, true);
      document.addEventListener('keydown', onKey, true);

      if (!isTime){
        input.addEventListener('blur',   function(){ cleanup(true); });
        input.addEventListener('change', function(){ cleanup(true); });
      }

      if (isTime){
        input.addEventListener('keydown', function(ev){
          const k = ev.key;
          const allow = (
            (k >= '0' && k <= '9') || k === ':' ||
            k === 'Backspace' || k === 'Delete' ||
            k === 'ArrowLeft' || k === 'ArrowRight' || k === 'Home' || k === 'End' ||
            k === 'Tab' || k === 'Enter' || k === 'Escape'
          );
          if (!allow) ev.preventDefault();
        });

        input.addEventListener('input', function(){
          let raw = input.value.replace(/[^\d]/g,'').slice(0,4);
          if (raw.length === 0){ input.value=''; return; }
          if (raw.length <= 2){ input.value = raw; return; }

          let hh = clamp(parseInt(raw.slice(0,2),10), 0, 23);
          let mRaw = raw.slice(2);
          if (mRaw.length === 1){ input.value = pad2(hh) + ':' + mRaw; }
          else{
            let mm = clamp(parseInt(mRaw,10), 0, 59);
            input.value = pad2(hh) + ':' + pad2(mm);
          }
        });
      }

      var dummy = document.createElement('span'); dummy.style.display = 'none'; return dummy;
    };
  }

  var dateEditor = overlayEditorFactory('date', 110);
  var timeEditor = overlayEditorFactory('time', 110);

  function w(px){ var s = Math.round(px * 0.5); return (s < 40 ? 40 : s); }

  function intValidator(cell){
    var v = cell.getValue();
    if (v === null || v === '' || typeof v === 'undefined') return true;
    return (/^-?\d+$/).test(String(v));
  }

  function toInt(x){ var n = parseInt(x,10); return isNaN(n) ? 0 : n; }

  function getActiveData(){
    var actRows = table.getRows('active');
    if (actRows && actRows.length) return actRows.map(r => r.getData());
    return table.getData();
  }

  var JML_TAIL_FIELDS = ['resep_asal','x6','t_report','t_ulang','t_gabung','warna_ctrl','resep_lain'];

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

  function zeroBlankFormatter(cell){
    var v = cell.getValue();
    return (v === 0 || v === '0') ? '' : v;
  }
  function zeroBlankDownload(value){ return (value===0 || value==='0') ? '' : value; }

  /* ===== opsi Jenis Celup ===== */
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
            formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload },
          { title:'BULK', field: prefix+'_'+name+'_bulk', width:w(80), editor:'number',
            headerHozAlign:'center', hozAlign:'center', validator:intValidator,
            formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload }
        ]
      });
    }
    return group;
  }

  /* ===== definisi kolom ===== */
  var columns = [
    { title:'ID', field:'id', visible:false, download:false },

    { title:'TGL',        field:'tgl',        editor:dateEditor,  width:w(120), headerHozAlign:'center' },
    { title:'JAM',        field:'jam',        editor:timeEditor,  width:w(100), headerHozAlign:'center' },
    {
      title:'SHIFT',
      field:'shift',
      editor:'select',
      editorParams:{ values:{ "1":"1", "2":"2", "3":"3" } },
      width:w(100),
      headerHozAlign:'center'
    },

    { title:'KLOTER',     field:'kloter',     editor:'number', validator:intValidator, width:w(90),  hozAlign:'center', headerHozAlign:'center' },

    /* ganti label ke "Jenis Celup" (field tetap jenis_kain) */
    { title:'Jenis<br>Celup', field:'jenis_kain', editor:'select',
      editorParams:{values:kainValues}, titleFormatter:"html", titleDownload:'Jenis Celup',
      width:w(120), headerHozAlign:'center' },

    { title:'STATUS', field:'status',
      editor:'select',
      editorParams:{ values:{ "NORMAL":"NORMAL", "URGENT":"URGENT" } },
      // normalisasi tampilan jadi UPPERCASE
      mutator:function(v){ return (v==null || v==='') ? '' : String(v).trim().toUpperCase(); },
      width:w(110), headerHozAlign:'center', titleDownload:'STATUS'
    },

    buildNamePairCols('visual','RESEP VISUAL'),
    buildNamePairCols('color','RESEP DATA COLOR'),

    { title:'RESEP<br>ASAL', field:'resep_asal', titleFormatter:"html", titleDownload:'RESEP ASAL',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(110), hozAlign:'center', headerHozAlign:'center' },

    { title:'X6', field:'x6',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(80), hozAlign:'center', headerHozAlign:'center' },

    { title:'T.<br>REPORT',  field:'t_report', titleFormatter:"html", titleDownload:'T. REPORT',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(110), hozAlign:'center', headerHozAlign:'center' },

    { title:'T.<br>ULANG',   field:'t_ulang', titleDownload:'T. ULANG',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(100), hozAlign:'center', headerHozAlign:'center' },

    { title:'T.<br>GABUNG',  field:'t_gabung', titleFormatter:"html", titleDownload:'T. GABUNG',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(110), hozAlign:'center', headerHozAlign:'center' },

    { title:'WARNA<br>CTRL', field:'warna_ctrl', titleFormatter:"html", titleDownload:'WARNA CTRL',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(120), hozAlign:'center', headerHozAlign:'center' },

    { title:'RESEP<br>LAIN', field:'resep_lain', titleFormatter:"html", titleDownload:'RESEP LAIN',
      editor:'number', validator:intValidator, formatter:zeroBlankFormatter, accessorDownload:zeroBlankDownload,
      width:w(110), hozAlign:'center', headerHozAlign:'center' },

    { title:'JML', field:'jml',
      editor:false, width:w(90), hozAlign:'center', headerHozAlign:'center' },

    { title:'Aksi', field:'_aksi',
      headerSort:false, headerHozAlign:'center', hozAlign:'center',
      width:87, minWidth:87, download:false,
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

  function disableSorting(cols){
    for (var i=0;i<cols.length;i++){
      cols[i].headerSort = false;
      if (cols[i].columns && cols[i].columns.length){ disableSorting(cols[i].columns); }
      if (cols[i].sorter){ cols[i].sorter = null; }
    }
    return cols;
  }
  columns = disableSorting(columns);

  var table = new Tabulator('#grid', {
    layout:'fitData',
    columnMinWidth:40,
    headerSort:false,
    columnDefaults: { vertAlign:'middle', hozAlign:'center' },
    reactiveData:true,
    addRowPos: "top",
    movableColumns:true,
    resizableRows:false,
    columns:columns,
    clipboard:true,
    clipboardPasteAction:'insert',
    selectable:true,
    placeholder:'',
    pagination: "local",
    paginationSize: 20,
    paginationSizeSelector: [10,20,50,100,true],
  });
  setTimeout(function(){ table.redraw(true); }, 0);
  window.addEventListener('resize', function(){ table.redraw(true); });

  table.on('pageLoaded', function(){ requestAnimationFrame(function(){ table.redraw(true); }); });
  table.on('columnResized',  ()=> table.redraw(true));
  table.on('columnMoved',    ()=> table.redraw(true));

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
      if (Math.abs(cur - last) > 0.001){
        last = cur; tbl.redraw(true);
      }
    }, 400);
    window.addEventListener('beforeunload', function(){ clearInterval(iv); });
  })(table);

  /* ====== FILTER RANGE TANGGAL+JAM ====== */
  function pad2(n){ n = parseInt(n,10); if (isNaN(n)) n = 0; return (n<10? '0'+n : ''+n); }

  // Gabung tanggal+jam; kalau jam kosong kita pakai default.
  function joinDateTime(d, t, isEnd){ // isEnd=true untuk batas akhir
    if (!d && !t) return '';
    if (!d) return '';
    if (!t){
      return d + (isEnd ? 'T23:59' : 'T00:00'); // <— ini kuncinya: tanggal saja = full day
    }
    const m = String(t).match(/^(\d{1,2})(?::(\d{1,2}))?$/);
    if (!m) return d + (isEnd ? 'T23:59' : 'T00:00');
    const hh = pad2(Math.min(23, Math.max(0, parseInt(m[1],10))));
    const mm = pad2(Math.min(59, Math.max(0, parseInt(m[2] || '0',10))));
    return d + 'T' + hh + ':' + mm;
  }

  function normRangeDT(){
    let fd = document.getElementById('fromDate').value || '';
    let ft = document.getElementById('fromTime').value || '';
    let td = document.getElementById('toDate').value   || '';
    let tt = document.getElementById('toTime').value   || '';

    // Tukar jika tanggal terbalik
    if (fd && td && fd > td){ let _d=fd; fd=td; td=_d; let _t=ft; ft=tt; tt=_t; }

    // Kalau jam kosong: from=00:00, to=23:59
    const fromDT = joinDateTime(fd, ft, /*isEnd=*/false);
    const toDT   = joinDateTime(td, tt, /*isEnd=*/true);

    return { fd, ft, td, tt, fromDT, toDT };
  }

  document.getElementById('applyFilter').addEventListener('click', function(){
    var r = normRangeDT();
    // backend: tetap kirim tanggal saja
    loadData(r.fd || '', r.td || '');
  });

  document.getElementById('resetFilter').addEventListener('click', function(){
    document.getElementById('fromDate').value = '';
    document.getElementById('toDate').value   = '';
    document.getElementById('fromTime').value = '';
    document.getElementById('toTime').value   = '';
    loadData();
  });

  // Enter di time langsung apply
  ['fromTime','toTime'].forEach(function(id){
    var el = document.getElementById(id);
    if (el){
      el.addEventListener('keydown', function(e){
        if (e.key === 'Enter'){ document.getElementById('applyFilter').click(); }
      });
    }
  });

  document.getElementById('addRow').addEventListener('click', function(){
    table.setPage(1);
    table.addRow({}, true);
  });

  document.getElementById('exportXls').addEventListener('click', function(){
    var rows = getActiveData();
    rows.forEach(function(r){ r.jml = computeJml(r); });
    table.getRows('active').forEach(function(r){ r.update({ jml: computeJml(r.getData()) }); });
    table.download("xlsx", "Summary-Preliminary.xlsx", {
      sheetName: "Summary",
      columnGroups: true,
      rowRange: "active"
    });
  });

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

  /* ===== REQUIRED FIELDS: TGL, JAM, SHIFT, KLOTER, Jenis Celup ===== */
  var REQUIRED_FIELDS = [
    { field: 'tgl',        label: 'TGL' },
    { field: 'jam',        label: 'JAM' },
    { field: 'shift',      label: 'SHIFT' },
    { field: 'kloter',     label: 'KLOTER' },
    { field: 'jenis_kain', label: 'Jenis Celup' },
    { field: 'status',     label: 'STATUS' },
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

    // Validasi format JAM 24 jam
    if (!missing.includes('JAM')) {
      var vjam = String((d.jam || '')).trim();
      var timeOk = /^([01]\d|2[0-3]):[0-5]\d$/.test(vjam);
      if (!timeOk){
        missing.push('JAM (format HH:MM)');
        markCellError(row, 'jam', 'Format JAM harus HH:MM, contoh 07:30 atau 16:05');
      }
    }

    // Validasi nilai sah untuk SHIFT
    if (!missing.includes('SHIFT')){
      var allowedShift = { "1":true, "2":true, "3":true };
      if (!allowedShift[String((d.shift||'')).trim()]){
        missing.push('SHIFT (hanya 1/2/3)');
        markCellError(row, 'shift', 'SHIFT hanya boleh 1/2/3');
      }
    }

    // Validasi pilihan Jenis Celup
    if (!missing.includes('Jenis Celup') && d.jenis_kain){
      var allowedKain = { "POLY":true, "COTTON":true, "WHITE":true };
      if (!allowedKain[String(d.jenis_kain).trim().toUpperCase()]){
        missing.push('Jenis Celup (nilai tidak dikenal)');
        markCellError(row, 'jenis_kain', 'Pilih nilai yang tersedia');
      }
    }

    // Validasi pilihan STATUS
    if (!missing.includes('STATUS') && d.status){
      var allowedStatus = { "NORMAL":true, "URGENT":true };
      if (!allowedStatus[String(d.status).trim().toUpperCase()]){
        missing.push('STATUS (hanya NORMAL/URGENT)');
        markCellError(row, 'status', 'Pilih NORMAL atau URGENT');
      }
    }

    return { ok: missing.length === 0, missing: missing };
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
      icon.className = 'fa fa-spinner fa-spin';
    }else{
      var prev = icon.getAttribute('data-prev') || 'fa fa-floppy-o';
      icon.className = prev;
      icon.removeAttribute('data-prev');
    }
  }

  /* ===== simpan/update per-baris ===== */
  async function saveRow(row, clickedBtn){
    var data = row.getData();

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

    data.jml = computeJml(data);
    row.update({ jml: data.jml });

    if (!validateIntRow(data)) return;

    var isUpdate = !!data.id;
    var url = isUpdate ? 'pages/ajax/update_row_summary_preliminary.php'
                       : 'pages/ajax/save_row_summary_preliminary.php';

    if (clickedBtn){ clickedBtn.disabled = true; setBtnSpinner(clickedBtn, true); }

    var resp = await postForm(url, rowToParams(data));

    if (clickedBtn){ setBtnSpinner(clickedBtn, false); clickedBtn.disabled = false; }

    alert((resp && resp.message) ? resp.message : (isUpdate ? 'Update selesai' : 'Tersimpan'));

    if (clickedBtn && resp && resp.ok){
      var icon = clickedBtn.querySelector('i');
      if (icon){
        var prev = icon.className;
        icon.className = 'fa fa-check';
        setTimeout(function(){ icon.className = prev; }, 600);
      }
    }

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

      if (clickedBtn){
        var icon = clickedBtn.querySelector('i');
        if (icon){
          var prev = icon.className;
          icon.className = 'fa fa-check';
          setTimeout(function(){}, 600);
        }
      }
    }
    row.delete();
  }

  /* ===== muat data tersimpan ===== */
  async function loadData(from, to){
    try{
      var qs = [];
      if (from) qs.push('from='+encodeURIComponent(from));
      if (to)   qs.push('to='+encodeURIComponent(to));
      var url = 'pages/ajax/get_summary_preliminary.php' + (qs.length ? ('?'+qs.join('&')) : '');

      var res = await fetch(url);
      var json = await res.json();
      if (json && json.ok){
        var rows = json.data || [];
        for (var i=0;i<rows.length;i++){ rows[i].jml = computeJml(rows[i]); }
        table.setData(rows);

        table.clearFilter(true);

        // === FILTER KLIEN: tanggal & jam ===
        var r = normRangeDT();
        if (r.fd || r.td || r.ft || r.tt){
          table.setFilter(function(data){
            var d = (data.tgl || '').trim();   // "YYYY-MM-DD"
            var t = (data.jam || '').trim();   // "HH:MM"

            if (t && !/^([01]\d|2[0-3]):[0-5]\d$/.test(t)) return false;

            // filter tanggal kasar
            if (r.fd && d && d < r.fd) return false;
            if (r.td && d && d > r.td) return false;

            // jam saja (tanpa tanggal)
            if (!r.fd && !r.td && (r.ft || r.tt)){
              if (r.ft && t && t < r.ft) return false;
              if (r.tt && t && t > r.tt) return false;
              return true;
            }

            // gabungan tanggal+jam
            var curDT = (d ? (d + 'T' + (t || '00:00')) : '');
            if (r.fromDT && curDT && curDT < r.fromDT) return false;
            if (r.toDT   && curDT && curDT > r.toDT)   return false;

            return true;
          });
        }

        if (!rows.length) { table.addRow({}, true); table.setPage(1); }
      }else{
        table.clearData(); table.addRow({}); alert(json && json.message ? json.message : 'Gagal ambil data');
      }
    }catch(e){
      table.clearData(); table.addRow({}); alert('Gagal ambil data');
    }
  }

  /* Recompute JML & bersihkan error required saat edit */
  table.on('cellEdited', function(cell){
    var f = cell.getField();
    if (JML_TRIGGERS.indexOf(f) !== -1){
      var row = cell.getRow();
      var d = row.getData();
      row.update({ jml: computeJml(d) });
    }
    if (['tgl','jam','shift','kloter','jenis_kain', 'status'].includes(f)){
      var el = cell.getElement();
      el.classList.remove('cell-error');
      el.removeAttribute('title');
    }
  });

  /* pertama kali: muat data */
  loadData();
</script>

<script>
  document.getElementById('exportPdf').addEventListener('click', function(){
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

    var zeroFields = new Set();
    N.forEach(function(n){
      zeroFields.add('visual_'+n+'_ld'); zeroFields.add('visual_'+n+'_bulk');
      zeroFields.add('color_'+n+'_ld');  zeroFields.add('color_'+n+'_bulk');
    });
    TAIL.forEach(f=>zeroFields.add(f));

    /* label kiri pakai "Jenis Celup" */
    var leftCols = [
      {field:'tgl',        title:'TGL'},
      {field:'jam',        title:'JAM'},
      {field:'shift',      title:'SHIFT'},
      {field:'kloter',     title:'KLOTER'},
      {field:'jenis_kain', title:'Jenis Celup'},
      {field:'status',     title:'STATUS'}
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

    var row1 = [], row2 = [], row3 = [];
    leftCols.forEach(c => row1.push({content:c.title, rowSpan:3, styles:{halign:'center', valign:'middle'}}));
    row1.push({content:'RESEP VISUAL',     colSpan:N.length*2, styles:{halign:'center'}});
    row1.push({content:'RESEP DATA COLOR', colSpan:N.length*2, styles:{halign:'center'}});
    tailCols.forEach(c => row1.push({content:c.title, rowSpan:3, styles:{halign:'center', valign:'middle'}}));
    N_UP.forEach(n => row2.push({content:n, colSpan:2, styles:{halign:'center'}}));
    N_UP.forEach(n => row2.push({content:n, colSpan:2, styles:{halign:'center'}}));
    for (var pass=0; pass<2; pass++){
      for (var i=0;i<N.length;i++){
        row3.push({content:'LD',   styles:{halign:'center'}});
        row3.push({content:'BULK', styles:{halign:'center'}});
      }
    }

    var rows = (function(){
      var actRows = table.getRows('active'); // mengikuti filter
      if (actRows && actRows.length) return actRows.map(r => r.getData());
      return table.getData();
    })();

    var body = rows.map(function(r){
      r.jml = computeJmlLocal(r);
      var line = [];
      leftCols.forEach(c => line.push(r[c.field] ?? ''));
      N.forEach(function(n){
        var f1='visual_'+n+'_ld', f2='visual_'+n+'_bulk';
        var v1=r[f1], v2=r[f2];
        if (zeroFields.has(f1) && (v1===0 || v1==='0' || v1==null)) v1='';
        if (zeroFields.has(f2) && (v2===0 || v2==='0' || v2==null)) v2='';
        line.push(v1==null?'':v1); line.push(v2==null?'':v2);
      });
      N.forEach(function(n){
        var f1='color_'+n+'_ld', f2='color_'+n+'_bulk';
        var v1=r[f1], v2=r[f2];
        if (zeroFields.has(f1) && (v1===0 || v1==='0' || v1==null)) v1='';
        if (zeroFields.has(f2) && (v2===0 || v2==='0' || v2==null)) v2='';
        line.push(v1==null?'':v1); line.push(v2==null?'':v2);
      });
      tailCols.forEach(function(c){
        var v = (c.field==='jml') ? r.jml : r[c.field];
        if (c.field!=='jml' && zeroFields.has(c.field) && (v===0 || v==='0' || v==null)) v='';
        line.push(v==null?'':v);
      });
      return line;
    });

    var doc = new jsPDF({ orientation:'landscape', unit:'pt', format:'a2' });
    doc.setFontSize(16); doc.setFont(undefined,'bold');
    doc.text('SUMMARY PRELIMINARY', 40, 40);

    var r = (function(){
      var f = document.getElementById('fromDate').value || '';
      var t = document.getElementById('toDate').value   || '';
      if (f && t && f > t){ var tmp=f; f=t; t=tmp; }
      return {from:f, to:t};
    })();
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
      styles: { fontSize: 9, halign: 'center', valign: 'middle', lineWidth: 0.3, lineColor: [170,170,170] },
      headStyles: { fillColor: [224,232,241], textColor: [0,0,0], fontStyle: 'bold', lineWidth: 0.6, lineColor: [120,120,120] },
      alternateRowStyles: { fillColor: [248,250,252] },
      tableLineWidth: 0.8, tableLineColor: [120,120,120],
      margin: { left: 40, right: 40 },
      tableWidth: 'auto',
      rowPageBreak: 'auto',
      didParseCell: function (data) {
        if (data.section === 'head') {
          if (data.row.index === 0) data.cell.styles.fillColor = [200,200,200];
          else if (data.row.index === 1) data.cell.styles.fillColor = [220,220,220];
          else data.cell.styles.fillColor = [235,235,235];
        }
      }
    });

    doc.save('Summary-Preliminary.pdf');
  });
</script>