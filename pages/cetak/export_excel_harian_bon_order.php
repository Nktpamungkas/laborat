<?php
// REKAP HARIAN BON ORDER (XLSX)
// Kolom:
// A: BON ORDER BELUM APPROVED LAB (H-1)            => nilai = $B
// B: BON ORDER RMP APPROVED TODAY (PO GREIGE)      => nilai = $C
// C: PIC                                           => multiline per PIC
// D: BON ORDER APPROVED LAB TODAY (PO GREIGE)      => multiline per PIC ($D_per)
// E: BON ORDER SELESAI REVIEW                      => multiline per PIC ($E_per, dari tbl_log_history_matching)
// F: SISA BELUM APPROVED                           => $F = $B + $C - $D_total
// G: SISA BELUM REVIEW                             => $G = $B + $D_total - $E_total
// H: AKUMULASI SISA BON ORDER BELUM REVIEW         => $H

declare(strict_types=1);
include "../../koneksi.php"; // mysqli $con, DB2 $conn1
mysqli_set_charset($con, "utf8mb4");

/* ===== 0) Tanggal ===== */
$todays  = (int)date('N');
$kemarin = date('Y-m-d', strtotime($todays === 1 ? '-2 days' : '-1 day'));
$today   = date('Y-m-d');

// $kemarin = "2025-11-16";
// $today   = "2025-11-17";

$sqlRevCode = "
    SELECT DISTINCT code 
    FROM approval_bon_order
    WHERE is_revision = 1 
      AND DATE(approvalrmpdatetime) >= '2025-11-17'
";
$resRev = mysqli_query($con, $sqlRevCode);
if (!$resRev) {
    http_response_code(500);
    die('MySQL error: ambil kode revisi. ' . htmlspecialchars(mysqli_error($con)));
}
$excludeRevCodes = [];
while ($r = mysqli_fetch_assoc($resRev)) {
    if (!empty($r['code'])) {
        $excludeRevCodes[] = "'" . str_replace("'", "''", $r['code']) . "'";
    }
}
mysqli_free_result($resRev);

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
  $c = trim((string)$row['CODE']); 
  if ($c !== '') $codesKemarin[] = "'" . str_replace("'", "''", $c) . "'";
}
$excludeCodes = [];
$resEx = mysqli_query($con, "SELECT code FROM approval_bon_order");
if (!$resEx) { http_response_code(500); die('MySQL error: exclude list. '.htmlspecialchars(mysqli_error($con))); }
while ($r = mysqli_fetch_assoc($resEx)) {
  if (!empty($r['code'])) $excludeCodes[] = "'" . str_replace("'", "''", $r['code']) . "'";
}
mysqli_free_result($resEx);

$B = 0; // kolom A (H-1)
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
  $c = trim((string)$row['CODE']); 
  if ($c !== '') $codesToday[] = "'" . str_replace("'", "''", $c) . "'";

  /* Buang code revisi dari list C */
  if (!empty($excludeRevCodes)) {
      $codesToday = array_diff($codesToday, $excludeRevCodes);
  }
}

$C = 0; // kolom B (RMP approved today)
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

/* Tambah PIC dari tbl_log_history_matching (kalau belum ada di picList)
   dan samakan dengan case-insensitive */
$picLowerMap = [];
foreach ($picList as $p) {
    $picLowerMap[strtolower($p)] = $p;
}

$sqlLogUsers = "
  SELECT DISTINCT user_update
  FROM tbl_log_history_matching
  WHERE process = 'input'
    AND DATE(date_update) = '$today'
";
$resLogUsers = mysqli_query($con, $sqlLogUsers);
if (!$resLogUsers) {
    http_response_code(500);
    die('MySQL error: ambil user_update log history. ' . htmlspecialchars(mysqli_error($con)));
}

while ($r = mysqli_fetch_assoc($resLogUsers)) {
    $uLog = trim((string)($r['user_update'] ?? ''));
    if ($uLog === '') continue;

    $key = strtolower($uLog);

    // kalau sudah ada (beda huruf besar/kecil), jangan ditambah lagi
    if (isset($picLowerMap[$key])) continue;

    // user_update baru -> tambahkan ke picList
    $picList[] = $uLog;
    $picLowerMap[$key] = $uLog;
}
mysqli_free_result($resLogUsers);

if (!$picList) $picList = ['(tidak ada PIC)'];

