<?php
include '../../koneksi.php';

$no_resep = $_POST['no_resep'];

$query = mysqli_query($con, "
    SELECT 
        no_resep,
        creationdatetime,
        COUNT(*) AS qty,
        MAX(status) AS status,
        MAX(dispensing_start) AS dispensing_start,
        MAX(dyeing_start) AS dyeing_start,
        MAX(darkroom_start) AS darkroom_start,
        MAX(darkroom_end) AS darkroom_end,
        MAX(sekali_celup) AS sekali_celup
    FROM tbl_preliminary_schedule
    WHERE no_resep = '$no_resep'
    GROUP BY no_resep, creationdatetime
    ORDER BY creationdatetime ASC
");
?>

<table id="detailTable" class="table table-sm table-bordered table-sm display compact">
  <thead>
    <tr class='bg-danger'>
      <th>#</th>
      <th>Creation Time</th>
      <th>Qty</th>
      <th>Dispensing Start</th>
      <th>Dyeing Start</th>
      <th>Darkroom Start</th>
      <th>Darkroom End</th>
      <!-- <th>Sekali Celup</th> -->
      <th>Status Terakhir</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    while ($row = mysqli_fetch_assoc($query)) {
        echo "<tr>
                <td>$no</td>
                <td>{$row['creationdatetime']}</td>
                <td>{$row['qty']}</td>
                <td>{$row['dispensing_start']}</td>
                <td>{$row['dyeing_start']}</td>
                <td>{$row['darkroom_start']}</td>
                <td>{$row['darkroom_end']}</td>
                <!--<td>{$row['sekali_celup']}</td>-->
                <td>{$row['status']}</td>
              </tr>";
        $no++;
    }
    ?>
  </tbody>
</table>

<script>
$(document).ready(function () {
  $('#detailTable').DataTable({
    pageLength: 25,
    pagingType: "simple_numbers",
    language: {
      paginate: {
        previous: '<i class="fa fa-angle-left"></i>',
        next: '<i class="fa fa-angle-right"></i>'
      }
    }
  });
});
</script>
