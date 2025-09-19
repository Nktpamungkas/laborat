<?php
require_once '../../koneksi.php';
require_once '../lib/revisi_compare.php';

error_reporting(E_ERROR | E_PARSE);
header('Content-Type: text/html; charset=utf-8');

$is_revision = isset($_GET['is_revision']) ? (int)$_GET['is_revision'] : 1;

$q = "
SELECT a.*
FROM approval_bon_order a
JOIN (
  SELECT code, MAX(id) AS max_id
  FROM approval_bon_order
  WHERE is_revision = {$is_revision}
  GROUP BY code
) m ON m.max_id = a.id
WHERE a.is_revision = {$is_revision}
ORDER BY a.id DESC
";
$res = mysqli_query($con, $q);

// echo <tr> rows saja
while ($row = mysqli_fetch_assoc($res)) {
    $codeApp = strtoupper(trim($row['code']));
    // hitung last display
    $reviN_last = first_non_empty([$row['drevisi5'], $row['drevisi4'], $row['drevisi3'], $row['drevisi2'], $row['revisin']]);
    $reviC_last = first_non_empty([$row['revisi5'], $row['revisi4'], $row['revisi3'], $row['revisi2'], $row['revisic']]);
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
    <a href="#" class="btn btn-primary btn-sm open-detail" data-code="<?= htmlspecialchars($row['code']) ?>" data-toggle="modal" data-target="#detailModal">
      <?= htmlspecialchars($row['code']) ?>
    </a>
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
<?php
}
