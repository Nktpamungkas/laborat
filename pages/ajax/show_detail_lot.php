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
              <h5 class="modal-title">Detail Data <strong>LOT Benang</strong></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"><i>
			Prod. Order (KGF) : <b><?php echo $modal_id;?></b>			
			</i>	
			<table id="lookup1" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 14px;">
						<thead>
							<tr>
								<th>#</th>
								<th><div align="center">LOTCODE</div></th>
								<th><div align="center">KODE</div></th>
								<th><div align="center">JENIS BENANG</div></th>															
							</tr>
							
						</thead>
						<tbody>
<?php
$no=1;
$sqlDB22 = "SELECT 
s.LOTCODE,
s.ITEMTYPECODE,s.DECOSUBCODE01,s.DECOSUBCODE02,
s.DECOSUBCODE03,s.DECOSUBCODE04,s.DECOSUBCODE05,
s.DECOSUBCODE06,s.DECOSUBCODE07,s.DECOSUBCODE08,
f.SUMMARIZEDDESCRIPTION 
 FROM STOCKTRANSACTION s 
 LEFT OUTER JOIN FULLITEMKEYDECODER f ON f.IDENTIFIER =s.FULLITEMIDENTIFIER 
WHERE ORDERCODE ='$modal_id'
GROUP BY s.LOTCODE,s.ITEMTYPECODE,s.DECOSUBCODE01,
s.DECOSUBCODE02,s.DECOSUBCODE03,s.DECOSUBCODE04,
s.DECOSUBCODE05,s.DECOSUBCODE06,s.DECOSUBCODE07,
s.DECOSUBCODE08,f.SUMMARIZEDDESCRIPTION
		";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
							while($rD=db2_fetch_assoc($stmt2)){
	$kode=trim($rD['DECOSUBCODE01'])."-".trim($rD['DECOSUBCODE02'])."-".trim($rD['DECOSUBCODE03'])."-".trim($rD['DECOSUBCODE04'])."-".trim($rD['DECOSUBCODE05'])."-".trim($rD['DECOSUBCODE06'])."-".trim($rD['DECOSUBCODE07'])."-".trim($rD['DECOSUBCODE08']);							
								
	echo"<tr'>
  	<td align=center>$no</td>
	<td align=center>$rD[LOTCODE]</td>
	<td align=left>$kode</td>
	<td align=left>$rD[SUMMARIZEDDESCRIPTION]</td>
	</tr>";
				$no++;	
				$tKG+=round($rD['BASEPRIMARYQUANTITY'],2);				
							}
								

     
  ?>
						</tbody>
			<tfoot>
			<tr>
			  <td>&nbsp;</td>
							  <td align="right">&nbsp;</td>
							  <td align="right">&nbsp;</td>
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
