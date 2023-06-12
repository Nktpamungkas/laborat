<?php
ini_set("error_reporting", 1);
session_start();
include("../../koneksi.php");
    $modal_id=$_GET['id'];
	
	
?>
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Detail DYC Consumption. </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"><i>
			Prod. demand: <b><?php echo $modal_id;?></b>				
			</i>	
			<table id="lookup1" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 14px;">
						<thead>
							<tr>
								<th>#</th>
								<th><div align="center">Item Code</div></th>
								<th><div align="center">Description</div></th>
								<th><div align="center">Used Qty</div></th>
								<th><div align="center">Lot</div></th>
								<th><div align="center">Status</div></th>															
							</tr>							
						</thead>
						<tbody>
<?php
$no=1;
$sqlDB22 = " SELECT x.USEDUSERPRIMARYQUANTITY,x.PROGRESSSTATUS,f.ITEMCODE,f.SUMMARIZEDDESCRIPTION, x.FULLITEMIDENTIFIER , x.PRODUCTIONORDERCODE FROM DB2ADMIN.PRODUCTIONRESERVATION x
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER f ON x.FULLITEMIDENTIFIER =f.IDENTIFIER 
WHERE x.ORDERCODE ='$modal_id' AND  x.ITEMTYPEAFICODE ='DYC'
		";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
							while($rD=db2_fetch_assoc($stmt2)){
	$sqlLOT =" SELECT LISTAGG(DISTINCT trim(x.LOTCODE),', ') AS LOTCODE,
x.PRODUCTIONORDERCODE , 
x.ITEMTYPECODE, 
x.FULLITEMIDENTIFIER, 
x.COMPANYCODE  FROM DB2ADMIN.STOCKTRANSACTION x
WHERE x.ITEMTYPECODE ='DYC' AND x.PRODUCTIONORDERCODE='$rD[PRODUCTIONORDERCODE]' AND x.FULLITEMIDENTIFIER ='$rD[FULLITEMIDENTIFIER]' 
GROUP BY x.PRODUCTIONORDERCODE, x.ITEMTYPECODE, x.FULLITEMIDENTIFIER, x.COMPANYCODE ";
	$stLOT=	db2_exec($conn1,$sqlLOT, array('cursor'=>DB2_SCROLLABLE));
	$rLOT=db2_fetch_assoc($stLOT);							
	if($rD['PROGRESSSTATUS']=="0"){
		$sts="Entered";
	}else if($rD['PROGRESSSTATUS']=="1"){
		$sts="Partially Used";
	}else if($rD['PROGRESSSTATUS']=="2"){
		$sts="Closed";
	}							
								
	echo"<tr'>
  	<td align=center>$no</td>
	<td align=center>$rD[ITEMCODE]</td>
	<td align=left>$rD[SUMMARIZEDDESCRIPTION]</td>
	<td align=right>".round($rD['USEDUSERPRIMARYQUANTITY'],2)."</td>	
	<td align=left>$rLOT[LOTCODE]</td>
	<td align=center>$sts</td>
	</tr>";
				$no++;				
							}
								

     
  ?>
						</tbody>	
				<tfoot>
				</tfoot>
					</table>   	
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              			  	
            </div>
			</form>	
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
               
<script>
  $(function () {	 
	$('.select2sts').select2({
    placeholder: "Select a status",
    allowClear: true
});   
  });
</script>
