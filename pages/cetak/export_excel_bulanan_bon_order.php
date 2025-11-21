<?php
// Rekap Bulanan Bon Order (XLSX, tanpa library)
// Kolom: A Bulan | B RMP Approved | C Bon Order Approved Lab | D OK | E Matching Ulang | F=B-C | G=(F+C)-(D+E)

declare(strict_types=1);
include "../../koneksi.php"; // $con (MySQLi), $conn1 (DB2)
mysqli_set_charset($con, "utf8mb4");

/* ===== Param bulan ===== */
$year  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');

$startDate = sprintf('%04d-%02d-01', $year, $month);
$endDate   = date('Y-m-d', strtotime("$startDate +1 month -1 day")); // akhir bulan

// Label bulan "MMM-yy" (id_ID jika tersedia)
$dt  = new DateTime("$year-$month-01");
if (class_exists('IntlDateFormatter')) {
    $fmt = new IntlDateFormatter('id_ID', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, "MMM-yy");
    $bulanLabel = $fmt->format($dt);
} else {
    $bulanMap = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
    $bulanLabel = ($bulanMap[(int)$dt->format('n')] ?? $dt->format('M')).'-'.$dt->format('y');
}

/* ===== B: BON ORDER RMP APPROVED (DB2, tanggal di bulan berjalan), distinct pair SO/OL ===== */
$sqlCodesRmp = "SELECT DISTINCT 
                    isa.CODE AS CODE
                FROM ITXVIEW_SALESORDER_APPROVED isa
                LEFT JOIN SALESORDER s
                    ON s.CODE = isa.CODE
                LEFT JOIN ADSTORAGE a
                    ON a.UNIQUEID = s.ABSUNIQUEID
                    AND a.FIELDNAME = 'ApprovalRMPDateTime'
                WHERE a.VALUETIMESTAMP IS NOT NULL
                    AND DATE(a.VALUETIMESTAMP) BETWEEN DATE('$startDate') AND DATE('$endDate')";
$resRmp = db2_exec($conn1, $sqlCodesRmp, ['cursor' => DB2_SCROLLABLE]) or die('DB2 error: ambil kode RMP.');
$codesRmp = [];
while ($row = db2_fetch_assoc($resRmp)) {
    $c = trim((string)$row['CODE']);
    if ($c !== '') $codesRmp[] = "'" . str_replace("'", "''", $c) . "'";
}
$B = 0;
if ($codesRmp) {
    $in = implode(',', $codesRmp);
    $sqlCnt = "
      SELECT COUNT(*) AS CNT FROM (
        SELECT DISTINCT SALESORDERCODE, ORDERLINE
        FROM ITXVIEWBONORDER
        WHERE SALESORDERCODE IN ($in) AND AKJ != 'AKJ'
      ) x
    ";
    $r = db2_exec($conn1, $sqlCnt, ['cursor' => DB2_SCROLLABLE]);
    if ($r && ($rc = db2_fetch_assoc($r))) $B = (int)$rc['CNT'];
}

/* ===== C: BON ORDER APPROVED LAB (MySQL status=Approved & approvalrmpdatetime di bulan) → distinct pair SO/OL dari DB2 ===== */
// Ambil daftar CODE dari MySQL (approved lab bulan ini)
$codesLab = [];
$qLab = "
  SELECT DISTINCT code
  FROM approval_bon_order
  WHERE status='Approved'
    AND DATE(approvalrmpdatetime) BETWEEN DATE('$startDate') AND DATE('$endDate')
";
$resLab = mysqli_query($con, $qLab) or die('MySQL error: ambil code approved lab.');
while ($r = mysqli_fetch_assoc($resLab)) {
    $c = trim((string)$r['code']);
    if ($c !== '') $codesLab[] = "'" . str_replace("'", "''", $c) . "'";
}
mysqli_free_result($resLab);

$C = 0;
if ($codesLab) {
    $in2 = implode(',', $codesLab);
    $sqlCnt = "
      SELECT COUNT(*) AS CNT FROM (
        SELECT DISTINCT SALESORDERCODE, ORDERLINE
        FROM ITXVIEWBONORDER
        WHERE SALESORDERCODE IN ($in2) AND AKJ != 'AKJ'
      ) x
    ";
    $r = db2_exec($conn1, $sqlCnt, ['cursor' => DB2_SCROLLABLE]);
    if ($r && ($rc = db2_fetch_assoc($r))) $C = (int)$rc['CNT'];
}

/* ===== D & E: BON ORDER SELESAI REVIEW (MySQL) untuk tgl_approve_lab bulan berjalan ===== */
// $qDE = "
// WITH base AS (
//   SELECT DATE(abo.approvalrmpdatetime) AS d_lab, smbo.status_bonorder
//   FROM approval_bon_order abo
//   LEFT JOIN status_matching_bon_order smbo ON smbo.salesorder = abo.code
// )
// SELECT
//   (SELECT COUNT(*) FROM base WHERE d_lab BETWEEN '$startDate' AND '$endDate' AND status_bonorder='OK')              AS ok_total,
//   (SELECT COUNT(*) FROM base WHERE d_lab BETWEEN '$startDate' AND '$endDate' AND status_bonorder='Matching Ulang') AS mu_total
// ";
// $resDE = mysqli_query($con, $qDE) or die('MySQL error: DE.');
// $de = mysqli_fetch_assoc($resDE) ?: ['ok_total'=>0,'mu_total'=>0];
// mysqli_free_result($resDE);
// $D = (int)$de['ok_total'];
// $E = (int)$de['mu_total'];

