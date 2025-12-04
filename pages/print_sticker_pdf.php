<?php
// Server-side PDF sticker generator using TCPDF
session_start();
include "../koneksi.php";

// Some PHP environments may not have the cURL extension enabled; TCPDF references
// several CURLOPT_* constants. Define safe fallbacks to avoid undefined constant
// fatal errors when cURL is not available. If cURL is available, these definitions
// will be ignored because the constants are already defined by the extension.
$curl_consts = [
  'CURLOPT_CONNECTTIMEOUT',
  'CURLOPT_TIMEOUT',
  'CURLOPT_RETURNTRANSFER',
  'CURLOPT_SSL_VERIFYPEER',
  'CURLOPT_SSL_VERIFYHOST',
  'CURLOPT_HEADER',
  'CURLOPT_USERAGENT',
  'CURLOPT_FOLLOWLOCATION',
  'CURLOPT_HTTPHEADER',
  'CURLOPT_POST',
  'CURLOPT_POSTFIELDS',
  'CURLOPT_MAXREDIRS',
  'CURLOPT_ENCODING',
  'CURLOPT_NOBODY',
  'CURLOPT_VERBOSE',
  'CURLOPT_SSL_VERIFYHOST',
  'CURLOPT_SSLVERSION',
  'CURLOPT_CAINFO',
  'CURLOPT_CAPATH',
  'CURLOPT_PROXY',
  'CURLOPT_PROXYPORT',
  'CURLOPT_HTTPAUTH',
  'CURLOPT_PROXYUSERPWD',
  'CURLOPT_COOKIE',
  'CURLOPT_COOKIEFILE',
  'CURLOPT_COOKIEJAR',
  'CURLOPT_PROTOCOLS',
  'CURLPROTO_HTTPS',
  'CURLPROTO_HTTP',
  'CURLPROTO_FTP',
  'CURLPROTO_FTPS',
  'CURLOPT_FAILONERROR'
];
foreach ($curl_consts as $c) {
  if (!defined($c)) define($c, 0);
}

require_once(__DIR__ . '/../plugins/tcpdf/tcpdf.php');

$element_id = trim($_GET['element_id'] ?? '');

// fetch data and compute ITEMCODE from subcodes
$data = [
  'element_code' => '',
  'item_code' => '',
  'lot_code' => '',
  'qty' => 0,
  'operator' => $_SESSION['userLAB'] ?? 'operator',
  'created_at' => date('d M Y H:i')
];

if ($element_id !== '') {
  $sql = "SELECT ELEMENTSCODE AS element_code,
        CONCAT(
            COALESCE(TRIM(DECOSUBCODE01),'') ,
            COALESCE(TRIM(DECOSUBCODE02),'') ,
            COALESCE(TRIM(DECOSUBCODE03),'') ,
            COALESCE(TRIM(DECOSUBCODE04),'')
        ) AS item_code,
        LOTCODE AS lot_code, 
        WAREHOUSELOCATIONCODE AS loc_code, 
        BASEPRIMARYQUANTITYUNIT AS qty
        FROM balance WHERE NUMBERID = ? LIMIT 1";
  if ($stmt = $con->prepare($sql)) {
    $stmt->bind_param('s', $element_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $row = $res->fetch_assoc()) {
      $data['element_code'] = $row['element_code'] ?? '';
      $data['item_code'] = $row['item_code'] ?? '';
      $data['lot_code'] = $row['lot_code'] ?? '';
      $data['loc_code'] = $row['loc_code'] ?? '';
      $data['qty'] = floatval($row['qty']) ?: 0;
      $data['created_at'] = date('d M Y H:i');
    }
    $stmt->close();
  }
}

if (!class_exists('TCPDF')) {
  // user-friendly message
  header('Content-Type: text/html; charset=utf-8');
  echo "<h3>TCPDF tidak ditemukan</h3>";
  exit;
}

// create PDF with small label size (mm). Adjust dimensions as needed.
$label_w = 111.5;
$label_h = 81.3;

$max_left = 2;  // mm
$max_right = $label_w - 30;  // mm

$pdf = new TCPDF('L', 'mm', array($label_w, $label_h), true, 'UTF-8', false);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetAutoPageBreak(false, 0);
$pdf->SetMargins(1, 1, 1);
$pdf->AddPage();

// layout: left area for text, right narrow area for vertical barcode
$left_w = 72;  // 100 - 28 for barcode column
$offsetY = 13; // mm

// Title

// logo (if exists)
$paths = [
  __DIR__ . '/../dist/img/ITTI_logo.png',
];

