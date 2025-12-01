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
            <?php $dateY = date('Y-m-d', strtotime("-2 days"));
            $sql_TotalYstrdyXcolorist = mysqli_query($con,"SELECT 
                                                                a.id,
                                                                b.grp,
                                                                a.no_order,
                                                                a.no_item,
                                                                b.STATUS,
                                                                b.approve,
                                                                a.jenis_matching,
                                                                b.percobaan_ke,
                                                                b.koreksi_resep,
                                                                b.koreksi_resep2
                                                            FROM
                                                                tbl_matching a
                                                            LEFT JOIN tbl_status_matching b ON a.no_resep = b.idm 
                                                            WHERE
                                                                b.approve = 'TRUE'
                                                            -- and b.status <> 'hold'
                                                            AND b.STATUS = 'selesai'
                                                            AND (b.koreksi_resep <> '' OR b.koreksi_resep2 <> '')
                                                            AND DATE_FORMAT( b.approve_at, '%Y-%m-%d %H:%i' ) BETWEEN '$dateY 23:00' AND '$tody 23:00' 
                                                            GROUP BY
                                                                a.no_resep 
                                                            ORDER BY
                                                                a.id DESC");
            $TotalYstrdyXcolorist = mysqli_num_rows($sql_TotalYstrdyXcolorist);
            $sql_colorist = mysqli_query($con,"SELECT
                                                    a.nama,
                                                    count( c.no_resep ) AS cout 
                                                FROM
                                                    tbl_colorist a
                                                    LEFT JOIN tbl_status_matching b ON a.nama = b.koreksi_resep
                                                    LEFT JOIN tbl_matching c ON b.idm = c.no_resep 
                                                WHERE
                                                    a.is_active = 'TRUE' 
                                                    AND b.approve = 'TRUE' 
                                                    AND b.STATUS = 'selesai' 
                                                    AND DATE_FORMAT( b.approve_at, '%Y-%m-%d %H:%i' ) BETWEEN '$dateY 23:00' AND '$tody 23:00' 
                                                GROUP BY
                                                    a.nama 
                                                ORDER BY
                                                    cout DESC");
            ?>
            <tbody>
                <?php while ($colorist = mysqli_fetch_array($sql_colorist)) : ?>
                    <tr>
                        <th><?php echo $colorist['nama'] ?></th>
                        <?php $sql_LD = mysqli_query($con, "SELECT
                                                                a.id,
                                                                b.grp,
                                                                a.no_order,
                                                                a.no_item,
                                                                b.STATUS,
                                                                b.approve,
                                                                a.jenis_matching,
                                                                b.percobaan_ke,
                                                                b.final_matcher 
                                                            FROM
                                                                tbl_matching a
                                                                LEFT JOIN tbl_status_matching b ON a.no_resep = b.idm 
                                                            WHERE
                                                                ( a.jenis_matching = 'L/D' OR a.jenis_matching = 'LD NOW' ) 
                                                                AND b.approve = 'TRUE' 
                                                                AND b.STATUS = 'selesai' 
                                                                AND b.koreksi_resep = '$colorist[nama]' 
                                                                AND NOT a.no_order = 'LABDIP'
                                                                AND DATE_FORMAT( b.approve_at, '%Y-%m-%d %H:%i' ) BETWEEN '$dateY 23:00' AND '$tody 23:00' 
                                                            GROUP BY
                                                                a.no_resep 
                                                            ORDER BY
                                                                a.id DESC");
                        $Color_LD = mysqli_num_rows($sql_LD); ?>
                        <td><?php echo $Color_LD ?></td>
                        <?php $sql_MU = mysqli_query($con,"SELECT
                                                                a.id,
                                                                b.grp,
                                                                a.no_order,
                                                                a.no_item,
                                                                b.STATUS,
                                                                b.approve,
                                                                a.jenis_matching,
                                                                b.percobaan_ke,
                                                                b.final_matcher 
                                                            FROM
                                                                tbl_matching a
                                                                LEFT JOIN tbl_status_matching b ON a.no_resep = b.idm 
                                                            WHERE
                                                                ( a.jenis_matching = 'Matching Ulang' OR a.jenis_matching = 'Matching Ulang NOW' ) 
                                                                AND b.approve = 'TRUE' 
                                                                AND b.STATUS = 'selesai' 
                                                                AND b.koreksi_resep = '$colorist[nama]' 
                                                                AND DATE_FORMAT( b.approve_at, '%Y-%m-%d %H:%i' ) BETWEEN '$dateY 23:00' AND '$tody 23:00' 
                                                            GROUP BY
                                                                a.no_resep 
                                                            ORDER BY
                                                                a.id DESC");
                        $Color_MU = mysqli_num_rows($sql_MU); ?>
                        <td><?php echo $Color_MU ?></td>
                        <?php $sql_PBKN = mysqli_query($con,"SELECT
                                                                a.id,
                                                                b.grp,
                                                                a.no_order,
                                                                a.no_item,
                                                                b.STATUS,
                                                                b.approve,
                                                                a.jenis_matching,
                                                                b.percobaan_ke,
                                                                b.final_matcher 
                                                            FROM
                                                                tbl_matching a
                                                                LEFT JOIN tbl_status_matching b ON a.no_resep = b.idm 
                                                            WHERE
                                                                ( a.jenis_matching = 'Perbaikan' OR a.jenis_matching = 'Perbaikan NOW' ) 
                                                                AND b.approve = 'TRUE' 
                                                                AND b.STATUS = 'selesai' 
                                                                AND b.koreksi_resep = '$colorist[nama]' 
                                                                AND DATE_FORMAT( b.approve_at, '%Y-%m-%d %H:%i' ) BETWEEN '$dateY 23:00' AND '$tody 23:00' 
                                                            GROUP BY
                                                                a.no_resep 
                                                            ORDER BY
                                                                a.id DESC");
                        $Color_PBKN = mysqli_num_rows($sql_PBKN); ?>
                        <td><?php echo $Color_PBKN ?></td>
                        <?php $sql_MD = mysqli_query($con,"SELECT
                                                                a.id,
                                                                b.grp,
                                                                a.no_order,
                                                                a.no_item,
                                                                b.STATUS,
                                                                b.approve,
                                                                a.jenis_matching,
                                                                b.percobaan_ke,
                                                                b.final_matcher 
                                                            FROM
                                                                tbl_matching a
                                                                LEFT JOIN tbl_status_matching b ON a.no_resep = b.idm 
                                                            WHERE
                                                                a.jenis_matching = 'Matching Development' 
                                                                AND b.approve = 'TRUE' 
                                                                AND b.STATUS = 'selesai' 
                                                                AND b.koreksi_resep = '$colorist[nama]' 
                                                                AND DATE_FORMAT( b.approve_at, '%Y-%m-%d %H:%i' ) BETWEEN '$dateY 23:00' AND '$tody 23:00' 
                                                            GROUP BY
                                                                a.no_resep 
                                                            ORDER BY
                                                                a.id DESC");
                        $Color_MD = mysqli_num_rows($sql_MD); ?>
                        <td><?php echo $Color_MD ?></td>
                        <?php $sql_SumPerCbn = mysqli_query($con,"SELECT
                                                                    sum( b.percobaan_ke ) AS total_percobaan 
                                                                FROM
                                                                    tbl_matching a
                                                                    LEFT JOIN tbl_status_matching b ON a.no_resep = b.idm 
                                                                WHERE
                                                                    b.approve = 'TRUE' 
                                                                    AND b.STATUS = 'selesai' 
                                                                    AND b.koreksi_resep = '$colorist[nama]' 
                                                                    AND DATE_FORMAT( b.approve_at, '%Y-%m-%d %H:%i' ) BETWEEN '$dateY 23:00' AND '$tody 23:00' 
                                                                GROUP BY
                                                                    b.koreksi_resep 
                                                                ORDER BY
                                                                    a.id DESC");
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
            $sql_TotalMonthXcolorist = mysqli_query($con,"SELECT
                                                            a.id,
                                                            b.grp,
                                                            a.no_order,
                                                            a.no_item,
                                                            b.STATUS,
                                                            b.approve,
                                                            a.jenis_matching,
                                                            b.percobaan_ke,
                                                            b.koreksi_resep,
                                                            b.koreksi_resep2 
                                                        FROM
                                                            tbl_matching a
                                                            LEFT JOIN tbl_status_matching b ON a.no_resep = b.idm 
                                                        WHERE
                                                            b.approve = 'TRUE' 
                                                            -- and b.status <> 'hold'	
                                                            AND b.STATUS = 'selesai' 
                                                            AND ( b.koreksi_resep <> '' OR b.koreksi_resep2 <> '' ) 
                                                            AND DATE_FORMAT( b.approve_at, '%Y-%m' ) = '$dateF' 
                                                        ORDER BY
                                                            a.id DESC");
            $TotalMonthXcolorist = mysqli_num_rows($sql_TotalMonthXcolorist);
            $sql_colorist = mysqli_query($con,"SELECT
                                                    a.nama,
                                                    count( c.no_resep ) AS cout 
                                                FROM
                                                    tbl_colorist a
                                                    LEFT JOIN tbl_status_matching b ON a.nama = b.koreksi_resep
                                                    LEFT JOIN tbl_matching c ON b.idm = c.no_resep 
                                                WHERE
                                                    a.is_active = 'TRUE' 
                                                    AND b.approve = 'TRUE' 
                                                    AND b.STATUS = 'selesai' 
                                                    AND DATE_FORMAT( b.approve_at, '%Y-%m' ) = '$dateF' 
                                                GROUP BY
                                                    a.nama 
                                                ORDER BY
                                                    cout DESC");
            ?>
            <tbody>
                <?php while ($colorist = mysqli_fetch_array($sql_colorist)) : ?>
                    <tr>
                        <th><?php echo $colorist['nama'] ?></th>
                        <?php $sql_LD = mysqli_query($con,"SELECT
                                                            a.id,
                                                            b.grp,
                                                            a.no_order,
                                                            a.no_item,
                                                            b.STATUS,
                                                            b.approve,
                                                            a.jenis_matching,
                                                            b.percobaan_ke,
                                                            b.final_matcher 
                                                        FROM
                                                            tbl_matching a
                                                            LEFT JOIN tbl_status_matching b ON a.no_resep = b.idm 
                                                        WHERE
                                                            ( a.jenis_matching = 'L/D' OR a.jenis_matching = 'LD NOW' ) 
                                                            AND b.approve = 'TRUE' 
                                                            AND b.STATUS = 'selesai' 
                                                            AND b.koreksi_resep = '$colorist[nama]' 
                                                            AND DATE_FORMAT( b.approve_at, '%Y-%m' ) = '$dateF' 
                                                        GROUP BY
                                                            a.no_resep 
                                                        ORDER BY
                                                            a.id DESC");
                        $Color_LD = mysqli_num_rows($sql_LD); ?>
                        <td><?php echo $Color_LD ?></td>
                        <?php $sql_MU = mysqli_query($con,"SELECT
                                                            a.id,
                                                            b.grp,
                                                            a.no_order,
                                                            a.no_item,
                                                            b.STATUS,
                                                            b.approve,
                                                            a.jenis_matching,
                                                            b.percobaan_ke,
                                                            b.final_matcher 
                                                        FROM
                                                            tbl_matching a
                                                            LEFT JOIN tbl_status_matching b ON a.no_resep = b.idm 
                                                        WHERE
                                                            ( a.jenis_matching = 'Matching Ulang' OR a.jenis_matching = 'Matching Ulang NOW' ) 
                                                            AND b.approve = 'TRUE' 
                                                            AND b.STATUS = 'selesai' 
                                                            AND b.koreksi_resep = '$colorist[nama]' 
                                                            AND DATE_FORMAT( b.approve_at, '%Y-%m' ) = '$dateF' 
                                                        GROUP BY
                                                            a.no_resep 
                                                        ORDER BY
                                                            a.id DESC");
                        $Color_MU = mysqli_num_rows($sql_MU); ?>
                        <td><?php echo $Color_MU ?></td>
                        <?php $sql_PBKN = mysqli_query($con,"SELECT
                                                            a.id,
                                                            b.grp,
                                                            a.no_order,
                                                            a.no_item,
                                                            b.STATUS,
                                                            b.approve,
                                                            a.jenis_matching,
                                                            b.percobaan_ke,
                                                            b.final_matcher 
                                                        FROM
                                                            tbl_matching a
                                                            LEFT JOIN tbl_status_matching b ON a.no_resep = b.idm 
                                                        WHERE
                                                            ( a.jenis_matching = 'Perbaikan' OR a.jenis_matching = 'Perbaikan NOW' ) 
                                                            AND b.approve = 'TRUE' 
                                                            AND b.STATUS = 'selesai' 
                                                            AND b.koreksi_resep = '$colorist[nama]' 
                                                            AND DATE_FORMAT( b.approve_at, '%Y-%m' ) = '$dateF' 
                                                        GROUP BY
                                                            a.no_resep 
                                                        ORDER BY
                                                            a.id DESC");
                        $Color_PBKN = mysqli_num_rows($sql_PBKN); ?>
                        <td><?php echo $Color_PBKN ?></td>
                        <?php $sql_MD = mysqli_query($con,"SELECT
                                                            a.id,
                                                            b.grp,
                                                            a.no_order,
                                                            a.no_item,
                                                            b.STATUS,
                                                            b.approve,
                                                            a.jenis_matching,
                                                            b.percobaan_ke,
                                                            b.final_matcher 
                                                        FROM
                                                            tbl_matching a
                                                            LEFT JOIN tbl_status_matching b ON a.no_resep = b.idm 
                                                        WHERE
                                                            a.jenis_matching = 'Matching Development' 
                                                            AND b.approve = 'TRUE' 
                                                            AND b.STATUS = 'selesai' 
                                                            AND b.koreksi_resep = '$colorist[nama]' 
                                                            AND DATE_FORMAT( b.approve_at, '%Y-%m' ) = '$dateF' 
                                                        GROUP BY
                                                            a.no_resep 
                                                        ORDER BY
                                                            a.id DESC");
                        $Color_MD = mysqli_num_rows($sql_MD); ?>
                        <td><?php echo $Color_MD ?></td>
                        <?php $sql_SumPerCbn = mysqli_query($con,"SELECT
                                                                    sum( b.percobaan_ke ) AS total_percobaan 
                                                                FROM
                                                                    tbl_matching a
                                                                    LEFT JOIN tbl_status_matching b ON a.no_resep = b.idm 
                                                                    AND b.approve = 'TRUE' 
                                                                    AND b.STATUS = 'selesai' 
                                                                    AND b.koreksi_resep = '$colorist[nama]' 
                                                                    AND DATE_FORMAT( b.approve_at, '%Y-%m' ) = '$dateF' 
                                                                GROUP BY
                                                                    b.koreksi_resep 
                                                                ORDER BY
                                                                    a.id DESC");
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