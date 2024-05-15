<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";

?>
<?php
    $Nowarna	= isset($_POST['nowarna']) ? $_POST['nowarna'] : '';
    $Item	    = isset($_POST['item']) ? $_POST['item'] : '';
    $Suffix	    = isset($_POST['suffix']) ? $_POST['suffix'] : '';
    $Buyer		= isset($_POST['buyer']) ? $_POST['buyer'] : '';
    $Treatment	= isset($_POST['treatment']) ? $_POST['treatment'] : '';
    $Warna	    = isset($_POST['warna']) ? $_POST['warna'] : '';	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Status Testing QC Final</title>
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
        font-size: 9pt;
    }

    #Table-sm td,
    #Table-sm th {
        border: 0.1px solid #ddd;
    }

    #Table-sm th {
        color: black;
        background: #4CAF50;
    }

    #Table-sm_filter label input.form-control {
        width: 500px;
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
                                <input name="suffix" type="text" class="form-control pull-right" id="suffix" placeholder="Suffix" value="<?php echo $Suffix;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="nowarna" type="text" class="form-control pull-right" id="nowarna" placeholder="No Warna" value="<?php echo $Nowarna;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="item" type="text" class="form-control pull-right" id="item" placeholder="No Item" value="<?php echo $Item;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="buyer" type="text" class="form-control pull-right" id="buyer" placeholder="Buyer" value="<?php echo $Buyer;  ?>" autocomplete="off"/>
                            </div>
							<div class="col-sm-2">
                                <input name="treatment" type="text" class="form-control pull-right" id="treatment" placeholder="Treatment" value="<?php echo $Treatment ;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="warna" type="text" class="form-control pull-right" id="warna" placeholder="Warna" value="<?php echo $Warna;  ?>" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-block btn-social btn-linkedin btn-sm" name="save" style="width: 60%">Search <i class="fa fa-search"></i></button>							
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
                <h3 class="box-title">Reports Testing</h3>
				</div>	
				<div class="box-body">	
				   <table id="Table-sm" class="table table-sm display compact" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="71%">Suffix</th>                                    
                                    <th width="8%">Buyer</th>                                    
                                    <th width="21%">Permintaan Testing</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
								$no=1;
								if($Nowarna!="" or $Item!="" or $Suffix!="" or $Buyer!="" or $Warna!="" or $Treatment!=""){
								$sql = mysqli_query($con,"SELECT * FROM tbl_test_qc WHERE sts_laborat ='Approved Full' AND suffix LIKE '%$Suffix%' AND treatment LIKE '%$Treatment%' AND no_warna LIKE '%$Nowarna%' AND warna LIKE '%$Warna%' AND no_item LIKE '%$Item%'");	
								}else{
                                $sql = mysqli_query($con,"SELECT * FROM tbl_test_qc WHERE sts_laborat ='Approved Full' LIMIT 100");
								}
                                while ($r = mysqli_fetch_array($sql)) {                                    
                                    $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
									$detail2=explode(",",$r['permintaan_testing']);
                                ?>
                                    <tr>
                                        <td valign="top" align="left"><strong>▕ Suffix > <?php echo $r['suffix']; ?>&nbsp;&nbsp;▕&nbsp;&nbsp;No Counter > <?php echo $r['no_counter']; ?>&nbsp;&nbsp;<br />▕&nbsp;&nbsp;Treatment > <?php echo $r['treatment']; ?>&nbsp;&nbsp;▕&nbsp;&nbsp;No Warna > <?php echo $r['no_warna']; ?>&nbsp;&nbsp;▕&nbsp;&nbsp;Warna > <?php echo $r['warna']; ?>&nbsp;&nbsp;▕&nbsp;&nbsp;Item > <?php echo $r['no_item']; ?></strong> 
											<li class="btn-group" role="group" aria-label="...">
                                                <a href="?p=Data-Testing&id=<?php echo $r['id'];?>" class="btn btn-primary btn-xs"  target="_blank"><i class="fa fa-fw fa-search"></i></a>
                                                <a href="pages/cetak/cetak_result_lab.php?idkk=<?php echo $r['id']; ?>&noitem=<?php echo $r['no_item']; ?>&nohanger=" class="btn btn-danger btn-xs" target="_blank"><i class="fa fa-fw fa-print"></i></a>                                                
                                            </li></td>
                                        <td valign="center"><?php echo $r['buyer']; ?></td>
                                        <td valign="center" align="left"><?php echo $r['permintaan_testing']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>    
                </div>
            </div>
        </div>
    </div>
	<!-- Modal Popup untuk Edit-->
    <div id="NoteLaboratEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    </div>
	<div id="StsLaboratEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    </div>
</body>

</html>
<script>
    $(document).ready(function() {
        const myTable = $('#Table-sm').DataTable({
            "ordering": false,
            "pageLength": 15,
            responsive: true,
            language: {
                searchPlaceholder: "Search..."
            },
        });

     });
</script>