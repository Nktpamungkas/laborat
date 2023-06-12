<?php
    ini_set("error_reporting", 1);
    session_start();
    include "koneksi.php";    

$Project 	= isset($_POST['project']) ? $_POST['project'] : '';
$KdBng = isset($_POST['kdbg']) ? $_POST['kdbg'] : '';	    	 
	
$sqlDB22PRO =" SELECT ITXVIEWKK.PROJECTCODE FROM 
 PRODUCTIONORDER  
 LEFT OUTER JOIN ( SELECT ugp.LONGDESCRIPTION AS WARNA, pr.LONGDESCRIPTION AS JNSKAIN,pd.PROJECTCODE,p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08  FROM PRODUCTIONDEMANDSTEP p
	LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE =p.PRODUCTIONDEMANDCODE
	LEFT JOIN PRODUCT pr ON
    pr.ITEMTYPECODE = pd.ITEMTYPEAFICODE
    AND pr.SUBCODE01 = pd.SUBCODE01
    AND pr.SUBCODE02 = pd.SUBCODE02
    AND pr.SUBCODE03 = pd.SUBCODE03
    AND pr.SUBCODE04 = pd.SUBCODE04
    AND pr.SUBCODE05 = pd.SUBCODE05
    AND pr.SUBCODE06 = pd.SUBCODE06
    AND pr.SUBCODE07 = pd.SUBCODE07
    AND pr.SUBCODE08 = pd.SUBCODE08
    AND pr.SUBCODE09 = pd.SUBCODE09
    AND pr.SUBCODE10 = pd.SUBCODE10
    LEFT JOIN DB2ADMIN.USERGENERICGROUP ugp ON
    pd.SUBCODE05 = ugp.CODE
	GROUP BY pr.LONGDESCRIPTION,p.PRODUCTIONORDERCODE,pd.PROJECTCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,ugp.LONGDESCRIPTION) ITXVIEWKK ON PRODUCTIONORDER.CODE=ITXVIEWKK.PRODUCTIONORDERCODE
 WHERE ITXVIEWKK.PRODUCTIONORDERCODE='$ProdOrder' ";	
$stmt2PRO   = db2_exec($conn1,$sqlDB22PRO, array('cursor'=>DB2_SCROLLABLE));
$rowdb22PRO = db2_fetch_assoc($stmt2PRO);
	
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
              				<input type="text" class="form-control input-sm" name="project" id="project" value="<?php echo $Project;?>" placeholder="Project" maxlength="20">
            </div>
						<button type="submit" name="submit" value="search" class="btn btn-primary btn-sm mb-2"><i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </form>
                    <hr />
                </div>
                <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                  <h5 class="text-center"><strong>Detail</strong></h5>
                    <table id="" class="table table-sm table-bordered table-striped" style="width: 100%;">
                        <thead>
                            <tr style="background-color: #4CAF50;">
                                <th style="border: 1px solid #ddd;">Trn No</th>
                                <th style="border: 1px solid #ddd;">TGL</th>
                                <th style="border: 1px solid #ddd;">Shift</th>
                                <th style="border: 1px solid #ddd;">User</th>
                                <th style="border: 1px solid #ddd;">KNITT</th>
                                <th style="border: 1px solid #ddd;">Project</th>
                                <th style="border: 1px solid #ddd;">Prod. Order</th>
                                <th style="border: 1px solid #ddd;">Code</th>
                                <th style="border: 1px solid #ddd;">LOT</th>
                                <th style="border: 1px solid #ddd;">Jenis Benang</th>
                                <th style="border: 1px solid #ddd;">Qty</th>
                                <th style="border: 1px solid #ddd;">Cones</th>
                                <th style="border: 1px solid #ddd;">Berat/Kg</th>
                                <th style="border: 1px solid #ddd;">Mesin</th>
                                <th style="border: 1px solid #ddd;">No Mesin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$c=0;					  
	$sqlDB21PB = " SELECT * FROM
