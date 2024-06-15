<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Recap Colorist</title>
</head>
<style>
    tr.group,
    tr.group:hover {
        background-color: #ddd !important;
    }

    table tr,
    table tr th,
    table tr td {
        border: 1px solid black !important;
    }
</style>

<body>
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" style="font-weight: bold; font-style: italic;">Recap Colorist Work</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="row">
                            <form class="form-horizontal" action="" method="post" name="form1">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-sm-8 text-center">
                                            <label>Tanggal Awal</label>
                                            <input style="text-align: center;" value="<?php echo $_POST['start'] ?>" type="text" class="form-control input-sm datepicker" required id="start" name="start" placeholder="Start" autocomplete="off">
											
                                        </div>
										<div class="col-sm-4 text-center">
                                            <label>Jam Awal</label>
                                            <input type="text" class="form-control input-sm time-picker" name="time_start" id="time_start" value="<?php
                                                                                                                    if ($_POST['submit']) {
                                                                                                                      echo $_POST['time_start'];
                                                                                                                    } else {
                                                                                                                      echo "23:00";
                                                                                                                    } ?>" placeholder="00:00" maxlength="5">											
                                        </div>
                                    </div>
									
                                </div>								
                                <div class="col-md-1" style="width: 2%;">
                                    <div class="form-group text-center">
                                        <label class="control-label"><i class="fa fa-calendar" aria-hidden="true"></i></label>
                                        <label class="control-label">S/d</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-sm-8 text-center">
                                            <label>Tanggal Akhir</label>
                                            <input style="text-align: center;" type="text" value="<?php echo $_POST['end'] ?>" class="form-control input-sm datepicker" required id="end" name="end" placeholder="End" autocomplete="off">
                                        </div>
										<div class="col-sm-4 text-center">
                                            <label>Jam Akhir</label>
                                            <input type="text" class="form-control input-sm time-picker" name="time_end" id="time_end" value="<?php
                                                                                                                    if ($_POST['submit']) {
                                                                                                                      echo $_POST['time_end'];
                                                                                                                    } else {
                                                                                                                      echo "23:00";
                                                                                                                    } ?>" placeholder="00:00" maxlength="5">											
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label>&nbsp;</label>
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-danger btn-sm" value="submit" name="submit">Submit</button>
                                        <a href="" class="btn btn-success btn-sm">Refresh</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box-header text-center">
                                    <h4 class="box-title" style="font-weight: bolder;">Recap Data Colorist <?php if ($_POST['start']) echo $_POST['start']." ".$_POST['time_start']. " S/d " . $_POST['end']." ".$_POST['time_end']; ?></h4>
                                </div>
                                <table class="table table-bordered table-hover" id="table_hasil">
                                    <thead class="bg-green">
                                        <tr>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Matching Ulang</th>
                                            <th class="text-center">Perbaikan</th>
                                            <th class="text-center">L/D</th>
                                            <th class="text-center">Matching Development</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <?php
                                        ini_set("error_reporting", 1);
                                        session_start();
                                        include "koneksi.php";
                                        function get_value($start, $end, $jenis, $colorist)
                                        {
                                            include "koneksi.php";
                                            $sql = mysqli_query($con,"SELECT
                                                                        idm,
                                                                        STATUS,
                                                                        approve,
                                                                        colorist1,
                                                                        colorist2,
                                                                        colorist3,
                                                                        colorist4,
                                                                        colorist5,
                                                                        colorist6,
                                                                        colorist7,
                                                                        colorist8,
                                                                        SUM(IF(a.colorist1 = '$colorist', 0.5, 0 ) + 
                                                                            IF(a.colorist2 = '$colorist', 0.5, 0 ) +
                                                                            IF(a.colorist3 = '$colorist', 0.5, 0 ) +
                                                                            IF(a.colorist4 = '$colorist', 0.5, 0 ) +
                                                                            IF(a.colorist5 = '$colorist', 0.5, 0 ) +
                                                                            IF(a.colorist6 = '$colorist', 0.5, 0 ) +
                                                                            IF(a.colorist7 = '$colorist', 0.5, 0 ) +
                                                                            IF(a.colorist8 = '$colorist', 0.5, 0 )) AS total_value 
                                                                    FROM
                                                                        tbl_status_matching a
                                                                        JOIN tbl_matching b ON a.idm = b.no_resep 
                                                                    WHERE
                                                                        DATE_FORMAT( a.approve_at, '%Y-%m-%d %H:%i' ) >= '$start' AND DATE_FORMAT( a.approve_at, '%Y-%m-%d %H:%i' ) <= '$end' 
                                                                        AND jenis_matching = '$jenis' 
                                                                        AND (a.colorist1 = '$colorist' 
                                                                          OR a.colorist2 = '$colorist'
                                                                          OR a.colorist3 = '$colorist'
                                                                          OR a.colorist4 = '$colorist'
                                                                          OR a.colorist5 = '$colorist'
                                                                          OR a.colorist6 = '$colorist'
                                                                          OR a.colorist7 = '$colorist'
                                                                          OR a.colorist8 = '$colorist')
                                                                        AND `status` = 'selesai'");
                                            $data = mysqli_fetch_array($sql);

                                            return $data['total_value'];
                                        }
                                        $colorist = mysqli_query($con,"SELECT * FROM tbl_colorist WHERE is_active = 'TRUE'");
                                        if ($_POST['submit']) {
                                            $start = $_POST['start']." ".$_POST['time_start'];
                                            $end = $_POST['end']." ".$_POST['time_end'];
                                        }
                                    ?>
                                    <?php if ($_POST['submit']) { ?>
                                        <tbody>
                                            <?php
                                            $all = 0;
											$start = $_POST['start']." ".$_POST['time_start'];
                                            $end = $_POST['end']." ".$_POST['time_end'];
                                            while ($clrst = mysqli_fetch_array($colorist)) { ?>
                                                <tr>
                                                    <td><?php echo $clrst['nama'] ?></td>
                                                    <td><?php $mu = get_value($start, $end, 'Matching Ulang', $clrst['nama']) + get_value($start, $end, 'Matching Ulang NOW', $clrst['nama']);
                                                        echo $mu; ?> </td>
                                                    <td><?php $mp = get_value($start, $end, 'Perbaikan', $clrst['nama']) + get_value($start, $end, 'Perbaikan NOW', $clrst['nama']);
                                                        echo $mp; ?> </td>
                                                    <td><?php $ld = get_value($start, $end, 'L/D', $clrst['nama']) + get_value($start, $end, 'LD NOW', $clrst['nama']);
                                                        echo $ld; ?> </td>
                                                    <td><?php $md = get_value($start, $end, 'Matching Development', $clrst['nama']);
                                                        echo $md; ?> </td>
                                                    <td><?php $total = $mu + $mp + $ld + $md;
                                                        echo $total ?></td>
                                                    <?php $all += $total; ?>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot class="bg-green">
                                            <tr>
                                                <th class="text-center" colspan="6"> TOTAL = <?php echo $all; ?> </th>
                                            </tr>
                                        </tfoot>
                                    <?php } else { ?>
                                        <tbody>
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    Data Not Found <br />PICK DATE TO GENERATE DATA
                                                </td>
                                            </tr>
                                        </tbody>
                                    <?php } ?>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <div class="box-header text-center">
                                    <h4 class="box-title" style="font-weight: bolder;">Recap Data Koreksi Resep <?php if ($_POST['start']) echo $_POST['start']." ".$_POST['time_start']. " S/d " . $_POST['end']." ".$_POST['time_end']; ?></h4>
                                </div>
                                <table class="table table-bordered table-hover" id="table_hasil_koreksi">
                                    <thead class="bg-yellow">
                                        <tr>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Matching Ulang</th>
                                            <th class="text-center">Perbaikan</th>
                                            <th class="text-center">L/D</th>
                                            <th class="text-center">Matching Development</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    function get_val($start, $end, $jenis, $colorist)
                                    {
                                        include "koneksi.php";
                                    //    $sql = mysqli_query($con,"SELECT SUM(IF(a.koreksi_resep != '' , 1, 0)) as total_value
                                    //        from tbl_status_matching a
                                    //        join tbl_matching b on a.idm = b.no_resep
                                    //        where DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') >= '$start' AND DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') <= '$end'
                                    //        and jenis_matching = '$jenis' and a.koreksi_resep = '$colorist'");
										$sql = mysqli_query($con,"SELECT
                                                                        a.koreksi_resep,
                                                                        a.koreksi_resep2,
                                                                        a.koreksi_resep3,
                                                                        a.koreksi_resep4,
                                                                        a.koreksi_resep5,
                                                                        a.koreksi_resep6,
                                                                        a.koreksi_resep7,
                                                                        a.koreksi_resep8,
                                                                        SUM(IF( a.koreksi_resep = '$colorist', 0.5, 0 ) +
                                                                            IF(a.koreksi_resep2 = '$colorist', 0.5, 0 ) +
                                                                            IF(a.koreksi_resep3 = '$colorist', 0.5, 0 ) +
                                                                            IF(a.koreksi_resep4 = '$colorist', 0.5, 0 ) +
                                                                            IF(a.koreksi_resep5 = '$colorist', 0.5, 0 ) +
                                                                            IF(a.koreksi_resep6 = '$colorist', 0.5, 0 ) +
                                                                            IF(a.koreksi_resep7 = '$colorist', 0.5, 0 ) +
                                                                            IF(a.koreksi_resep8 = '$colorist', 0.5, 0 )) AS total_value 
                                                                    FROM
                                                                        tbl_status_matching a
                                                                        JOIN tbl_matching b ON a.idm = b.no_resep 
                                                                    WHERE
                                                                        DATE_FORMAT( a.approve_at, '%Y-%m-%d %H:%i' ) >= '$start' AND DATE_FORMAT( a.approve_at, '%Y-%m-%d %H:%i' ) <= '$end' 
                                                                        AND jenis_matching = '$jenis' 
                                                                        AND (a.koreksi_resep = '$colorist' 
                                                                          OR a.koreksi_resep2 = '$colorist'
                                                                          OR a.koreksi_resep3 = '$colorist'
                                                                          OR a.koreksi_resep4 = '$colorist'
                                                                          OR a.koreksi_resep5 = '$colorist'
                                                                          OR a.koreksi_resep6 = '$colorist'
                                                                          OR a.koreksi_resep7 = '$colorist'
                                                                          OR a.koreksi_resep8 = '$colorist')
                                                                        AND `status` = 'selesai'");
                                        $data = mysqli_fetch_array($sql);

                                        return $data['total_value'];
                                    }
                                    if ($_POST['submit']) { ?>
                                        <tbody>
                                            <?php
                                            $alll = 0;
                                            $start = $_POST['start']." ".$_POST['time_start'];
                                            $end = $_POST['end']." ".$_POST['time_end'];
                                            $colorist = mysqli_query($con,"SELECT * FROM tbl_colorist WHERE is_active= 'TRUE' ");
                                            while ($clrst = mysqli_fetch_array($colorist)) { ?>
                                                <tr>
                                                    <td><?php echo $clrst['nama'] ?></td>
                                                    <td><?php $mu2 = get_val($start, $end, 'Matching Ulang', $clrst['nama']) + get_val($start, $end, 'Matching Ulang NOW', $clrst['nama']);
                                                        echo $mu2; ?> </td>
                                                    <td><?php $mp2 = get_val($start, $end, 'Perbaikan', $clrst['nama']) + get_val($start, $end, 'Perbaikan NOW', $clrst['nama']);
                                                        echo $mp2; ?> </td>
                                                    <td><?php $ld2 = get_val($start, $end, 'L/D', $clrst['nama']) + get_val($start, $end, 'LD NOW', $clrst['nama']);
                                                        echo $ld2; ?> </td>
                                                    <td><?php $md2 = get_val($start, $end, 'Matching Development', $clrst['nama']);
                                                        echo $md2; ?> </td>
                                                    <td><?php $totall = $mu2 + $mp2 + $ld2 + $md2;
                                                        echo $totall ?></td>
                                                    <?php $alll += $totall; ?>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <?php
                                            $avg = mysqli_query($con,"SELECT sum(percobaan_ke) as summary,count(jenis_matching) as `row`, jenis_matching
                                                from tbl_status_matching a 
                                                join tbl_matching b on a.idm = b.no_resep
                                                where DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') >= '".$_POST['start']." ".$_POST['time_start']."'
                                                AND DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') <= '".$_POST['end']." ".$_POST['time_end']."' 
                                                group by jenis_matching");
                                        ?>
                                        <tfoot class="bg-yellow">
                                            <tr>
                                                <th class="text-center" colspan="6"> TOTAL = <?php echo $alll; ?> </th>
                                            </tr>
                                            <?php while ($li = mysqli_fetch_array($avg)) { ?>
                                                <tr>
                                                    <th colspan="1"><?php echo $li['jenis_matching'] ?></th>
                                                    <th colspan="2"> Total Percobaan = <?php echo $li['summary'] ?> X</th>
                                                    <th colspan="2"> Hasil Matching = <?php echo $li['row'] ?> Match</th>
                                                    <th class="text-center">Efisiensi > <?php echo number_format($li['summary'] / $li['row'], 2); ?></th>
                                                </tr>
                                            <?php } ?>
                                        </tfoot>
                                    <?php } else { ?>
                                        <tbody>
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    Data Not Found <br />PICK DATE TO GENERATE DATA
                                                </td>
                                            </tr>
                                        </tbody>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        })
        var dataTable = $('#table_hasil').DataTable({
            responsive: true,
            "pageLength": 100,
            "select": true,
            "orderable": false,
            "columnDefs": [{
                "className": "text-center",
                "targets": [0, 1, 2, 3, 4, 5]
            }],
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    title: 'Recap Data Colorist <?php if ($_POST['start']) echo $_POST['start']." ".$_POST['time_start'] . " S/d " . $_POST['end']." ".$_POST['end_start']; ?>'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Recap Data Colorist <?php if ($_POST['start']) echo $_POST['start']." ".$_POST['time_start'] . " S/d " . $_POST['end']." ".$_POST['end_start']; ?>'
                }, {
                    extend: 'csvHtml5',
                    title: 'Recap Data Colorist <?php if ($_POST['start']) echo $_POST['start']." ".$_POST['time_start'] . " S/d " . $_POST['end']." ".$_POST['end_start']; ?>'
                }
            ]
            // 'rowsGroup': [0, 1]
        });


        var dataTablee = $('#table_hasil_koreksi').DataTable({
            responsive: true,
            "pageLength": 100,
            "select": true,
            "orderable": false,
            "columnDefs": [{
                "className": "text-center",
                "targets": [0, 1, 2, 3, 4, 5]
            }],
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    title: 'Recap Data Koreksi Resep <?php if ($_POST['start']) echo $_POST['start']." ".$_POST['time_start'] . " S/d " .  $_POST['end']." ".$_POST['end_start']; ?>'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Recap Data Koreksi Resep <?php if ($_POST['start']) echo $_POST['start']." ".$_POST['time_start'] . " S/d " .  $_POST['end']." ".$_POST['end_start']; ?>'
                }, {
                    extend: 'csvHtml5',
                    title: 'Recap Data Koreksi Resep <?php if ($_POST['start']) echo $_POST['start']." ".$_POST['time_start'] . " S/d " .  $_POST['end']." ".$_POST['end_start']; ?>'
                }
            ]
            // 'rowsGroup': [0, 1]
        });

        new $.fn.dataTable.FixedHeader(dataTable);
        new $.fn.dataTable.FixedHeader(dataTablee);

    });

    function centeredPopup(url, winName, w, h, scroll) {
        LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
        TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
        settings =
            'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable'
        popupWindow = window.open(url, winName, settings)
    }
</script>

</html>