<?php
    ini_set("error_reporting", 1);
    session_start();
    include "koneksi.php";    

$ProdOrder 	= isset($_POST['prod_order']) ? $_POST['prod_order'] : '';
$ProdDemand = isset($_POST['prod_demand']) ? $_POST['prod_demand'] : '';	    	 
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Production Order Tracing</title>
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
                        <div class="form-group mx-sm-3 mb-2"></div>
						<div class="form-group mb-2">
              				<input type="text" class="form-control input-sm" name="prod_order" id="prod_order" value="<?php echo $ProdOrder;?>" placeholder="Prod. Order" maxlength="8">
            </div>
                        Atau
                      <div class="form-group mx-sm-3 mb-2"></div>
						<div class="form-group mb-2">
              				<input type="text" class="form-control input-sm" name="prod_demand" id="prod_demand" value="<?php echo $ProdDemand;?>" placeholder="Prod. Demand" maxlength="8">
            </div>
                        <button type="submit" name="submit" value="search" class="btn btn-primary btn-sm mb-2"><i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </form>
                    <hr />
                </div>
                <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                  <h5 class="text-center"><strong>Detail</strong></h5>
                    <table id="Table-sm" class="table table-sm table-bordered table-striped" style="width: 100%;">
                        <thead>
                            <tr style="background-color: #4CAF50;">
                                <th style="border: 1px solid #ddd;">KFF Order</th>
                                <th style="border: 1px solid #ddd;">KFF Demand</th>
                                <th style="border: 1px solid #ddd;">Original PD</th>
                                <th style="border: 1px solid #ddd;">ProjectCode KGF</th>
                                <th style="border: 1px solid #ddd;">KGF Demand</th>
                                <th style="border: 1px solid #ddd;">KGF Order</th>
                                <th style="border: 1px solid #ddd;">No OF KGF</th>
                                <th style="border: 1px solid #ddd;">GYR Lot</th>
                                <th style="border: 1px solid #ddd;">Machine</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
