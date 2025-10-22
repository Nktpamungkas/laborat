<?php
// REKAP HARIAN BON ORDER (XLSX) — PIC setelah kolom C, D/E/F per PIC multiline
// B: DB2 (RMP-approved = kemarin) EXCLUDE MySQL (all code)
// C: DB2 (RMP-approved = today) no exclude
// D per PIC: DISTINCT pair (DB2) utk code yg Approved Lab Today (per PIC pic_lab)
// E/F per PIC: MySQL today per PIC (smbo.pic_check)
// G/H: rumus dari total
// I: ≤ today & creation > 2025-06-01, irisan approved, exclude OK/MU

declare(strict_types=1);
include "../../koneksi.php"; // mysqli $con, DB2 $conn1
mysqli_set_charset($con, "utf8mb4");

/* ===== 0) Tanggal ===== */
$todays  = (int)date('N');
$kemarin = date('Y-m-d', strtotime($todays === 1 ? '-2 days' : '-1 day'));
$today   = date('Y-m-d');

// $kemarin = "2025-10-18";
// $today   = "2025-10-20";

/* ===== 1) B: RMP-approved kemarin (DB2) EXCLUDE semua code di MySQL ===== */
$sqlCodesKemarin = "SELECT DISTINCT 
                        isa.CODE AS CODE,
                        COALESCE(ip.LANGGANAN, '') || COALESCE(ip.BUYER, '') AS CUSTOMER,
                        isa.TGL_APPROVEDRMP AS TGL_APPROVE_RMP,
                        VARCHAR_FORMAT(a.VALUETIMESTAMP, 'YYYY-MM-DD HH24:MI:SS') AS ApprovalRMPDateTime
                    FROM ITXVIEW_SALESORDER_APPROVED isa
                    LEFT JOIN SALESORDER s
                        ON s.CODE = isa.CODE
                    LEFT JOIN ITXVIEW_PELANGGAN ip
                        ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
                        AND ip.CODE = s.CODE
                    LEFT JOIN ADSTORAGE a
                        ON a.UNIQUEID = s.ABSUNIQUEID
                        AND a.FIELDNAME = 'ApprovalRMPDateTime'
                    WHERE a.VALUETIMESTAMP IS NOT NULL
                        AND DATE(a.VALUETIMESTAMP) = '$kemarin'";
$resDB2 = db2_exec($conn1, $sqlCodesKemarin, ['cursor' => DB2_SCROLLABLE]);
if (!$resDB2) { http_response_code(500); die('DB2 error: ambil kode kemarin.'); }
$codesKemarin = [];
while ($row = db2_fetch_assoc($resDB2)) {
  $c = trim((string)$row['CODE']); if ($c !== '') $codesKemarin[] = "'" . str_replace("'", "''", $c) . "'";
}
$excludeCodes = [];
$resEx = mysqli_query($con, "SELECT code FROM approval_bon_order");
if (!$resEx) { http_response_code(500); die('MySQL error: exclude list. '.htmlspecialchars(mysqli_error($con))); }
while ($r = mysqli_fetch_assoc($resEx)) {
  if (!empty($r['code'])) $excludeCodes[] = "'" . str_replace("'", "''", $r['code']) . "'";
}
mysqli_free_result($resEx);

$B = 0;
if (!empty($codesKemarin)) {
  $inKemarin = implode(",", $codesKemarin);
  $sqlCountB = "
    SELECT COUNT(*) AS CNT
    FROM (
      SELECT DISTINCT i.SALESORDERCODE, i.ORDERLINE
      FROM ITXVIEWBONORDER i
      WHERE i.SALESORDERCODE IN ($inKemarin)
      AND i.AKJ != 'AKJ'
      " . (!empty($excludeCodes) ? " AND i.SALESORDERCODE NOT IN (" . implode(",", $excludeCodes) . ") " : "") . "
    ) x
  ";
  $resCountB = db2_exec($conn1, $sqlCountB, ['cursor' => DB2_SCROLLABLE]);
  if ($resCountB && ($rc = db2_fetch_assoc($resCountB))) $B = (int)$rc['CNT'];
}

