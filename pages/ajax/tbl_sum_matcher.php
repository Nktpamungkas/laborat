<?php 
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start(); 
//$tody = date('Y-m-d');
$tody = date('Y-m-d', strtotime("-1 days"));
?>
<div class="col-md-12 box">
    <div class="col-md-6">
        <h4 class="text-center" style="font-weight: bold;">
            Matcher <?php echo date('d F', strtotime("-1 days")); ?> </br>
            Resep/Total Jumlah Percobaan
        </h4>
        <table class="table table-matcher" id="matcher_yesterday">
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
            <?php $dateY = date('Y-m-d', strtotime("-2 days"));
            $sql_TotalYstrdyXmatcher = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where b.approve = 'TRUE' 
											  -- and b.status <> 'hold' 
											  and b.status = 'selesai'
											  and b.final_matcher <> '' 
                                              and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 23:00' AND '$tody 23:00'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
            $TotalYstrdyXmatcher = mysqli_num_rows($sql_TotalYstrdyXmatcher);
            $sql_matcher = mysqli_query($con,"SELECT a.nama , count(c.no_resep) as cout
                                        FROM tbl_matcher a
                                        left join tbl_status_matching b on a.nama = b.final_matcher
                                        left join tbl_matching c on b.idm = c.no_resep
                                        where a.status = 'Aktif'
                                        and b.approve = 'TRUE' 
										and b.status = 'selesai'
                                        and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 23:00' AND '$tody 23:00'
                                        group by a.nama
                                        ORDER BY cout desc");
            ?>
            <tbody>
                <?php while ($matcher = mysqli_fetch_array($sql_matcher)) : ?>
                    <tr>
                        <th><?php echo $matcher['nama'] ?></th>
                        <?php $sql_LD = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where (a.jenis_matching = 'L/D' or a.jenis_matching = 'LD NOW') 
                                              and b.approve = 'TRUE' 
                                              and b.final_matcher = '$matcher[nama]'
											  and b.status = 'selesai'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 23:00' AND '$tody 23:00'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Match_LD = mysqli_num_rows($sql_LD); ?>
                        <td><?php echo $Match_LD ?></td>
                        <?php $sql_MU = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where (a.jenis_matching = 'Matching Ulang' or a.jenis_matching = 'Matching Ulang NOW')  
                                              and b.approve = 'TRUE' 
                                              and b.final_matcher = '$matcher[nama]'
											  and b.status = 'selesai'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 23:00' AND '$tody 23:00'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Match_MU = mysqli_num_rows($sql_MU); ?>
                        <td><?php echo $Match_MU ?></td>
                        <?php $sql_PBKN = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where (a.jenis_matching = 'Perbaikan' or a.jenis_matching = 'Perbaikan NOW') 
                                              and b.approve = 'TRUE' 
                                              and b.final_matcher = '$matcher[nama]'
											  and b.status = 'selesai'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 23:00' AND '$tody 23:00'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Match_PBKN = mysqli_num_rows($sql_PBKN); ?>
                        <td><?php echo $Match_PBKN ?></td>
                        <?php $sql_MD = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where a.jenis_matching = 'Matching Development'  
                                              and b.approve = 'TRUE' 
                                              and b.final_matcher = '$matcher[nama]'
											  and b.status = 'selesai'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 23:00' AND '$tody 23:00'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Match_MD = mysqli_num_rows($sql_MD); ?>
                        <td><?php echo $Match_MD ?></td>
                        <?php $sql_SumPerCbn = mysqli_query($con,"SELECT sum(b.percobaan_ke) as total_percobaan
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where b.approve = 'TRUE' 
                                              and b.final_matcher = '$matcher[nama]'
											  and b.status = 'selesai'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m-%d %H:%i') BETWEEN '$dateY 23:00' AND '$tody 23:00'
                                              group by b.final_matcher
                                              ORDER BY a.id desc");
                        $SumPerCbn = mysqli_fetch_array($sql_SumPerCbn); ?>
                        <td class="alert-warning"><?php
                                                    $totot = $Match_LD + $Match_MU + $Match_PBKN + $Match_MD;
                                                    if ($totot == 0) {
                                                        echo '-</td><td class="alert-info">-</td>';
                                                    } else {
                                                        $toPercent = (floatval($totot) / floatval($TotalYstrdyXmatcher)) * 100;
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
                    $MatchLDharian += $Match_LD;
                    $MatchMUharian += $Match_MU;
                    $MatchPBKNharian += $Match_PBKN;
                    $MatchMDharian += $Match_MD;
                    ?>
                <?php endwhile; ?>
                <tr>
                    <th style="text-align: center;">Total</th>
                    <td class="bg-primary"><?php echo $MatchLDharian ?></td>
                    <td class="bg-primary"><?php echo $MatchMUharian ?></td>
                    <td class="bg-primary"><?php echo $MatchPBKNharian ?></td>
                    <td class="bg-primary"><?php echo $MatchMDharian ?></td>
                    <td class="alert-danger"><?php echo $TotalYstrdyXmatcher; ?></td>
                    <td class="bg-primary">100.00%</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h4 class="text-center" style="font-weight: bold;">
            Matcher <?php echo date('F Y') ?> </br>
            Resep/Total Jumlah Percobaan
        </h4>
        <table class="table table-matcher" id="matcher_month">
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
            $sql_TotalMonthXmatcher = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where b.approve = 'TRUE' 
											  -- and b.status <> 'hold'
											  and b.status = 'selesai'
											  and b.final_matcher <> ''
                                              and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                              ORDER BY a.id desc");
            $TotalMonthXmatcher = mysqli_num_rows($sql_TotalMonthXmatcher);
            $sql_matcher = mysqli_query($con,"SELECT a.nama , count(c.no_resep) as cout
                                        FROM tbl_matcher a
                                        left join tbl_status_matching b on a.nama = b.final_matcher
                                        left join tbl_matching c on b.idm = c.no_resep
                                        where a.status = 'Aktif'
                                        and b.approve = 'TRUE' 
										and b.status = 'selesai'
                                        and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                        group by a.nama
                                        ORDER BY cout desc");
            ?>
            <tbody>
                <?php while ($matcher = mysqli_fetch_array($sql_matcher)) : ?>
                    <tr>
                        <th><?php echo $matcher['nama'] ?></th>
                        <?php $sql_LD = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where (a.jenis_matching = 'L/D' or a.jenis_matching = 'LD NOW')  
                                              and b.approve = 'TRUE'
											  and b.status = 'selesai'
                                              and b.final_matcher = '$matcher[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Match_LD = mysqli_num_rows($sql_LD); ?>
                        <td><?php echo $Match_LD ?></td>
                        <?php $sql_MU = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where (a.jenis_matching = 'Matching Ulang' or a.jenis_matching = 'Matching Ulang NOW') 
                                              and b.approve = 'TRUE' 
											  and b.status = 'selesai'
                                              and b.final_matcher = '$matcher[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Match_MU = mysqli_num_rows($sql_MU); ?>
                        <td><?php echo $Match_MU ?></td>
                        <?php $sql_PBKN = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where (a.jenis_matching = 'Perbaikan' or a.jenis_matching = 'Perbaikan NOW')  
                                              and b.approve = 'TRUE' 
											  and b.status = 'selesai'
                                              and b.final_matcher = '$matcher[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Match_PBKN = mysqli_num_rows($sql_PBKN); ?>
                        <td><?php echo $Match_PBKN ?></td>
                        <?php $sql_MD = mysqli_query($con,"SELECT a.id, b.grp, a.no_order, a.no_item, b.status , b.approve , 
                                              a.jenis_matching, b.percobaan_ke, b.final_matcher
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where a.jenis_matching = 'Matching Development'  
                                              and b.approve = 'TRUE' 
											  and b.status = 'selesai'
                                              and b.final_matcher = '$matcher[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                              group by a.no_resep
                                              ORDER BY a.id desc");
                        $Match_MD = mysqli_num_rows($sql_MD); ?>
                        <td><?php echo $Match_MD ?></td>
                        <?php $sql_SumPerCbn = mysqli_query($con,"SELECT sum(b.percobaan_ke) as total_percobaan
                                              FROM tbl_matching a 
                                              left join tbl_status_matching b on a.no_resep = b.idm
                                              where b.approve = 'TRUE'
											  and b.status = 'selesai'
                                              and b.final_matcher = '$matcher[nama]'
                                              and DATE_FORMAT(b.approve_at,'%Y-%m') = '$dateF'
                                              group by b.final_matcher
                                              ORDER BY a.id desc");
                        $SumPerCbn = mysqli_fetch_array($sql_SumPerCbn); ?>
                        <td class="alert-warning"><?php
                                                    $totot = $Match_LD + $Match_MU + $Match_PBKN + $Match_MD;
                                                    if ($totot == 0) {
                                                        echo '-</td><td class="alert-info">-</td>';
                                                    } else {
                                                        $toPercent = (floatval($totot) / floatval($TotalMonthXmatcher)) * 100;
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
                    $MatchLDbulanan += $Match_LD;
                    $MatchMUbulanan += $Match_MU;
                    $MatchPBKNbulanan += $Match_PBKN;
                    $MatchMDbulanan += $Match_MD;
                    ?>
                <?php endwhile; ?>
                <tr>
                    <th style="text-align: center;">Total</th>
                    <td class="bg-primary"><?php echo $MatchLDbulanan ?></td>
                    <td class="bg-primary"><?php echo $MatchMUbulanan ?></td>
                    <td class="bg-primary"><?php echo $MatchPBKNbulanan ?></td>
                    <td class="bg-primary"><?php echo $MatchMDbulanan ?></td>
                    <td class="alert-danger"><?php echo $TotalMonthXmatcher; ?></td>
                    <td class="bg-primary">100.00%</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>