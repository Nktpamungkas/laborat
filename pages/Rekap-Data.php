<?php
    include "koneksi.php";

    $sql = "
    SELECT 
        DATE_FORMAT(ab.tgl_approve_lab, '%Y-%m') AS bulan,
        COUNT(DISTINCT ab.id) AS jumlah_bon,
        SUM(CASE WHEN smbo.status_bon_order = 'OK' THEN 1 ELSE 0 END) AS status_ok,
        SUM(CASE WHEN smbo.status_bon_order = 'Matching Ulang' THEN 1 ELSE 0 END) AS status_matching
    FROM approval_bon_order ab
    LEFT JOIN status_matching_bon_order smbo
        ON ab.code = smbo.sales_order_code
    WHERE ab.tgl_approve_lab IS NOT NULL
    GROUP BY bulan
    ORDER BY bulan ASC;
    ";

    $res = mysqli_query($con, $sql);

    $data = [];
    $no = 1;
    while ($r = mysqli_fetch_assoc($res)) {
        $data[] = [
            'no'       => $no++,
            'bulan'    => date("F Y", strtotime($r['bulan'] . "-01")),
            'bon'      => (int) $r['jumlah_bon'],
            'ok'       => (int) $r['status_ok'],
            'matching' => (int) $r['status_matching']
        ];
    }
?>

<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <h3>Rekap Data</h3>
        <table id="rekapTable" class="table table-bordered table-striped">
          <thead class="bg-primary">
            <tr>
              <th>No.</th>
              <th>Bulan</th>
              <th>Jumlah Bon Order</th>
              <th>Status: OK</th>
              <th>Status: Matching Ulang</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $row): ?>
              <tr>
                <td><?= $row['no'] ?></td>
                <td><?= $row['bulan'] ?></td>
                <td><?= $row['bon'] ?></td>
                <td><?= $row['ok'] ?></td>
                <td><?= $row['matching'] ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($data)): ?>
              <tr><td colspan="5" align="center">Tidak ada data.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('#rekapTable').DataTable({
      dom: 'Bfrtip',
      buttons: ['excel', 'pdf', 'print'],
      responsive: true,
      pageLength: 12
    });
  });
</script>