$D = 0;
$E = 0;

if ($codesLab) {
    $inLab = implode(',', $codesLab);

    // 1) DB2: ambil jumlah DISTINCT pair SO/OL non-AKJ per code
    //    Catatan: pakai COALESCE(AKJ,'')<>'AKJ' supaya NULL tidak dianggap AKJ.
    $sqlPairsPerCode = "
        SELECT SALESORDERCODE, COUNT(DISTINCT ORDERLINE) AS CNT
        FROM ITXVIEWBONORDER
        WHERE SALESORDERCODE IN ($inLab)
          AND COALESCE(AKJ,'') <> 'AKJ'
        GROUP BY SALESORDERCODE
    ";
    $resPairs = db2_exec($conn1, $sqlPairsPerCode, ['cursor' => DB2_SCROLLABLE]) or die('DB2 error: pairs non-AKJ.');
    $pairsPerCode = []; // [code => cnt_pair_non_akj]
    while ($row = db2_fetch_assoc($resPairs)) {
        $code = trim((string)$row['SALESORDERCODE']);
        $pairsPerCode[$code] = (int)$row['CNT'];
    }

    if (!empty($pairsPerCode)) {
        // 2) MySQL: ambil status review (OK / Matching Ulang) untuk code-code tersebut pada bulan berjalan
        //    Agar efisien, batasi ke code yang memang punya pair non-AKJ (keys dari $pairsPerCode)
        $codesForStatus = array_map(function($c){ return "'" . str_replace("'", "''", $c) . "'"; }, array_keys($pairsPerCode));
        $inCodesForStatus = implode(',', $codesForStatus);

        $qStatus = "
            SELECT abo.code, smbo.status_bonorder
            FROM approval_bon_order abo
            LEFT JOIN status_matching_bon_order smbo ON smbo.salesorder = abo.code
            WHERE DATE(abo.approvalrmpdatetime) BETWEEN '$startDate' AND '$endDate'
              AND abo.code IN ($inCodesForStatus)
        ";
        $resStatus = mysqli_query($con, $qStatus) or die('MySQL error: status review.');
        while ($r = mysqli_fetch_assoc($resStatus)) {
            $code   = trim((string)$r['code']);
            $status = trim((string)($r['status_bonorder'] ?? ''));
            $cnt    = $pairsPerCode[$code] ?? 0;

            // Distribusikan jumlah pair non-AKJ ke D atau E sesuai statusnya
            if ($cnt > 0) {
                if (strcasecmp($status, 'OK') === 0) {
                    $D += $cnt;
                } elseif (strcasecmp($status, 'Matching Ulang') === 0) {
                    $E += $cnt;
                }
            }
            // Supaya tidak terhitung dua kali jika ada duplikat baris status untuk code yang sama,
            // kamu bisa kosongkan $pairsPerCode[$code] setelah dihitung pertama kali:
            $pairsPerCode[$code] = 0;
        }
        mysqli_free_result($resStatus);
    }
}

/* ===== F & G (rumus) ===== */
$F = $B - $C;              // SISA BELUM APPROVED
$G = ($F + $C) - ($D + $E); // SISA BELUM REVIEW

/* ===== XLSX build (ZipArchive) ===== */
$filename = "Rekap_Bulanan_Bon_Order_{$bulanLabel}.xlsx";
$zip = new ZipArchive();
$tmpXlsx = tempnam(sys_get_temp_dir(), 'xlsx');
if ($zip->open($tmpXlsx, ZipArchive::OVERWRITE) !== true) { http_response_code(500); die("Gagal open zip"); }

/* Shared strings helper */
$strings = [];
$addStr = function(string $s) use (&$strings): int {
    if (!array_key_exists($s, $strings)) $strings[$s] = count($strings);
    return $strings[$s];
};

/* Data visual */
$title = "REKAP BULANAN BON ORDER ($bulanLabel)";
$hdr1  = ["BULAN","BON ORDER RMP APPROVED","BON ORDER APPROVED LAB","BON ORDER SELESAI REVIEW","","SISA BELUM APPROVED","SISA BELUM REVIEW"];
$hdr2  = ["","","","OK","MATCHING ULANG","",""];
$row4  = [$bulanLabel, $B, $C, $D, $E, $F, $G];

/* Register strings */
$idxTitle = $addStr($title);
$idxHdr1  = array_map($addStr, $hdr1);
$idxHdr2  = array_map($addStr, $hdr2);
$idxBulan = $addStr($bulanLabel);