/* ===== 4) Hitung per PIC: D (Approved Lab today) & E (Selesai Review) =====
   D per PIC: DISTINCT pair (SALESORDERCODE, ORDERLINE) dari DB2 untuk
              code yang Approved Lab Today & pic_lab = PIC.
   E per PIC: COUNT(*) dari tbl_log_history_matching
              WHERE process='input' AND DATE(date_update) = $today AND values_pic = $picEsc
*/
$D_per = [];
$E_per = [];

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

    if (!empty($excludeRevCodes)) {
        $codesForPic = array_diff($codesForPic, $excludeRevCodes);
    }
  }
  mysqli_free_result($resCodesPic);

  // 4b) D per PIC = DISTINCT pair dari DB2 untuk kode-kode Approved Lab today
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

  // 4c) E per PIC = BON ORDER SELESAI REVIEW (dari log history matching)
  $picEscLower = mysqli_real_escape_string($con, strtolower($picName));
  $sqlEpic = "
    SELECT COUNT(*) AS CNT
    FROM tbl_log_history_matching lhm
    WHERE lhm.process = 'insert'
      AND DATE(lhm.date_update) = '$today'
      AND LOWER(lhm.user_update) = '$picEscLower'
  ";
  $resEpic = mysqli_query($con, $sqlEpic);
  $E_per[] = (int) (mysqli_fetch_assoc($resEpic)['CNT'] ?? 0);
  mysqli_free_result($resEpic);
}

/* Totals */
$D_total = array_sum($D_per);
$E_total = array_sum($E_per);

/* ===== 5) F & G (pakai totals baru) =====
   F: SISA BELUM APPROVED = B + C - D_total
   G: SISA BELUM REVIEW   = B + D_total - E_total
*/
$F = $B + $C - $D_total;      // SISA BELUM APPROVED
$G = $B + $D_total - $E_total; // SISA BELUM REVIEW

/* ===== 6) H (AKUMULASI SISA BON ORDER BELUM REVIEW)
*/
// 6a) Kode DB2 sesuai kondisi akumulasi
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
                        AND DATE(a.VALUETIMESTAMP) >= '2025-11-17'";

$resDB2I = db2_exec($conn1, $sqlCodesI, ['cursor' => DB2_SCROLLABLE]);
if (!$resDB2I) { http_response_code(500); die('DB2 error: ambil kode untuk kolom H (akumulasi).'); }
$codesI = [];
while ($row = db2_fetch_assoc($resDB2I)) {
  $c = trim((string)$row['CODE']); 
  if ($c !== '') $codesI[] = "'" . str_replace("'", "''", $c) . "'";
}

// 6b) Set exclude pair (OK/MU) dari MySQL
$exSet = [];
$sqlEx = "
  SELECT DISTINCT smbo.salesorder, smbo.orderline
  FROM status_matching_bon_order smbo
  WHERE smbo.status_bonorder IN ('OK','Matching Ulang')
";
$resEx2 = mysqli_query($con, $sqlEx);
if (!$resEx2) { http_response_code(500); die('MySQL error (exclude kolom H): '.htmlspecialchars(mysqli_error($con))); }
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

if (!empty($excludeRevCodes)) {
    $codesApprovedMy = array_diff($codesApprovedMy, $excludeRevCodes);
}

$H = 0;
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
  if (!$resPairsI) { http_response_code(500); die('DB2 error: ambil pair kolom H (akumulasi).'); }

  while ($p = db2_fetch_assoc($resPairsI)) {
    $so = trim((string)$p['SALESORDERCODE']);
    $ol = (int)$p['ORDERLINE'];
    if (!isset($exSet[$so.'|'.$ol])) $H++;
  }
}

/* ===== 7) Siapkan teks multiline per kolom PIC/D/E ===== */
$PIC_text = implode("\n", $picList);
$D_text   = implode("\n", array_map(fn($v)=>(string)$v, $D_per));
$E_text   = implode("\n", array_map(fn($v)=>(string)$v, $E_per));

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
   A=B, B=C, C=PIC, D=D(per PIC), E=SELESAI REVIEW(per PIC), F=SISA BELUM APPROVED, G=SISA BELUM REVIEW, H=AKUMULASI
*/
$title = "REKAP HARIAN BON ORDER (" . date('d-m-Y', strtotime($today)) . ")";

$hdr1  = [
  "BON ORDER BELUM APPROVED LAB (H-1)",            // A
  "BON ORDER RMP APPROVED TODAY (PO GREIGE)",      // B
  "PIC",                                           // C
  "BON ORDER APPROVED LAB TODAY (PO GREIGE)",      // D
  "BON ORDER SELESAI REVIEW",                      // E
  "SISA BELUM APPROVED",                           // F
  "SISA BELUM REVIEW",                             // G
  "AKUMULASI SISA BON ORDER BELUM REVIEW"          // H
];
$hdr2 = ["", "", "", "", "", "", "", ""];          // baris header ke-2 kosong (tetap 2 baris header)

$idxTitle = $addStr($title);
$idxHdr1  = array_map($addStr, $hdr1);
$idxHdr2  = array_map($addStr, $hdr2);
$idxPIC   = $addStr($PIC_text);
$idxDtxt  = $addStr($D_text);
$idxEtxt  = $addStr($E_text);