/* ===== 2) C: RMP-approved hari ini (DB2) tanpa exclude ===== */
$sqlCodesToday ="SELECT DISTINCT 
                        isa.CODE AS CODE,
                        COALESCE(ip.LANGGANAN, '') || COALESCE(ip.BUYER, '') AS CUSTOMER,
                        isa.TGL_APPROVEDRMP AS TGL_APPROVE_RMP,
                        VARCHAR_FORMAT(a.VALUETIMESTAMP, 'YYYY-MM-DD HH24:MI:SS') AS ApprovalRMPDateTime
                    FROM ITXVIEW_SALESORDER_APPROVED isa
                    LEFT JOIN SALESORDER s
                        ON s.CODE = isa.CODE
                    LEFT JOIN ITXVIEW_PELANGGAN ip
                        ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
                        AND ip.CODE = s.CODE
                    LEFT JOIN ADSTORAGE a
                        ON a.UNIQUEID = s.ABSUNIQUEID
                        AND a.FIELDNAME = 'ApprovalRMPDateTime'
                    WHERE a.VALUETIMESTAMP IS NOT NULL
                        AND DATE(a.VALUETIMESTAMP) = '$today'";

$resDB2C = db2_exec($conn1, $sqlCodesToday, ['cursor' => DB2_SCROLLABLE]);
if (!$resDB2C) { http_response_code(500); die('DB2 error: ambil kode hari ini.'); }
$codesToday = [];
while ($row = db2_fetch_assoc($resDB2C)) {
  $c = trim((string)$row['CODE']); if ($c !== '') $codesToday[] = "'" . str_replace("'", "''", $c) . "'";
}

$C = 0;
if (!empty($codesToday)) {
  $inToday = implode(",", $codesToday);
  $sqlCountC = "
    SELECT COUNT(*) AS CNT
    FROM (
      SELECT DISTINCT i.SALESORDERCODE, i.ORDERLINE
      FROM ITXVIEWBONORDER i
      WHERE i.SALESORDERCODE IN ($inToday) 
      AND i.AKJ != 'AKJ'
    ) x
  ";
  $resCountC = db2_exec($conn1, $sqlCountC, ['cursor' => DB2_SCROLLABLE]);
  if ($resCountC && ($rc = db2_fetch_assoc($resCountC))) $C = (int)$rc['CNT'];
}

/* ===== 3) Ambil daftar PIC (urut tbl_user.pic_bonorder=1) ===== */
$picList = [];
$resPIC = mysqli_query($con, "SELECT username FROM tbl_user WHERE pic_bonorder = 1 ORDER BY id ASC");
if (!$resPIC) { http_response_code(500); die('MySQL error: ambil PIC. '.htmlspecialchars(mysqli_error($con))); }
while ($r = mysqli_fetch_assoc($resPIC)) {
  $u = trim((string)($r['username'] ?? ''));
  if ($u !== '') $picList[] = $u;
}
mysqli_free_result($resPIC);
if (!$picList) $picList = ['(tidak ada PIC)'];

/* ===== 4) Hitung per PIC: D/E/F =====
   D per PIC: DISTINCT pair (SALESORDERCODE, ORDERLINE) dari DB2 untuk
              code yang Approved Lab Today & pic_lab = PIC.
   E per PIC: COUNT(*) status_bonorder='OK' (tgl_approve_lab=today) dan smbo.pic_check = PIC
   F per PIC: COUNT(*) status_bonorder='Matching Ulang' (tgl_approve_lab=today) dan smbo.pic_check = PIC
*/
$D_per = []; $E_per = []; $F_per = [];

