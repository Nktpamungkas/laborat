<?php 
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$tody = date('Y-m-d');
?>
<div class="col-md-12 box">
    <div class="col-md-6">
        <h4 class="text-center" style="font-weight: bold;">
            Colorist <?php echo date('d F', strtotime("-1 days")); ?> </br>
            Resep/Total Jumlah Percobaan
        </h4>
        <table class="table table-colorist" id="colorist_yesterday">
            <thead>
                <tr>
                    <th style="font-size: small;">Nama</th>
                    <th style="font-size: small;">Lab_Dip</th>
                    <th style="font-size: small;">Match_Ulang</th>
                    <th style="font-size: small;">Perbaikan</th>
                    <th style="font-size: small;">Develop</th>
                    <th style="font-size: small; text-align: center;">TOTAL</th>
                    <th style="font-size: small; text-align: center;">%</th>
                    <th style="font-size: small; text-align: center;">E</th>
                </tr>
            </thead>
            <?php $dateY = date('Y-m-d', strtotime("-1 days"));
            $sql_TotalYstrdyXcolorist = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.koreksi_resep
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where b.approve = 'TRUE' 
                                              and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 07:00' AND '$tody 07:00'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
            $TotalYstrdyXcolorist = mysqli_num_rows($sql_TotalYstrdyXcolorist);
            $sql_colorist = mysqli_query($con,"SELECT a.nama , count(c.no_resep) as cout
                                        FROM tbl_colorist a
                                        left join tbl_status_matching b on a.nama = b.koreksi_resep
                                        left join tbl_matching c on b.idm = c.no_resep
                                        where a.is_active = 'TRUE'  
                                        and b.approve = 'TRUE' 
                                        and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 07:00' AND '$tody 07:00'
                                        group by a.nama
                                        ORDER BY cout desc");
            ?>
            <tbody>
                <?php while ($colorist = mysqli_fetch_array($sql_colorist)) : ?>
                    <tr>
                        <th><?php echo $colorist['nama'] ?></th>
                        <?php $sql_LD = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where (a.jenis_matching = 'L/D' or a.jenis_matching = 'LD NOW') 
                                              and b.approve = 'TRUE' 
                                              and b.koreksi_resep = '$colorist[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 07:00' AND '$tody 07:00'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Color_LD = mysqli_num_rows($sql_LD); ?>
                        <td><?php echo $Color_LD ?></td>
                        <?php $sql_MU = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where (a.jenis_matching = 'Matching Ulang' or a.jenis_matching = 'Matching Ulang NOW') 
                                              and b.approve = 'TRUE' 
                                              and b.koreksi_resep = '$colorist[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 07:00' AND '$tody 07:00'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Color_MU = mysqli_num_rows($sql_MU); ?>
                        <td><?php echo $Color_MU ?></td>
                        <?php $sql_PBKN = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where (a.jenis_matching = 'Perbaikan' or a.jenis_matching = 'Perbaikan NOW') 
                                              and b.approve = 'TRUE' 
                                              and b.koreksi_resep = '$colorist[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 07:00' AND '$tody 07:00'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Color_PBKN = mysqli_num_rows($sql_PBKN); ?>
                        <td><?php echo $Color_PBKN ?></td>
                        <?php $sql_MD = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where a.jenis_matching = 'Matching Development' 
                                              and b.approve = 'TRUE' 
                                              and b.koreksi_resep = '$colorist[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 07:00' AND '$tody 07:00'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Color_MD = mysqli_num_rows($sql_MD); ?>
                        <td><?php echo $Color_MD ?></td>
                        <?php $sql_SumPerCbn = mysqli_query($con,"SELECT sum(b.percobaan_ke) as total_percobaan
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where b.approve = 'TRUE' 
                                              and b.koreksi_resep = '$colorist[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 07:00' AND '$tody 07:00'
                                              group by b.koreksi_resep
                                              ORDER BY a.id desc");
                        $SumPerCbn = mysqli_fetch_array($sql_SumPerCbn); ?>
                        <td class="alert-warning"><?php
                                                    $totot = $Color_LD + $Color_MU + $Color_PBKN + $Color_MD;
                                                    if ($totot == 0) {
                                                        echo '-</td><td class="alert-info">-</td>';
                                                    } else {
                                                        $toPercent = (floatval($totot) / floatval($TotalYstrdyXcolorist)) * 100;
                                                        echo $totot . ' </td><td class="alert-info"> ' . number_format($toPercent, 2) . '%</td>';
                                                    }
                                                    ?></td>
                        <td class="bg-warning"><?php
                                                if ($totot == 0) {
                                                    echo '-';
                                                } else {
                                                    $Bagi = (floatval($SumPerCbn['total_percobaan']) / floatval($totot));
                                                    echo number_format($Bagi, 2);
                                                }
                                                ?></td>
                    </tr>
                    <?php
                    $ColorLDharian += $Color_LD;
                    $ColorMUharian += $Color_MU;
                    $ColorPBKNharian += $Color_PBKN;
                    $ColorMDharian += $Color_MD;
                    ?>
                <?php endwhile; ?>
                <tr>
                    <th style="text-align: center;">Total</th>
                    <td class="bg-primary"><?php echo $ColorLDharian ?></td>
                    <td class="bg-primary"><?php echo $ColorMUharian ?></td>
                    <td class="bg-primary"><?php echo $ColorPBKNharian ?></td>
                    <td class="bg-primary"><?php echo $ColorMDharian ?></td>
                    <td class="alert-danger"><?php echo $TotalYstrdyXcolorist; ?></td>
                    <td class="bg-primary">100.00%</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h4 class="text-center" style="font-weight: bold;">
            Colorist <?php echo date('F'); ?> </br>
            Resep/Total Jumlah Percobaan
        </h4>
        <table class="table table-colorist" id="colorist_yesterday">
            <thead>
                <tr>
                    <th style="font-size: small;">Nama</th>
                    <th style="font-size: small;">Lab_Dip</th>
                    <th style="font-size: small;">Match_Ulang</th>
                    <th style="font-size: small;">Perbaikan</th>
                    <th style="font-size: small;">Develop</th>
                    <th style="font-size: small; text-align: center;">TOTAL</th>
                    <th style="font-size: small; text-align: center;">%</th>
                    <th style="font-size: small; text-align: center;">E</th>
                </tr>
            </thead>
            <?php $dateF = date('Y-m');
            $sql_TotalMonthXcolorist = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.koreksi_resep
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where b.approve = 'TRUE' 
                                              and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                              ORDER BY a.id desc");
            $TotalMonthXcolorist = mysqli_num_rows($sql_TotalMonthXcolorist);
            $sql_colorist = mysqli_query($con,"SELECT a.nama , count(c.no_resep) as cout
                                        FROM tbl_colorist a
                                        left join tbl_status_matching b on a.nama = b.koreksi_resep
                                        left join tbl_matching c on b.idm = c.no_resep
                                        where a.is_active = 'TRUE' and b.approve = 'TRUE' 
                                        and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                        group by a.nama
                                        ORDER BY cout desc");
            ?>
            <tbody>
                <?php while ($colorist = mysqli_fetch_array($sql_colorist)) : ?>
                    <tr>
                        <th><?php echo $colorist['nama'] ?></th>
                        <?php $sql_LD = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where (a.jenis_matching = 'L/D' or a.jenis_matching = 'LD NOW')  
                                              and b.approve = 'TRUE' 
                                              and b.koreksi_resep = '$colorist[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Color_LD = mysqli_num_rows($sql_LD); ?>
                        <td><?php echo $Color_LD ?></td>
                        <?php $sql_MU = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where (a.jenis_matching = 'Matching Ulang' or a.jenis_matching = 'Matching Ulang NOW')  
                                              and b.approve = 'TRUE' 
                                              and b.koreksi_resep = '$colorist[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Color_MU = mysqli_num_rows($sql_MU); ?>
                        <td><?php echo $Color_MU ?></td>
                        <?php $sql_PBKN = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where (a.jenis_matching = 'Perbaikan' or a.jenis_matching = 'Perbaikan NOW')
                                              and b.approve = 'TRUE' 
                                              and b.koreksi_resep = '$colorist[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Color_PBKN = mysqli_num_rows($sql_PBKN); ?>
                        <td><?php echo $Color_PBKN ?></td>
                        <?php $sql_MD = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where a.jenis_matching = 'Matching Development' 
                                              and b.approve = 'TRUE' 
                                              and b.koreksi_resep = '$colorist[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Color_MD = mysqli_num_rows($sql_MD); ?>
                        <td><?php echo $Color_MD ?></td>
                        <?php $sql_SumPerCbn = mysqli_query($con,"SELECT sum(b.percobaan_ke) as total_percobaan
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm 
                                              and b.approve = 'TRUE' 
                                              and b.koreksi_resep = '$colorist[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                              group by b.koreksi_resep
                                              ORDER BY a.id desc");
                        $SumPerCbn = mysqli_fetch_array($sql_SumPerCbn); ?>
                        <td class="alert-warning"><?php
                                                    $totot = $Color_LD + $Color_MU + $Color_PBKN + $Color_MD;
                                                    if ($totot == 0) {
                                                        echo '-</td><td class="alert-info">-</td>';
                                                    } else {
                                                        $toPercent = (floatval($totot) / floatval($TotalMonthXcolorist)) * 100;
                                                        echo $totot . ' </td><td class="alert-info"> ' . number_format($toPercent, 2) . '%</td>';
                                                    }
                                                    ?></td>
                        <td class="bg-warning"><?php
                                                if ($totot == 0) {
                                                    echo '-';
                                                } else {
                                                    $Bagi = (floatval($SumPerCbn['total_percobaan']) / floatval($totot));
                                                    echo number_format($Bagi, 2);
                                                }
                                                ?></td>
                    </tr>
                    <?php
                    $ColorLDbulanan += $Color_LD;
                    $ColorMUbulanan += $Color_MU;
                    $ColorPBKNbulanan += $Color_PBKN;
                    $ColorMDbulanan += $Color_MD;
                    ?>
                <?php endwhile; ?>
                <tr>
                    <th style="text-align: center;">Total</th>
                    <td class="bg-primary"><?php echo $ColorLDbulanan ?></td>
                    <td class="bg-primary"><?php echo $ColorMUbulanan ?></td>
                    <td class="bg-primary"><?php echo $ColorPBKNbulanan ?></td>
                    <td class="bg-primary"><?php echo $ColorMDbulanan ?></td>
                    <td class="alert-danger"><?php echo $TotalMonthXcolorist; ?></td>
                    <td class="bg-primary">100.00%</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>