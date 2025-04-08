<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Cycle Time</title>
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
        border: 1px solid black;
    }

    td {
        font-size: 10pt;
        border: 1px solid black;
    }

    #Table-sm th {
        border: 0.1px solid black;
        vertical-align: middle;
        text-align: center;
    }

    #Table-sm th {
        color: black;
        background: #4CAF50;
    }

    #Table-sm tr:hover {
        background-color: rgb(151, 170, 212);
    }

    #Table-sm>thead>tr>td {
        border: 1px solid black;
    }

    .btn-circle {
        border-radius: 10px;
        color: black;
        font-weight: 800;
    }

    .btn-grp>a,
    .btn-grp>button {
        margin-top: 2px;
    }
</style>
<?php
    require_once 'koneksi.php';
    if (isset($_POST['simpan'])) {
        $grp_matching   = $_POST['grp_matching'];
        $shift          = $_POST['shift'];
        $nama_matcher   = $_POST['nama_matcher'];
        $tgl_input      = date('Y-m-d H:i:s');
        $user_input     = $_SESSION['userLAB'];

        $sql = "INSERT INTO tbl_cycletime(group_matching, shift, nama_matcher, tgl_input, user_input) VALUES ('$grp_matching', '$shift', '$nama_matcher', '$tgl_input', '$user_input')";
        $query = mysqli_query($con, $sql);

        if ($query) {
            echo "<script>alert('Data berhasil disimpan!')</script>";
            echo "<script>window.location.href='?p=Cycle-Time'</script>";
        } else {
            echo "<script>alert('Data gagal disimpan!')</script>";
            echo "<script>window.location.href='?p=Cycle-Time'</script>";
        }
    }elseif (isset($_POST['RunManualClosure'])) {
        $id = $_POST['id'];
        $query = mysqli_query($con, "UPDATE tbl_cycletime SET `status` = 'Closed' WHERE id = '$id'");
        if ($query) {
            echo "<script>alert('Data berhasil diclosed!')</script>";
            echo "<script>window.location.href='?p=Cycle-Time'</script>";
        } else {
            echo "<script>alert('Data gagal diclosed!')</script>";
            echo "<script>window.location.href='?p=Cycle-Time'</script>";
        }
    }
?>
<body>
	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom">
				<div class="tab-content">
                    <div class="tab-pane <?php if($_GET['gm']){ echo ''; }else{ echo 'active'; } ?>" id="tab_1">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <div class="box-header with-border">
                                        <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                                            <table id="Table-sm" class="table table-sm display compact" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Group Matching</th>
                                                        <th>Shift</th>
                                                        <th>Nama Karyawan Matcher</th>
                                                        <th>Jumlah Kartu Matching</th>
                                                        <th>Status</th>
                                                        <th>Handle</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $dataCycleTime = mysqli_query($con, "SELECT DISTINCT
                                                                                                    a.id,
                                                                                                    a.tgl_input,
                                                                                                    a.group_matching,
                                                                                                    a.shift,
                                                                                                    a.nama_matcher,
                                                                                                    a.user_input
                                                                                                FROM
                                                                                                    tbl_cycletime a
                                                                                                LEFT JOIN tbl_cycletime_detail b ON b.id_cycletime = a.id
                                                                                                WHERE
                                                                                                    a.`status` = 'Closed'
                                                                                                ORDER BY
                                                                                                    a.id DESC");
                                                        while ($rowCycleTime = mysqli_fetch_array($dataCycleTime)) {

                                                            $dataMainCycletime_detail_normal = mysqli_query($con, "SELECT * FROM tbl_cycletime_detail WHERE id_cycletime = '$rowCycleTime[id]' AND status = 'Normal'");
                                                            $rowMainCycletime_detail_normal = mysqli_fetch_assoc($dataMainCycletime_detail_normal);
                                                            
                                                            $dataMainCycletime_detail_urgent = mysqli_query($con, "SELECT * FROM tbl_cycletime_detail WHERE id_cycletime = '$rowCycleTime[id]' AND status = 'Urgent'");
                                                            $rowMainCycletime_detail_urgent = mysqli_fetch_assoc($dataMainCycletime_detail_urgent);
                                                    ?>
                                                    <tr>
                                                        <td align="center"><?= $rowCycleTime['tgl_input'] ?></td>
                                                        <td align="center"><?= $rowCycleTime['group_matching'] ?></td>
                                                        <td align="center"><?= $rowCycleTime['shift'] ?></td>
                                                        <td align="center"><?= $rowCycleTime['nama_matcher'] ?></td>
                                                        <td align="center">
                                                            <?php
                                                                $sqlKM_start    = "SELECT COUNT(*) AS Normal FROM tbl_cycletime_suffix_start WHERE id_cycletime = '$rowCycleTime[id]' AND `status` = 'Normal'";
                                                                $dataKM_start   = mysqli_query($con, $sqlKM_start);
                                                                $rowKM_start    = mysqli_fetch_assoc($dataKM_start);
                                                                echo 'Normal : '.$rowKM_start['Normal'];
                                                                
                                                                $sqlKM_start    = "SELECT COUNT(*) AS Urgent FROM tbl_cycletime_suffix_start WHERE id_cycletime = '$rowCycleTime[id]' AND `status` = 'Urgent'";
                                                                $dataKM_start   = mysqli_query($con, $sqlKM_start);
                                                                $rowKM_start    = mysqli_fetch_assoc($dataKM_start);
                                                                echo '<br>Urgent : '.$rowKM_start['Urgent'];
                                                            ?>
                                                        </td>
                                                        <td align="left">
                                                            Normal : <?= $rowMainCycletime_detail_normal['total_point']; ?> 
                                                            <?php if($rowMainCycletime_detail_urgent['total_point']) : ?>
                                                                <br> Urgent : <?= $rowMainCycletime_detail_urgent['total_point']; ?> 
                                                            <?php endif; ?>
                                                            <br>
                                                            <br> <b>Total Point :</b>   <?php
                                                                                if($rowMainCycletime_detail_urgent['total_point']){
                                                                                    echo ($rowMainCycletime_detail_normal['total_point'] + $rowMainCycletime_detail_urgent['total_point']) / 2; 
                                                                                }else{
                                                                                    echo $rowMainCycletime_detail_normal['total_point'];
                                                                                }
                                                                            ?>
                                                        </td>
                                                        <td align="center">
                                                            <div class="btn-group-vertical">
                                                                <a style="color: white;" href="?p=Form-CycleTime-Detail&id=<?= $rowCycleTime['id'] ?>&status=Normal" class="btn btn-xs btn-success">Detail Normal! <i class="fa fa-clock-o"></i></a>
                                                                <a style="color: white;" href="?p=Form-CycleTime-Detail&id=<?= $rowCycleTime['id'] ?>&status=Urgent" class="btn btn-xs btn-warning">Detail Urgent! <i class="fa fa-clock-o"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
    document.querySelectorAll('[id^="runClosureButton"]').forEach(button => {
        button.addEventListener('click', function() {
            const formId = this.id.replace('runClosureButton', 'closureForm');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Untuk menutup cycletime ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0275d8',
                cancelButtonColor: '#d9534f',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        });
    });

	$(document).ready(function() {
        $('.selectMatcher').on('mouseenter', function() {
			$(this).select2({});
		})

        const myTable = $('#Table-sm').DataTable({
            dom: 'Bfrtip',
            buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
            "ordering": false,
            "pageLength": 10
        });
    });
</script>
</html>