foreach ($picList as $picName) {
  $picEsc = mysqli_real_escape_string($con, $picName);

  // 4a) Kode MySQL Approved Lab today untuk PIC ini
  $codesForPic = [];
  $sqlCodesPic = "
    SELECT DISTINCT code
    FROM approval_bon_order
    WHERE DATE(tgl_approve_rmp) = '$today'
      AND status = 'Approved'
      AND pic_lab = '$picEsc'
  ";
  $resCodesPic = mysqli_query($con, $sqlCodesPic);
  if (!$resCodesPic) { http_response_code(500); die('MySQL error (codes per PIC): '.htmlspecialchars(mysqli_error($con))); }
  while ($r = mysqli_fetch_assoc($resCodesPic)) {
    if (!empty($r['code'])) $codesForPic[] = "'" . str_replace("'", "''", $r['code']) . "'";
  }
  mysqli_free_result($resCodesPic);

  // 4b) D per PIC = DISTINCT pair dari DB2 untuk kode di atas
  $D_val = 0;
  if (!empty($codesForPic)) {
    $inPicCodes = implode(",", $codesForPic);
    $sqlD_DB2 = "
      SELECT COUNT(*) AS CNT
      FROM (
        SELECT DISTINCT i.SALESORDERCODE, i.ORDERLINE
        FROM ITXVIEWBONORDER i
        WHERE i.SALESORDERCODE IN ($inPicCodes)
        AND i.AKJ != 'AKJ'
      ) x
    ";
    $resD_DB2 = db2_exec($conn1, $sqlD_DB2, ['cursor' => DB2_SCROLLABLE]);
    if ($resD_DB2 && ($rc = db2_fetch_assoc($resD_DB2))) {
      $D_val = (int)$rc['CNT'];
    }
  }
  $D_per[] = $D_val;

  // 4c) E per PIC = OK
  $sqlEpic = "
    SELECT COUNT(*) AS CNT
    FROM approval_bon_order abo
    LEFT JOIN status_matching_bon_order smbo ON smbo.salesorder = abo.code
    WHERE DATE(abo.approvalrmpdatetime) = '$today'
      AND smbo.status_bonorder = 'OK'
      AND smbo.pic_check = '$picEsc'
  ";
  $resEpic = mysqli_query($con, $sqlEpic);
  $E_per[] = (int) (mysqli_fetch_assoc($resEpic)['CNT'] ?? 0);
  mysqli_free_result($resEpic);

  // 4d) F per PIC = Matching Ulang
  $sqlFpic = "
    SELECT COUNT(*) AS CNT
    FROM approval_bon_order abo
    LEFT JOIN status_matching_bon_order smbo ON smbo.salesorder = abo.code
    WHERE DATE(abo.approvalrmpdatetime) = '$today'
      AND smbo.status_bonorder = 'Matching Ulang'
      AND smbo.pic_check = '$picEsc'
  ";
  $resFpic = mysqli_query($con, $sqlFpic);
  $F_per[] = (int) (mysqli_fetch_assoc($resFpic)['CNT'] ?? 0);
  mysqli_free_result($resFpic);
}

/* Totals untuk rumus */
$D_total = array_sum($D_per);
$E_total = array_sum($E_per);
$F_total = array_sum($F_per);

/* ===== 5) G & H (pakai totals baru) ===== */
$G = $B + $C - $D_total;                 // SISA BELUM APPROVED
$H = $B + $D_total - ($E_total + $F_total); // SISA BELUM REVIEW

/* ===== 6) I: ≤ today & creation > 2025-06-01, irisan approved, exclude pair OK/MU ===== */
// 6a) Kode DB2 sesuai kondisi I
$sqlCodesI = "SELECT DISTINCT 
                        isa.CODE AS CODE,
                        COALESCE(ip.LANGGANAN, '') || COALESCE(ip.BUYER, '') AS CUSTOMER,
                        isa.TGL_APPROVEDRMP AS TGL_APPROVE_RMP,
                        VARCHAR_FORMAT(a.VALUETIMESTAMP, 'YYYY-MM-DD HH24:MI:SS') AS ApprovalRMPDateTime
                    FROM ITXVIEW_SALESORDER_APPROVED isa
                    LEFT JOIN SALESORDER s
                        ON s.CODE = isa.CODE
                    LEFT JOIN ITXVIEW_PELANGGAN ip
                        ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
                        AND ip.CODE = s.CODE
                    LEFT JOIN ADSTORAGE a
                        ON a.UNIQUEID = s.ABSUNIQUEID
                        AND a.FIELDNAME = 'ApprovalRMPDateTime'
                    WHERE a.VALUETIMESTAMP IS NOT NULL
                        AND DATE(a.VALUETIMESTAMP) <= DATE('$today')
                        AND DATE(s.CREATIONDATETIME) > DATE('2025-06-01')";

$resDB2I = db2_exec($conn1, $sqlCodesI, ['cursor' => DB2_SCROLLABLE]);
if (!$resDB2I) { http_response_code(500); die('DB2 error: ambil kode untuk kolom I.'); }
$codesI = [];
while ($row = db2_fetch_assoc($resDB2I)) {
  $c = trim((string)$row['CODE']); if ($c !== '') $codesI[] = "'" . str_replace("'", "''", $c) . "'";
}

