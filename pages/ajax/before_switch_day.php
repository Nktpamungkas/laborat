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
                    $sql_23         = mysqli_query($con,"SELECT * FROM sisa_schedule where DATE_FORMAT(`time`, '%Y-%m-%d %H:%i') BETWEEN '$ystrdy 23:00' AND '$tody 23:00'");
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
        <h4 class="text-center" style="font-weight: bold;">OUTPUT PER GROUP H-1 <span class="text-center" style="font-weight: bold;">H-1</span></h4>
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
				$ystrdy1 = date('Y-m-d', strtotime("-1 days"))." 23:00";
				$tody1 = date('Y-m-d')." 23:00";
				function get_val($jenismatching, $group)
                {
                    include '../../koneksi.php';
                    $sql = mysqli_query($con, "SELECT
                                                    a.grp,
                                                    SUM(IF(a.koreksi_resep IS NOT NULL, 0.5, 0 ) + 
                                                            IF(a.koreksi_resep2 IS NOT NULL, 0.5, 0 ) +
                                                            IF(a.koreksi_resep3 IS NOT NULL, 0.5, 0 ) +
                                                            IF(a.koreksi_resep4 IS NOT NULL, 0.5, 0 ) +
                                                            IF(a.koreksi_resep5 IS NOT NULL, 0.5, 0 ) +
                                                            IF(a.koreksi_resep6 IS NOT NULL, 0.5, 0 )) +
                                                    SUM(IF(a.colorist1 IS NOT NULL, 0.5, 0 ) + 
                                                            IF(a.colorist2 IS NOT NULL, 0.5, 0 ) +
                                                            IF(a.colorist3 IS NOT NULL, 0.5, 0 ) +
                                                            IF(a.colorist4 IS NOT NULL, 0.5, 0 ) +
                                                            IF(a.colorist5 IS NOT NULL, 0.5, 0 ) +
                                                            IF(a.colorist6 IS NOT NULL, 0.5, 0 )) AS Total_value
                                                FROM
                                                    `tbl_status_matching` a
                                                LEFT JOIN tbl_matching b ON b.no_resep = a.idm
                                                WHERE 
                                                    NOT a.grp = ''
                                                    AND DATE_FORMAT(a.approve_at, '%Y-%m-%d') = DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY)
                                                    AND b.jenis_matching = '$jenismatching'
                                                    AND a.grp = '$group'
                                                GROUP BY 
                                                    a.grp");
                    $data = mysqli_fetch_array($sql);

                    return $data['Total_value'];
                }
			?>
            <tbody>
                <tr>
                    <td align="center">Group A</td>
                    <td align="center"><?php $A_LD = get_val('L/D', 'A') + get_val('LD NOW', 'A'); echo $A_LD; ?></td>
                    <td align="center"><?php $A_MU = get_val('Matching Ulang', 'A') + get_val('Matching Ulang NOW', 'A'); echo $A_MU; ?></td>
                    <td align="center"><?php $A_P = get_val('Perbaikan', 'A') + get_val('Perbaikan NOW', 'A'); echo $A_P; ?></td>
                    <td align="center"><?php $A_D = get_val('Matching Development', 'A'); echo $A_D; ?></td>
                    <td align="center"><?= $A_LD + $A_MU + $A_P + $A_D; ?></td>
                </tr>
                <tr>
                    <td align="center">Group B</td>
                    <td align="center"><?php $A_LD = get_val('L/D', 'B') + get_val('LD NOW', 'B'); echo $A_LD; ?></td>
                    <td align="center"><?php $A_MU = get_val('Matching Ulang', 'B') + get_val('Matching Ulang NOW', 'B'); echo $A_MU; ?></td>
                    <td align="center"><?php $A_P = get_val('Perbaikan', 'B') + get_val('Perbaikan NOW', 'B'); echo $A_P; ?></td>
                    <td align="center"><?php $A_D = get_val('Matching Development', 'B'); echo $A_D; ?></td>
                    <td align="center"><?= $A_LD + $A_MU + $A_P + $A_D; ?></td>
                </tr>
                <tr>
                    <td align="center">Group C</td>
                    <td align="center"><?php $A_LD = get_val('L/D', 'C') + get_val('LD NOW', 'C'); echo $A_LD; ?></td>
                    <td align="center"><?php $A_MU = get_val('Matching Ulang', 'C') + get_val('Matching Ulang NOW', 'C'); echo $A_MU; ?></td>
                    <td align="center"><?php $A_P = get_val('Perbaikan', 'C') + get_val('Perbaikan NOW', 'C'); echo $A_P; ?></td>
                    <td align="center"><?php $A_D = get_val('Matching Development', 'C'); echo $A_D; ?></td>
                    <td align="center"><?= $A_LD + $A_MU + $A_P + $A_D; ?></td>
                </tr>
                <tr>
                    <td align="center">Group D</td>
                    <td align="center"><?php $A_LD = get_val('L/D', 'D') + get_val('LD NOW', 'D'); echo $A_LD; ?></td>
                    <td align="center"><?php $A_MU = get_val('Matching Ulang', 'D') + get_val('Matching Ulang NOW', 'D'); echo $A_MU; ?></td>
                    <td align="center"><?php $A_P = get_val('Perbaikan', 'D') + get_val('Perbaikan NOW', 'D'); echo $A_P; ?></td>
                    <td align="center"><?php $A_D = get_val('Matching Development', 'D'); echo $A_D; ?></td>
                    <td align="center"><?= $A_LD + $A_MU + $A_P + $A_D; ?></td>
                </tr>
                <tr>
                    <td align="center">Group E</td>
                    <td align="center"><?php $A_LD = get_val('L/D', 'E') + get_val('LD NOW', 'E'); echo $A_LD; ?></td>
                    <td align="center"><?php $A_MU = get_val('Matching Ulang', 'E') + get_val('Matching Ulang NOW', 'E'); echo $A_MU; ?></td>
                    <td align="center"><?php $A_P = get_val('Perbaikan', 'E') + get_val('Perbaikan NOW', 'E'); echo $A_P; ?></td>
                    <td align="center"><?php $A_D = get_val('Matching Development', 'E'); echo $A_D; ?></td>
                    <td align="center"><?= $A_LD + $A_MU + $A_P + $A_D; ?></td>
                </tr>
                <tr>
                    <td align="center">Group F</td>
                    <td align="center"><?php $A_LD = get_val('L/D', 'F') + get_val('LD NOW', 'F'); echo $A_LD; ?></td>
                    <td align="center"><?php $A_MU = get_val('Matching Ulang', 'F') + get_val('Matching Ulang NOW', 'F'); echo $A_MU; ?></td>
                    <td align="center"><?php $A_P = get_val('Perbaikan', 'F') + get_val('Perbaikan NOW', 'F'); echo $A_P; ?></td>
                    <td align="center"><?php $A_D = get_val('Matching Development', 'F'); echo $A_D; ?></td>
                    <td align="center"><?= $A_LD + $A_MU + $A_P + $A_D; ?></td>
                </tr>
            </tbody>
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
				  <td><?php // $ld21 = get_val($ystrdy1, $tody1, 'L/D', 'Joni') + get_val($ystrdy1, $tody1, 'L/D NOW', 'Joni'); echo $ld21; ?></td>
				  <td><?php // $mu21 = get_val($ystrdy1, $tody1, 'Matching Ulang', 'Joni') + get_val($ystrdy1, $tody1, 'Matching Ulang NOW', 'Joni'); echo $mu21; ?></td>
				  <td><?php // $mp21 = get_val($ystrdy1, $tody1, 'Perbaikan', 'Joni') + get_val($ystrdy1, $tody1, 'Perbaikan NOW', 'Joni'); echo $mp21; ?></td>
				  <td><?php // $md21 = get_val($ystrdy1, $tody1, 'Matching Development', 'Joni')+ 0; echo $md21; ?></td>
				  <th><?php // echo  $ld21 + $mu21 + $mp21 + $md21 ?></th>
			  </tr>
				<tr>
				  <td>Yana</td>
				  <td><?php $ld22 = get_val($ystrdy1, $tody1, 'L/D', 'Yana') + get_val($ystrdy1, $tody1, 'L/D NOW', 'Yana');
                                                        echo $ld22; ?></td>
				  <td><?php $mu22 = get_val($ystrdy1, $tody1, 'Matching Ulang', 'Yana') + get_val($ystrdy1, $tody1, 'Matching Ulang NOW', 'Yana');
                                                        echo $mu22; ?></td>
				  <td><?php $mp22 = get_val($ystrdy1, $tody1, 'Perbaikan', 'Yana') + get_val($ystrdy1, $tody1, 'Perbaikan NOW', 'Yana');
                                                        echo $mp22; ?></td>
				  <td><?php $md22 = get_val($ystrdy1, $tody1, 'Matching Development', 'Yana')+ 0;
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
				  <td><?php $md23 = get_val($ystrdy1, $tody1, 'Matching Development', 'Ganang')+ 0;
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
                      <td><?php $md24 = get_val($ystrdy1, $tody1, 'Matching Development', 'Tidak Matching')+ 0;
                                                        echo $md24; ?></td>
                      <th><?php echo  $ld24 + $mu24 + $mp24 + $md24 ?></th>
                    </tr>                
            </tbody>
            <tfoot>
				<?php
                    $lab_dip1 = $ld21+$ld22+$ld22+$ld22;
                    $matching_ulang1 = $mu21+$mu22+$mu22+$mu22;
                    $perbaikan1 = $mp21+$mp22+$mp22+$mp22;
                    $development1 = $md21+$md22+$md22+$md22; 
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