foreach ($paths as $p) {
  if (file_exists($p)) {
    $logoPath = $p;
    break;
  }
}

// ignore warnings from libpng
set_error_handler(function () {});

// draw image
$pdf->Image($logoPath, 2, 2 + $offsetY, 16, 16, '', '', '', false, 300);

// restore handler
restore_error_handler();

// Title centered
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetXY(20, 5 + $offsetY);
$pdf->Cell($left_w, 6, 'GREIGE FABRIC LABEL-1', 0, 1, 'L', 0, '', 0);

// small subtitle / order
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetXY(20, 10 + $offsetY);
$pdf->Cell($left_w, 6, 'Order Nr :', 0, 1, 'L', 0, '', 0);

// Item code (prominent)
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetXY($max_left, 20 + $offsetY);
$pdf->Cell(20, 5, 'Item Code', 0, 0, 'L');
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 5, ": " . $data['item_code'], 0, 1, 'L');

// Lot and other small fields at same line on left
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetXY($max_left, 25 + $offsetY);
$pdf->Cell(20, 5, 'Lot Code', 0, 0, 'L');
$pdf->Cell(0, 5, ": " . $data['lot_code'], 0, 1, 'L');

// No MC
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetXY(50, 25 + $offsetY);
$pdf->Cell(18, 5, 'No MC', 0, 0, 'L');
$pdf->Cell(0, 5, ": ", 0, 1, 'L');

// Width
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetXY($max_left,  30 + $offsetY);
$pdf->Cell(20, 5, 'Width', 0, 0, 'L');
$pdf->Cell(0, 5, ": ", 0, 1, 'L');

// Grade
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetXY(50, 30 + $offsetY);
$pdf->Cell(18, 5, 'Grade', 0, 0, 'L');
$pdf->Cell(0, 5, ": ", 0, 1, 'L');

// Celup
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetXY($max_left, 35 + $offsetY);
$pdf->Cell(20, 5, 'Celup', 0, 0, 'L');
$pdf->Cell(0, 5, ": ", 0, 1, 'L');

// Rak
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetXY(50, 35 + $offsetY);
$pdf->Cell(18, 5, 'Loc. Code', 0, 0, 'L');
$pdf->Cell(0, 5, ": " . $data['loc_code'], 0, 1, 'L');

// small table Gross / Net (placed below Lot Code)
$pdf->SetXY($max_left, 45 + $offsetY);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(15, 5, 'Gross', 1, 0, 'L');
$pdf->Cell(15, 5, number_format($data['qty'], 2), 1, 1, 'R');

$pdf->SetXY($max_left, 50 + $offsetY);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(15, 5, 'Net', 1, 0, 'L');
$pdf->Cell(15, 5, number_format($data['qty'], 2), 1, 1, 'R');

$pdf->write2DBarcode($data['item_code'], 'QRCODE,H', 35, 42 + $offsetY, 11, 11, null, null);

$pdf->SetXY($max_left, 55 + $offsetY);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 5, 'Element Nr ' . $data['element_code'], 1, 0, 'L');

// // footer info (date + operator)
$pdf->SetXY($max_left, 60 + $offsetY);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(60, 5, $data['created_at'], 0, 0, 'L');
$pdf->Cell(10, 5, 'Operator: ' . $data['operator'], 0, 1, 'R');

// Barcode on right column (vertical)
$barcode_x = $label_w - 20; // start near right edge
$barcode_y = 12;
$barcode_w = 12; // barcode width
$barcode_h = $label_h - 12; // barcode height

// rotate to draw vertical barcode top-to-bottom
$barcode_width  = 50 + $offsetY; // horizontal width
$barcode_height = 16; // rotated height
$style = array(
  'position' => '',
  'align' => 'C',
  'stretch' => true,
  'fitwidth' => true,
  'cellfitalign' => '',
  'border' => false,
  'hpadding' => 0,
  'vpadding' => 0.2,
  'fgcolor' => array(0, 0, 0),
  'bgcolor' => false,
  'text' => true,
  'font' => 'helvetica',
  'fontsize' => 8,
  'stretchtext' => 0
);

$pdf->StartTransform();
$pdf->Rotate(90, $barcode_x, $barcode_y);

$pdf->write1DBarcode(
  $data['element_code'],
  'C128',
  $barcode_x - $barcode_width, // geser ke kiri agar pas
  $barcode_y,
  $barcode_width,
  $barcode_height,
  0.4,
  $style,
  'N'
);

$pdf->StopTransform();


// Output inline
$pdf->Output('sticker_' . ($data['element_code'] ?: $element_id) . '.pdf', 'I');

exit;
