<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Status Matching</title>
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
        border: 1px solid #ddd;
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
$TglTutup	    = isset($_POST['tgl_tutup']) ? $_POST['tgl_tutup'] : '';	
?>
<body>
<div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"> Filter Data</h3>
                    <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
                    <div class="box-body">
						<div class="form-group">
                            <div class="col-sm-2">
              				<input type="text" class="form-control input-sm date-picker" name="tgl_tutup" id="tgl_tutup" value="<?php echo $TglTutup; ?>">
            			</div>
						<button type="submit" name="submit" value="search" class="btn btn-primary btn-sm mb-2"><i class="fa fa-search" aria-hidden="true"></i>
            			</button>	
						</div>				
                        
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>		
<div class="row">
    <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                        <?php
                        
                        $sql = mysqli_query($con,"SELECT *
                                            FROM tbl_listsch_11 WHERE tgl_tutup ='".date("Y-m-d", strtotime($TglTutup))."'
                                            ORDER BY id DESC");
                        ?>
                        <table id="Table-sm" class="table table-sm display compact" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>No. Resep</th>
                                    <th>J. Matching</th>
                                    <th>No. Order</th>
                                    <th>Benang</th>
                                    <th>Warna</th>
                                    <th>No.warna</th>
                                    <th>Langganan</th>
                                    <th>No. Item</th>
                                    <th>Keterangan</th>
                                    <th>Tgl Update</th>
                                    <th>Tgl Tutup</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($li = mysqli_fetch_array($sql)) { ?>
                                    <tr>
                                        <td>
                                            <?php if ($li['status'] == null or $li['status'] == "") { ?>
                                                <!-- status kosong -->
                                                <?php if ($li['status_bagi'] == 'siap bagi') { ?>
                                                    <button class="btn btn-circle btn-xs btn-success">Siap Bagi</button>
                                                <?php } else if ($li['status_bagi'] == 'tunggu') { ?>
                                                    <button class="btn btn-circle btn-xs btn-warning">tunggu</button>
                                                <?php } else { ?>
                                                    <button class="btn btn-circle btn-xs btn-primary">Belum Bagi</button>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php if ($li['status'] == 'buka') {
                                                    echo '<button class="btn btn-circle btn-xs btn-info">:: sedang jalan</button>';
                                                } else if ($li['status'] == 'selesai' && $li['approve'] == 'NONE') {
                                                    echo '<button class="btn btn-circle btn-xs bg-purple">:: Waiting Approval</button>';
                                                } else if ($li['status'] == 'selesai' && $li['approve'] == 'TRUE') {
                                                    echo '<button class="btn btn-circle btn-xs btn-default">:: Selesai</button>';
                                                } else {
                                                    echo '<button class="btn btn-circle btn-xs btn-default">:: ' . $li['status'] . '</button>';
                                                }
                                                ?>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $li['no_resep'] ?></td>
                                        <td><?php echo $li['jenis_matching'] ?></td>
                                        <td><?php echo $li['no_order'] ?></td>
                                        <td><?php echo $li['benang'] ?></td>
                                        <td><?php echo $li['warna'] ?></td>
                                        <td><?php echo $li['no_warna'] ?></td>
                                        <td><?php echo $li['langganan'] ?></td>
                                        <td><?php echo $li['no_item'] ?></td>
                                        <td width="150"><?php echo $li['ket'] ?></td>
                                        <td><?php echo $li['tgl_update'] ?></td>
                                        <td><?php echo $li['tgl_tutup'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
  $(document).ready(function() {
    var table = $('#Table-sm').DataTable({
      select: true,
      dom: 'Bfrtip',
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
      ],
          
    });

    
  });
</script>

</html>