/* sharedStrings.xml */
$sst = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
     . '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="'.(1+count($hdr1)+count($hdr2)+1).'" uniqueCount="'.count($strings).'">';
foreach ($strings as $s => $i) {
    $sst .= '<si><t xml:space="preserve">'.htmlspecialchars((string)$s, ENT_XML1).'</t></si>';
}
$sst .= '</sst>';
$zip->addFromString('xl/sharedStrings.xml', $sst);

/* styles.xml — sama palet harian (fillId=2 untuk semua header), border tipis, wrap header */
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

  <!-- 0 def, 1 wrap+center+border (text), 2 center+border (angka),
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

/* sheet1.xml (7 kolom: A..G) */
$sheet = [];
$sheet[] = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
$sheet[] = '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';
$sheet[] = '<cols>'
         . '<col min="1" max="1" width="14" customWidth="1"/>'
         . '<col min="2" max="2" width="24" customWidth="1"/>'
         . '<col min="3" max="3" width="26" customWidth="1"/>'
         . '<col min="4" max="4" width="16" customWidth="1"/>'
         . '<col min="5" max="5" width="18" customWidth="1"/>'
         . '<col min="6" max="6" width="20" customWidth="1"/>'
         . '<col min="7" max="7" width="20" customWidth="1"/>'
         . '</cols>';
$sheet[] = '<sheetData>';

/* Row 1: Title */
$sheet[] = '<row r="1" ht="22"><c r="A1" t="s" s="5"><v>'.$idxTitle.'</v></c></row>';

/* Row 2: Header level 1 (A..G) */
$sheet[] = '<row r="2" ht="46" customHeight="1">';
for ($i=0;$i<7;$i++){
    $v = $idxHdr1[$i];
    $sheet[] = '<c r="'.cRef($i+1,2).'" t="s" s="3"><v>'.$v.'</v></c>';
}
$sheet[] = '</row>';

/* Row 3: Header level 2 (A..G) → hanya D & E terisi; lain kosong */
$sheet[] = '<row r="3" ht="46" customHeight="1">';
for ($i=0;$i<7;$i++){
    $v = $idxHdr2[$i];
    $sheet[] = '<c r="'.cRef($i+1,3).'" t="s" s="4"><v>'.$v.'</v></c>';
}
$sheet[] = '</row>';

/* Row 4: Data */
$sheet[] = '<row r="4">';
$sheet[] = '<c r="A4" t="s" s="1"><v>'.$idxBulan.'</v></c>';   // Bulan
$vals = [$B,$C,$D,$E,$F,$G]; // B..G
for ($i=0;$i<count($vals);$i++){
    $sheet[] = '<c r="'.cRef($i+2,4).'" t="n" s="2"><v>'.(int)$vals[$i].'</v></c>';
}
$sheet[] = '</row>';

$sheet[] = '</sheetData>';

/* Merge:
   - A1:G1 (title)
   - A2:A3, B2:B3, C2:C3, D2:E2, F2:F3, G2:G3
*/
$sheet[] = '<mergeCells count="6">'
         . '<mergeCell ref="A1:G1"/>'
         . '<mergeCell ref="A2:A3"/>'
         . '<mergeCell ref="B2:B3"/>'
         . '<mergeCell ref="C2:C3"/>'
         . '<mergeCell ref="D2:E2"/>'
         . '<mergeCell ref="F2:F3"/>'
         . '<mergeCell ref="G2:G3"/>'
         . '</mergeCells>';

$sheet[] = '</worksheet>';
$zip->addFromString('xl/worksheets/sheet1.xml', implode('', $sheet));

/* workbook.xml */
$workbook = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"
          xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="REKAP BULANAN" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>
XML;
$zip->addFromString('xl/workbook.xml', $workbook);

/* _rels */
$zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>');

/* xl/_rels/workbook.xml.rels */
$zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>
</Relationships>');

/* Content Types */
$zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml"  ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
  <Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>
  <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
  <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
</Types>');

/* docProps */
$zip->addFromString('docProps/app.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties"
            xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
  <Application>PHP XLSX</Application>
  <DocSecurity>0</DocSecurity>
  <ScaleCrop>false</ScaleCrop>
</Properties>');

$isoNow = date('c');
$core = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"
                   xmlns:dc="http://purl.org/dc/elements/1.1/"
                   xmlns:dcterms="http://purl.org/dc/terms/"
                   xmlns:dcmitype="http://purl.org/dc/dcmitype/"
                   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <dc:title>REKAP BULANAN BON ORDER</dc:title>
  <dc:creator>System</dc:creator>
  <cp:lastModifiedBy>System</cp:lastModifiedBy>
  <dcterms:created xsi:type="dcterms:W3CDTF">{$isoNow}</dcterms:created>
  <dcterms:modified xsi:type="dcterms:W3CDTF">{$isoNow}</dcterms:modified>
</cp:coreProperties>
XML;
$zip->addFromString('docProps/core.xml', $core);

$zip->close();

/* Stream ke browser */
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Length: " . filesize($tmpXlsx));
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
readfile($tmpXlsx);
unlink($tmpXlsx);
exit;
