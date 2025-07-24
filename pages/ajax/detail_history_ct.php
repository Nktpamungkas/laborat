<?php
include '../../koneksi.php';

$no_resep = $_POST['no_resep'];

$query = mysqli_query($con, "
    SELECT 
        t.no_resep,
        t.creationdatetime,
        COUNT(*) AS qty,
        MAX(t.status) AS status,

        -- Ambil dispensing_start terbaru
        (
            SELECT dispensing_start 
            FROM tbl_preliminary_schedule 
            WHERE no_resep = t.no_resep
                AND creationdatetime = t.creationdatetime 
                AND dispensing_start IS NOT NULL
            ORDER BY dispensing_start DESC 
            LIMIT 1
        ) AS dispensing_start,

        -- Ambil user_dispensing terbaru
        (
            SELECT user_dispensing 
            FROM tbl_preliminary_schedule 
            WHERE no_resep = t.no_resep
                AND creationdatetime = t.creationdatetime 
                AND dispensing_start IS NOT NULL
            ORDER BY dispensing_start DESC 
            LIMIT 1
        ) AS user_dispensing,

        MAX(t.dyeing_start) AS dyeing_start,
        MAX(t.user_dyeing) AS user_dyeing,
        MAX(t.darkroom_start) AS darkroom_start,
        MAX(t.user_darkroom_start) AS user_darkroom_start,
        MAX(t.darkroom_end) AS darkroom_end,
        MAX(t.user_darkroom_end) AS user_darkroom_end,
        MAX(t.sekali_celup) AS sekali_celup,
        MAX(t.username) AS username

    FROM tbl_preliminary_schedule t
    WHERE t.no_resep = '$no_resep'
    GROUP BY t.no_resep, t.creationdatetime
    ORDER BY t.creationdatetime ASC
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
    while ($row = mysqli_fetch_assoc($query)) :
    ?>
        <tr>
        <td><?= $no ?></td>

        <td class="text-nowrap">
            <?= $row['creationdatetime'] ?>
            <?php if (!empty($row['username'])): ?>
            <br><small class="text-muted">User: <?= $row['username'] ?></small>
            <?php endif; ?>
        </td>

        <td><?= $row['qty'] ?></td>

        <td>
            <?= $row['dispensing_start'] ?>
            <?php if (!empty($row['dispensing_start'])): ?>
            <br><small class="text-muted">User: <?= $row['user_dispensing'] ?></small>
            <?php endif; ?>
        </td>

        <td>
            <?= $row['dyeing_start'] ?>
            <?php if (!empty($row['dyeing_start'])): ?>
            <br><small class="text-muted">User: <?= $row['user_dyeing'] ?></small>
            <?php endif; ?>
        </td>

        <td>
            <?= $row['darkroom_start'] ?>
            <?php if (!empty($row['darkroom_start'])): ?>
            <br><small class="text-muted">User: <?= $row['user_darkroom_start'] ?></small>
            <?php endif; ?>
        </td>

        <td>
            <?= $row['darkroom_end'] ?>
            <?php if (!empty($row['darkroom_end'])): ?>
            <br><small class="text-muted">User: <?= $row['user_darkroom_end'] ?></small>
            <?php endif; ?>
        </td>

        <td>
        <?= $row['status'] ?>
        <?php
            $byUser = '';

            switch ($row['status']) {
            case 'ready':
                $byUser = $row['username'] ?? '';
                break;
            case 'scheduled':
                $byUser = $row['user_scheduled'] ?? '';
                break;
            case 'in_progress_dispensing':
                $byUser = $row['user_dispensing'] ?? '';
                break;
            case 'in_progress_dyeing':
                $byUser = $row['user_dyeing'] ?? '';
                break;
            case 'in_progress_darkroom':
                $byUser = $row['user_darkroom_start'] ?? '';
                break;
            default:
                if (!empty($row['user_darkroom_start'])) {
                $byUser = $row['user_darkroom_start'];
                } elseif (!empty($row['user_darkroom_end'])) {
                $byUser = $row['user_darkroom_end'];
                }
                break;
            }

            if (!empty($byUser)) {
            echo "<br><small class='text-muted'>By: {$byUser}</small>";
            }
        ?>
        </td>
        </tr>
    <?php
        $no++;
    endwhile;
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
