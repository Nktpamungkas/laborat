<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();

/////////////////// QUERY TABLE !
function Masuk($jenis_matching)
{
    include "../../koneksi.php";
    $start = date('Y-m-01');
    $end = date('Y-m-d');
    $sql = mysqli_fetch_array(mysqli_query($con,"SELECT count(id) as `count` from tbl_matching
                                         WHERE jenis_matching = '$jenis_matching'
                                        AND DATE_FORMAT(tgl_buat,'%Y-%m-%d') >= '$start' 
                                        AND DATE_FORMAT(tgl_buat,'%Y-%m-%d') <= '$end'"));

    return $sql['count'];
}
function SiapBagi($jenis_matching)
{
    include "../../koneksi.php";
    $sql = mysqli_fetch_array(
		/*mysqli_query($con,"SELECT count(a.id) as count 
                                        from tbl_matching a
                                        left join tbl_status_matching b on a.no_resep = b.idm
                                        where a.jenis_matching = '$jenis_matching' and a.status_bagi = 'siap bagi' and ifnull(b.`status`, 'siap bagi') = 'siap bagi'")*/
		mysqli_query($con,"select  count(a.id) as `count` FROM tbl_matching a 
			left join tbl_status_matching b on a.`no_resep` = b.`idm`
			where b.approve_at is null  and b.status is null and a.status_bagi = 'siap bagi' and a.jenis_matching = '$jenis_matching'")
	
	);
	
    return $sql['count'];
}
function SedangJalan($jenis_matching)
{
    include "../../koneksi.php";
    $sql = mysqli_fetch_array(
        /*mysqli_query($con,"SELECT count(b.id) as `count`
        FROM tbl_status_matching a
        JOIN tbl_matching b ON a.idm = b.no_resep
        where a.status in ('buka', 'mulai', 'hold', 'batal', 'revisi','tunggu') 
        and b.jenis_matching = '$jenis_matching'")*/
		mysqli_query($con,"SELECT count(b.id) as `count`
        FROM tbl_status_matching a
        JOIN tbl_matching b ON a.idm = b.no_resep
        where a.status ='buka'
        and b.jenis_matching = '$jenis_matching'")
    );
    return $sql['count'];
}
function WaitingApprove($jenis_matching)
{
    include "../../koneksi.php";
    $sql = mysqli_fetch_array(
        mysqli_query($con,"SELECT count(b.id) as `count`
        FROM tbl_status_matching a
        INNER JOIN tbl_matching b ON a.idm = b.no_resep
        where a.status in ('selesai', 'batal') and a.approve = 'NONE' and b.jenis_matching = '$jenis_matching'")
    );
    return $sql['count'];
}

function Delete($jenis_matching)
{
    include "../../koneksi.php";
    $start = date('Y-m-01');
    $end = date('Y-m-d');
    $sql = mysqli_fetch_array(
        mysqli_query($con,"SELECT count(id) as `count` FROM db_laborat.historical_delete_matching
        where jenis_matching = '$jenis_matching' 
        AND DATE_FORMAT(delete_at ,'%Y-%m-%d') >= '$start' 
        AND DATE_FORMAT(delete_at ,'%Y-%m-%d') <= '$end'")
    );

    return $sql['count'];
}

function Tunggu($jenis_matching)
{
    include "../../koneksi.php";
    // $dm = date('Y-m');
    $sql = mysqli_fetch_array(
        //mysqli_query($con,"SELECT count(id) as `count` from tbl_matching where status_bagi = 'tunggu' and jenis_matching = '$jenis_matching'")
		mysqli_query($con,"select  count(a.id) as `count` FROM tbl_matching a 
			left join tbl_status_matching b on a.`no_resep` = b.`idm`
			where b.approve_at is null  and b.status is null and a.status_bagi = 'tunggu' and a.jenis_matching = '$jenis_matching'")
    );

    return $sql['count'];
}
function belum_bagi($jenis_matching)
{
    include "../../koneksi.php";
    // $dm = date('Y-m');
    $sql = mysqli_fetch_array(
       /* mysqli_query($con,"SELECT count(a.id) as `count` from tbl_matching a
        left join tbl_status_matching b on a.no_resep = b.idm
        where a.jenis_matching = '$jenis_matching' and a.status_bagi IS NULL and ifnull(b.`status`, 'siap bagi') = 'siap bagi'")*/
		mysqli_query($con,"select  count(a.id) as `count` FROM tbl_matching a 
			left join tbl_status_matching b on a.`no_resep` = b.`idm`
			where b.approve_at is null  and b.status is null and 
			a.status_bagi IS NULL and a.jenis_matching = '$jenis_matching'")
    );

    return $sql['count'];
}

function Selesai($jenis_matching)
{
    include "../../koneksi.php";
    $start = date('Y-m-01');
    $end = date('Y-m-d');
    $sql = mysqli_fetch_array(
        mysqli_query($con,"SELECT count(b.id ) as `count`
                    FROM tbl_status_matching a 
                    join tbl_matching b on b.no_resep = a.idm
                    WHERE b.jenis_matching = '$jenis_matching' and a.approve = 'TRUE'
                    AND DATE_FORMAT(a.approve_at,'%Y-%m-%d') >= '$start' 
                    AND DATE_FORMAT(a.approve_at,'%Y-%m-%d') <= '$end'")
    );

    return $sql['count'];
}

?>
<div class="col-md-6">
    <div class="box">
        <h4 class="text-center" style="font-weight: bold;">Status Matching <br /> <?php echo date('F-Y') ?></h4>
        <table class="table table-chart">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>LAB DIP</th>
                    <th>MATCHING ULG</th>
                    <th>PERBAIKAN</th>
                    <th>DEVELOPMENT</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr title="Data di bagi berdasarkan jenis matching dari awal bulan sampai akhir bulan (Bulan ini)">
                    <td>Masuk (Bulan <?php echo date('F') ?>)</td>
                    <td><?php $masukLD = Masuk('L/D');
                            echo $masukLD ?></td>
                    <td><?php $masukMU = Masuk('Matching Ulang');
                        echo $masukMU ?></td>
                    <td><?php $masukP = Masuk('Perbaikan');
                        echo $masukP ?></td>
                    <td><?php $masukMD = Masuk('Matching Development');
                        echo $masukMD ?></td>
                    <td><?php echo $masukLD + $masukMU + $masukP + $masukMD ?></td>
                </tr>
                <tr>
                    <td>Siap Bagi</td>
                    <td class="bg-warning"><?php $sbLD = SiapBagi('L/D');
                                            echo $sbLD ?></td>
                    <td class="bg-success"><?php $sbMU = SiapBagi('Matching Ulang');
                                            echo $sbMU ?></td>
                    <td class="bg-danger"><?php $sbP = SiapBagi('Perbaikan');
                                            echo $sbP ?></td>
                    <td class="bg-info"><?php $sbMD = SiapBagi('Matching Development');
                                        echo $sbMD ?></td>
                    <td><?php echo $sbLD +  $sbMU + $sbP + $sbMD ?></td>
                </tr>
                <tr>
                    <td>Sedang Jalan</td>
                    <td class="bg-warning"><?php $sjLD = SedangJalan('L/D');
                                            echo $sjLD ?></td>
                    <td class="bg-success"><?php $sjMU = SedangJalan('Matching Ulang');
                                            echo $sjMU ?></td>
                    <td class="bg-danger"><?php $sjP = SedangJalan('Perbaikan');
                                            echo $sjP ?></td>
                    <td class="bg-info"><?php $sjMD = SedangJalan('Matching Development');
                                        echo $sjMD ?></td>
                    <td><?php echo $sjLD + $sjMU + $sjP + $sjMD ?> *</td>
                </tr>
                <tr>
                    <td>Waiting Approve</td>
                    <td class="bg-warning"><?php $waLD = WaitingApprove('L/D');
                                            echo $waLD ?></td>
                    <td class="bg-success"><?php $waMU = WaitingApprove('Matching Ulang');
                                            echo $waMU ?></td>
                    <td class="bg-danger"><?php $waP = WaitingApprove('Perbaikan');
                                            echo $waP ?></td>
                    <td class="bg-info"><?php $waMD = WaitingApprove('Matching Development');
                                        echo $waMD ?></td>
                    <td><?php echo $waLD + $waMU + $waP + $waMD ?></td>
                </tr>
                <tr>
                    <td>Tunggu (list schedule)</td>
                    <td class="bg-warning"><?php $tgLD = Tunggu('L/D');
                                            echo $tgLD ?></td>
                    <td class="bg-success"><?php $tgMU = Tunggu('Matching Ulang');
                                            echo $tgMU ?></td>
                    <td class="bg-danger"><?php $tgP = Tunggu('Perbaikan');
                                            echo $tgP ?></td>
                    <td class="bg-info"><?php $tgMD = Tunggu('Matching Development');
                                        echo $tgMD ?></td>
                    <td><?php echo $tgLD +  $tgMU + $tgP + $tgMD ?></td>
                </tr>
                <tr>
                    <td>Belum Bagi</td>
                    <td class="bg-warning"><?php $bbLD = belum_bagi('L/D');
                                            echo $bbLD ?></td>
                    <td class="bg-success"><?php $bbMU = belum_bagi('Matching Ulang');
                                            echo $bbMU ?></td>
                    <td class="bg-danger"><?php $bbP = belum_bagi('Perbaikan');
                                            echo $bbP?></td>
                    <td class="bg-info"><?php $bbMD = belum_bagi('Matching Development');
                                        echo $bbMD ?></td>
                    <td><?php echo $bbLD + $bbMU + $bbP + $bbMD ?></td>
                </tr>
                <tr>
                    <td>Cancel/Delete</td>
                    <td><?php $dltLD = Delete('L/D');
                        echo $dltLD ?></td>
                    <td><?php $dltMU = Delete('Matching Ulang');
                        echo $dltMU ?></td>
                    <td><?php $dltP = Delete('Perbaikan');
                        echo $dltP ?></td>
                    <td><?php $dltMD = Delete('Matching Development');
                        echo $dltMD ?></td>
                    <td><?php echo $dltLD + $dltMU + $dltP + $dltMD ?></td>
                </tr>
                <tr>
                    <td>Selesai (Bulan <?php echo date('F') ?>)</td>
                    <td><?php $selesaiLD = Selesai('L/D');
                        echo $selesaiLD ?></td>
                    <td><?php $selesaiMU = Selesai('Matching Ulang');
                        echo $selesaiMU ?></td>
                    <td><?php $selesaiP = Selesai('Perbaikan');
                        echo $selesaiP ?></td>
                    <td><?php $selesaiMD = Selesai('Matching Development');
                        echo $selesaiMD ?></td>
                    <td><?php echo $selesaiLD +  $selesaiMU + $selesaiP + $selesaiMD ?></td>
                </tr>
                <tr>
                    <td>SISA (Real Time)</td>
                    <td class="bg-warning"><?php $LD =  $sbLD +  $sjLD + $waLD + $tgLD + $bbLD;
                                            echo $LD; ?></td>
                    <td class="bg-success"><?php $MU =  $sbMU +  $sjMU + $waMU + $tgMU + $bbMU;
                                            echo $MU; ?></td>
                    <td class="bg-danger"><?php $P = $sbP +  $sjP + $waP + $tgP + $bbP;
                                            echo $P; ?></td>
                    <td class="bg-info"><?php $MD =  $sbMD +  $sjMD + $waMD + $tgMD + $bbMD;
                                        echo $MD; ?></td>
                    <td><?php echo $LD + $MU + $P + $MD; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
function MasukYesterday($jenis_matching)
{
    include "../../koneksi.php";
    $ystrdy = date('Y-m-d', strtotime("-1 days"));
    $sql = mysqli_fetch_array(mysqli_query($con,"SELECT count(id) as `count` from tbl_matching
                                         WHERE jenis_matching = '$jenis_matching'
                                        AND DATE_FORMAT(tgl_buat,'%Y-%m-%d') = '$ystrdy'"));

    return $sql['count'];
}

function Selesai_Y($jenis_matching)
{
    include "../../koneksi.php";
    $ystrdy = date('Y-m-d', strtotime("-1 days"));
    $sql = mysqli_fetch_array(
        mysqli_query($con,"SELECT count(b.id ) as `count`
                    FROM tbl_status_matching a 
                    join tbl_matching b on b.no_resep = a.idm
                    WHERE b.jenis_matching = '$jenis_matching' and a.approve = 'TRUE'
                    AND DATE_FORMAT(a.approve_at,'%Y-%m-%d') = '$ystrdy'")
    );

    return $sql['count'];
}

function grpA($jenis_matching)
{
    include "../../koneksi.php";
    $sql = mysqli_fetch_array(
        mysqli_query($con,"SELECT count(a.id) as `count`
        FROM tbl_status_matching a
        JOIN tbl_matching b ON a.idm = b.no_resep
        where a.status in ('buka', 'mulai', 'hold', 'batal', 'revisi','tunggu') and a.grp = 'A' and b.jenis_matching = '$jenis_matching'")
    );

    return $sql['count'];
}
function grpB($jenis_matching)
{
    include "../../koneksi.php";
    $sql = mysqli_fetch_array(
        mysqli_query($con,"SELECT count(a.id) as `count`
        FROM tbl_status_matching a
        JOIN tbl_matching b ON a.idm = b.no_resep
        where a.status in ('buka', 'mulai', 'hold', 'batal', 'revisi','tunggu') and a.grp = 'B' and b.jenis_matching = '$jenis_matching'")
    );

    return $sql['count'];
}
function grpC($jenis_matching)
{
    include "../../koneksi.php";
    $sql = mysqli_fetch_array(
        mysqli_query($con,"SELECT count(a.id) as `count`
        FROM tbl_status_matching a
        JOIN tbl_matching b ON a.idm = b.no_resep
        where a.status in ('buka', 'mulai', 'hold', 'batal', 'revisi','tunggu') and a.grp = 'C' and b.jenis_matching = '$jenis_matching'")
    );

    return $sql['count'];
}
function grpD($jenis_matching)
{
    include "../../koneksi.php";
    $sql = mysqli_fetch_array(
        mysqli_query($con,"SELECT count(a.id) as `count`
        FROM tbl_status_matching a
        JOIN tbl_matching b ON a.idm = b.no_resep
        where a.status in ('buka', 'mulai', 'hold', 'batal', 'revisi','tunggu') and a.grp = 'D' and b.jenis_matching = '$jenis_matching'")
    );

    return $sql['count'];
}
function grpE($jenis_matching)
{
    include "../../koneksi.php";
    $sql = mysqli_fetch_array(
        mysqli_query($con,"SELECT count(a.id) as `count`
        FROM tbl_status_matching a
        JOIN tbl_matching b ON a.idm = b.no_resep
        where a.status in ('buka', 'mulai', 'hold', 'batal', 'revisi','tunggu') and a.grp = 'E' and b.jenis_matching = '$jenis_matching'")
    );

    return $sql['count'];
}
function grpF($jenis_matching)
{
    include "../../koneksi.php";
    $sql = mysqli_fetch_array(
        mysqli_query($con,"SELECT count(a.id) as `count`
        FROM tbl_status_matching a
        JOIN tbl_matching b ON a.idm = b.no_resep
        where a.status in ('buka', 'mulai', 'hold', 'batal', 'revisi','tunggu') and a.grp = 'F' and b.jenis_matching = '$jenis_matching'")
    );

    return $sql['count'];
}
?>
<div class="col-md-6">
    <div class="box">
        <h4 class="text-center" style="font-weight: bold;">KM Sedang Jalan <?php echo date('Y-m-d') ?><br />Real Time</h4>
        <table class="table table-chart">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th style="font-size: small;">Lab-Dip</th>
                    <th style="font-size: small;">Match Ulg</th>
                    <th style="font-size: small;">Perbaikan</th>
                    <th style="font-size: small;">Development</th>
                    <th style="font-size: small;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-orange">
                    <td>MASUK (H-1/Kemarin)</td>
                    <td><?php $myLD = MasukYesterday('L/D');
                        echo $myLD ?></td>
                    <td><?php $myMU = MasukYesterday('Matching Ulang');
                        echo $myMU ?></td>
                    <td><?php $myP = MasukYesterday('Perbaikan');
                        echo $myP ?></td>
                    <td><?php $myMD = MasukYesterday('Matching Development');
                        echo $myMD ?></td>
                    <td><?php $my = $myLD +  $myMU + $myP + $myMD;
                        echo  $my; ?></td>
                </tr>
                <tr class="bg-orange">
                    <td>SELESAI (H-1/Kemarin)</td>
                    <td><?php $syLD = Selesai_Y('L/D');
                        echo $syLD ?></td>
                    <td><?php $syMU = Selesai_Y('Matching Ulang');
                        echo $syMU ?></td>
                    <td><?php $syP = Selesai_Y('Perbaikan');
                        echo $syP ?></td>
                    <td><?php $syMD = Selesai_Y('Matching Development');
                        echo $syMD ?></td>
                    <td><?php $sy = $syLD +  $syMU + $syP + $syMD;
                        echo  $sy; ?></td>
                </tr>
                <tr>
                    <td>Group A</td>
                    <td><?php $gaLD = grpA('L/D') ;
                        echo $gaLD ?></td>
                    <td><?php $gaMU = grpA('Matching Ulang');
                        echo $gaMU ?></td>
                    <td><?php $gaP = grpA('Perbaikan');
                        echo $gaP ?></td>
                    <td><?php $gaMD = grpA('Matching Development');
                        echo $gaMD ?></td>
                    <td class="bg-purple"><?php $ga = $gaLD +  $gaMU + $gaP + $gaMD;
                                            echo  $ga; ?></td>
                </tr>
                <tr>
                    <td>Group B</td>
                    <td><?php $gbLD = grpB('L/D');
                        echo $gbLD ?></td>
                    <td><?php $gbMU = grpB('Matching Ulang');
                        echo $gbMU ?></td>
                    <td><?php $gbP = grpB('Perbaikan');
                        echo $gbP ?></td>
                    <td><?php $gbMD = grpB('Matching Development');
                        echo $gbMD ?></td>
                    <td class="bg-purple"><?php $gb = $gbLD +  $gbMU + $gbP + $gbMD;
                                            echo  $gb; ?></td>
                </tr>
                <tr>
                    <td>Group C</td>
                    <td><?php $gcLD = grpC('L/D');
                        echo $gcLD ?></td>
                    <td><?php $gcMU = grpC('Matching Ulang');
                        echo $gcMU ?></td>
                    <td><?php $gcP = grpC('Perbaikan');
                        echo $gcP ?></td>
                    <td><?php $gcMD = grpC('Matching Development');
                        echo $gcMD ?></td>
                    <td class="bg-purple"><?php $gc = $gcLD +  $gcMU + $gcP + $gcMD;
                                            echo  $gc; ?></td>
                </tr>
                <tr>
                    <td>Group D</td>
                    <td><?php $gdLD = grpD('L/D');
                        echo $gdLD ?></td>
                    <td><?php $gdMU = grpD('Matching Ulang');
                        echo $gdMU ?></td>
                    <td><?php $gdP = grpD('Perbaikan');
                        echo $gdP ?></td>
                    <td><?php $gdMD = grpD('Matching Development');
                        echo $gdMD ?></td>
                    <td class="bg-purple"><?php $gd = $gdLD +  $gdMU + $gdP + $gdMD;
                                            echo  $gd; ?></td>
                </tr>
                <tr>
                    <td>Group E</td>
                    <td><?php $geLD = grpE('L/D');
                        echo $geLD ?></td>
                    <td><?php $geMU = grpE('Matching Ulang');
                        echo $geMU ?></td>
                    <td><?php $geP = grpE('Perbaikan');
                        echo $geP ?></td>
                    <td><?php $geMD = grpE('Matching Development');
                        echo $geMD ?></td>
                    <td class="bg-purple"><?php $ge = $geLD +  $geMU + $geP + $geMD;
                                            echo  $ge; ?></td>
                </tr>
                <tr>
                    <td>Group F</td>
                    <td><?php $gfLD = grpF('L/D') ;
                        echo $gfLD ?></td>
                    <td><?php $gfMU = grpF('Matching Ulang');
                        echo $gfMU ?></td>
                    <td><?php $gfP = grpF('Perbaikan');
                        echo $gfP ?></td>
                    <td><?php $gfMD = grpF('Matching Development');
                        echo $gfMD ?></td>
                    <td class="bg-purple"><?php $gf = $gfLD +  $gfMU + $gfP + $gfMD;
                                            echo  $gf; ?></td>
                </tr>
                <tr>
                    <td>SUB TOTAL</td>
                    <td class="text-center"><?php echo $gaLD + $gbLD + $gcLD + $gdLD + $geLD + $gfLD; ?></td>
                    <td class="text-center"><?php echo $gaMU + $gbMU + $gcMU + $gdMU + $geMU + $gfMU; ?></td>
                    <td class="text-center"><?php echo $gaP + $gbP + $gcP + $gdP + $geP + $gfP; ?></td>
                    <td class="text-center"><?php echo $gaMD + $gbMD + $gcMD + $gdMD + $geMD + $gfMD; ?></td>
                    <td class="bg-purple"><?php echo $ga + $gb + $gc + $gd + $ge + $gf; ?> *</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>