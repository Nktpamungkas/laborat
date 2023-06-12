<?php
ini_set("error_reporting", 1);
session_start();
include("../../koneksi.php");
    $modal_id=$_GET['id'];
	/*$ORDERCODE=substr($modal_id,0,8);	
	$TGL=substr($modal_id,9,10);
	$LOTCODE=substr($modal_id,20,200);*/
	$ORDERCODE=substr($modal_id,0,8);	
	$TGL=substr($modal_id,9,10);
	$pos=substr($modal_id,20,300);
	$poss=strpos($pos,"#");	
	$UID=substr($modal_id,20,$poss);
	$pos1=substr($modal_id,(21+$poss),200);
	$poss1=strpos($pos1,"#");
	$LOTCODE=substr($modal_id,(21+$poss),$poss1);
	$KD=substr($pos1,($poss1+1),200);
	
	
?>
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Detail Data Element</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"><i>
			Tgl: <b><?php echo $TGL ;?></b><br>
			Lot: <b><?php echo $LOTCODE;?></b><br>	
			BON: <b><?php echo $ORDERCODE;?></b>				
			</i>	
			<table id="lookup1" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 14px;">
						<thead>
							<tr>
								<th>#</th>
								<th><div align="center">TRN NO.</div></th>
								<th><div align="center">ELEMENTCODE</div></th>
								<th><div align="center">CODE</div></th>
								<th><div align="center">CONES</div></th>
								<th><div align="center">KGS</div></th>
								<th><div align="center">LOTCODE</div></th>
								<th><div align="center">LOKASI</div></th>
								<th><div align="center">JAM</div></th>															
							</tr>
						</thead>
						<tbody>
							<?php
							$no=1;
							$sqlDB22 = "SELECT 
							STOCKTRANSACTION.TRANSACTIONNUMBER,
							STOCKTRANSACTION.LOGICALWAREHOUSECODE,
							STOCKTRANSACTION.ITEMELEMENTCODE,STOCKTRANSACTION.BASESECONDARYQUANTITY,
							STOCKTRANSACTION.BASEPRIMARYQUANTITY,STOCKTRANSACTION.LOTCODE,
							STOCKTRANSACTION.DECOSUBCODE01,
							STOCKTRANSACTION.DECOSUBCODE02,
							STOCKTRANSACTION.DECOSUBCODE03,
							STOCKTRANSACTION.DECOSUBCODE04,
							STOCKTRANSACTION.DECOSUBCODE05,
							STOCKTRANSACTION.DECOSUBCODE06,
							STOCKTRANSACTION.DECOSUBCODE07,
							STOCKTRANSACTION.DECOSUBCODE08,
							STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
							STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
							STOCKTRANSACTION.TRANSACTIONTIME  
		FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION  
		WHERE (STOCKTRANSACTION.LOGICALWAREHOUSECODE='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE='M904') AND STOCKTRANSACTION.ORDERCODE='$ORDERCODE'
		AND STOCKTRANSACTION.LOTCODE ='$LOTCODE' AND STOCKTRANSACTION.TRANSACTIONDATE='$TGL' AND STOCKTRANSACTION.CREATIONUSER='$UID' AND 		
CONCAT(TRIM(STOCKTRANSACTION.DECOSUBCODE01),
CONCAT(' ',CONCAT(TRIM(STOCKTRANSACTION.DECOSUBCODE02),
CONCAT(' ',CONCAT(TRIM(STOCKTRANSACTION.DECOSUBCODE03),
CONCAT(' ',CONCAT(TRIM(STOCKTRANSACTION.DECOSUBCODE04),
CONCAT(' ',CONCAT(TRIM(STOCKTRANSACTION.DECOSUBCODE05),
CONCAT(' ',CONCAT(TRIM(STOCKTRANSACTION.DECOSUBCODE06),
CONCAT(' ',CONCAT(TRIM(STOCKTRANSACTION.DECOSUBCODE07),
CONCAT(' ',TRIM(STOCKTRANSACTION.DECOSUBCODE08)))))))))))))))='$KD'
		";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
							while($rD=db2_fetch_assoc($stmt2)){
										
	echo"<tr>
  	<td align=center>$no</td>
	<td align=center>$rD[TRANSACTIONNUMBER]</td>
	<td align=center>$rD[ITEMELEMENTCODE]</td>
	<td align=center>$rD[DECOSUBCODE01] $rD[DECOSUBCODE02] $rD[DECOSUBCODE03] $rD[DECOSUBCODE04] $rD[DECOSUBCODE05] $rD[DECOSUBCODE06] $rD[DECOSUBCODE07] $rD[DECOSUBCODE08]</td>
	<td align=center>".round($rD['BASESECONDARYQUANTITY'])."</td>
	<td align=center>".number_format(round($rD['BASEPRIMARYQUANTITY'],2),2)."</td>	
	<td align=center>$rD[LOTCODE]</td>
	<td align=center>$rD[WHSLOCATIONWAREHOUSEZONECODE]-$rD[WAREHOUSELOCATIONCODE]</td>
	<td align=center>$rD[TRANSACTIONTIME]</td>
	</tr>";
				$no++;				
							}
								

     
  ?>
						</tbody>
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
