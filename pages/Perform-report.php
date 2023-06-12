<?php
    ini_set("error_reporting", 1);
    session_start();
    include "koneksi.php";
    if ($_POST['submit']) {
        $start_date = $_POST['start_date']." ".$_POST['time_start'];
        $end_date = $_POST['end_date']." ".$_POST['time_end'];
		//$time_s = $_POST['time_start'];
        //$time_e = $_POST['time_end'];
    } else {
       $start_date = date('Y-m-d').' 07:00';
	   $end_date = date('Y-m-d', strtotime($day . ' + 1 day')).' 07:00';	
       //$end_date = date('Y-m-d');
	   //$time_s = '07:00';
       //$time_e = '07:00';	
    }
    $m_ago = date('Y-m', strtotime($month . ' - 1 month'));

    function sisa_bulan_lalu($head_code, $month_param, $jenis_matching)
    {
        include "koneksi.php";
        $sql_ = mysqli_query($con,"SELECT substring(a.no_resep, 1,2) as headcd, a.jenis_matching, count(a.jenis_matching) as summary
        FROM tbl_matching a 
        left join tbl_status_matching b on a.no_resep = b.idm
        where substring(a.no_resep, 1,2) = '$head_code' and DATE_FORMAT(a.tgl_buat ,'%Y-%m') = '$month_param' and jenis_matching = '$jenis_matching'
        and (b.status in ('buka') or b.status is null) 
        group by a.jenis_matching, headcd ");
        $data = mysqli_fetch_array($sql_);
        return $data['summary'];
    }

    function masuk_bulan_ini($head_code, $start, $end, $jenis_matching)
    {
        include "koneksi.php";
        $sql_ = mysqli_query($con,"SELECT substring(a.no_resep, 1,2) as headcd, a.jenis_matching, count(a.jenis_matching) as summary
        FROM tbl_matching a 
        left join tbl_status_matching b on a.no_resep = b.idm
        where substring(a.no_resep, 1,2) = '$head_code' and DATE_FORMAT(a.tgl_buat ,'%Y-%m-%d %H:%i') >= '$start' and DATE_FORMAT(a.tgl_buat , '%Y-%m-%d %H:%i') <= '$end' and jenis_matching = '$jenis_matching'
        group by a.jenis_matching, headcd
        ORDER BY a.jenis_matching");
        $data = mysqli_fetch_array($sql_);
        return $data['summary'];
    }
    function belum_keluar_bulan_ini($head_code, $start, $end, $jenis_matching)
    {
        include "koneksi.php";
        $sql_ = mysqli_query($con,"SELECT substring(a.no_resep, 1,2) as headcd, a.jenis_matching, count(a.jenis_matching) as summary
        FROM tbl_matching a 
        left join tbl_status_matching b on a.no_resep = b.idm
        where substring(a.no_resep, 1,2) = '$head_code' and DATE_FORMAT(a.tgl_buat ,'%Y-%m-%d %H:%i') >= '$start' and DATE_FORMAT(a.tgl_buat , '%Y-%m-%d %H:%i') <= '$end' 
        and jenis_matching = '$jenis_matching' and b.approve =  'NONE'
        group by a.jenis_matching, headcd
        ORDER BY a.jenis_matching");
        $data = mysqli_fetch_array($sql_);
        return $data['summary'];
    }

    function keluar_bulan_ini($head_code, $start, $end, $jenis_matching)
    {
        include "koneksi.php";
        $sql_ = mysqli_query($con,"SELECT substring(a.no_resep, 1,2) as headcd, a.jenis_matching, count(a.jenis_matching) as summary
        FROM tbl_matching a 
        left join tbl_status_matching b on a.no_resep = b.idm
        where substring(a.no_resep, 1,2) = '$head_code' and DATE_FORMAT(b.approve_at ,'%Y-%m-%d %H:%i') >= '$start' and DATE_FORMAT(b.approve_at , '%Y-%m-%d %H:%i') <= '$end' 
        and jenis_matching = '$jenis_matching' and b.approve = 'TRUE' and b.status = 'selesai'
        group by a.jenis_matching, headcd
        ORDER BY a.jenis_matching");
        $data = mysqli_fetch_array($sql_);
        return $data['summary'];
    }

    function get_sum_by_matcher($final_matcher, $head_code, $start, $end, $jenis_matching)
    {
        include "koneksi.php";
        $sql_ = mysqli_query($con,"SELECT a.final_matcher, count(a.final_matcher) as summary, substring(b.no_resep, 1,2) as headcd, b.jenis_matching
        from tbl_status_matching a
        join tbl_matching b on a.idm = b.no_resep
        where a.final_matcher = '$final_matcher' and substring(b.no_resep, 1,2) = '$head_code' and DATE_FORMAT(a.approve_at ,'%Y-%m-%d %H:%i') >= '$start' and DATE_FORMAT(a.approve_at , '%Y-%m-%d %H:%i') <= '$end' 
        and b.jenis_matching = '$jenis_matching' and a.approve = 'TRUE'
        group by b.jenis_matching, headcd");
        $data = mysqli_fetch_array($sql_);
        return $data['summary'];
    }

    function get_summary($start, $end)
    {
        include "koneksi.php";
        $sql_ = mysqli_query($con,"SELECT a.final_matcher, count(a.final_matcher) as summary, substring(b.no_resep, 1,2) as headcd, b.jenis_matching
        from tbl_status_matching a
        join tbl_matching b on a.idm = b.no_resep
        where DATE_FORMAT(a.approve_at ,'%Y-%m-%d %H:%i') >= '$start' and DATE_FORMAT(a.approve_at , '%Y-%m-%d %H:%i') <= '$end' and a.approve = 'TRUE'");
        $data = mysqli_fetch_array($sql_);
        return $data['summary'];
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Summary data</title>
</head>
<style>
    td.details-control {
        background: url('bower_components/DataTable/img/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('bower_components/DataTable/img/details_close.png') no-repeat center center;
    }

    th {
        font-size: 10pt;
    }

    td {
        font-size: 10pt;
    }

    #Table-sm td,
    #Table-sm th {
        border: 0.1px solid #ddd;
    }

    #Table-sm th {
        color: black;
        background: #4CAF50;
    }

    #Table-sm tr:hover {
        background-color: rgb(151, 170, 212);
    }

    .input-xs {
        height: 22px !important;
        padding: 2px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }

    .input-group-xs>.form-control,
    .input-group-xs>.input-group-addon,
    .input-group-xs>.input-group-btn>.btn {
        height: 22px;
        padding: 1px 5px;
        font-size: 12px;
        line-height: 1.5;
    }
</style>
<style>
    td.details-control {
        background: url('bower_components/DataTable/img/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('bower_components/DataTable/img/details_close.png') no-repeat center center;
    }

    th {
        font-size: 10pt;
    }

    td {
        font-size: 10pt;
    }

    #Table-lg td,
    #Table-lg th {
        border: 0.1px solid #ddd;
    }

    #Table-lg th {
        color: black;
        background: #5980ff;
    }

    #Table-lg tr:hover {
        background-color: rgb(151, 170, 212);
    }
</style>

<body>
    <div class="row">
        <div class="box">`
            <div class="box-header with-border">
                <div class="container-fluid">
                    <form class="form-inline" method="POST" action="">
                        <div class="form-group mx-sm-3 mb-2">
                            <input type="text" class="form-control input-sm month-picker" value="<?php
                                                                                                    if ($_POST['submit']) {
                                                                                                        echo $_POST['start_date'];
                                                                                                    } else {
                                                                                                        echo date('Y-m-d');
                                                                                                    } ?>" name="start_date" id="start_date">
                        </div>
						<div class="form-group mb-2">
              				<input type="text" class="form-control input-sm time-picker" name="time_start" id="time_start" value="<?php
                                                                                                                    if ($_POST['submit']) {
                                                                                                                      echo $_POST['time_start'];
                                                                                                                    } else {
                                                                                                                      echo "07:00";
                                                                                                                    } ?>" placeholder="00:00" maxlength="5">
            </div>
                        S/d
                        <div class="form-group mx-sm-3 mb-2">
                            <input type="text" class="form-control input-sm month-picker" value="<?php
                                                                                                    if ($_POST['submit']) {
                                                                                                        echo $_POST['end_date'];
                                                                                                    } else {
                                                                                                        //echo date('Y-m-d');
																										echo date('Y-m-d', strtotime($day . ' + 1 day'));
                                                                                                    } ?>" name="end_date" id="end_date">
                        </div>
						<div class="form-group mb-2">
              				<input type="text" class="form-control input-sm time-picker" name="time_end" id="time_end" value="<?php
                                                                                                                    if ($_POST['submit']) {
                                                                                                                      echo $_POST['time_end'];
                                                                                                                    } else {
                                                                                                                      echo "07:00";
                                                                                                                    } ?>" placeholder="00:00" maxlength="5">
            </div>
                        <button type="submit" name="submit" value="search" class="btn btn-primary btn-sm mb-2"><i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </form>
                    <hr />
                </div>
                <div class="col-lg-6 overflow-auto table-responsive" style="overflow-x: auto;">
                    <h5 class="text-center"><strong>Data Matching General</strong></h5>
                    <table id="Table-sm" class="table table-sm table-bordered table-striped" style="width: 100%;">
                        <thead>
                            <tr style="background-color: #4CAF50;">
                                <th style="border: 1px solid #ddd;">Kategori </th>
                                <th style="border: 1px solid #ddd;">Head Rcode </th>
                                <th style="border: 1px solid #ddd;">L\D</th>
                                <th style="border: 1px solid #ddd;">MATCH ULG</th>
                                <th style="border: 1px solid #ddd;">PERBAIKAN</th>
                                <th style="border: 1px solid #ddd;">MATCH DEV</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- SISA BULAN LALU -->
                            <tr>
                                <td align="center" valign="center">Sisa bulan lalu</td>
                                <td align="center" valign="center">D</td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('D2', $m_ago, 'L/D') + sisa_bulan_lalu('D2', $m_ago, 'LD NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('D2', $m_ago, 'Matching Ulang') + sisa_bulan_lalu('D2', $m_ago, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('D2', $m_ago, 'Perbaikan') + sisa_bulan_lalu('D2', $m_ago, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('D2', $m_ago, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Sisa bulan lalu</td>
                                <td align="center" valign="center">R</td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('R2', $m_ago, 'L/D') + sisa_bulan_lalu('R2', $m_ago, 'LD NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('R2', $m_ago, 'Matching Ulang') + sisa_bulan_lalu('R2', $m_ago, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('R2', $m_ago, 'Perbaikan') + sisa_bulan_lalu('R2', $m_ago, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('R2', $m_ago, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Sisa bulan lalu</td>
                                <td align="center" valign="center">DR</td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('DR', $m_ago, 'L/D') + sisa_bulan_lalu('DR', $m_ago, 'LD NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('DR', $m_ago, 'Matching Ulang') + sisa_bulan_lalu('DR', $m_ago, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('DR', $m_ago, 'Perbaikan') + sisa_bulan_lalu('DR', $m_ago, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('DR', $m_ago, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Sisa bulan lalu</td>
                                <td align="center" valign="center">A</td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('A2', $m_ago, 'L/D') + sisa_bulan_lalu('A2', $m_ago, 'LD NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('A2', $m_ago, 'Matching Ulang') + sisa_bulan_lalu('A2', $m_ago, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('A2', $m_ago, 'Perbaikan') + sisa_bulan_lalu('A2', $m_ago, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('A2', $m_ago, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Sisa bulan lalu</td>
                                <td align="center" valign="center">CD</td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('CD', $m_ago, 'L/D') + sisa_bulan_lalu('CD', $m_ago, 'LD NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('CD', $m_ago, 'Matching Ulang') + sisa_bulan_lalu('CD', $m_ago, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('CD', $m_ago, 'Perbaikan') + sisa_bulan_lalu('CD', $m_ago, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('CD', $m_ago, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Sisa bulan lalu</td>
                                <td align="center" valign="center">OBA</td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('OB', $m_ago, 'L/D') + sisa_bulan_lalu('OB', $m_ago, 'LD NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('OB', $m_ago, 'Matching Ulang') + sisa_bulan_lalu('OB', $m_ago, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('OB', $m_ago, 'Perbaikan') + sisa_bulan_lalu('OB', $m_ago, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center" class="bg-warning"><?php echo sisa_bulan_lalu('OB', $m_ago, 'Matching Development'); ?></td>
                            </tr>
                            <!-- END SISA BULAN LALU -->
                            <!-- Masuk Bulan ini -->
                            <tr>
                                <td align="center" valign="center">Masuk Tanggal <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">D</td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('D2', $start_date, $end_date, 'L/D') + masuk_bulan_ini('D2', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('D2', $start_date, $end_date, 'Matching Ulang') + masuk_bulan_ini('D2', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('D2', $start_date, $end_date, 'Perbaikan') + masuk_bulan_ini('D2', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('D2', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Masuk Tanggal <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">R</td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('R2', $start_date, $end_date, 'L/D') + masuk_bulan_ini('R2', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('R2', $start_date, $end_date, 'Matching Ulang') + masuk_bulan_ini('R2', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('R2', $start_date, $end_date, 'Perbaikan') + masuk_bulan_ini('R2', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('R2', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Masuk Tanggal <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">DR</td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('DR', $start_date, $end_date, 'L/D') + masuk_bulan_ini('DR', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('DR', $start_date, $end_date, 'Matching Ulang') + masuk_bulan_ini('DR', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('DR', $start_date, $end_date, 'Perbaikan') + masuk_bulan_ini('DR', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('DR', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Masuk Tanggal <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">A</td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('A2', $start_date, $end_date, 'L/D') + masuk_bulan_ini('A2', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('A2', $start_date, $end_date, 'Matching Ulang') + masuk_bulan_ini('A2', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('A2', $start_date, $end_date, 'Perbaikan') + masuk_bulan_ini('A2', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('A2', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Masuk Tanggal <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">CD</td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('CD', $start_date, $end_date, 'L/D') + masuk_bulan_ini('CD', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('CD', $start_date, $end_date, 'Matching Ulang') + masuk_bulan_ini('CD', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('CD', $start_date, $end_date, 'Perbaikan') + masuk_bulan_ini('CD', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('CD', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Masuk Tanggal <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">OBA</td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('OB', $start_date, $end_date, 'L/D') + masuk_bulan_ini('OB', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('OB', $start_date, $end_date, 'Matching Ulang') + masuk_bulan_ini('OB', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('OB', $start_date, $end_date, 'Perbaikan') + masuk_bulan_ini('OB', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo masuk_bulan_ini('OB', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <!-- END Masuk Bulan ini -->
                            <!-- Keluar Bulan ini -->
                            <tr>
                                <td align="center" valign="center">Belum Keluar(sedang jalan) <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">D</td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('D2', $start_date, $end_date, 'L/D') + belum_keluar_bulan_ini('D2', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('D2', $start_date, $end_date, 'Matching Ulang') + belum_keluar_bulan_ini('D2', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('D2', $start_date, $end_date, 'Perbaikan') + belum_keluar_bulan_ini('D2', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('D2', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Belum Keluar(sedang jalan) <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">R</td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('R2', $start_date, $end_date, 'L/D') + belum_keluar_bulan_ini('R2', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('R2', $start_date, $end_date, 'Matching Ulang') + belum_keluar_bulan_ini('R2', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('R2', $start_date, $end_date, 'Perbaikan') + belum_keluar_bulan_ini('R2', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('R2', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Belum Keluar(sedang jalan) <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">DR</td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('DR', $start_date, $end_date, 'L/D') + belum_keluar_bulan_ini('DR', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('DR', $start_date, $end_date, 'Matching Ulang') + belum_keluar_bulan_ini('DR', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('DR', $start_date, $end_date, 'Perbaikan') + belum_keluar_bulan_ini('DR', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('DR', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Belum Keluar(sedang jalan) <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">A</td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('A2', $start_date, $end_date, 'L/D') + belum_keluar_bulan_ini('A2', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('A2', $start_date, $end_date, 'Matching Ulang') + belum_keluar_bulan_ini('A2', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('A2', $start_date, $end_date, 'Perbaikan') + belum_keluar_bulan_ini('A2', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('A2', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Belum Keluar(sedang jalan) <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">CD</td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('CD', $start_date, $end_date, 'L/D') + belum_keluar_bulan_ini('CD', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('CD', $start_date, $end_date, 'Matching Ulang') + belum_keluar_bulan_ini('CD', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('CD', $start_date, $end_date, 'Perbaikan') + belum_keluar_bulan_ini('CD', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('CD', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Belum Keluar(sedang jalan) <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">OBA</td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('OB', $start_date, $end_date, 'L/D') + belum_keluar_bulan_ini('OB', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('OB', $start_date, $end_date, 'Matching Ulang') + belum_keluar_bulan_ini('OB', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('OB', $start_date, $end_date, 'Perbaikan') + belum_keluar_bulan_ini('OB', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo belum_keluar_bulan_ini('OB', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <!-- END Belum Keluar(sedang jalan) -->
                            <!-- Resep Keluar -->
                            <tr>
                                <td align="center" valign="center">Resep Keluar <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">D</td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('D2', $start_date, $end_date, 'L/D') + keluar_bulan_ini('D2', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('D2', $start_date, $end_date, 'Matching Ulang') + keluar_bulan_ini('D2', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('D2', $start_date, $end_date, 'Perbaikan') + keluar_bulan_ini('D2', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('D2', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Resep Keluar <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">R</td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('R2', $start_date, $end_date, 'L/D') + keluar_bulan_ini('R2', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('R2', $start_date, $end_date, 'Matching Ulang') + keluar_bulan_ini('R2', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('R2', $start_date, $end_date, 'Perbaikan') + keluar_bulan_ini('R2', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('R2', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Resep Keluar <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">DR</td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('DR', $start_date, $end_date, 'L/D') + keluar_bulan_ini('DR', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('DR', $start_date, $end_date, 'Matching Ulang') + keluar_bulan_ini('DR', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('DR', $start_date, $end_date, 'Perbaikan') + keluar_bulan_ini('DR', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('DR', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Resep Keluar <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">A</td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('A2', $start_date, $end_date, 'L/D') + keluar_bulan_ini('A2', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('A2', $start_date, $end_date, 'Matching Ulang') + keluar_bulan_ini('A2', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('A2', $start_date, $end_date, 'Perbaikan') + keluar_bulan_ini('A2', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('A2', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Resep Keluar <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">CD</td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('CD', $start_date, $end_date, 'L/D') + keluar_bulan_ini('CD', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('CD', $start_date, $end_date, 'Matching Ulang') + keluar_bulan_ini('CD', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('CD', $start_date, $end_date, 'Perbaikan'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('CD', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <tr>
                                <td align="center" valign="center">Resep Keluar <?php echo $start_date . ' S/d ' . $end_date  ?></td>
                                <td align="center" valign="center">OBA</td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('OB', $start_date, $end_date, 'L/D') + keluar_bulan_ini('OB', $start_date, $end_date, 'LD NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('OB', $start_date, $end_date, 'Matching Ulang') + keluar_bulan_ini('OB', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('OB', $start_date, $end_date, 'Perbaikan') + keluar_bulan_ini('OB', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                <td align="center" valign="center"><?php echo keluar_bulan_ini('OB', $start_date, $end_date, 'Matching Development'); ?></td>
                            </tr>
                            <!-- END Resep Keluar -->
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6 overflow-auto table-responsive" style="overflow-x: auto;">
                    <h5 class="text-center"><strong>Persentase Matcher Jenis Matching <?php echo $start_date . ' S/d ' . $end_date  ?></strong></h5>
                    <table id="Table-lg" class="table table-sm table-bordered table-striped" style="width: 100%;">
                        <thead>
                            <tr style="background-color: #5980ff;">
                                <th style="border: 1px solid #ddd;">MATCHER </th>
                                <th style="border: 1px solid #ddd;">Head Rcode </th>
                                <th style="border: 1px solid #ddd;">L\D</th>
                                <th style="border: 1px solid #ddd;">MATCH ULG</th>
                                <th style="border: 1px solid #ddd;">PERBAIKAN</th>
                                <th style="border: 1px solid #ddd;">MATCH DEV</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sql_matcher = mysqli_query($con,"SELECT * from tbl_matcher WHERE `status`='Aktif'"); ?>
                            <?php while ($matcher = mysqli_fetch_array($sql_matcher)) : ?>
                                <tr>
                                    <td><?php echo $matcher['nama'] ?></td>
                                    <td>D</td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'D2', $start_date, $end_date, 'L/D') + get_sum_by_matcher($matcher['nama'], 'D2', $start_date, $end_date, 'LD NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'D2', $start_date, $end_date, 'Matching Ulang') + get_sum_by_matcher($matcher['nama'], 'D2', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'D2', $start_date, $end_date, 'Perbaikan') + get_sum_by_matcher($matcher['nama'], 'D2', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'D2', $start_date, $end_date, 'Matching Development') ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $matcher['nama'] ?></td>
                                    <td>R</td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'R2', $start_date, $end_date, 'L/D') + get_sum_by_matcher($matcher['nama'], 'R2', $start_date, $end_date, 'LD NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'R2', $start_date, $end_date, 'Matching Ulang') + get_sum_by_matcher($matcher['nama'], 'R2', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'R2', $start_date, $end_date, 'Perbaikan') + get_sum_by_matcher($matcher['nama'], 'R2', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'R2', $start_date, $end_date, 'Matching Development') ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $matcher['nama'] ?></td>
                                    <td>DR</td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'DR', $start_date, $end_date, 'L/D') + get_sum_by_matcher($matcher['nama'], 'DR', $start_date, $end_date, 'LD NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'DR', $start_date, $end_date, 'Matching Ulang') + get_sum_by_matcher($matcher['nama'], 'DR', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'DR', $start_date, $end_date, 'Perbaikan') + get_sum_by_matcher($matcher['nama'], 'DR', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'DR', $start_date, $end_date, 'Matching Development') ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $matcher['nama'] ?></td>
                                    <td>A</td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'A2', $start_date, $end_date, 'L/D') + get_sum_by_matcher($matcher['nama'], 'A2', $start_date, $end_date, 'LD NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'A2', $start_date, $end_date, 'Matching Ulang') + get_sum_by_matcher($matcher['nama'], 'A2', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'A2', $start_date, $end_date, 'Perbaikan') + get_sum_by_matcher($matcher['nama'], 'A2', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'A2', $start_date, $end_date, 'Matching Development') ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $matcher['nama'] ?></td>
                                    <td>CD</td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'CD', $start_date, $end_date, 'L/D') + get_sum_by_matcher($matcher['nama'], 'CD', $start_date, $end_date, 'LD NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'CD', $start_date, $end_date, 'Matching Ulang') + get_sum_by_matcher($matcher['nama'], 'CD', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'CD', $start_date, $end_date, 'Perbaikan') + get_sum_by_matcher($matcher['nama'], 'CD', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'CD', $start_date, $end_date, 'Matching Development') ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $matcher['nama'] ?></td>
                                    <td>OBA</td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'OB', $start_date, $end_date, 'L/D') + get_sum_by_matcher($matcher['nama'], 'OB', $start_date, $end_date, 'LD NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'OB', $start_date, $end_date, 'Matching Ulang') + get_sum_by_matcher($matcher['nama'], 'OB', $start_date, $end_date, 'Matching Ulang NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'OB', $start_date, $end_date, 'Perbaikan') + get_sum_by_matcher($matcher['nama'], 'OB', $start_date, $end_date, 'Perbaikan NOW'); ?></td>
                                    <td><?php echo get_sum_by_matcher($matcher['nama'], 'OB', $start_date, $end_date, 'Matching Development') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6"> TOTAL : <?php echo get_summary($start_date, $end_date) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#Table-sm').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            "searching": false,
            "ordering": false,
            "paging": false,
            "pageLength": 50,
            dom: 'Bfrtip',
            order: [0],
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            'rowsGroup': [0]
        });
        var table = $('#Table-lg').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            "searching": false,
            "ordering": false,
            "paging": false,
            "pageLength": 50,
            dom: 'Bfrtip',
            order: [0],
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            'rowsGroup': [0]
        });


        $('.month-picker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        })
    });
</script>

</html>