// 6b) Set exclude pair (OK/MU) dari MySQL
$exSet = [];
$sqlEx = "
  SELECT DISTINCT smbo.salesorder, smbo.orderline
  FROM status_matching_bon_order smbo
  WHERE smbo.status_bonorder IN ('OK','Matching Ulang')
";
$resEx2 = mysqli_query($con, $sqlEx);
if (!$resEx2) { http_response_code(500); die('MySQL error (exclude kolom I): '.htmlspecialchars(mysqli_error($con))); }
while ($r = mysqli_fetch_assoc($resEx2)) {
  $so = trim((string)($r['salesorder'] ?? ''));
  $ol = (string)(isset($r['orderline']) ? (int)$r['orderline'] : 0);
  if ($so !== '') $exSet[$so.'|'.$ol] = true;
}
mysqli_free_result($resEx2);

// 6c) Irisan dengan Approved (MySQL)
$codesApprovedMy = [];
$resAp = mysqli_query($con, "SELECT DISTINCT code FROM approval_bon_order WHERE status = 'Approved'");
if (!$resAp) { http_response_code(500); die('MySQL error: ambil codes Approved. '.htmlspecialchars(mysqli_error($con))); }
while ($r = mysqli_fetch_assoc($resAp)) {
  if (!empty($r['code'])) $codesApprovedMy[] = "'" . str_replace("'", "''", $r['code']) . "'";
}
mysqli_free_result($resAp);

$I = 0;
if (!empty($codesI) && !empty($codesApprovedMy)) {
  $inI        = implode(",", $codesI);
  $inApproved = implode(",", $codesApprovedMy);
  $sqlPairsI = "
    SELECT DISTINCT i.SALESORDERCODE, i.ORDERLINE
    FROM ITXVIEWBONORDER i
    WHERE i.SALESORDERCODE IN ($inI)
      AND i.SALESORDERCODE IN ($inApproved)
      AND i.AKJ != 'AKJ'
  ";
  $resPairsI = db2_exec($conn1, $sqlPairsI, ['cursor' => DB2_SCROLLABLE]);
  if (!$resPairsI) { http_response_code(500); die('DB2 error: ambil pair kolom I.'); }

  while ($p = db2_fetch_assoc($resPairsI)) {
    $so = trim((string)$p['SALESORDERCODE']);
    $ol = (int)$p['ORDERLINE'];
    if (!isset($exSet[$so.'|'.$ol])) $I++;
  }
}

/* ===== 7) Siapkan teks multiline per kolom PIC/D/E/F ===== */
$PIC_text = implode("\n", $picList);
$D_text   = implode("\n", array_map(fn($v)=>(string)$v, $D_per));
$E_text   = implode("\n", array_map(fn($v)=>(string)$v, $E_per));
$F_text   = implode("\n", array_map(fn($v)=>(string)$v, $F_per));

/* ===== 8) Bikin XLSX (ZipArchive) ===== */
$filename = "Rekap_Harian_Bon_Order_" . date('d-m-Y', strtotime($today)) . ".xlsx";
$zip = new ZipArchive();
$tmpXlsx = tempnam(sys_get_temp_dir(), 'xlsx');
if ($zip->open($tmpXlsx, ZipArchive::OVERWRITE) !== true) { http_response_code(500); die("Gagal open zip"); }

/* sharedStrings helper */
$strings = [];
$addStr = function(string $s) use (&$strings): int {
  if (!array_key_exists($s, $strings)) $strings[$s] = count($strings);
  return $strings[$s];
};

/* Header & data (urutan kolom baru): 
   A=B, B=C, C=PIC, D=D(per PIC), E=OK(per PIC), F=MU(per PIC), G, H, I */
$title = "REKAP HARIAN BON ORDER (" . date('d-m-Y', strtotime($today)) . ")";

$hdr1  = [
  "BON ORDER BELUM APPROVED LAB (H-1)",            // A
  "BON ORDER RMP APPROVED TODAY (PO GREIGE)",      // B
  "PIC",                                           // C
  "BON ORDER APPROVED LAB TODAY (PO GREIGE)",      // D
  "BON ORDER SELESAI REVIEW",                      // E-F merged
  "",                                              // (placeholder level-1 untuk F)
  "SISA BELUM APPROVED",                           // G
  "SISA BELUM REVIEW",                             // H
  "AKUMULASI SISA BON ORDER BELUM REVIEW"          // I
];
$hdr2  = [
  "", "", "", "", "OK", "MATCHING ULANG", "", "", ""
];

