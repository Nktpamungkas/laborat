<?php
ini_set("error_reporting", 1);
session_start();
include("../../koneksi.php");
    $modal_id=$_GET['id'];
	$ORDERCODE= substr($modal_id,0,8);
	$LOTCODE= substr($modal_id,8,8);
	
	
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
			Prod. Order (KFF) : <b><?php echo $ORDERCODE;?></b><br>
			Prod. demand (KGF): <b><?php echo $LOTCODE;?></b>				
			</i>	
			<table id="lookup1" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 14px;">
						<thead>
							<tr>
								<th>#</th>
								<th><div align="center">ELEMENTCODE</div></th>
								<th><div align="center">KG</div></th>
								<th><div align="center">LOTCODE</div></th>															
							</tr>
							
						</thead>
						<tbody>
<?php
$no=1;
$sqlDB22 = "SELECT
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.ORDERCODE,
	STOCKTRANSACTION.PROJECTCODE,
	STOCKTRANSACTION.BASEPRIMARYQUANTITY,
	STOCKTRANSACTION.ITEMELEMENTCODE,
	STOCKTRANSACTION.CREATIONUSER,
	STOCKTRANSACTION.LOTCODE
FROM STOCKTRANSACTION 
WHERE STOCKTRANSACTION.ITEMTYPECODE ='KGF'  AND STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
AND TEMPLATECODE ='120' AND ORDERCODE='$ORDERCODE' AND LOTCODE='$LOTCODE'
		";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
							while($rD=db2_fetch_assoc($stmt2)){
								
	echo"<tr'>
  	<td align=center>$no</td>
	<td align=center>$rD[ITEMELEMENTCODE]</td>
	<td align=right>".round($rD['BASEPRIMARYQUANTITY'],2)."</td>
	<td align=center>$rD[LOTCODE]</td>
	</tr>";
				$no++;	
				$tKG+=round($rD['BASEPRIMARYQUANTITY'],2);				
							}
								

     
  ?>
						</tbody>
			<tfoot>
			<tr>
			  <td>&nbsp;</td>
							  <td align="right"><strong>Total</strong></td>
							  <td align="right"><?php echo $tKG; ?></td>
							  <td>&nbsp;</td>
			  </tr>	
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