if($ProdOrder!="" and $ProdDemand!=""){
	$where=" AND x.PRODUCTIONORDERCODE ='$ProdOrder' AND PRODUCTIONDEMANDCODE='$ProdDemand' ";
}else if($ProdOrder!="" and $ProdDemand=="") {
	$where=" AND x.PRODUCTIONORDERCODE ='$ProdOrder' ";
}elseif($ProdOrder=="" and $ProdDemand!="") {
	$where=" AND PRODUCTIONDEMANDCODE='$ProdDemand' ";
}else{
	$where=" AND x.PRODUCTIONORDERCODE ='$ProdOrder' ";
}	
$sqlDB2 =" SELECT x.PRODUCTIONDEMANDCODE, x.PRODUCTIONORDERCODE, s.LOTCODE, COUNT(s.ITEMELEMENTCODE) AS JML   FROM DB2ADMIN.ITXVIEWKK x
LEFT OUTER JOIN DB2ADMIN.STOCKTRANSACTION s ON x.PRODUCTIONORDERCODE = s.ORDERCODE 
WHERE s.TEMPLATECODE ='120' AND s.ITEMTYPECODE ='KGF' $where
GROUP BY x.PRODUCTIONDEMANDCODE, x.PRODUCTIONORDERCODE, s.LOTCODE ";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$no=1;   
$c=0;
$prsn=0;
$prsn1=0;
$prsn2=0;					  
while ($rowdb2 = db2_fetch_assoc($stmt)) { 	
	
$sqlDB21 =" SELECT x.* FROM DB2ADMIN.ITXVIEWKK x
WHERE PRODUCTIONDEMANDCODE = '".$rowdb2['LOTCODE']."' ";	
$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
$rowdb21 = db2_fetch_assoc($stmt1);	
$sqlDBMC = " 
SELECT ad.VALUESTRING AS MESIN FROM PRODUCTIONDEMAND pd 
LEFT OUTER JOIN ADSTORAGE ad ON ad.UNIQUEID = pd.ABSUNIQUEID AND ad.NAMENAME ='MachineNo'
WHERE CODE ='".$rowdb2['LOTCODE']."'
";					  
$stmt2MC   = db2_exec($conn1,$sqlDBMC, array('cursor'=>DB2_SCROLLABLE));	
$rowdbMC = db2_fetch_assoc($stmt2MC);
$sqlDBLOT = " 
SELECT  LISTAGG(DISTINCT  a.LOT, ', ') AS LOT FROM 
(SELECT 
CASE
        WHEN LOCATE('+', s.LOTCODE) = 0 THEN
    s.LOTCODE
        ELSE
    SUBSTR(s.LOTCODE, 1, LOCATE('+', s.LOTCODE)-1)
    END
    AS LOT  FROM STOCKTRANSACTION s 
WHERE s.ORDERCODE ='".$rowdb21['PRODUCTIONORDERCODE']."') a
";					  
$stmt2LOT   = db2_exec($conn1,$sqlDBLOT, array('cursor'=>DB2_SCROLLABLE));	
$rowdbLOT = db2_fetch_assoc($stmt2LOT);	
$sqlDBPROKGF =" SELECT PROJECTCODE FROM DB2ADMIN.STOCKTRANSACTION 
WHERE LOTCODE ='".$rowdb2['LOTCODE']."' AND TEMPLATECODE ='120' AND 
ITEMTYPECODE ='KGF' AND ORDERCODE ='".$rowdb2['PRODUCTIONORDERCODE']."'
GROUP BY PROJECTCODE ";	
$stmtPKGF   = db2_exec($conn1,$sqlDBPROKGF, array('cursor'=>DB2_SCROLLABLE));
$rPKGF = db2_fetch_assoc($stmtPKGF);	

					  ?>
                            <tr>
                                <td align="center" valign="center" class="bg-warning"><span style="text-align: center"><?php echo $rowdb2['PRODUCTIONORDERCODE']; ?></span></td>
                                <td style="text-align: center" class="bg-warning"><a href="#" id="<?php echo trim($rowdb2['PRODUCTIONDEMANDCODE']); ?>" class="show_detail_dyc"><?php echo $rowdb2['PRODUCTIONDEMANDCODE']; ?></a></td>
                                <td style="text-align: center" class="bg-warning">&nbsp;</td>
                                <td style="text-align: center" class="bg-warning"><?php echo $rPKGF['PROJECTCODE']; ?></td>
                                <td style="text-align: center" class="bg-warning"><?php echo $rowdb2['LOTCODE']; ?></td>
                                <td style="text-align: center" class="bg-warning"><?php echo $rowdb21['PRODUCTIONORDERCODE']; ?></td>
                                <td style="text-align: center" class="bg-warning"><a href="#" id="<?php echo trim($rowdb2['PRODUCTIONORDERCODE']).trim($rowdb2['LOTCODE']); ?>" class="show_detail_out"><?php echo $rowdb2['JML']; ?></a></td>
                                <td style="text-align: left" class="bg-warning"><a href="#" id="<?php echo trim($rowdb21['PRODUCTIONORDERCODE']); ?>" class="show_detail_lot"><?php echo $rowdbLOT['LOT'];?></a></td>
                                <td style="text-align: center" class="bg-warning"><?php echo $rowdbMC['MESIN'];?></td>
                            </tr>
                            <?php 
	 $no++;} ?>
                        </tbody>
                    </table>
                </div>                
            </div>
        </div>
    </div>
    </div>
</body>
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js"></script>
<div id="DYCDetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
</div>
<div id="OutDetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
</div>
<div id="LOTDetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
</div>
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
<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('click', '.show_detail_dyc', function(e) {
			var m = $(this).attr("id");
			console.log(m);
			$.ajax({
				url: "pages/ajax/show_detail_dyc.php",
				type: "GET",
				data: {
					id: m,
				},
				success: function(ajaxData) {
					$("#DYCDetailShow").html(ajaxData);
					$("#DYCDetailShow").modal('show', {
						backdrop: 'false'
					});
				}
			});
		});
		$(document).on('click', '.show_detail_out', function(e) {
			var m = $(this).attr("id");
			console.log(m);
			$.ajax({
				url: "pages/ajax/show_detail_out.php",
				type: "GET",
				data: {
					id: m,
				},
				success: function(ajaxData) {
					$("#OutDetailShow").html(ajaxData);
					$("#OutDetailShow").modal('show', {
						backdrop: 'false'
					});
				}
			});
		});
		$(document).on('click', '.show_detail_lot', function(e) {
			var m = $(this).attr("id");
			console.log(m);
			$.ajax({
				url: "pages/ajax/show_detail_lot.php",
				type: "GET",
				data: {
					id: m,
				},
				success: function(ajaxData) {
					$("#LOTDetailShow").html(ajaxData);
					$("#LOTDetailShow").modal('show', {
						backdrop: 'false'
					});
				}
			});
		});
	})	
</script>		
</html>