<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';
?>
<div class="col-md-6">
    <div class="box">
        <h4 class="text-center" style="font-weight: bold;">SISA SCHEDULE H-1 <br> 23:00 - 23:00</h4>
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
                $ystrdy         = date('Y-m-d', strtotime("-1 days"));
                $tody           = date('Y-m-d');
                //     $ystrdy         = date('Y-m-d', strtotime("-2 days"));
                //    $tody           = date('Y-m-d', strtotime("-1 days"));
                $sql_23         = mysqli_query($con, "SELECT * FROM sisa_schedule where DATE_FORMAT(`time`, '%Y-%m-%d %H:%i') BETWEEN '$ystrdy 23:00' AND '$tody 23:00'");
                $lab_dip        = 0;
                $matching_ulang = 0;
                $perbaikan      = 0;
                $development    = 0;
                ?>
                <?php while ($li = mysqli_fetch_array($sql_23)) { ?>
                    <tr>
                        <td><?php echo $li['data'] ?></td>
                        <td><?php echo $li['lab_dip'] ?></td>
                        <td><?php echo $li['matching_ulang'] ?></td>
                        <td><?php echo $li['perbaikan'] ?></td>
                        <td><?php echo $li['development'] ?></td>
                        <th><?php echo $li['lab_dip'] + $li['matching_ulang'] + $li['perbaikan'] + $li['development'] ?></th>
                    </tr>
                    <?php
                    $lab_dip        += $li['lab_dip'];
                    $matching_ulang += $li['matching_ulang'];
                    $perbaikan      += $li['perbaikan'];
                    $development    += $li['development'];
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
<div class="col-md-6">
    <div class="box">
        <h4 class="text-center" style="font-weight: bold;">OUTPUT PER GROUP <span class="text-center" style="font-weight: bold;">H-1</span></h4>
        <table class="table table-chart">
            <thead>
                <tr align="center">
                    <th align="center">GROUP</th>
                    <th align="center">L/D</th>
                    <th align="center">MU</th>
                    <th align="center">PERBAIKAN</th>
                    <th align="center">DEVELOPMENT</th>
                    <th align="center">TOTAL</th>
                </tr>
            </thead>
            <?php
            function get_val($jenismatching, $group)
            {
                include '../../koneksi.php';

                $start_date = date('Y-m-d', strtotime("-2 days"));
                $end_date = date('Y-m-d', strtotime("-1 day"));

                $start_datetime = $start_date . " 23:00:00";
                $end_datetime = $end_date . " 23:00:00";

                $sql = mysqli_query($con, "SELECT
                                    a.grp,
                                    SUM( IF(a.koreksi_resep <> '' AND a.koreksi_resep IS NOT NULL, 0.5, 0) +
                                        IF(a.koreksi_resep2 <> '' AND a.koreksi_resep2 IS NOT NULL, 0.5, 0) +
                                        IF(a.koreksi_resep3 <> '' AND a.koreksi_resep3 IS NOT NULL, 0.5, 0) +
                                        IF(a.koreksi_resep4 <> '' AND a.koreksi_resep4 IS NOT NULL, 0.5, 0) +
                                        IF(a.koreksi_resep5 <> '' AND a.koreksi_resep5 IS NOT NULL, 0.5, 0) +
                                        IF(a.koreksi_resep6 <> '' AND a.koreksi_resep6 IS NOT NULL, 0.5, 0) +
                                        IF(a.koreksi_resep7 <> '' AND a.koreksi_resep7 IS NOT NULL, 0.5, 0) +
                                        IF(a.koreksi_resep8 <> '' AND a.koreksi_resep8 IS NOT NULL, 0.5, 0) ) AS total_value 
                                FROM
                                    `tbl_status_matching` a
                                LEFT JOIN tbl_matching b ON b.no_resep = a.idm
                                WHERE 
                                    a.grp = '$group'
                                    AND b.jenis_matching = '$jenismatching'
                                    AND a.approve_at >= '$start_datetime'
                                    AND a.approve_at < '$end_datetime'
                                    AND a.`status` = 'selesai'
                                GROUP BY 
                                    a.grp");
                $data = mysqli_fetch_array($sql);

                return $data['total_value'];
            }




            ?>
            <tbody>
                <tr>
                    <td align="center">Group A</td>
                    <td align="center"><?php $A_LD = get_val('L/D', 'A') + get_val('LD NOW', 'A');
                                        echo $A_LD; ?></td>
                    <td align="center"><?php $A_MU = get_val('Matching Ulang', 'A') + get_val('Matching Ulang NOW', 'A');
                                        echo $A_MU; ?></td>
                    <td align="center"><?php $A_P = get_val('Perbaikan', 'A') + get_val('Perbaikan NOW', 'A');
                                        echo $A_P; ?></td>
                    <td align="center"><?php $A_D = get_val('Matching Development', 'A') + get_val('Matching Development NOW', 'A');
                                        echo $A_D; ?></td>
                    <td align="center"><?= $A_LD + $A_MU + $A_P + $A_D; ?></td>
                </tr>
                <tr>
                    <td align="center">Group B</td>
                    <td align="center"><?php $B_LD = get_val('L/D', 'B') + get_val('LD NOW', 'B');
                                        echo $B_LD; ?></td>
                    <td align="center"><?php $B_MU = get_val('Matching Ulang', 'B') + get_val('Matching Ulang NOW', 'B');
                                        echo $B_MU; ?></td>
                    <td align="center"><?php $B_P = get_val('Perbaikan', 'B') + get_val('Perbaikan NOW', 'B');
                                        echo $B_P; ?></td>
                    <td align="center"><?php $B_D = get_val('Matching Development', 'B') + get_val('Matching Development NOW', 'B');
                                        echo $B_D; ?></td>
                    <td align="center"><?= $B_LD + $B_MU + $B_P + $B_D; ?></td>
                </tr>
                <tr>
                    <td align="center">Group C</td>
                    <td align="center"><?php $C_LD = get_val('L/D', 'C') + get_val('LD NOW', 'C');
                                        echo $C_LD; ?></td>
                    <td align="center"><?php $C_MU = get_val('Matching Ulang', 'C') + get_val('Matching Ulang NOW', 'C');
                                        echo $C_MU; ?></td>
                    <td align="center"><?php $C_P = get_val('Perbaikan', 'C') + get_val('Perbaikan NOW', 'C');
                                        echo $C_P; ?></td>
                    <td align="center"><?php $C_D = get_val('Matching Development', 'C') + get_val('Matching Development NOW', 'C');
                                        echo $C_D; ?></td>
                    <td align="center"><?= $C_LD + $C_MU + $C_P + $C_D; ?></td>
                </tr>
                <tr>
                    <td align="center">Group D</td>
                    <td align="center"><?php $D_LD = get_val('L/D', 'D') + get_val('LD NOW', 'D');
                                        echo $D_LD; ?></td>
                    <td align="center"><?php $D_MU = get_val('Matching Ulang', 'D') + get_val('Matching Ulang NOW', 'D');
                                        echo $D_MU; ?></td>
                    <td align="center"><?php $D_P = get_val('Perbaikan', 'D') + get_val('Perbaikan NOW', 'D');
                                        echo $D_P; ?></td>
                    <td align="center"><?php $D_D = get_val('Matching Development', 'D') + get_val('Matching Development NOW', 'D');
                                        echo $D_D; ?></td>
                    <td align="center"><?= $D_LD + $D_MU + $D_P + $D_D; ?></td>
                </tr>
                <tr>
                    <td align="center">Group E</td>
                    <td align="center"><?php $E_LD = get_val('L/D', 'E') + get_val('LD NOW', 'E');
                                        echo $E_LD; ?></td>
                    <td align="center"><?php $E_MU = get_val('Matching Ulang', 'E') + get_val('Matching Ulang NOW', 'E');
                                        echo $E_MU; ?></td>
                    <td align="center"><?php $E_P = get_val('Perbaikan', 'E') + get_val('Perbaikan NOW', 'E');
                                        echo $E_P; ?></td>
                    <td align="center"><?php $E_D = get_val('Matching Development', 'E') + get_val('Matching Development NOW', 'E');
                                        echo $E_D; ?></td>
                    <td align="center"><?= $E_LD + $E_MU + $E_P + $E_D; ?></td>
                </tr>
                <tr>
                    <td align="center">Group F</td>
                    <td align="center"><?php $F_LD = get_val('L/D', 'F') + get_val('LD NOW', 'F');
                                        echo $F_LD; ?></td>
                    <td align="center"><?php $F_MU = get_val('Matching Ulang', 'F') + get_val('Matching Ulang NOW', 'F');
                                        echo $F_MU; ?></td>
                    <td align="center"><?php $F_P = get_val('Perbaikan', 'F') + get_val('Perbaikan NOW', 'F');
                                        echo $F_P; ?></td>
                    <td align="center"><?php $F_D = get_val('Matching Development', 'F') + get_val('Matching Development NOW', 'F');
                                        echo $F_D; ?></td>
                    <td align="center"><?= $F_LD + $F_MU + $F_P + $F_D; ?></td>
                </tr>
                <tr>
                    <td align="center">Non Group</td>
                    <td align="center"><?php $N_LD = get_val('L/D', 'N') + get_val('LD NOW', 'N');
                                        echo $N_LD; ?></td>
                    <td align="center"><?php $N_MU = get_val('Matching Ulang', 'N') + get_val('Matching Ulang NOW', 'N');
                                        echo $N_MU; ?></td>
                    <td align="center"><?php $N_P = get_val('Perbaikan', 'N') + get_val('Perbaikan NOW', 'N');
                                        echo $N_P; ?></td>
                    <td align="center"><?php $N_D = get_val('Matching Development', 'N') + get_val('Matching Development NOW', 'N');
                                        echo $N_D; ?></td>
                    <td align="center"><?= $N_LD + $N_MU + $N_P + $N_D; ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>SUB TOTAL</th>
                    <th align="center"><?= $T_LD = $A_LD + $B_LD + $C_LD + $D_LD + $E_LD + $F_LD + $N_LD; ?></th>
                    <th align="center"><?= $T_MU = $A_MU + $B_MU + $C_MU + $D_MU + $E_MU + $F_MU + $N_MU; ?></th>
                    <th align="center"><?= $T_P  = $A_P + $B_P + $C_P + $D_P + $E_P + $F_P + $N_P; ?></th>
                    <th align="center"><?= $T_D  = $A_D + $B_D + $C_D + $D_D + $E_D + $F_D + $N_D; ?></th>
                    <th align="center"><?= $T_LD + $T_MU + $T_P + $T_D; ?></th>
                </tr>
            </tfoot>
        </table>
        <!-- <h4 class="text-center" style="font-weight: bold;">RECAP PENANGGUNG JAWAB <span class="text-center" style="font-weight: bold;">H-1</span></h4> -->
        <!-- <table class="table table-chart">
            <thead>
                <tr>
                    <th align="center">NAMA</th>
                    <th>L/D</th>
                    <th>MATCHING ULANG</th>
                    <th>PERBAIKAN</th>
                    <th>DVELOPMENT</th>
                    <th>SUB-TOTAL</th>
                </tr>
            </thead>
			<?php
            // $ystrdy1 = date('Y-m-d', strtotime("-1 days"))." 23:00";
            // $tody1 = date('Y-m-d')." 23:00";
            // // $ystrdy1 = date('Y-m-d', strtotime("-2 days"))." 23:00";
            // // $tody1 = date('Y-m-d', strtotime("-1 days"))." 23:00";
            // function get_val($start, $end, $jenis, $colorist)
            // {
            //     include '../../koneksi.php';
            //     $sql = mysqli_query($con,"SELECT SUM(IF(a.penanggung_jawab != '' , 1, 0)) as total_value
            //         from tbl_status_matching a
            //         join tbl_matching b on a.idm = b.no_resep
            //         where DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') >= '$start' AND DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') <= '$end'
            //         and jenis_matching = '$jenis' and a.penanggung_jawab = '$colorist'");
            //     $data = mysqli_fetch_array($sql);

            //     return $data['total_value'];
            // }
            ?>
            <tbody>
				<tr>
				  <td>Joni</td>
				  <td><?php // $ld21 = get_val($ystrdy1, $tody1, 'L/D', 'Joni') + get_val($ystrdy1, $tody1, 'L/D NOW', 'Joni'); echo $ld21; 
                        ?></td>
				  <td><?php // $mu21 = get_val($ystrdy1, $tody1, 'Matching Ulang', 'Joni') + get_val($ystrdy1, $tody1, 'Matching Ulang NOW', 'Joni'); echo $mu21; 
                        ?></td>
				  <td><?php // $mp21 = get_val($ystrdy1, $tody1, 'Perbaikan', 'Joni') + get_val($ystrdy1, $tody1, 'Perbaikan NOW', 'Joni'); echo $mp21; 
                        ?></td>
				  <td><?php // $md21 = get_val($ystrdy1, $tody1, 'Matching Development', 'Joni')+ 0; echo $md21; 
                        ?></td>
				  <th><?php // echo  $ld21 + $mu21 + $mp21 + $md21 
                        ?></th>
			  </tr>
				<tr>
				  <td>Yana</td>
				  <td><?php $ld22 = get_val($ystrdy1, $tody1, 'L/D', 'Yana') + get_val($ystrdy1, $tody1, 'L/D NOW', 'Yana');
                        echo $ld22; ?></td>
				  <td><?php $mu22 = get_val($ystrdy1, $tody1, 'Matching Ulang', 'Yana') + get_val($ystrdy1, $tody1, 'Matching Ulang NOW', 'Yana');
                        echo $mu22; ?></td>
				  <td><?php $mp22 = get_val($ystrdy1, $tody1, 'Perbaikan', 'Yana') + get_val($ystrdy1, $tody1, 'Perbaikan NOW', 'Yana');
                        echo $mp22; ?></td>
				  <td><?php $md22 = get_val($ystrdy1, $tody1, 'Matching Development', 'Yana') + 0;
                        echo $md22; ?></td>
				  <th><?php echo  $ld22 + $mu22 + $mp22 + $md22 ?></th>
			  </tr>
				<tr>
				  <td>Ganang</td>
				  <td><?php $ld23 = get_val($ystrdy1, $tody1, 'L/D', 'Ganang') + get_val($ystrdy1, $tody1, 'L/D NOW', 'Ganang');
                        echo $ld23; ?></td>
				  <td><?php $mu23 = get_val($ystrdy1, $tody1, 'Matching Ulang', 'Ganang') + get_val($ystrdy1, $tody1, 'Matching Ulang NOW', 'Ganang');
                        echo $mu23; ?></td>
				  <td><?php $mp23 = get_val($ystrdy1, $tody1, 'Perbaikan', 'Ganang') + get_val($ystrdy1, $tody1, 'Perbaikan NOW', 'Ganang');
                        echo $mp23; ?></td>
				  <td><?php $md23 = get_val($ystrdy1, $tody1, 'Matching Development', 'Ganang') + 0;
                        echo $md23; ?></td>
				  <th><?php echo  $ld23 + $mu23 + $mp23 + $md23 ?></th>
			  </tr>
				<tr>
                      <td>Tidak Matching</td>
                      <td><?php $ld24 = get_val($ystrdy1, $tody1, 'L/D', 'Tidak Matching') + get_val($ystrdy1, $tody1, 'L/D NOW', 'Tidak Matching');
                            echo $ld24; ?></td>
                      <td><?php $mu24 = get_val($ystrdy1, $tody1, 'Matching Ulang', 'Tidak Matching') + get_val($ystrdy1, $tody1, 'Matching Ulang NOW', 'Tidak Matching');
                            echo $mu24; ?></td>
                      <td><?php $mp24 = get_val($ystrdy1, $tody1, 'Perbaikan', 'Tidak Matching') + get_val($ystrdy1, $tody1, 'Perbaikan NOW', 'Tidak Matching');
                            echo $mp24; ?></td>
                      <td><?php $md24 = get_val($ystrdy1, $tody1, 'Matching Development', 'Tidak Matching') + 0;
                            echo $md24; ?></td>
                      <th><?php echo  $ld24 + $mu24 + $mp24 + $md24 ?></th>
                    </tr>                
            </tbody>
            <tfoot>
				<?php
                $lab_dip1 = $ld21 + $ld22 + $ld22 + $ld22;
                $matching_ulang1 = $mu21 + $mu22 + $mu22 + $mu22;
                $perbaikan1 = $mp21 + $mp22 + $mp22 + $mp22;
                $development1 = $md21 + $md22 + $md22 + $md22;
                ?>
                <tr>
                    <th>TOTAL-PERJENIS</th>
                    <th><?php echo $lab_dip1 ?></th>
                    <th><?php echo $matching_ulang1 ?></th>
                    <th><?php echo $perbaikan1 ?></th>
                    <th><?php echo $development1 ?></th>
                    <th><?php echo $lab_dip1 + $matching_ulang1 + $perbaikan1 + $development1 ?></th>
                </tr>
            </tfoot>
        </table> -->
    </div>
</div>