$row4_strings = [
  (string)$B,
  (string)$C,
  $PIC_text,
  $D_text,
  $E_text,
  $F_text
];
$row4_nums = [
  (int)$G,
  (int)$H,
  (int)$I
];

/* register strings */
$idxTitle = $addStr($title);
$idxHdr1  = array_map($addStr, $hdr1);
$idxHdr2  = array_map($addStr, $hdr2);
$idxPIC   = $addStr($PIC_text);
$idxDtxt  = $addStr($D_text);
$idxEtxt  = $addStr($E_text);
$idxFtxt  = $addStr($F_text);

/* sharedStrings.xml */
$sstCount = 1 + count($hdr1) + count($hdr2) + 4; // title + hdrs + PIC/D/E/F
$sst = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
     . '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="'.$sstCount.'" uniqueCount="'.count($strings).'">';
foreach ($strings as $s => $i) {
  $sst .= '<si><t xml:space="preserve">'.htmlspecialchars($s, ENT_XML1).'</t></si>';
}
$sst .= '</sst>';
$zip->addFromString('xl/sharedStrings.xml', $sst);

/* styles.xml — tetap seperti versi kamu (wrap & fill header sama) */
$styles = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="3">
    <font><sz val="11"/><color theme="1"/><name val="Calibri"/></font>
    <font><b/><sz val="11"/><color theme="1"/><name val="Calibri"/></font>
    <font><b/><sz val="14"/><color theme="1"/><name val="Calibri"/></font>
  </fonts>
  <fills count="3">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFBDD7EE"/><bgColor indexed="64"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFD9E1F2"/><bgColor indexed="64"/></patternFill></fill>
  </fills>
  <borders count="2">
    <border><left/><right/><top/><bottom/><diagonal/></border>
    <border><left style="thin"/><right style="thin"/><top style="thin"/><bottom style="thin"/><diagonal/></border>
  </borders>
  <cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>
  <!-- 0 def, 1 wrap+center+border (multiline), 2 center+border (angka; non-wrap),
       3 header1 bold + fill2 + border + wrap,
       4 header2 bold + fill2 + border + wrap,
       5 title bold14 + center + border -->
  <cellXfs count="6">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>
    <xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>
    <xf numFmtId="0" fontId="2" fillId="0" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>
  </cellXfs>
  <cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>
</styleSheet>
XML;
$zip->addFromString('xl/styles.xml', $styles);

/* helper col ref */
function cRef(int $col, int $row): string {
  $letters = '';
  while ($col > 0) { $mod = ($col - 1) % 26; $letters = chr(65 + $mod) . $letters; $col = intdiv($col - 1, 26); }
  return $letters.$row;
}

/* sheet1.xml — urutan kolom baru */
$sheet = [];
$sheet[] = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
$sheet[] = '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';
$sheet[] = '<cols>'
         . '<col min="1" max="1" width="18" customWidth="1"/>'  // A = B total
         . '<col min="2" max="2" width="22" customWidth="1"/>'  // B = C total
         . '<col min="3" max="3" width="25" customWidth="1"/>'  // C = PIC (wrap)
         . '<col min="4" max="4" width="24" customWidth="1"/>'  // D per PIC (wrap)
         . '<col min="5" max="5" width="16" customWidth="1"/>'  // E (OK) per PIC
         . '<col min="6" max="6" width="16" customWidth="1"/>'  // F (MU) per PIC
         . '<col min="7" max="7" width="20" customWidth="1"/>'  // G
         . '<col min="8" max="8" width="20" customWidth="1"/>'  // H
         . '<col min="9" max="9" width="28" customWidth="1"/>'  // I
         . '</cols>';
$sheet[] = '<sheetData>';

/* row1 Title */
$sheet[] = '<row r="1" ht="22"><c r="A1" t="s" s="5"><v>'.$idxTitle.'</v></c></row>';

