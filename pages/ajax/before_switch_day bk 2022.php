<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';
?>
<div class="col-md-6">
    <div class="box">
        <h4 class="text-center" style="font-weight: bold;">SISA SCHEDULE H-1 23:59:59</h4>
        <table class="table table-chart">
            <thead>
                <tr>
                    <th align="center">DATA</th>
                    <th>L/D</th>
                    <th>MATCHING ULANG</th>
                    <th>PERBAIKAN</th>
                    <th>DVELOPMENT</th>
                    <th>SUB-TOTAL</th>
                </tr>
            </thead>
            <tbody>


                <?php
                $ystrdy = date('Y-m-d', strtotime("-1 days"));
                $sql_23 = mysqli_query($con,"SELECT * FROM sisa_schedule where DATE_FORMAT(`time`, '%Y-%m-%d') = '$ystrdy'");
                $lab_dip = 0;
                $matching_ulang = 0;
                $perbaikan = 0;
                $development = 0;
                ?>
                <?php while ($li = mysqli_fetch_array($sql_23)) { ?>
                    <tr>
                        <td><?php echo $li['data'] ?></td>
                        <td><?php echo $li['lab_dip'] ?></td>
                        <td><?php echo $li['matching_ulang'] ?></td>
                        <td><?php echo $li['perbaikan'] ?></td>
                        <td><?php echo $li['development'] ?></td>
                        <th><?php echo  $li['lab_dip'] + $li['matching_ulang'] + $li['perbaikan'] + $li['development'] ?></th>
                    </tr>
                    <?php
                    $lab_dip += $li['lab_dip'];
                    $matching_ulang += $li['matching_ulang'];
                    $perbaikan += $li['perbaikan'];
                    $development += $li['development'];
                    ?>
                    <?php ?>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>TOTAL-PERJENIS</th>
                    <th><?php echo $lab_dip ?></th>
                    <th><?php echo $matching_ulang ?></th>
                    <th><?php echo $perbaikan ?></th>
                    <th><?php echo $development ?></th>
                    <th><?php echo $lab_dip + $matching_ulang + $perbaikan + $development ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>