(SELECT ITXVIEWKK.PROJECTCODE,ITXVIEWKK.ORIGDLVSALORDLINESALORDERCODE,PRODUCTIONORDER.CODE FROM 
 PRODUCTIONORDER  
 LEFT OUTER JOIN ( SELECT ugp.LONGDESCRIPTION AS WARNA, pr.LONGDESCRIPTION AS JNSKAIN,pd.PROJECTCODE,p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,pd.ORIGDLVSALORDLINESALORDERCODE  FROM PRODUCTIONDEMANDSTEP p
	LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE =p.PRODUCTIONDEMANDCODE
	LEFT JOIN PRODUCT pr ON
    pr.ITEMTYPECODE = pd.ITEMTYPEAFICODE
    AND pr.SUBCODE01 = pd.SUBCODE01
    AND pr.SUBCODE02 = pd.SUBCODE02
    AND pr.SUBCODE03 = pd.SUBCODE03
    AND pr.SUBCODE04 = pd.SUBCODE04
    AND pr.SUBCODE05 = pd.SUBCODE05
    AND pr.SUBCODE06 = pd.SUBCODE06
    AND pr.SUBCODE07 = pd.SUBCODE07
    AND pr.SUBCODE08 = pd.SUBCODE08
    AND pr.SUBCODE09 = pd.SUBCODE09
    AND pr.SUBCODE10 = pd.SUBCODE10
    LEFT JOIN DB2ADMIN.USERGENERICGROUP ugp ON
    pd.SUBCODE05 = ugp.CODE
	GROUP BY pr.LONGDESCRIPTION,p.PRODUCTIONORDERCODE,pd.PROJECTCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,ugp.LONGDESCRIPTION,pd.ORIGDLVSALORDLINESALORDERCODE) ITXVIEWKK ON PRODUCTIONORDER.CODE=ITXVIEWKK.PRODUCTIONORDERCODE	
) ORD 
INNER JOIN 
(SELECT 
	STOCKTRANSACTION.TRANSACTIONNUMBER,
	STOCKTRANSACTION.ORDERCODE,
	STOCKTRANSACTION.LOGICALWAREHOUSECODE,
	STOCKTRANSACTION.DECOSUBCODE01,
	STOCKTRANSACTION.DECOSUBCODE02,
	STOCKTRANSACTION.DECOSUBCODE03,
	STOCKTRANSACTION.DECOSUBCODE04,
	STOCKTRANSACTION.DECOSUBCODE05,
	STOCKTRANSACTION.DECOSUBCODE06,
	STOCKTRANSACTION.DECOSUBCODE07,
	STOCKTRANSACTION.DECOSUBCODE08,
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.LOTCODE,
	STOCKTRANSACTION.CREATIONUSER,
	COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_DUS,
	SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
	SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES,
	ITXVIEWKNTORDER.SCHEDULEDRESOURCECODE,
	MCN.NOMC,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
	FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN 
	DB2ADMIN.ITXVIEWKNTORDER ITXVIEWKNTORDER ON ITXVIEWKNTORDER.PRODUCTIONORDERCODE =STOCKTRANSACTION.ORDERCODE 
	LEFT OUTER JOIN (
 	SELECT ADSTORAGE.VALUESTRING AS NOMC,PRODUCTIONDEMAND.CODE FROM PRODUCTIONDEMAND
 	LEFT OUTER JOIN ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
 	) MCN ON MCN.CODE=ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
	LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
    STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.ONHANDUPDATE >1 AND NOT ORDERCODE IS NULL
GROUP BY
	STOCKTRANSACTION.TRANSACTIONNUMBER,
    STOCKTRANSACTION.ORDERCODE,
	STOCKTRANSACTION.LOGICALWAREHOUSECODE,
	STOCKTRANSACTION.DECOSUBCODE01,
	STOCKTRANSACTION.DECOSUBCODE02,
	STOCKTRANSACTION.DECOSUBCODE03,
	STOCKTRANSACTION.DECOSUBCODE04,
	STOCKTRANSACTION.DECOSUBCODE05,
	STOCKTRANSACTION.DECOSUBCODE06,
	STOCKTRANSACTION.DECOSUBCODE07,
	STOCKTRANSACTION.DECOSUBCODE08,
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.LOTCODE,
	STOCKTRANSACTION.CREATIONUSER,
	ITXVIEWKNTORDER.SCHEDULEDRESOURCECODE,
	MCN.NOMC,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
) PAKAI ON ORD.CODE=PAKAI.ORDERCODE
WHERE ( ORD.PROJECTCODE='$Project' OR ORD.ORIGDLVSALORDLINESALORDERCODE='$Project')
";
	$stmt1PB   = db2_exec($conn1,$sqlDB21PB, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21PB = db2_fetch_assoc($stmt1PB)){ 
if (trim($rowdb21PB['LOGICALWAREHOUSECODE']) =='M501' or trim($rowdb21PB['LOGICALWAREHOUSECODE']) =='M904') { $knittPB = 'LT2'; }
else if(trim($rowdb21PB['LOGICALWAREHOUSECODE']) =='P501'){ $knittPB = 'LT1'; }
$kdbenangPB=trim($rowdb21PB['DECOSUBCODE01'])." ".trim($rowdb21PB['DECOSUBCODE02'])." ".trim($rowdb21PB['DECOSUBCODE03'])." ".trim($rowdb21PB['DECOSUBCODE04'])." ".trim($rowdb21PB['DECOSUBCODE05'])." ".trim($rowdb21PB['DECOSUBCODE06'])." ".trim($rowdb21PB['DECOSUBCODE07'])." ".trim($rowdb21PB['DECOSUBCODE08']);

$sqlDB22PB = " SELECT TRANSACTIONDATE,TRANSACTIONTIME FROM STOCKTRANSACTION WHERE 
(STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.ONHANDUPDATE >1 AND STOCKTRANSACTION.TRANSACTIONDATE='$rowdb21PB[TRANSACTIONDATE]' 
AND STOCKTRANSACTION.ORDERCODE='$rowdb21PB[ORDERCODE]' AND STOCKTRANSACTION.CREATIONUSER='$rowdb21PB[CREATIONUSER]' ";
$stmt2PB   = db2_exec($conn1,$sqlDB22PB, array('cursor'=>DB2_SCROLLABLE));
$rowdb22PB = db2_fetch_assoc($stmt2PB);		
if($rowdb22PB['TRANSACTIONTIME']>="07:00:00" and $rowdb22PB['TRANSACTIONTIME']<="15:00:00"){
	$shfPB="1";
}elseif($rowdb22PB['TRANSACTIONTIME']>="15:00:00" and $rowdb22PB['TRANSACTIONTIME']<="23:00:00"){
	$shfPB="2";
}else{
	$shfPB="3";
}		
if($rowdb21PB['SCHEDULEDRESOURCECODE']!="") { $msinPB = $rowdb21PB['SCHEDULEDRESOURCECODE'];}else { $msinPB = $rowdb21PB['NOMC']; }	
$sqlDB22PROPB =" SELECT ITXVIEWKK.PROJECTCODE,ITXVIEWKK.ORIGDLVSALORDLINESALORDERCODE FROM 
 PRODUCTIONORDER  
 LEFT OUTER JOIN ( SELECT ugp.LONGDESCRIPTION AS WARNA, pr.LONGDESCRIPTION AS JNSKAIN,pd.PROJECTCODE,p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,pd.ORIGDLVSALORDLINESALORDERCODE  FROM PRODUCTIONDEMANDSTEP p
	LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE =p.PRODUCTIONDEMANDCODE
	LEFT JOIN PRODUCT pr ON
    pr.ITEMTYPECODE = pd.ITEMTYPEAFICODE
    AND pr.SUBCODE01 = pd.SUBCODE01
    AND pr.SUBCODE02 = pd.SUBCODE02
    AND pr.SUBCODE03 = pd.SUBCODE03
    AND pr.SUBCODE04 = pd.SUBCODE04
    AND pr.SUBCODE05 = pd.SUBCODE05
    AND pr.SUBCODE06 = pd.SUBCODE06
    AND pr.SUBCODE07 = pd.SUBCODE07
    AND pr.SUBCODE08 = pd.SUBCODE08
    AND pr.SUBCODE09 = pd.SUBCODE09
    AND pr.SUBCODE10 = pd.SUBCODE10
    LEFT JOIN DB2ADMIN.USERGENERICGROUP ugp ON
    pd.SUBCODE05 = ugp.CODE
	GROUP BY pr.LONGDESCRIPTION,p.PRODUCTIONORDERCODE,pd.PROJECTCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,ugp.LONGDESCRIPTION,pd.ORIGDLVSALORDLINESALORDERCODE) ITXVIEWKK ON PRODUCTIONORDER.CODE=ITXVIEWKK.PRODUCTIONORDERCODE
 WHERE ITXVIEWKK.PRODUCTIONORDERCODE='$rowdb21PB[ORDERCODE]' ";	
$stmt2PROPB   = db2_exec($conn1,$sqlDB22PROPB, array('cursor'=>DB2_SCROLLABLE));
$rowdb22PROPB = db2_fetch_assoc($stmt2PROPB);
$sqlMC =" SELECT USERGENERICGROUP.CODE AS KDMC,USERGENERICGROUP.LONGDESCRIPTION, 
USERGENERICGROUP.SHORTDESCRIPTION,USERGENERICGROUP.SEARCHDESCRIPTION FROM DB2ADMIN.USERGENERICGROUP
WHERE USERGENERICGROUP.USERGENERICGROUPTYPECODE = 'MCK' AND 
	USERGENERICGROUP.USERGENGROUPTYPECOMPANYCODE = '100' AND 
	USERGENERICGROUP.OWNINGCOMPANYCODE = '100' AND USERGENERICGROUP.CODE = '$msinPB'";		
$stmMC   = db2_exec($conn1,$sqlMC, array('cursor'=>DB2_SCROLLABLE));
$rMC = db2_fetch_assoc($stmMC);
?>
                            <tr>
                              <td style="text-align: center"><?php echo $rowdb21PB['TRANSACTIONNUMBER']; ?></td>
                              <td style="text-align: center"><?php echo $rowdb21PB['TRANSACTIONDATE']; ?></td>
                              <td style="text-align: center"><?php echo $shfPB; ?></td>
                              <td style="text-align: center"><?php echo $rowdb21PB['CREATIONUSER']; ?></td>
                              <td style="text-align: center"><?php echo $knittPB; ?></td>
                              <td style="text-align: center"><?php if($rowdb21PB['PROJECTCODE']!=""){echo $rowdb21PB['PROJECTCODE'];}else{echo $rowdb21PB['ORIGDLVSALORDLINESALORDERCODE'];} ?></td>
                              <td style="text-align: center"><a href="#" id="<?php echo trim($rowdb21PB['ORDERCODE'])."#".trim($rowdb21PB['TRANSACTIONDATE'])."#".trim($rowdb21PB['CREATIONUSER'])."#".trim($rowdb21PB['LOTCODE'])."#".trim($kdbenangPB); ?>" class="show_detailPakai"><?php echo $rowdb21PB['ORDERCODE']; ?></a></td>
                              <td><?php echo $kdbenangPB; ?></td>
                              <td style="text-align: center"><?php echo $rowdb21PB['LOTCODE']; ?></td>
                              <td style="text-align: left"><?php echo $rowdb21PB['SUMMARIZEDDESCRIPTION']; ?></td>
                              <td style="text-align: center"><?php echo $rowdb21PB['QTY_DUS']; ?></td>
                              <td style="text-align: right"><?php echo round($rowdb21PB['QTY_CONES']); ?></td>
                              <td style="text-align: right"><?php echo number_format(round($rowdb21PB['QTY_KG'],2),2); ?></td>
                              <td style="text-align: center"><?php  echo $msinPB; ?></td>
                              <td style="text-align: center"><?php echo $rMC['SEARCHDESCRIPTION']; ?></td>
                            </tr>                            
                            <?php 
	 $no++;
	
	$tRolPB+=$rowdb21PB['QTY_DUS'];
		$tConesPB+=$rowdb21PB['QTY_CONES'];
		$tKgPB+=$rowdb21PB['QTY_KG'];
	} 
?>
						<tr>
                              <td style="text-align: center">&nbsp;</td>
                              <td style="text-align: center">&nbsp;</td>
                              <td style="text-align: center">&nbsp;</td>
                              <td style="text-align: center">&nbsp;</td>
                              <td style="text-align: center">&nbsp;</td>
                              <td style="text-align: center">&nbsp;</td>
                              <td style="text-align: center">&nbsp;</td>
                              <td>&nbsp;</td>
                              <td style="text-align: center">&nbsp;</td>
                              <td style="text-align: left">Total</td>
                              <td style="text-align: right"><strong><?php echo $tRolPB; ?></strong></td>
                              <td style="text-align: right"><strong><?php echo $tConesPB; ?></strong></td>
                              <td style="text-align: right"><strong><?php echo number_format(round($tKgPB,2),2); ?></strong></td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>	
                        </tbody>
                    </table>
                </div>                
            </div>
        </div>
    </div>
    </div>
</body>
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js"></script>
<div id="DetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
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
		$(document).on('click', '.show_detailPakai', function(e) {
			var m = $(this).attr("id");
			console.log(m);
			$.ajax({
				url: "pages/ajax/show_detailPakai.php",
				type: "GET",
				data: {
					id: m,
				},
				success: function(ajaxData) {
					$("#DetailShow").html(ajaxData);
					$("#DetailShow").modal('show', {
						backdrop: 'false'
					});
				}
			});
		});		
	})	
</script>		
</html>