/* row2 Header level-1 */
$sheet[] = '<row r="2" ht="46" customHeight="1">';
for ($i=0; $i<9; $i++) {
  $sheet[] = '<c r="'.cRef($i+1,2).'" t="s" s="3"><v>'.$idxHdr1[$i].'</v></c>';
}
$sheet[] = '</row>';

/* row3 Header level-2 */
$sheet[] = '<row r="3" ht="46" customHeight="1">';
for ($i=0; $i<9; $i++) {
  $sheet[] = '<c r="'.cRef($i+1,3).'" t="s" s="4"><v>'.$idxHdr2[$i].'</v></c>';
}
$sheet[] = '</row>';

/* row4 Data:
   A=B (angka), B=C (angka), C=PIC (wrap), D=DTXT (wrap), E=ETXT (wrap), F=FTXT (wrap), G/H/I angka */
$sheet[] = '<row r="4">';
$sheet[] = '<c r="A4" t="n" s="2"><v>'.$B.'</v></c>';        // B total
$sheet[] = '<c r="B4" t="n" s="2"><v>'.$C.'</v></c>';        // C total
$sheet[] = '<c r="C4" t="s" s="1"><v>'.$idxPIC.'</v></c>';   // PIC multiline
$sheet[] = '<c r="D4" t="s" s="1"><v>'.$idxDtxt.'</v></c>';  // D per PIC ml
$sheet[] = '<c r="E4" t="s" s="1"><v>'.$idxEtxt.'</v></c>';  // E per PIC ml
$sheet[] = '<c r="F4" t="s" s="1"><v>'.$idxFtxt.'</v></c>';  // F per PIC ml
$sheet[] = '<c r="G4" t="n" s="2"><v>'.$G.'</v></c>';        // G
$sheet[] = '<c r="H4" t="n" s="2"><v>'.$H.'</v></c>';        // H
$sheet[] = '<c r="I4" t="n" s="2"><v>'.$I.'</v></c>';        // I
$sheet[] = '</row>';

$sheet[] = '</sheetData>';

/* merge: title & header groups */
$sheet[] = '<mergeCells count="7">'
         . '<mergeCell ref="A1:I1"/>'
         . '<mergeCell ref="A2:A3"/>'
         . '<mergeCell ref="B2:B3"/>'
         . '<mergeCell ref="C2:C3"/>'
         . '<mergeCell ref="D2:D3"/>'
         . '<mergeCell ref="E2:F2"/>'
         . '<mergeCell ref="G2:G3"/>'
         . '<mergeCell ref="H2:H3"/>'
         . '<mergeCell ref="I2:I3"/>'
         . '</mergeCells>';

$sheet[] = '</worksheet>';
$zip->addFromString('xl/worksheets/sheet1.xml', implode('', $sheet));

/* workbook.xml */
$workbook = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"
          xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="REKAP" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>
XML;
$zip->addFromString('xl/workbook.xml', $workbook);

/* rels & content types & docProps */
$zip->addFromString('_rels/.rels',
'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>'
);
$zip->addFromString('xl/_rels/workbook.xml.rels',
'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>
</Relationships>'
);
$zip->addFromString('[Content_Types].xml',
'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml"  ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
  <Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>
  <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
  <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
</Types>'
);
$zip->addFromString('docProps/app.xml',
'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties"
            xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
  <Application>PHP XLSX</Application>
  <DocSecurity>0</DocSecurity>
  <ScaleCrop>false</ScaleCrop>
</Properties>'
);
$zip->addFromString('docProps/core.xml',
'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"
                   xmlns:dc="http://purl.org/dc/elements/1.1/"
                   xmlns:dcterms="http://purl.org/dc/terms/"
                   xmlns:dcmitype="http://purl.org/dc/dcmitype/"
                   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <dc:title>REKAP HARIAN BON ORDER</dc:title>
  <dc:creator>System</dc:creator>
  <cp:lastModifiedBy>System</cp:lastModifiedBy>
  <dcterms:created xsi:type="dcterms:W3CDTF">'.date('c').'</dcterms:created>
  <dcterms:modified xsi:type="dcterms:W3CDTF">'.date('c').'</dcterms:modified>
</cp:coreProperties>'
);

$zip->close();

/* Stream */
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Length: " . filesize($tmpXlsx));
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
readfile($tmpXlsx);
unlink($tmpXlsx);
exit;