/* sharedStrings.xml */
$sstCount = 1 + count($hdr1) + count($hdr2) + 3; // title + hdrs + PIC/D/E
$sst = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
     . '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="'.$sstCount.'" uniqueCount="'.count($strings).'">';
foreach ($strings as $s => $i) {
  $sst .= '<si><t xml:space="preserve">'.htmlspecialchars((string)$s, ENT_XML1).'</t></si>';
}
$sst .= '</sst>';
$zip->addFromString('xl/sharedStrings.xml', $sst);

/* styles.xml — tetap seperti versi lama (wrap & fill header sama) */
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
  while ($col > 0) {
    $mod = ($col - 1) % 26;
    $letters = chr(65 + $mod) . $letters;
    $col = intdiv($col - 1, 26);
  }
  return $letters.$row;
}

/* sheet1.xml — 8 kolom (A..H) */
$sheet = [];
$sheet[] = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
$sheet[] = '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';
$sheet[] = '<cols>'
         . '<col min="1" max="1" width="18" customWidth="1"/>'  // A = B total (H-1)
         . '<col min="2" max="2" width="22" customWidth="1"/>'  // B = C total (RMP approved today)
         . '<col min="3" max="3" width="25" customWidth="1"/>'  // C = PIC (wrap)
         . '<col min="4" max="4" width="24" customWidth="1"/>'  // D per PIC (wrap)
         . '<col min="5" max="5" width="20" customWidth="1"/>'  // E per PIC (Selesai Review)
         . '<col min="6" max="6" width="20" customWidth="1"/>'  // F (Sisa Belum Approved)
         . '<col min="7" max="7" width="20" customWidth="1"/>'  // G (Sisa Belum Review)
         . '<col min="8" max="8" width="28" customWidth="1"/>'  // H (Akumulasi)
         . '</cols>';
$sheet[] = '<sheetData>';

/* row1 Title */
$sheet[] = '<row r="1" ht="22"><c r="A1" t="s" s="5"><v>'.$idxTitle.'</v></c></row>';

/* row2 Header level-1 */
$sheet[] = '<row r="2" ht="46" customHeight="1">';
for ($i=0; $i<8; $i++) {
  $sheet[] = '<c r="'.cRef($i+1,2).'" t="s" s="3"><v>'.$idxHdr1[$i].'</v></c>';
}
$sheet[] = '</row>';

/* row3 Header level-2 (kosong, tapi tetap ada untuk merge vertikal) */
$sheet[] = '<row r="3" ht="20" customHeight="1">';
for ($i=0; $i<8; $i++) {
  $sheet[] = '<c r="'.cRef($i+1,3).'" t="s" s="4"><v>'.$idxHdr2[$i].'</v></c>';
}
$sheet[] = '</row>';

/* row4 Data:
   A=B (angka), B=C (angka), C=PIC (wrap),
   D=D_text (wrap), E=E_text (wrap),
   F=F (angka), G=G (angka), H=H (angka)
*/
$sheet[] = '<row r="4">';
$sheet[] = '<c r="A4" t="n" s="2"><v>'.$B.'</v></c>';        // A = B total (H-1)
$sheet[] = '<c r="B4" t="n" s="2"><v>'.$C.'</v></c>';        // B = C total (RMP today)
$sheet[] = '<c r="C4" t="s" s="1"><v>'.$idxPIC.'</v></c>';   // C = PIC multiline
$sheet[] = '<c r="D4" t="s" s="1"><v>'.$idxDtxt.'</v></c>';  // D per PIC multiline
$sheet[] = '<c r="E4" t="s" s="1"><v>'.$idxEtxt.'</v></c>';  // E per PIC multiline (Selesai Review)
$sheet[] = '<c r="F4" t="n" s="2"><v>'.$F.'</v></c>';        // F = Sisa Belum Approved
$sheet[] = '<c r="G4" t="n" s="2"><v>'.$G.'</v></c>';        // G = Sisa Belum Review
$sheet[] = '<c r="H4" t="n" s="2"><v>'.$H.'</v></c>';        // H = Akumulasi Sisa
$sheet[] = '</row>';

$sheet[] = '</sheetData>';

/* merge: title & header groups */
$sheet[] = '<mergeCells count="9">'
         . '<mergeCell ref="A1:H1"/>'
         . '<mergeCell ref="A2:A3"/>'
         . '<mergeCell ref="B2:B3"/>'
         . '<mergeCell ref="C2:C3"/>'
         . '<mergeCell ref="D2:D3"/>'
         . '<mergeCell ref="E2:E3"/>'
         . '<mergeCell ref="F2:F3"/>'
         . '<mergeCell ref="G2:G3"/>'
         . '<mergeCell ref="H2:H3"/>'
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
