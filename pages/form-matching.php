<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Form Matching</title>
</head>

<body>
	<?php
		ini_set("error_reporting", 1);
		session_start();
		include "koneksi.php";
		function nourut($str)
		{
			include "koneksi.php";
			// $res = preg_replace("/[^0-9]/", "", $str);
			// $fourStr = substr($res, 0, 4);
			// $fourInt = intval(substr($res, 4, 4) + 1);
			// $date = date('ym');

			// if ($fourStr != $date) {
			// 	$FChar = $date . "0001";
			// } else {
			// 	$FE = "";
			// 	if (strlen(strval($fourInt)) <= 3) {
			// 		for ($i = strlen(strval($fourInt)); $i <= 3; $i++) {
			// 			$FE = "0" . $fourInt;
			// 		}
			// 		$FChar = $date . $FE;
			// 	} else {
			// 		$FChar = $date . $fourInt;
			// 	}
			// }

			// $fourInt = $str + 1;
			return $str;
		}

		// $sqlNoResep = mysqli_query($con,"SELECT no_resep FROM tbl_matching where id = (select max(id) from tbl_matching) LIMIT 1");
		// $noResep = mysqli_fetch_array($sqlNoResep);
		// $nourut = nourut($noResep['no_resep']);

		$sqlNoResep = mysqli_query($con, "SELECT nourut FROM no_urut_matching");
		$noResep = mysqli_fetch_array($sqlNoResep);
		$nourut = nourut($noResep['nourut']) + 1;

		if ($_GET['idk'] != "") {
			$order 		= $_GET['idk'];
			$jns_match	= $_GET['jn_mcng'];
			// QUERY untuk Standart Cocok Warna Dan Lap Dip
			$stdcckwarna_lapdip = "CASE
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 1 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END			
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 2 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 3 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 4 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 5 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 6 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 7 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END	
									END AS LABDIPNO,
									CASE
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 1 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'Labdip - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'Labdip - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50) 
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 2 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'First Lot - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'First Lot - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50) 
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 3 THEN   
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'Original - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'Original - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50)
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 4 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'Previous Order - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'Previous Order - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50) 
											END
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 5 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'Master Color - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'Master Color - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50)
											END
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 6 THEN  
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'Lampiran Buyer - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'Lampiran Buyer - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50) 
											END
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 7 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'Body - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'Body - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50)
											END
									END AS STDCCKWARNA,";

			if ($jns_match == "Matching Ulang NOW" or $jns_match == "Perbaikan NOW") {
				$query_langganan = db2_exec($conn1, "SELECT TRIM(s.CODE) AS PROJECTCODE, TRIM(ip.LANGGANAN) AS LANGGANAN, TRIM(ip.BUYER) AS BUYER
															FROM SALESORDER s 
															LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s.CODE 
															WHERE s.CODE LIKE '%$order%'");
				$dt_langganan = db2_fetch_assoc($query_langganan);
			} else if ($jns_match == "LD NOW") {
				$query_langganan = db2_exec($conn1, "SELECT TRIM(s.CODE) AS PROJECTCODE, TRIM(ip.LANGGANAN) AS LANGGANAN, TRIM(ip.BUYER) AS BUYER
															FROM SALESORDER s 
															LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s.CODE 
															WHERE s.CODE LIKE '%$order%'");
				$dt_langganan = db2_fetch_assoc($query_langganan);
			} else if ($jns_match == "Matching Development") {
				$demand = $_GET['demand'];
				if($demand){
					$where_demand = "AND TRIM(NO_DEMAND) = '$demand'";
				}else{
					$where_demand = "";
				}
				$resultKKTas = db2_exec($conn1, "SELECT * FROM ITXVIEW_KK_TAS WHERE PROJECTCODE LIKE '%$order%' $where_demand");
				$dt_kk_tas = db2_fetch_assoc($resultKKTas);
			}else {
				// $sqlLot = sqlsrv_query($conn, "SELECT
				// 										x.*,dbo.fn_StockMovementDetails_GetTotalWeightPCC(0, x.PCBID) as Weight,
				// 										dbo.fn_StockMovementDetails_GetTotalRollPCC(0, x.PCBID) as RollCount
				// 									FROM( SELECT
				// 										so.CustomerID, so.BuyerID,
				// 										sod.ID as SODID, sod.ProductID, sod.UnitID, sod.WeightUnitID,
				// 										pcb.ID as PCBID,pcb.UnitID as BatchUnitID,
				// 										pcblp.DepartmentID,pcb.PCID,pcb.LotNo,pcb.ChildLevel,pcb.RootID
				// 									FROM
				// 										SalesOrders so INNER JOIN
				// 										JobOrders jo ON jo.SOID=so.ID INNER JOIN
				// 										SODetails sod ON so.ID = sod.SOID INNER JOIN
				// 										SODetailsAdditional soda ON sod.ID = soda.SODID LEFT JOIN
				// 										ProcessControlJO pcjo ON sod.ID = pcjo.SODID LEFT JOIN
				// 										ProcessControlBatches pcb ON pcjo.PCID = pcb.PCID LEFT JOIN
				// 										ProcessControlBatchesLastPosition pcblp ON pcb.ID = pcblp.PCBID LEFT JOIN
				// 										ProcessFlowProcessNo pfpn ON pfpn.EntryType = 2 and pcb.ID = pfpn.ParentID AND pfpn.MachineType = 24 LEFT JOIN
				// 										ProcessFlowDetailsNote pfdn ON pfpn.EntryType = pfdn.EntryType AND pfpn.ID = pfdn.ParentID
				// 									WHERE jo.DocumentNo='" . $_GET['idk'] . "' AND pcb.Gross<>'0'
				// 										GROUP BY
				// 											so.SONumber, so.SODate, so.CustomerID, so.BuyerID, so.PONumber, so.PODate,jo.DocumentNo,
				// 											sod.ID, sod.ProductID, sod.Quantity, sod.UnitID, sod.Weight, sod.WeightUnitID,
				// 											soda.RefNo,pcb.DocumentNo,pcb.Dated,sod.RequiredDate,
				// 											pcb.ID, pcb.DocumentNo, pcb.Gross,
				// 											pcb.Quantity, pcb.UnitID, pcb.ScheduledDate, pcb.ProductionScheduledDate,
				// 											pcblp.DepartmentID,pcb.LotNo,pcb.PCID,pcb.ChildLevel,pcb.RootID
				// 										) x INNER JOIN
				// 										ProductMaster pm ON x.ProductID = pm.ID LEFT JOIN
				// 										Departments dep ON x.DepartmentID  = dep.ID LEFT JOIN
				// 										Departments pdep ON dep.RootID = pdep.ID LEFT JOIN
				// 										Partners cust ON x.CustomerID = cust.ID LEFT JOIN
				// 										Partners buy ON x.BuyerID = buy.ID LEFT JOIN
				// 										UnitDescription udq ON x.UnitID = udq.ID LEFT JOIN
				// 										UnitDescription udw ON x.WeightUnitID = udw.ID LEFT JOIN
				// 										UnitDescription udb ON x.BatchUnitID = udb.ID
				// 									ORDER BY
				// 										x.SODID, x.PCBID");
				// $sLot = sqlsrv_fetch_array($sqlLot);

				// $cLot = sqlsrv_num_rows($sqlLot);
				// $child = $sLot['ChildLevel'];

				// if ($child > 0) {
				// 	$sqlgetparent = sqlsrv_query($conn, "select ID,LotNo from ProcessControlBatches where ID='$sLot[RootID]' and ChildLevel='0'");
				// 	$rowgp = sqlsrv_fetch_array($sqlgetparent);

				// 	$nomLot = $rowgp['LotNo'];
				// 	$nomorLot = "$nomLot/K$sLot[ChildLevel]&nbsp;";
				// } else {
				// 	$nomorLot = $sLot['LotNo'];
				// }

				// $sqlLot1 = "Select count(*) as TotalLot From ProcessControlBatches where PCID='$sLot[PCID]' and RootID='0' and LotNo < '1000'";
				// $qryLot1 = sqlsrv_query($conn, $sqlLot1) or die('A error occured : ');
				// $rowLot = sqlsrv_fetch_array($qryLot1);

				// $sqls = sqlsrv_query($conn, "select salesorders.customerid,salesorders.buyerid from Joborders
				// 		left join salesorders on soid= salesorders.id
				// 		where JobOrders.documentno='$_GET[idk]'", array(), array("Scrollable" => 'static'));
				// $ssr = sqlsrv_fetch_array($sqls);
				// $cek = sqlsrv_num_rows($sqls);
				// $lgn1 = sqlsrv_query($conn, "select partnername from partners where id='$ssr[customerid]'");
				// $ssr1 = sqlsrv_fetch_array($lgn1);
				// $lgn2 = sqlsrv_query($conn, "select partnername from partners where id='$ssr[buyerid]'");
				// $ssr2 = sqlsrv_fetch_array($lgn2);
			}
		}
	?>
	<?php
		// $sqljkd = sqlsrv_query($conn, "SELECT processcontrol.id AS pcid,
		// 									processcontrolJO.SODID,
		// 									salesorders.ponumber,
		// 									joborders.documentno,
		// 									processcontrol.productid,
		// 									salesorders.customerid,
		// 									CONVERT ( VARCHAR ( 10 ), SODetails.RequiredDate, 121 ) AS RequiredDate,
		// 									salesorders.buyerid,
		// 									processcontrolbatches.lotno,
		// 									productcode,
		// 									productmaster.color,
		// 									colorno,
		// 									description,
		// 									productmaster.weight,
		// 									cuttablewidth,
		// 									SOSampleColor.OtherDesc,
		// 									SOSampleColor.Flag,
		// 									hangerno 
		// 								FROM
		// 									Joborders
		// 								LEFT JOIN salesorders ON soid = salesorders.id
		// 								LEFT JOIN SOSampleColor ON SOSampleColor.SOID= SalesOrders.id
		// 								LEFT JOIN SODetails ON SalesOrders.id= SODetails.SOID
		// 								LEFT JOIN productmaster ON productmaster.id= SODetails.productid
		// 								LEFT JOIN productpartner ON productpartner.productid= SODetails.productid
		// 								LEFT JOIN processcontrolJO ON processcontrolJO.joid = Joborders.id
		// 								LEFT JOIN processcontrol ON processcontrolJO.pcid = processcontrol.id
		// 								LEFT JOIN processcontrolbatches ON processcontrolbatches.pcid = processcontrol.id 
		// 								WHERE
		// 									productmaster.id= '$_GET[iditem]' 
		// 									AND processcontrol.productid= '$_GET[iditem]' 
		// 									AND JobOrders.documentno= '$_GET[idk]'", array(), array("Scrollable" => 'static'));
		// $r1 = sqlsrv_fetch_array($sqljkd);
		// $cek1 = sqlsrv_num_rows($sqljkd);
	?>

	<?php
		if (isset($_POST['simpan'])) {
			$ip_num = $_SERVER['REMOTE_ADDR'];
			$kain = str_replace("'", "''", $_POST['kain']);
			$benang = str_replace("'", "''", $_POST['benang']);
			$cocok_warna = str_replace("'", "''", $_POST['cocok_warna']);
			$warna = str_replace("'", "''", $_POST['warna']);
			$nowarna = str_replace("'", "", $_POST['no_warna']);
			$langganan = str_replace("'", "''", $_POST['langganan']);
			if ($_POST['salesman_sample'] == "1") {
				$salesman = "1";
			} else {
				$salesman = "0";
			}
			$char = preg_replace('/[^a-z]/i', '', $_POST['no_resep']);

			// get data no urut terakhir
			$queryGetNoUrut = mysqli_query($con, "SELECT nourut FROM no_urut_matching");
			$fetchGetNoUrut = mysqli_fetch_array($queryGetNoUrut);
			$dataNoUrut 	= $fetchGetNoUrut['nourut'] + 1;
			$no_resep 		= $_POST['Dyestuff'] . $dataNoUrut;

			$recipe 		= str_replace("'", "''", $_POST['recipe_code']);
			$colorcode 		= str_replace("'", "''", $_POST['color_code']);
			$gLD 			= str_replace("'", "''", $_POST['g_LD']);
			$jnsMtch 		= $_POST['jen_matching'];
			$tempCode 		= $_POST['temp_code'];
			$tempCode2 		= $_POST['temp_code2'];

			echo $suhuchamber 	= $_POST['suhu_chamber'] !== '' ? $_POST['suhu_chamber'] : ($_POST['none_suhu_chamber'] !== '' ? $_POST['none_suhu_chamber'] : null);
			$warnafluorescent	= $_POST['warna_fluorescent'];

			$qry = mysqli_query($con, "INSERT INTO tbl_matching SET
					no_resep='$no_resep',
					no_order='$_POST[no_order]',
					no_po='$_POST[no_po]',
					langganan='$langganan',
					no_item='$_POST[no_item1]',
					jenis_kain='$kain',
					benang='$benang',
					tgl_in=now(),
					cocok_warna='$cocok_warna',
					warna='$warna',
					no_warna='$nowarna',
					lebar='$_POST[lebar]',
					qty_order='$_POST[qty]',
					gramasi='$_POST[gramasi]',
					proses='$_POST[proses]',
					buyer='$_POST[buyer]',
					tgl_delivery='$_POST[tgl_delivery]',
					jenis_matching='$jnsMtch',
					temp_code='$tempCode',
					temp_code2='$tempCode2',
					recipe_code='$recipe',
					color_code='$colorcode',
					g_ld='$gLD',
					tgl_buat= now(),
					tgl_update=now(),
					salesman_sample='" . $salesman . "',
					created_by = '$_SESSION[userLAB]',
					suhu_chamber = '$suhuchamber',
					warna_flourescent = '$warnafluorescent'
					");
 
			// update nomor urut terakhir
			mysqli_query($con, "UPDATE no_urut_matching SET nourut = '$nourut'");

			if ($qry) {
				mysqli_query($con, "INSERT INTO log_status_matching SET
						`ids` = '$no_resep',
						`status` = 'Create No.resep',
						`info` = 'generate no resep',
						`do_by` = '$_SESSION[userLAB]',
						`do_at` = '$time',
						`ip_address` = '$ip_num'");
				echo "<script>alert('Data Tersimpan');window.location.href='?p=form-matching-detail&noresep=$no_resep';</script>";
			} else {
				echo "There's been a problem: " . mysqli_error();
			}
		}
	?>
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_1" data-toggle="tab">Input Order <?php $_GET['Dystf'] ?></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
							<div class="box-body">
								<div class="form-group">
									<label for="order" class="col-sm-2 control-label">Dyestuff</label>
									<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#staticBackdrop">
										...
									</button>
									<div class="col-sm-2">
										<select value="<?php echo $_GET['Dystf'] ?>" type="text" class="form-control" id="Dyestuff" name="Dyestuff" required>
											<option value="" selected disabled>Pilih...</option>
											<?php
											$sqlmstrcd = mysqli_query($con, "SELECT kode, `value` from tbl_mstrheadercd;");
											while ($li = mysqli_fetch_array($sqlmstrcd)) { ?>
												<option value="<?php echo $li['value'] ?>" <?php if ($li['value'] == $_GET['Dystf']) {
																								echo 'selected';
																							} ?>><?php echo $li['kode'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<input type="hidden" value="<?php echo $nourut; ?>" id="shadow_no_resep" name="shadow_no_resep">
								<div class=" form-group">
									<label for="order" class="col-sm-2 control-label">Rcode</label>
									<div class="col-sm-2">
										<input name="no_resep" type="text" class="form-control" id="no_resep" placeholder="No Resep" required readonly>
									</div>
								</div>
								<div class=" form-group">
									<label for="order" class="col-sm-2 control-label">J. Matching</label>
									<div class="col-sm-2">
										<select class="form-control" id="jen_matching" name="jen_matching" required>
											<option selected disabled>Pilih...</option>
											<option <?php if ($_GET['jn_mcng'] == "L/D") {
														echo "selected";
													} ?> value="L/D">L/D</option>
											<option <?php if ($_GET['jn_mcng'] == "LD NOW") {
														echo "selected";
													} ?> value="LD NOW">L/D NOW</option>
											<option <?php if ($_GET['jn_mcng'] == "Matching Ulang") {
														echo "selected";
													} ?> value="Matching Ulang">Matching Ulang</option>
											<option <?php if ($_GET['jn_mcng'] == "Perbaikan") {
														echo "selected";
													} ?> value="Perbaikan">Perbaikan</option>
											<option <?php if ($_GET['jn_mcng'] == "Matching Ulang NOW") {
														echo "selected";
													} ?> value="Matching Ulang NOW">Matching Ulang NOW</option>
											<option <?php if ($_GET['jn_mcng'] == "Perbaikan NOW") {
														echo "selected";
													} ?> value="Perbaikan NOW">Perbaikan NOW</option>
											<option <?php if ($_GET['jn_mcng'] == "Matching Development") {
														echo "selected";
													} ?> value="Matching Development">Matching Development</option>
										</select>
									</div>
								</div>								
								<!-- End Temp -->
								<div id="echoing_the_choice">
									<div id="before_append">
										<div class=" form-group">
											<label for="order" class="col-sm-4 control-label" style="font-style: italic;"> Pilih Jenis Matching untuk men-generate form...</label>
										</div>
									</div>
								</div>
							</div>
						</form>
						<!-- /.box-body -->
					</div>
					<!-- /.tab-pane -->

				</div>
				<!-- /.tab-content -->
			</div>
			<!-- nav-tabs-custom -->
		</div>
		<!-- /.col -->
	</div>
</body>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-center" id="staticBackdropLabel">Rincian Kode</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid bg-light">
					<table id="tablee" class="display compact nowrap" style="width:100%">
						<thead>
							<th>No.</th>
							<th>Kode</th>
							<th class="text-center">Keterangan</th>
						</thead>
						<tbody>
							<?php
							$i = 1;
							$sqlmstrcd = mysqli_query($con, "SELECT kode, keterangan from tbl_mstrheadercd;");
							while ($title = mysqli_fetch_array($sqlmstrcd)) {
								echo '<tr><td>' . $i++ . '.</td>
									<td>' . $title['kode'] . '</td>
									<td>' . $title['keterangan'] . '</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- end modal -->
<div style="display: none;" id="hidding-choice">

</div>

<!--/////////////////////////////////////////////////////////////// Matching_ulang_perbaikan -->
<div id="Matching_ulang_perbaikan" style="display: none;">
	<div class="form-group">
		<label for="order" class="col-sm-2 control-label">No Order</label>
		<div class="col-sm-4">
			<input name="no_order" placeholder="No order ..." type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control ordercuy" id="order" onchange="window.location='?p=Form-Matching&idk='+this.value+'&Dystf='+document.getElementById(`Dyestuff`).value+'&jn_mcng='+document.getElementById(`jen_matching`).value" value="<?php if ($_GET['idk'] != "") {
																																																																																							echo $_GET['idk'];
																																																																																						} ?>" placeholder="No Order" required>
		</div>
	</div>
	<div class="form-group">
		<label for="langganan" class="col-sm-2 control-label">Langganan</label>
		<div class="col-sm-8">
			<input name="langganan" type="text" class="form-control" id="langganan" placeholder="Langganan" value="<?php if ($cek > 0) {
																														echo $ssr1['partnername'] . "/" . $ssr2['partnername'];
																													} else {
																														echo $rw['langganan'];
																													} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="no_item" class="col-sm-2 control-label">Item</label>
		<div class="col-sm-10">
			<select name="no_item" class="form-control selectNoItem" id="no_item" onchange="window.location='?p=Form-Matching&idk=<?php echo $_GET['idk']; ?>&iditem='+this.value+'&Dystf='+document.getElementById(`Dyestuff`).value+'&jn_mcng='+document.getElementById(`jen_matching`).value" required style="width: 400px;">
				<?php
				// $sqljk = sqlsrv_query($conn, "select productmaster.id,productpartner.productcode,productmaster.color,colorno,hangerno from Joborders
				// 					left join salesorders on soid= salesorders.id
				// 					left join SODetails on SalesOrders.id=SODetails.SOID
				// 					left join productmaster on productmaster.id= SODetails.productid
				// 					left join productpartner on productpartner.productid= SODetails.productid
				// 					where JobOrders.documentno='$_GET[idk]'
				// 					GROUP BY productmaster.id,productpartner.productcode,productmaster.color,
				// 					productmaster.colorno,productmaster.hangerno");
				?>
				<option value="">Pilih</option>
				<?php //while ($r = sqlsrv_fetch_array($sqljk)) { ?>
					<option value="<?php// echo $r['id']; ?>" <?php //if ($_GET['iditem'] == $r['id']) { echo "SELECTED"; } ?>>
						<?php //echo $r['hangerno'] . "-" . $r['colorno'] . " | " . $r['color']; ?>
					</option>
				<?php // } ?>
			</select>
			<input name="no_item1" type="hidden" class="form-control" id="no_item1" placeholder="No Item" value="<?php if ($cek1 > 0) {
																														if ($r1['hangerno'] != "") {
																															echo $r1['hangerno'];
																														} else {
																															echo $r1['productcode'];
																														}
																													} else {
																														echo $rw['no_item'];
																													} ?>">
		</div>
	</div>
	<?php
		// $ko = sqlsrv_query($conn, "SELECT 
		// 								ko.KONo 
		// 							FROM
		// 								ProcessControlJO pcjo 
		// 							INNER JOIN ProcessControl pc ON pcjo.PCID = pc.ID 
		// 							LEFT JOIN KnittingOrders ko ON pc.CID = ko.CID AND pcjo.KONo = ko.KONo
		// 							WHERE
		// 								pcjo.PCID = '$r1[pcid]'
		// 						GROUP BY ko.KONo");
		// $r2 = sqlsrv_fetch_array($ko);
	?>
	<div class="form-group">
		<label for="color_code" class="col-sm-2 control-label">Color Code</label>
		<div class="col-sm-4">
			<input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="">
		</div>
	</div>
	<div class="form-group">
		<label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
		<div class="col-sm-4">
			<!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
			<textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="no_po" class="col-sm-2 control-label">PO Greige</label>

		<div class="col-sm-4">
			<input name="no_po" type="text" class="form-control" id="no_po" placeholder="No PO" value="<?php //echo $r2['KONo']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="kain" class="col-sm-2 control-label">Kain</label>
		<div class="col-sm-8">
			<input name="kain" type="text" class="form-control" id="kain" placeholder="Kain" value="<?php if ($cek1 > 0) {
																										echo htmlentities($r1['description'], ENT_QUOTES);
																									} else {
																										echo $rw['jenis_kain'];
																									} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Warna</label>
		<div class="col-sm-6">
			<input name="warna" type="text" class="form-control" id="warna" placeholder="Warna" value="<?php if ($cek1 > 0) {
																											echo $r1['color'];
																										} else {
																											echo $rw['warna'];
																										} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="no_warna" class="col-sm-2 control-label">LAB DIP NO</label>
		<div class="col-sm-6">
			<input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="LAB DIP NO" value="<?php if ($cek1 > 0) {
																														echo $r1['colorno'];
																													} else {
																														echo $rw['no_warna'];
																													} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="gramasi" class="col-sm-2 control-label">Gramasi</label>
		<div class="col-sm-2">
			<input name="lebar" type="text" class="form-control" id="lebar" placeholder="Inci" value="<?php if ($cek1 > 0) {
																											echo round($r1['cuttablewidth']);
																										} else {
																											echo $rw['warna'];
																										} ?>">
		</div>
		<div class="col-sm-2">
			<input name="gramasi" type="text" class="form-control" id="gramasi" placeholder="Gr/M2" value="<?php if ($cek1 > 0) {
																												echo round($r1['weight']);
																											} else {
																												echo $rw['warna'];
																											} ?>">
		</div>
	</div>
	<?php
		// $bng = sqlsrv_query($conn, "SELECT 
		// 								CAST(SODetailsAdditional.Note AS NVARCHAR(255)) AS note 
		// 							FROM 
		// 								Joborders
		// 							LEFT JOIN processcontrolJO ON processcontrolJO.joid = Joborders.id
		// 							LEFT JOIN SODetailsAdditional ON processcontrolJO.sodid = SODetailsAdditional.sodid
		// 							WHERE 
		// 								JobOrders.documentno='$_GET[idk]' AND processcontrolJO.pcid='$r1[pcid]'");
		// $r3 = sqlsrv_fetch_array($bng);
	?>
	<div class="form-group">
		<label for="benang" class="col-sm-2 control-label">Benang</label>
		<div class="col-sm-8">
			<textarea name="benang" rows="6" class="form-control" id="benang" placeholder="Benang"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="cocok_warna" class="col-sm-2 control-label">Cocok Warna</label>
		<div class="col-sm-8">
			<input name="cocok_warna" type="text" class="form-control" id="cocok_warna" placeholder="Cocok Warna" value="<?php if ($r1['Flag'] == " 1") {
																																echo "Original Color";
																															} elseif ($r1['Flag'] == "2") {
																																echo "Color LD";
																															} else {
																																echo
																																$r1['OtherDesc'];
																															} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery</label>
		<div class="col-sm-3">
			<input name="tgl_delivery" type="text" class="form-control datepicker" id="tgl_delivery" placeholder="Tgl Delivery" value="<?php echo $r1['RequiredDate']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="salesman_sample" class="col-sm-2 control-label">Salesman Sample</label>
		<div class="col-sm-8">
			<input type="checkbox" id="salesman_sample" name="salesman_sample" <?php if ($cek > 0) {
																				} else {
																					if ($rw['salesman_sample'] == "1") {
																						echo "checked";
																					}
																				}  ?> value="1">
		</div>
	</div>
	<div class="form-group">
		<label for="qty" class="col-sm-2 control-label">Qty Order</label>
		<div class="col-sm-3">
			<input name="qty" type="text" required class="form-control" id="qty" placeholder="Qty Order">
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Buyer</label>
		<div class="col-sm-3">
			<select name="buyer" id="buyer" class="form-control selectBuyer1" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Lampu Buyer :</label>
		<div class="col-sm-10" id="lampu-buyer1">
			<!-- i do some magic here  -->
		</div>
	</div>
	<div class="form-group">
		<label for="proses" class="col-sm-2 control-label">Proses</label>
		<div class="col-sm-3">
			<select class="form-control selectProses1" name="proses" id="proses" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	
	<?php
		$getDyestuff = $_GET['Dystf'] ?? null;
		$where = "1"; // default: tanpa filter

		// Filtering berdasarkan Dystf
		if ($getDyestuff) {
			if ($getDyestuff == 'DR') {
				$where = "dispensing IN (1,2,3)";
			} elseif ($getDyestuff == 'CD') {
				$where = "dispensing = 1";
			} elseif ($getDyestuff == 'OB') {
				$where = "dispensing = 3";
			} else {
				$char = strtoupper(substr($getDyestuff, 0, 1));
				switch ($char) {
					case 'D':
					case 'A':
						$where = "dispensing = 1";
						break;
					case 'R':
						$where = "dispensing = 2";
						break;
					default:
						$where = "1";
				}
			}
		}

		// Fungsi untuk generate <option>
		function generateTempOptions($con, $where) {
			$query = "SELECT * FROM master_suhu WHERE $where AND status = 1 ORDER BY suhu ASC, waktu ASC";
			$result = mysqli_query($con, $query);

			while ($row = mysqli_fetch_assoc($result)) {
				$optionText = htmlspecialchars($row['product_name']);
				$program = $row['program'];
				$dyeing = $row['dyeing'];
				$dispensing = $row['dispensing'];

				$additionalInfo = '';
				if ($program == 1) {
					$additionalInfo = 'KONSTAN';
				} elseif ($program == 2) {
					$additionalInfo = 'RAISING';
				} else {
					$additionalInfo = '-';
				}

				if ($dyeing == 1) {
					$additionalInfo .= ' - POLY';
				} elseif ($dyeing == 2) {
					$additionalInfo .= ' - COTTON';
				}

				if ($dispensing == 1) {
					$additionalInfo .= ' - POLY';
				} elseif ($dispensing == 2) {
					$additionalInfo .= ' - COTTON';
				} elseif ($dispensing == 3) {
					$additionalInfo .= ' - WHITE';
				}

				echo '<option value="' . htmlspecialchars($row['code']) . '">' . $optionText . ' (' . $additionalInfo . ')</option>';
			}
		}
	?>

	<!-- Temp 1 -->
	<div class="form-group">
		<label for="temp_code" class="col-sm-2 control-label">Temp</label>
		<div class="col-sm-2">
			<select name="temp_code" id="temp_code" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi akan diisi otomatis oleh JS -->
			</select>
		</div>
	</div>

	<!-- Temp 2 (hanya tampil jika Dyestuff == DR) -->
	<div class="form-group" id="temp2-wrapper" style="display: none;">
		<label for="temp_code2" class="col-sm-2 control-label">Temp 2</label>
		<div class="col-sm-2">
			<select name="temp_code2" id="temp_code2" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi bisa diisi sama seperti temp_code jika perlu -->
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label">Suhu Chamber</label>
		<div class="col-sm-2">
			<!-- Checkbox hanya untuk menampilkan input -->
			<input type="checkbox" id="suhu_chamber" onchange="toggleInputSuhu()">
			<label class="form-check-label" for="suhu_chamber">Stempel Aktif</label>

			<!-- Input suhu yang akan dikirim -->
			<input type="number" id="input_suhu" name="suhu_chamber" class="form-control mt-1"
				placeholder="Isi suhu" style="display: none;" min="0">
		</div>
		<div class="col-sm-3">
			<!-- Checkbox None -->
			<label style="color: red;">
				<input type="checkbox" id="none_suhu_chamber" name="none_suhu_chamber" value="none" onchange="toggleNoneSuhu()" <?php if ($_GET['Dystf'] === 'R') echo 'checked'; ?>> ❌ None - Suhu Chamber
			</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Warna Flourescent</label>
		<div class="col-sm-2">
			<input type="checkbox" name="warna_fluorescent" id="warna_fluorescent" value="1">
			<label class="form-check-label" for="warna_fluorescent">Stempel Aktif</label>
		</div>
	</div>

	<script>
		function toggleInputSuhu() {
			const suhuCheckbox = document.getElementById('suhu_chamber');
			const inputSuhu = document.getElementById('input_suhu');
			const noneCheckbox = document.getElementById('none_suhu_chamber');

			if (suhuCheckbox.checked) {
				inputSuhu.style.display = 'block';
				inputSuhu.disabled = false;
				noneCheckbox.checked = false;
			} else {
				inputSuhu.style.display = 'none';
				inputSuhu.value = '';
			}
		}

		function toggleNoneSuhu() {
			const noneCheckbox = document.getElementById('none_suhu_chamber');
			const suhuCheckbox = document.getElementById('suhu_chamber');
			const inputSuhu = document.getElementById('input_suhu');

			if (noneCheckbox.checked) {
				suhuCheckbox.checked = false;
				inputSuhu.style.display = 'none';
				inputSuhu.value = 'none';
			}
		}
	</script>
	
	<div class="box-footer">
		<div class="col-sm-2">
			<button type="submit" class="btn btn-block btn-social btn-linkedin" name="simpan" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
		</div>
	</div>
	
</div>
<!-- ////////////////////////////////////////////////////////////////////// LD -->
<div id="LD" style="display: none;">
	<div class="form-group">
		<label for="order" class="col-sm-2 control-label">L/D Req No.</label>
		<div class="col-sm-4">
			<input name="no_order" type="text" class="form-control" id="order" required placeholder="Request Number">
		</div>
	</div>
	<div class="form-group">
		<label for="langganan" class="col-sm-2 control-label">Langganan</label>
		<div class="col-sm-6">
			<input name="langganan" type="text" class="form-control" id="langganan" placeholder="Langganan" required>
		</div>
	</div>
	<!-- hidden item -->
	<!-- <input type="hidden" name="no_item1" id="no_item1" class="form-control" value="-"> -->
	<input name="no_po" type="hidden" class="form-control" id="no_po" placeholder="No PO" value="-">
	<!-- <input name="kain" type="hidden" class="form-control" id="kain" placeholder="Kain" value="-"> -->
	<!--/ hidden kain -->
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">No. Item</label>
		<div class="col-sm-6">
			<input name="no_item1" type="text" class="form-control" id="no_item1" placeholder="No item" required>
		</div>
	</div>
	<div class="form-group">
		<label for="color_code" class="col-sm-2 control-label">Color Code</label>
		<div class="col-sm-4">
			<input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="">
		</div>
	</div>
	<div class="form-group">
		<label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
		<div class="col-sm-4">
			<!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
			<textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Jenis Kain</label>
		<div class="col-sm-6">
			<input name="kain" type="text" class="form-control" id="kain" placeholder="Jenis Kain" required>
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Warna</label>
		<div class="col-sm-6">
			<input name="warna" type="text" class="form-control" id="warna" placeholder="Warna" required>
		</div>
	</div>
	<div class="form-group">
		<label for="no_warna" class="col-sm-2 control-label">LAB DIP NO</label>
		<div class="col-sm-6">
			<input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="LAB DIP NO" required>
		</div>
	</div>
	<!-- HIDDEN INPUT -->
	<input name="lebar" type="hidden" value="-" class="form-control" id="lebar" placeholder="Inci">
	<input name="gramasi" type="hidden" value="-" class="form-control" id="gramasi" placeholder="Gr/M2">
	<input name="benang" value="-" class="form-control" id="benang" type="hidden" placeholder="Benang">
	<!-- HIDDEN INPUT -->
	<div class="form-group">
		<label for="cocok_warna" class="col-sm-2 control-label">Cocok Warna</label>
		<div class="col-sm-6">
			<input name="cocok_warna" type="text" class="form-control" id="cocok_warna" placeholder="Cocok Warna" required>
		</div>
	</div>
	<div class="form-group">
		<label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery</label>
		<div class="col-sm-3">
			<input name="tgl_delivery" type="text" class="form-control datepicker" id="tgl_delivery" placeholder="Tgl Delivery" required>
		</div>
	</div>
	<!-- HIDDEN INPUT -->
	<input name="qty" type="hidden" value="0" class="form-control" id="qty" placeholder="Qty Order">
	<!-- HIDDEN INPUT -->

	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Buyer</label>
		<div class="col-sm-3">
			<select name="buyer" id="buyer" class="form-control selectBuyer2" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Lampu Buyer :</label>
		<div class="col-sm-10" id="lampu-buyer2">
			<!-- i do some magic here  -->
		</div>
	</div>
	<div class="form-group">
		<label for="proses" class="col-sm-2 control-label">Proses</label>
		<div class="col-sm-3">
			<select class="form-control selectProses2" name="proses" id="proses" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	
	<!-- Temp 1 -->
	<div class="form-group">
		<label for="temp_code" class="col-sm-2 control-label">Temp</label>
		<div class="col-sm-2">
			<select name="temp_code" id="temp_code" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi akan diisi otomatis oleh JS -->
			</select>
		</div>
	</div>

	<!-- Temp 2 (hanya tampil jika Dyestuff == DR) -->
	<div class="form-group" id="temp2-wrapper" style="display: none;">
		<label for="temp_code2" class="col-sm-2 control-label">Temp 2</label>
		<div class="col-sm-2">
			<select name="temp_code2" id="temp_code2" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi bisa diisi sama seperti temp_code jika perlu -->
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label">Suhu Chamber</label>
		<div class="col-sm-2">
			<!-- Checkbox hanya untuk menampilkan input -->
			<input type="checkbox" id="suhu_chamber" onchange="toggleInputSuhu()">
			<label class="form-check-label" for="suhu_chamber">Stempel Aktif</label>

			<!-- Input suhu yang akan dikirim -->
			<input type="number" id="input_suhu" name="suhu_chamber" class="form-control mt-1"
				placeholder="Isi suhu" style="display: none;" min="0">
		</div>
		<div class="col-sm-3">
			<!-- Checkbox None -->
			<label style="color: red;">
				<input type="checkbox" id="none_suhu_chamber" name="none_suhu_chamber" value="none" onchange="toggleNoneSuhu()" <?php if ($_GET['Dystf'] === 'R') echo 'checked'; ?>> ❌ None - Suhu Chamber
			</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Warna Flourescent</label>
		<div class="col-sm-2">
			<input type="checkbox" name="warna_fluorescent" id="warna_fluorescent" value="1">
			<label class="form-check-label" for="warna_fluorescent">Stempel Aktif</label>
		</div>
	</div>

	<script>
		function toggleInputSuhu() {
			const suhuCheckbox = document.getElementById('suhu_chamber');
			const inputSuhu = document.getElementById('input_suhu');
			const noneCheckbox = document.getElementById('none_suhu_chamber');

			if (suhuCheckbox.checked) {
				inputSuhu.style.display = 'block';
				inputSuhu.disabled = false;
				noneCheckbox.checked = false;
			} else {
				inputSuhu.style.display = 'none';
				inputSuhu.value = '';
			}
		}

		function toggleNoneSuhu() {
			const noneCheckbox = document.getElementById('none_suhu_chamber');
			const suhuCheckbox = document.getElementById('suhu_chamber');
			const inputSuhu = document.getElementById('input_suhu');

			if (noneCheckbox.checked) {
				suhuCheckbox.checked = false;
				inputSuhu.style.display = 'none';
				inputSuhu.value = 'none';
			}
		}
	</script>

	<div class="box-footer">
		<div class="col-sm-2">
			<button type="submit" class="btn btn-block btn-social btn-linkedin" name="simpan" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
		</div>
	</div>
	
</div>
<!-- ////////////////////////////////////////////////////////////////////// Development -->
<div id="Development" style="display: none;">
	<div class="form-group">
		<label for="order" class="col-sm-2 control-label">No Order</label>
		<div class="col-sm-4">
			<input name="no_order" type="text" class="form-control orderdevelopment" id="order" required placeholder="No Order..."
			onchange="window.location='?p=Form-Matching&idk='+document.getElementById(`order`).value+'&Dystf='+document.getElementById(`Dyestuff`).value+'&jn_mcng='+document.getElementById(`jen_matching`).value" value="<?= $_GET['idk']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="demand" class="col-sm-2 control-label" style="font-style: italic;">No Production Demand</label>
		<div class="col-sm-2">
			<select name="demand" id="demand" class="form-control" style="width: 100%;"
			onchange="window.location='?p=Form-Matching&idk='+document.getElementById(`order`).value+'&Dystf='+document.getElementById(`Dyestuff`).value+'&jn_mcng='+document.getElementById(`jen_matching`).value+'&demand='+document.getElementById(`demand`).value">
				<option value="" selected disabled>Pilih...</option>
				<?php if($_GET['idk']) : ?>
					<?php 
						$sql = db2_exec($conn1, "SELECT DISTINCT NO_DEMAND FROM ITXVIEW_KK_TAS WHERE PROJECTCODE LIKE '%$_GET[idk]%'");
						while ($row = db2_fetch_assoc($sql)) {
							echo '<option value="'.$row['NO_DEMAND'].'" '.($_GET['demand'] == $row['NO_DEMAND'] ? 'selected' : '').'>'.$row['NO_DEMAND'].'</option>';
						}
					?>
				<?php endif; ?>
			</select>
		</div>
		<div class="col-sm-2">
			<span style="color: red;">*Production demand bersifat referensi dan tidak tersimpan di database.</span>
		</div>
	</div>
	<div class="form-group">
		<label for="langganan" class="col-sm-2 control-label">Langganan dev</label>
		<div class="col-sm-8">
			<input name="langganan" type="text" class="form-control" id="langganan" value="<?= $dt_kk_tas['BUYER'] ?>" placeholder="Langganan">
		</div>
	</div>
	<!-- HIDDEN -->
	<input name="no_po" type="hidden" class="form-control" id="no_po" value="-">
	<!-- HIDDEN -->
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">No. Item</label>
		<div class="col-sm-6">
			<input type="text" name="no_item1" id="no_item1" class="form-control" required placeholder="No. item ..." value="<?= $dt_kk_tas['NO_HANGER'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="color_code" class="col-sm-2 control-label">Color Code</label>
		<div class="col-sm-4">
			<input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="<?= $dt_kk_tas['NO_WARNA'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
		<div class="col-sm-4">
			<!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
			<textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Jenis Kain</label>
		<div class="col-sm-6">
			<input name="kain" type="text" value="<?= $dt_kk_tas['JENIS_KAIN'] ?>" class="form-control" required id="kain" placeholder="Jenis kain...">
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Warna</label>
		<div class="col-sm-6">
			<input name="warna" type="text" class="form-control" id="warna" value="<?= $dt_kk_tas['WARNA'] ?>" placeholder="Warna">
		</div>
	</div>
	<div class="form-group">
		<label for="no_warna" class="col-sm-2 control-label">LAB DIP NO</label>
		<div class="col-sm-6">
			<input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="LAB DIP NO">
		</div>
	</div>
	<!-- HIDDEN VALUE -->
	<div class="form-group">
		<label for="gramasi" class="col-sm-2 control-label">Gramasi</label>
		<div class="col-sm-2">
			<input name="lebar" required type="text" class="form-control" id="lebar" placeholder="Inci" value="<?= $dt_kk_tas['LEBAR'] ?>">
		</div>
		<div class="col-sm-2">
			<input name="gramasi" required type="text" class="form-control" id="gramasi" placeholder="Gr/M2" value="<?= $dt_kk_tas['GRAMASI'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="benang" class="col-sm-2 control-label">Benang</label>
		<div class="col-sm-8">
			<textarea name="benang" rows="6" class="form-control" id="benang" required placeholder="Benang"><?= $dt_kk_tas['JENIS_BENANG'] ?></textarea>
		</div>
	</div>
	<!-- HIDDEN VALUE -->
	<div class="form-group">
		<label for="cocok_warna" class="col-sm-2 control-label">Cocok Warna</label>
		<div class="col-sm-8">
			<input name="cocok_warna" type="text" class="form-control" id="cocok_warna" placeholder="Cocok Warna" value="<?= $dt_kk_tas['STDCCKWARNA'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery</label>
		<div class="col-sm-3">
			<input name="tgl_delivery" type="text" class="form-control datepicker" id="tgl_delivery" placeholder="Tgl Delivery" value="<?= $dt_kk_tas['TGL_KIRIM'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="salesman_sample" class="col-sm-2 control-label">Salesman Sample</label>
		<div class="col-sm-8">
			<input type="checkbox" id="salesman_sample" name="salesman_sample" <?php if ($cek > 0) {
																				} else {
																					if ($rw['salesman_sample'] == "1") {
																						echo "checked";
																					}
																				}  ?> value="1">
		</div>
	</div>
	<div class="form-group">
		<label for="qty" class="col-sm-2 control-label">Qty Order</label>
		<div class="col-sm-3">
			<input name="qty" type="text" required class="form-control" id="qty" placeholder="Qty Order" value="<?= $dt_kk_tas['QTY'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Buyer</label>
		<div class="col-sm-3">
			<select name="buyer" id="buyer" class="form-control selectBuyer3" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Lampu Buyer :</label>
		<div class="col-sm-10" id="lampu-buyer3">
			<!-- i do some magic here  -->
		</div>
	</div>
	<div class="form-group">
		<label for="proses" class="col-sm-2 control-label">Proses</label>
		<div class="col-sm-3">
			<select class="form-control selectProses3" required name="proses" id="proses" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	
	<!-- Temp 1 -->
	<div class="form-group">
		<label for="temp_code" class="col-sm-2 control-label">Temp</label>
		<div class="col-sm-2">
			<select name="temp_code" id="temp_code" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi akan diisi otomatis oleh JS -->
			</select>
		</div>
	</div>

	<!-- Temp 2 (hanya tampil jika Dyestuff == DR) -->
	<div class="form-group" id="temp2-wrapper" style="display: none;">
		<label for="temp_code2" class="col-sm-2 control-label">Temp 2</label>
		<div class="col-sm-2">
			<select name="temp_code2" id="temp_code2" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi bisa diisi sama seperti temp_code jika perlu -->
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label">Suhu Chamber</label>
		<div class="col-sm-2">
			<!-- Checkbox hanya untuk menampilkan input -->
			<input type="checkbox" id="suhu_chamber" onchange="toggleInputSuhu()">
			<label class="form-check-label" for="suhu_chamber">Stempel Aktif</label>

			<!-- Input suhu yang akan dikirim -->
			<input type="number" id="input_suhu" name="suhu_chamber" class="form-control mt-1"
				placeholder="Isi suhu" style="display: none;" min="0">
		</div>
		<div class="col-sm-3">
			<!-- Checkbox None -->
			<label style="color: red;">
				<input type="checkbox" id="none_suhu_chamber" name="none_suhu_chamber" value="none" onchange="toggleNoneSuhu()" <?php if ($_GET['Dystf'] === 'R') echo 'checked'; ?>> ❌ None - Suhu Chamber
			</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Warna Flourescent</label>
		<div class="col-sm-2">
			<input type="checkbox" name="warna_fluorescent" id="warna_fluorescent" value="1">
			<label class="form-check-label" for="warna_fluorescent">Stempel Aktif</label>
		</div>
	</div>

	<script>
		function toggleInputSuhu() {
			const suhuCheckbox = document.getElementById('suhu_chamber');
			const inputSuhu = document.getElementById('input_suhu');
			const noneCheckbox = document.getElementById('none_suhu_chamber');

			if (suhuCheckbox.checked) {
				inputSuhu.style.display = 'block';
				inputSuhu.disabled = false;
				noneCheckbox.checked = false;
			} else {
				inputSuhu.style.display = 'none';
				inputSuhu.value = '';
			}
		}

		function toggleNoneSuhu() {
			const noneCheckbox = document.getElementById('none_suhu_chamber');
			const suhuCheckbox = document.getElementById('suhu_chamber');
			const inputSuhu = document.getElementById('input_suhu');

			if (noneCheckbox.checked) {
				suhuCheckbox.checked = false;
				inputSuhu.style.display = 'none';
				inputSuhu.value = 'none';
			}
		}
	</script>

	<div class="box-footer">
		<div class="col-sm-2">
			<button type="submit" class="btn btn-block btn-social btn-linkedin" name="simpan" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
		</div>
	</div>
	
</div>
<!-- ////////////////////////////////////////////////////////////////////// LD NOW -->
<div id="LDNOW" style="display: none;">
	<div class="form-group">
		<label for="order" class="col-sm-2 control-label">L/D NOW Req No.</label>
		<div class="col-sm-4">
			<input name="no_order" placeholder="Request Number ..." onkeyup="this.value = this.value.toUpperCase();" type="text" class="form-control ordernowcuyld" id="order" onchange="window.location='?p=Form-Matching&idk='+this.value+'&Dystf='+document.getElementById(`Dyestuff`).value+'&jn_mcng='+document.getElementById(`jen_matching`).value" value="<?php if ($_GET['idk'] != "") {
																																																																																										echo $_GET['idk'];
																																																																																									} ?>" placeholder="No Order" required>
		</div>
	</div>
	<div class="form-group">
		<label for="langganan" class="col-sm-2 control-label">Langganan</label>
		<div class="col-sm-6">
			<input name="langganan" type="text" class="form-control" id="langganan" value="<?= $dt_langganan['LANGGANAN'] . '/' . $dt_langganan['BUYER']; ?>" placeholder="Langganan">
		</div>
	</div>
	<!-- hidden item -->
	<!-- <input type="hidden" name="no_item1" id="no_item1" class="form-control" value="-"> -->
	<input name="no_po" type="hidden" class="form-control" id="no_po" placeholder="No PO" value="-">
	<!-- <input name="kain" type="hidden" class="form-control" id="kain" placeholder="Kain" value="-"> -->
	<!--/ hidden kain -->
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">No. Item</label>
		<div class="col-sm-6">
			<select name="no_item" class="form-control selectNoItemNOW" onchange="window.location='?p=Form-Matching&idk=<?php echo $_GET['idk']; ?>&iditem='+this.value+'&Dystf='+document.getElementById(`Dyestuff`).value+'&jn_mcng='+document.getElementById(`jen_matching`).value" id="no_item" required style="width: 400px;">
				<?php
				$order = $dt_langganan['PROJECTCODE'];
				$sqljk = db2_exec($conn1, "SELECT 
												TRIM(p2.CODE) AS CODE,
												p.ORDERLINE AS DLVSALESORDERLINEORDERLINE,
												p.ITEMTYPEAFICODE AS ITEMTYPEAFICODE,
												p.ITEMDESCRIPTION AS WARNA,
												trim(p.SUBCODE01) AS SUBCODE01, trim(p.SUBCODE02) AS SUBCODE02, trim(p.SUBCODE03) AS SUBCODE03, trim(p.SUBCODE04) AS SUBCODE04, trim(p.SUBCODE05) AS SUBCODE05,
												p.ORDERLINE
											FROM SALESORDERLINE p
											LEFT JOIN PRODUCTIONDEMAND p2 ON p2.ORIGDLVSALORDLINESALORDERCODE = p.SALESORDERCODE AND p2.ORIGDLVSALORDERLINEORDERLINE = p.ORDERLINE
											WHERE p.SALESORDERCODE = '$order' AND NOT p.ORDERLINE IS NULL
											GROUP BY 
												p2.CODE, p.ORDERLINE,p.SUBCODE01,p.SUBCODE02,p.SUBCODE03,p.SUBCODE04,p.SUBCODE05,p.SUBCODE08,p.SUBCODE07,p.ITEMTYPEAFICODE,p.ITEMDESCRIPTION");
				?>
				<option value="">Pilih</option>
				<?php while ($r = db2_fetch_assoc($sqljk)) { ?>
					<option value="<?= $r['DLVSALESORDERLINEORDERLINE']; ?>" <?php if ($_GET['iditem'] == $r['DLVSALESORDERLINEORDERLINE']) {
																					echo "SELECTED";
																				} ?>>
						<?= $r['ITEMTYPEAFICODE'] . '-' . $r['SUBCODE02'] . '.' . $r['SUBCODE03']; ?> | <?= $r['WARNA']; ?> | <?= $r['ORDERLINE']; ?> | <?= $r['CODE']; ?>
					</option>
				<?php } ?>
			</select>

			<?php
			$order = $dt_langganan['PROJECTCODE'];
			$getorderline = $_GET['iditem'];
			$sqlitem = db2_exec($conn1, "SELECT 
												TRIM(p2.CODE) AS CODE,
												p.ORDERLINE AS DLVSALESORDERLINEORDERLINE,
												p.ITEMTYPEAFICODE AS ITEMTYPEAFICODE,
												p.ITEMDESCRIPTION AS WARNA,
												trim(p.SUBCODE01) AS SUBCODE01, trim(p.SUBCODE02) AS SUBCODE02, trim(p.SUBCODE03) AS SUBCODE03, trim(p.SUBCODE04) AS SUBCODE04, trim(p.SUBCODE05) AS SUBCODE05, trim(p.SUBCODE06) AS SUBCODE06, trim(p.SUBCODE07) AS SUBCODE07, trim(p.SUBCODE08) AS SUBCODE08,
												trim(p.SUBCODE09) AS SUBCODE09, trim(p.SUBCODE10) AS SUBCODE10
											FROM SALESORDERLINE p
											LEFT JOIN PRODUCTIONDEMAND p2 ON p2.ORIGDLVSALORDLINESALORDERCODE = p.SALESORDERCODE AND p2.ORIGDLVSALORDERLINEORDERLINE = p.ORDERLINE
											WHERE p.SALESORDERCODE = '$order' AND p.ORDERLINE = '$getorderline'
											GROUP BY 
												p2.CODE, p.ORDERLINE,p.SUBCODE01,p.SUBCODE02,p.SUBCODE03,p.SUBCODE04,p.SUBCODE05,p.SUBCODE06,p.SUBCODE07,p.SUBCODE08,p.SUBCODE09,p.SUBCODE10,p.ITEMTYPEAFICODE,p.ITEMDESCRIPTION");
			$r_item = db2_fetch_assoc($sqlitem)
			?>
			<input name="no_item1" type="hidden" class="form-control" id="no_item1" value="<?= $r_item['SUBCODE02'] . $r_item['SUBCODE03']; ?>" placeholder="No Item">
		</div>
	</div>
	<div class="form-group">
		<label for="color_code" class="col-sm-2 control-label">Color Code</label>
		<div class="col-sm-4">
			<!-- <input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="<?= $r_item['SUBCODE05']; ?>"> -->
			<input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="">
		</div>
	</div>
	<div class="form-group">
		<label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
		<div class="col-sm-4">
			<!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
			<textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="no_po" class="col-sm-2 control-label">PO Greige</label>
		<div class="col-sm-4">
			<?php
			$d_pogreigenew = db2_exec($conn1, "SELECT 
														CASE
															WHEN LOTCODE IS NOT NULL THEN LOTCODE
															ELSE '-'
														END AS LOTCODE,
														CASE
															WHEN DEMAND_KGF IS NOT NULL THEN DEMAND_KGF
															ELSE '-'
														END AS DEMAND_KGF
													FROM 
													(SELECT 
														i.LOTCODE AS LOTCODE,
														i.DEMAND_KGF AS DEMAND_KGF
													FROM 
														ITXVIEWPOGREIGENEW i 
													WHERE 
														i.SALESORDERCODE = '$_GET[idk]' AND i.ORDERLINE = '$_GET[iditem]'
													UNION ALL
													SELECT 
														i2.LOTCODE AS LOTCODE,
														i2.DEMAND_KGF AS DEMAND_KGF
													FROM 
														ITXVIEWPOGREIGENEW2 i2 
													WHERE 
														i2.SALESORDERCODE = '$_GET[idk]' AND i2.ORDERLINE = '$_GET[iditem]'
													UNION ALL
													SELECT
														i3.LOTCODE AS LOTCODE,
														i3.DEMAND_KGF AS DEMAND_KGF
													FROM
														ITXVIEWPOGREIGENEW3 i3 
													WHERE 
														i3.SALESORDERCODE = '$_GET[idk]' AND i3.ORDERLINE = '$_GET[iditem]')
													GROUP BY 
														LOTCODE, DEMAND_KGF");
			$d_pogreigenew4 = db2_exec($conn1, "SELECT INTERNALREFERENCE FROM PRODUCTIONDEMAND WHERE ORIGDLVSALORDLINESALORDERCODE = '$_GET[idk]' AND ORIGDLVSALORDERLINEORDERLINE = '$_GET[iditem]'");
			$d_pogreigenew5 = db2_exec($conn1, "SELECT 
														a.ORIGDLVSALORDLINESALORDERCODE,
														a.ORIGDLVSALORDERLINEORDERLINE,
														a.INTERNALREFERENCE,
														b.NAMENAME,
														b.VALUESTRING 
													FROM 
														PRODUCTIONDEMAND a
													LEFT JOIN ADSTORAGE b ON b.UNIQUEID = a.ABSUNIQUEID 
													WHERE 
														ORIGDLVSALORDLINESALORDERCODE = '$_GET[idk]' AND ORIGDLVSALORDERLINEORDERLINE = '$_GET[iditem]'
														AND
														(b.NAMENAME = 'ProAllow' OR b.NAMENAME = 'ProAllow2' OR b.NAMENAME = 'ProAllo3' OR b.NAMENAME = 'ProAllow4' OR b.NAMENAME = 'ProAllow5')");

			$r_pogreigenew = db2_fetch_assoc($d_pogreigenew);
			$r_pogreigenew4 = db2_fetch_assoc($d_pogreigenew4);
			$r_pogreigenew5 = db2_fetch_assoc($d_pogreigenew5);

			if ($r_pogreigenew['LOTCODE'] && $r_pogreigenew['DEMAND_KGF']) {
				$pogreige = 'NO KO : ' . $r_pogreigenew['LOTCODE'] . '/ DEMAND KGF :' . $r_pogreigenew['DEMAND_KGF'];
			}
			if ($r_pogreigenew4['INTERNALREFERENCE']) {
				$pogreige2 = $r_pogreigenew4['INTERNALREFERENCE'];
			} else {
				$pogreige2 = $r_pogreigenew5['VALUESTRING'];
			}
			?>
			<input name="no_po" type="text" class="form-control" id="no_po" placeholder="No PO" value="<?= $pogreige; ?>, PROJECT : <?= $pogreige2; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Jenis Kain</label>
		<div class="col-sm-6">
			<?php
			$itemtype = $r_item['ITEMTYPEAFICODE'];
			$s1 = $r_item['SUBCODE01'];
			$s2 = $r_item['SUBCODE02'];
			$s3 = $r_item['SUBCODE03'];
			$s4 = $r_item['SUBCODE04'];
			$s5 = $r_item['SUBCODE05'];
			$s6 = $r_item['SUBCODE06'];
			$s7 = $r_item['SUBCODE07'];
			$s8 = $r_item['SUBCODE08'];
			$s9 = $r_item['SUBCODE09'];
			$s10 = $r_item['SUBCODE10'];

			$sql_jk = db2_exec($conn1, "SELECT * FROM PRODUCT WHERE TRIM(SUBCODE01) = '$s1' 
															AND TRIM(SUBCODE02) = '$s2' 
															AND TRIM(SUBCODE03) = '$s3' 
															AND TRIM(SUBCODE04) = '$s4' 
															AND TRIM(SUBCODE05) = '$s5' 
															AND TRIM(SUBCODE06) = '$s6' 
															AND TRIM(SUBCODE07) = '$s7' 
															AND TRIM(SUBCODE08) = '$s8'
															AND TRIM(SUBCODE09) = '$s9'
															AND TRIM(SUBCODE10) = '$s10'
															AND TRIM(ITEMTYPECODE) = '$itemtype'");
			$r_jk = db2_fetch_assoc($sql_jk);
			?>
			<input name="kain" type="text" class="form-control" required id="kain" value="<?= str_replace('"', " ", $r_jk['LONGDESCRIPTION']); ?>" placeholder="Jenis kain...">
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Warna</label>
		<div class="col-sm-6">
			<input name="warna" type="text" class="form-control" id="warna" value="<?= $r_item['WARNA']; ?>" placeholder="Warna">
		</div>
	</div>
	<div class="form-group">
		<label for="no_warna" class="col-sm-2 control-label">LAB DIP NO</label>
		<div class="col-sm-6">
			<?php
				$order = $dt_langganan['PROJECTCODE'];
				$orderline = $_GET['iditem'];

				// $sql_cck = db2_exec($conn1, "SELECT
				// 									$stdcckwarna_lapdip
				// 									ITXVIEW_COLORREMARKS.VALUESTRING
				// 								FROM
				// 									SALESORDERLINE SALESORDERLINE
				// 								LEFT JOIN ITXVIEW_COLORSTANDARD ITXVIEW_COLORSTANDARD ON SALESORDERLINE.ABSUNIQUEID = ITXVIEW_COLORSTANDARD.UNIQUEID
				// 								LEFT JOIN ITXVIEW_COLORREMARKS ITXVIEW_COLORREMARKS ON ITXVIEW_COLORSTANDARD.UNIQUEID = ITXVIEW_COLORREMARKS.UNIQUEID
				// 								WHERE TRIM(SALESORDERLINE.SALESORDERCODE) = '$order' AND TRIM(SALESORDERLINE.ORDERLINE) = '$orderline'");
				$sql_cck = db2_exec($conn1, "SELECT * FROM ITXVIEW_STD_CCK_WARNA WHERE SALESORDERCODE = '$order' AND ORDERLINE = '$orderline'");
				$r_cck = db2_fetch_assoc($sql_cck);
			?>
			<!-- <input name="no_warna" type="text" class="form-control" id="no_warna" value="<?= $r_cck['LABDIPNO']; ?>" placeholder="LAB DIP NO"> -->
			<input name="no_warna" type="text" class="form-control" id="no_warna" value="" placeholder="LAB DIP NO">
		</div>
	</div>
	<!-- HIDDEN INPUT -->
	<input name="lebar" type="hidden" value="-" class="form-control" id="lebar" placeholder="Inci">
	<input name="gramasi" type="hidden" value="-" class="form-control" id="gramasi" placeholder="Gr/M2">
	<input name="benang" value="-" class="form-control" id="benang" type="hidden" placeholder="Benang">
	<!-- HIDDEN INPUT -->
	<div class="form-group">
		<label for="cocok_warna" class="col-sm-2 control-label">Cocok Warna</label>
		<div class="col-sm-6">
			<input name="cocok_warna" type="text" class="form-control" id="cocok_warna" value="<?= $r_cck['STDCCKWARNA']; ?>" placeholder="Cocok Warna">
		</div>
	</div>
	<div class="form-group">
		<label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery</label>
		<div class="col-sm-3">
			<?php
			$d_tgldelivery = db2_exec($conn1, "SELECT * FROM SALESORDERDELIVERY WHERE SALESORDERLINESALESORDERCODE = '$_GET[idk]' AND SALESORDERLINEORDERLINE = '$_GET[iditem]'");
			$r_delivery = db2_fetch_assoc($d_tgldelivery);
			?>
			<input name="tgl_delivery" type="text" value="<?php $date_deliv = date_create($r_delivery['DELIVERYDATE']);
															echo date_format($date_deliv, "Y-m-d"); ?>" class="form-control datepicker" id="tgl_delivery" placeholder="Tgl Delivery">
		</div>
	</div>
	<!-- HIDDEN INPUT -->
	<input name="qty" type="hidden" value="0" class="form-control" id="qty" placeholder="Qty Order">
	<!-- HIDDEN INPUT -->

	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Buyer</label>
		<div class="col-sm-3">
			<select name="buyer" id="buyer" class="form-control selectBuyer2" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Lampu Buyer :</label>
		<div class="col-sm-10" id="lampu-buyer2">
			<!-- i do some magic here  -->
		</div>
	</div>
	<div class="form-group">
		<label for="proses" class="col-sm-2 control-label">Proses</label>
		<div class="col-sm-3">
			<select class="form-control selectProses2" name="proses" id="proses" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	
	<!-- Temp 1 -->
	<div class="form-group">
		<label for="temp_code" class="col-sm-2 control-label">Temp</label>
		<div class="col-sm-2">
			<select name="temp_code" id="temp_code" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi akan diisi otomatis oleh JS -->
			</select>
		</div>
	</div>

	<!-- Temp 2 (hanya tampil jika Dyestuff == DR) -->
	<div class="form-group" id="temp2-wrapper" style="display: none;">
		<label for="temp_code2" class="col-sm-2 control-label">Temp 2</label>
		<div class="col-sm-2">
			<select name="temp_code2" id="temp_code2" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi bisa diisi sama seperti temp_code jika perlu -->
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label">Suhu Chamber</label>
		<div class="col-sm-2">
			<!-- Checkbox hanya untuk menampilkan input -->
			<input type="checkbox" id="suhu_chamber" onchange="toggleInputSuhu()">
			<label class="form-check-label" for="suhu_chamber">Stempel Aktif</label>

			<!-- Input suhu yang akan dikirim -->
			<input type="number" id="input_suhu" name="suhu_chamber" class="form-control mt-1"
				placeholder="Isi suhu" style="display: none;" min="0">
		</div>
		<div class="col-sm-3">
			<!-- Checkbox None -->
			<label style="color: red;">
				<input type="checkbox" id="none_suhu_chamber" name="none_suhu_chamber" value="none" onchange="toggleNoneSuhu()" <?php if ($_GET['Dystf'] === 'R') echo 'checked'; ?>> ❌ None - Suhu Chamber
			</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Warna Flourescent</label>
		<div class="col-sm-2">
			<input type="checkbox" name="warna_fluorescent" id="warna_fluorescent" value="1">
			<label class="form-check-label" for="warna_fluorescent">Stempel Aktif</label>
		</div>
	</div>

	<script>
		function toggleInputSuhu() {
			const suhuCheckbox = document.getElementById('suhu_chamber');
			const inputSuhu = document.getElementById('input_suhu');
			const noneCheckbox = document.getElementById('none_suhu_chamber');

			if (suhuCheckbox.checked) {
				inputSuhu.style.display = 'block';
				inputSuhu.disabled = false;
				noneCheckbox.checked = false;
			} else {
				inputSuhu.style.display = 'none';
				inputSuhu.value = '';
			}
		}

		function toggleNoneSuhu() {
			const noneCheckbox = document.getElementById('none_suhu_chamber');
			const suhuCheckbox = document.getElementById('suhu_chamber');
			const inputSuhu = document.getElementById('input_suhu');

			if (noneCheckbox.checked) {
				suhuCheckbox.checked = false;
				inputSuhu.style.display = 'none';
				inputSuhu.value = 'none';
			}
		}
	</script>

	<div class="box-footer">
		<div class="col-sm-2">
			<button type="submit" class="btn btn-block btn-social btn-linkedin" name="simpan" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
		</div>
	</div>
	
</div>
<!-- ////////////////////////////////////////////////////////////////////// NowForm -->
<div id="NowForm" style="display: none;">
	<div class="form-group">
		<label for="order" class="col-sm-2 control-label">No Order</label>
		<div class="col-sm-4">
			<input name="no_order" placeholder="No order ..." onkeyup="this.value = this.value.toUpperCase();" type="text" class="form-control ordernowcuy" id="order" onchange="window.location='?p=Form-Matching&idk='+this.value+'&Dystf='+document.getElementById(`Dyestuff`).value+'&jn_mcng='+document.getElementById(`jen_matching`).value" value="<?php if ($_GET['idk'] != "") {
																																																																																								echo $_GET['idk'];
																																																																																							} ?>" placeholder="No Order" required>
		</div>
	</div>
	<div class="form-group">
		<label for="langganan" class="col-sm-2 control-label">Langganan</label>
		<div class="col-sm-8">
			<input name="langganan" type="text" class="form-control" id="langganan" value="<?= $dt_langganan['LANGGANAN'] . '/' . $dt_langganan['BUYER']; ?>" placeholder="Langganan">
		</div>
	</div>
	<!-- HIDDEN -->
	<input name="no_po" type="hidden" class="form-control" id="no_po" value="-">
	<!-- HIDDEN -->
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">No. Item..</label>
		<div class="col-sm-6">
			<select name="no_item" class="form-control selectNoItemNOW" onchange="window.location='?p=Form-Matching&idk=<?php echo $_GET['idk']; ?>&iditem='+this.value+'&Dystf='+document.getElementById(`Dyestuff`).value+'&jn_mcng='+document.getElementById(`jen_matching`).value" id="no_item" required style="width: 400px;">
				<?php
					$order = $dt_langganan['PROJECTCODE'];
					$sqljk = db2_exec($conn1, "SELECT
												i.ORDERLINE AS DLVSALESORDERLINEORDERLINE,
												i.ITEMTYPEAFICODE,
												i.WARNA,
												trim(i.SUBCODE01) AS SUBCODE01,
												trim(i.SUBCODE02) AS SUBCODE02,
												trim(i.SUBCODE03) AS SUBCODE03,
												trim(i.SUBCODE04) AS SUBCODE04,
												trim(i.SUBCODE05) AS SUBCODE05, 
												SUM(i2.USERPRIMARYQUANTITY) AS BRUTO
											FROM
												ITXVIEWBONORDER i
											LEFT JOIN ITXVIEWKGBRUTOBONORDER2 i2 ON i2.ORIGDLVSALORDLINESALORDERCODE = i.SALESORDERCODE AND i2.ORIGDLVSALORDERLINEORDERLINE = i.ORDERLINE 
											WHERE
												i.SALESORDERCODE = '$order'
											GROUP BY 
												i.ORDERLINE,
												i.ITEMTYPEAFICODE,
												i.WARNA,
												i.SUBCODE01,
												i.SUBCODE02,
												i.SUBCODE03,
												i.SUBCODE04,
												i.SUBCODE05,
												i2.USERPRIMARYQUANTITY");
				?>
				<option value="">Pilih</option>
				<?php while ($r = db2_fetch_assoc($sqljk)) { ?>
					<option value="<?= $r['DLVSALESORDERLINEORDERLINE']; ?>" <?php if ($_GET['iditem'] == $r['DLVSALESORDERLINEORDERLINE']) {
																					echo "SELECTED";
																				} ?>>
						<?= $r['ITEMTYPEAFICODE'] . '-' . $r['SUBCODE02'] . '.' . $r['SUBCODE03']; ?> | <?= $r['WARNA']; ?> | <?= number_format($r['BRUTO'], 2); ?>
					</option>
				<?php } ?>
			</select>

			<?php
				$order = $dt_langganan['PROJECTCODE'];
				$getorderline = $_GET['iditem'];
				$sqlitem = db2_exec($conn1, "SELECT 
												p.DLVSALESORDERLINEORDERLINE AS DLVSALESORDERLINEORDERLINE,
												p.ITEMTYPEAFICODE AS ITEMTYPEAFICODE,
												CASE
													-- WARNA DARI PRINTING 
													WHEN trim(p.ITEMTYPEAFICODE) = 'KFF' AND NOT trim(p.SUBCODE07) = '-' AND NOT trim(p.SUBCODE08) = '-' THEN DESIGNCOMPONENT.SHORTDESCRIPTION
													-- WARNA DARI BON RESEP 
													WHEN trim(p.ITEMTYPEAFICODE) = 'KFF' AND trim(p.SUBCODE07) = '-' AND trim(p.SUBCODE08) = '-' THEN ITXVIEW_INV_RESEPCOLOR.LONGDESCRIPTION
													-- WARNA DARI FLAT KNIT
													WHEN trim(p.ITEMTYPEAFICODE) = 'FKF' AND trim(p.SUBCODE07) = '' AND trim(p.SUBCODE08) = '' THEN USERGENERICGROUP.LONGDESCRIPTION
													ELSE '-'
												END AS WARNA,
												trim(p.SUBCODE01) AS SUBCODE01, trim(p.SUBCODE02) AS SUBCODE02, trim(p.SUBCODE03) AS SUBCODE03, trim(p.SUBCODE04) AS SUBCODE04, trim(p.SUBCODE05) AS SUBCODE05
											FROM PRODUCTIONDEMAND p 
											LEFT JOIN DESIGN DESIGN ON DESIGN.SUBCODE01 = p.SUBCODE07
											LEFT JOIN DESIGNCOMPONENT DESIGNCOMPONENT ON DESIGNCOMPONENT.VARIANTCODE = p.SUBCODE08 AND DESIGNCOMPONENT.DESIGNNUMBERID = DESIGN.NUMBERID
											LEFT JOIN (SELECT 
															ITXVIEW_INV_RESEPCOLOR.LONGDESCRIPTION,
															ITXVIEW_INV_RESEPCOLOR.ARTIKEL,
															ITXVIEW_INV_RESEPCOLOR.NO_WARNA
														FROM 
															ITXVIEW_INV_RESEPCOLOR ITXVIEW_INV_RESEPCOLOR
													GROUP BY 
															ITXVIEW_INV_RESEPCOLOR.LONGDESCRIPTION,
															ITXVIEW_INV_RESEPCOLOR.ARTIKEL,
															ITXVIEW_INV_RESEPCOLOR.NO_WARNA
															)ITXVIEW_INV_RESEPCOLOR ON ITXVIEW_INV_RESEPCOLOR.ARTIKEL = p.SUBCODE03 AND ITXVIEW_INV_RESEPCOLOR.NO_WARNA = p.SUBCODE05
											LEFT JOIN USERGENERICGROUP USERGENERICGROUP ON p.SUBCODE05 = USERGENERICGROUP.CODE 
											WHERE p.ORIGDLVSALORDLINESALORDERCODE = '$order' AND p.DLVSALESORDERLINEORDERLINE = '$getorderline'
											GROUP BY 
												p.DLVSALESORDERLINEORDERLINE,p.SUBCODE01,p.SUBCODE02,p.SUBCODE03,p.SUBCODE04,p.SUBCODE05,p.SUBCODE08,p.SUBCODE07,
												p.ITEMTYPEAFICODE,DESIGNCOMPONENT.SHORTDESCRIPTION,ITXVIEW_INV_RESEPCOLOR.LONGDESCRIPTION,USERGENERICGROUP.LONGDESCRIPTION");
				$r_item = db2_fetch_assoc($sqlitem);
			?>
			<input name="no_item1" type="hidden" class="form-control" id="no_item1" value="<?= $r_item['SUBCODE02'] . $r_item['SUBCODE03']; ?>" placeholder="No Item">
		</div>
	</div>
	<div class="form-group">
		<label for="color_code" class="col-sm-2 control-label">Color Code</label>
		<div class="col-sm-4">
			<?php
			$order = $dt_langganan['PROJECTCODE'];
			$orderline = $_GET['iditem'];

			$sqljk_colorcode = db2_exec($conn1, "SELECT 
													p.DLVSALESORDERLINEORDERLINE,
													TRIM(p.CODE) AS DEMANDCODE,
													trim(i2.ITEMTYPEAFICODE) AS ITEMTYPEAFICODE,
													trim(i2.SUBCODE01) AS SUBCODE01, 
													trim(i2.SUBCODE02) AS SUBCODE02,
													trim(i2.SUBCODE03) AS SUBCODE03, 
													trim(i2.SUBCODE04) AS SUBCODE04, 
													trim(i2.SUBCODE05) AS SUBCODE05,
													trim(i2.SUBCODE06) AS SUBCODE06,
													trim(i2.SUBCODE07) AS SUBCODE07,
													trim(i2.SUBCODE08) AS SUBCODE08,
													trim(i2.SUBCODE09) AS SUBCODE09,
													trim(i2.SUBCODE10) AS SUBCODE10,
													i.WARNA AS WARNA
												FROM PRODUCTIONDEMAND p 
												LEFT JOIN ITXVIEWBONORDER i2 ON i2.SALESORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE AND i2.ORDERLINE = p.ORIGDLVSALORDERLINEORDERLINE 
												LEFT JOIN ITXVIEWCOLOR i ON i.ITEMTYPECODE = i2.ITEMTYPEAFICODE 
																		AND i.SUBCODE01 = i2.SUBCODE01 
																		AND i.SUBCODE02 = i2.SUBCODE02 
																		AND i.SUBCODE03 = i2.SUBCODE03 
																		AND i.SUBCODE04 = i2.SUBCODE04 
																		AND i.SUBCODE05 = i2.SUBCODE05 
																		AND i.SUBCODE06 = i2.SUBCODE06 
																		AND i.SUBCODE07 = i2.SUBCODE07 
																		AND i.SUBCODE08 = i2.SUBCODE08 
																		AND i.SUBCODE09 = i2.SUBCODE09 
																		AND i.SUBCODE10 = i2.SUBCODE10
												LEFT JOIN USERGENERICGROUP USERGENERICGROUP ON p.SUBCODE05 = USERGENERICGROUP.CODE 
												WHERE p.ORIGDLVSALORDLINESALORDERCODE = '$order' AND p.DLVSALESORDERLINEORDERLINE = '$orderline'
												GROUP BY 
													p.DLVSALESORDERLINEORDERLINE,i2.SUBCODE01,i2.SUBCODE02,i2.SUBCODE03,i2.SUBCODE04,i2.SUBCODE05,i2.SUBCODE06,i2.SUBCODE07,i2.SUBCODE08,i2.SUBCODE09,i2.SUBCODE10,i2.ITEMTYPEAFICODE,i.WARNA,p.CODE");
			$assoc_colorcode = db2_fetch_assoc($sqljk_colorcode);
			?>
			<input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="<?= $assoc_colorcode['SUBCODE05']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
		<div class="col-sm-4">
			<!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
			<textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"></textarea>
		</div>
	</div>
	<div class="form-group hidden">
		<label for="g_LD" class="col-sm-2 control-label">Grouping to L/D</label>
		<div class="col-sm-4">
			<input name="g_LD" type="text" class="form-control" id="g_LD" placeholder="Grouping to L/D" value="">
		</div>
	</div>
	<div class="form-group">
		<label for="no_po" class="col-sm-2 control-label">PO Greige</label>
		<div class="col-sm-4">
			<?php
			$d_pogreigenew = db2_exec($conn1, "SELECT 
														CASE
															WHEN LOTCODE IS NOT NULL THEN LOTCODE
															ELSE '-'
														END AS LOTCODE,
														CASE
															WHEN DEMAND_KGF IS NOT NULL THEN DEMAND_KGF
															ELSE '-'
														END AS DEMAND_KGF
													FROM 
													(SELECT 
														i.LOTCODE AS LOTCODE,
														i.DEMAND_KGF AS DEMAND_KGF
													FROM 
														ITXVIEWPOGREIGENEW i 
													WHERE 
														i.SALESORDERCODE = '$_GET[idk]' AND i.ORDERLINE = '$_GET[iditem]'
													UNION ALL
													SELECT 
														i2.LOTCODE AS LOTCODE,
														i2.DEMAND_KGF AS DEMAND_KGF
													FROM 
														ITXVIEWPOGREIGENEW2 i2 
													WHERE 
														i2.SALESORDERCODE = '$_GET[idk]' AND i2.ORDERLINE = '$_GET[iditem]'
													UNION ALL
													SELECT
														i3.LOTCODE AS LOTCODE,
														i3.DEMAND_KGF AS DEMAND_KGF
													FROM
														ITXVIEWPOGREIGENEW3 i3 
													WHERE 
														i3.SALESORDERCODE = '$_GET[idk]' AND i3.ORDERLINE = '$_GET[iditem]')
													GROUP BY 
														LOTCODE, DEMAND_KGF");
			$d_pogreigenew4 = db2_exec($conn1, "SELECT INTERNALREFERENCE FROM PRODUCTIONDEMAND WHERE ORIGDLVSALORDLINESALORDERCODE = '$_GET[idk]' AND ORIGDLVSALORDERLINEORDERLINE = '$_GET[iditem]'");
			$d_pogreigenew5 = db2_exec($conn1, "SELECT 
														a.ORIGDLVSALORDLINESALORDERCODE,
														a.ORIGDLVSALORDERLINEORDERLINE,
														a.INTERNALREFERENCE,
														b.NAMENAME,
														b.VALUESTRING 
													FROM 
														PRODUCTIONDEMAND a
													LEFT JOIN ADSTORAGE b ON b.UNIQUEID = a.ABSUNIQUEID 
													WHERE 
														ORIGDLVSALORDLINESALORDERCODE = '$_GET[idk]' AND ORIGDLVSALORDERLINEORDERLINE = '$_GET[iditem]'
														AND
														(b.NAMENAME = 'ProAllow' OR b.NAMENAME = 'ProAllow2' OR b.NAMENAME = 'ProAllo3' OR b.NAMENAME = 'ProAllow4' OR b.NAMENAME = 'ProAllow5')");

			$r_pogreigenew = db2_fetch_assoc($d_pogreigenew);
			$r_pogreigenew4 = db2_fetch_assoc($d_pogreigenew4);
			$r_pogreigenew5 = db2_fetch_assoc($d_pogreigenew5);

			if ($r_pogreigenew['LOTCODE'] && $r_pogreigenew['DEMAND_KGF']) {
				$pogreige = 'NO KO : ' . $r_pogreigenew['LOTCODE'] . '/ DEMAND KGF :' . $r_pogreigenew['DEMAND_KGF'];
			}
			if ($r_pogreigenew4['INTERNALREFERENCE']) {
				$pogreige2 = $r_pogreigenew4['INTERNALREFERENCE'];
			} else {
				$pogreige2 = $r_pogreigenew5['VALUESTRING'];
			}
			?>
			<input name="no_po" type="text" class="form-control" id="no_po" placeholder="No PO" value="<?= $pogreige; ?>, PROJECT : <?= $pogreige2; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Jenis Kain</label>
		<div class="col-sm-6">
			<?php
			$itemtype = $assoc_colorcode['ITEMTYPEAFICODE'];
			$s1 = $assoc_colorcode['SUBCODE01'];
			$s2 = $assoc_colorcode['SUBCODE02'];
			$s3 = $assoc_colorcode['SUBCODE03'];
			$s4 = $assoc_colorcode['SUBCODE04'];
			$s5 = $assoc_colorcode['SUBCODE05'];
			$s6 = $assoc_colorcode['SUBCODE06'];
			$s7 = $assoc_colorcode['SUBCODE07'];
			$s8 = $assoc_colorcode['SUBCODE08'];
			$s9 = $assoc_colorcode['SUBCODE09'];
			$s10 = $assoc_colorcode['SUBCODE10'];

			$sql_jk = db2_exec($conn1, "SELECT * FROM PRODUCT WHERE TRIM(SUBCODE01) = '$s1' 
															AND TRIM(SUBCODE02) = '$s2' 
															AND TRIM(SUBCODE03) = '$s3' 
															AND TRIM(SUBCODE04) = '$s4' 
															AND TRIM(SUBCODE05) = '$s5' 
															AND TRIM(SUBCODE06) = '$s6' 
															AND TRIM(SUBCODE07) = '$s7' 
															AND TRIM(SUBCODE08) = '$s8'
															AND TRIM(SUBCODE09) = '$s9'
															AND TRIM(SUBCODE10) = '$s10'
															AND TRIM(ITEMTYPECODE) = '$itemtype'");
			$r_jk = db2_fetch_assoc($sql_jk);
			?>
			<input name="kain" type="text" class="form-control" required id="kain" value="<?= str_replace('"', " ", $r_jk['LONGDESCRIPTION']); ?>" placeholder="Jenis kain...">
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Warna</label>
		<div class="col-sm-6">
			<input name="warna" type="text" class="form-control" id="warna" value="<?= $assoc_colorcode['WARNA']; ?>" placeholder="Warna">
		</div>
	</div>
	<div class="form-group">
		<label for="no_warna" class="col-sm-2 control-label">LAB DIP NO</label>
		<div class="col-sm-6">
			<?php
			$order = $dt_langganan['PROJECTCODE'];
			$orderline = $_GET['iditem'];

			// $sql_cck = db2_exec($conn1, "SELECT
			// 							$stdcckwarna_lapdip
			// 							ITXVIEW_COLORREMARKS.VALUESTRING
			// 						FROM
			// 							SALESORDERLINE SALESORDERLINE
			// 						LEFT JOIN ITXVIEW_COLORSTANDARD ITXVIEW_COLORSTANDARD ON SALESORDERLINE.ABSUNIQUEID = ITXVIEW_COLORSTANDARD.UNIQUEID
			// 						LEFT JOIN ITXVIEW_COLORREMARKS ITXVIEW_COLORREMARKS ON ITXVIEW_COLORSTANDARD.UNIQUEID = ITXVIEW_COLORREMARKS.UNIQUEID
			// 						WHERE TRIM(SALESORDERLINE.SALESORDERCODE) = '$order' AND TRIM(SALESORDERLINE.ORDERLINE) = '$orderline'");
			$sql_cck = db2_exec($conn1, "SELECT * FROM ITXVIEW_STD_CCK_WARNA WHERE SALESORDERCODE = '$order' AND ORDERLINE = '$orderline'");
			$r_cck = db2_fetch_assoc($sql_cck);
			?>
			<input name="no_warna" type="text" class="form-control" id="no_warna" value="<?= $r_cck['LABDIPNO']; ?>" placeholder="LAB DIP NO">
		</div>
	</div>
	<!-- HIDDEN VALUE -->
	<div class="form-group">
		<label for="gramasi" class="col-sm-2 control-label">Gramasi</label>
		<div class="col-sm-2">
			<input name="lebar" required type="text" class="form-control" id="lebar" placeholder="Inci" value="<?php
																												$jn_mcng = $_GET['jn_mcng'];

																												if ($jn_mcng == "Matching Ulang NOW" or $jns_match == "Perbaikan NOW") {
																													$sql_lebar = db2_exec($conn1, "SELECT
														ADSTORAGE.NAMENAME,
														ADSTORAGE.VALUEDECIMAL,
														PRODUCT.ITEMTYPECODE,
														PRODUCT.SUBCODE01,
														PRODUCT.SUBCODE02,
														PRODUCT.SUBCODE03,
														PRODUCT.SUBCODE04,
														PRODUCT.SUBCODE05,
														PRODUCT.SUBCODE06,
														PRODUCT.SUBCODE07,
														PRODUCT.SUBCODE08,
														PRODUCT.SUBCODE09,
														PRODUCT.SUBCODE10,
														CASE
															WHEN TRIM(ADSTORAGE.NAMENAME) = 'Width' AND TRIM(PRODUCT.ITEMTYPECODE) = 'KFF' THEN ADSTORAGE.VALUEDECIMAL
															WHEN TRIM(ADSTORAGE.NAMENAME) = 'Width' AND TRIM(PRODUCT.ITEMTYPECODE) = 'FKF' THEN SUBSTRING(PRODUCT.SUBCODE04, 0, LOCATE('-', PRODUCT.SUBCODE04))
														END AS LEBAR
													FROM
														ADSTORAGE ADSTORAGE
													RIGHT JOIN PRODUCT PRODUCT ON ADSTORAGE.UNIQUEID = PRODUCT.ABSUNIQUEID
													WHERE TRIM(PRODUCT.SUBCODE01) = '$s1' 
														AND TRIM(PRODUCT.SUBCODE02) = '$s2' 
														AND TRIM(PRODUCT.SUBCODE03) = '$s3' 
														AND TRIM(PRODUCT.SUBCODE04) = '$s4' 
														AND TRIM(PRODUCT.SUBCODE05) = '$s5' 
														AND TRIM(PRODUCT.SUBCODE06) = '$s6' 
														AND TRIM(PRODUCT.SUBCODE07) = '$s7' 
														AND TRIM(PRODUCT.SUBCODE08) = '$s8'
														AND TRIM(PRODUCT.SUBCODE09) = '$s9'
														AND TRIM(PRODUCT.SUBCODE10) = '$s10'
														AND TRIM(PRODUCT.ITEMTYPECODE) = '$itemtype'
														AND TRIM(ADSTORAGE.NAMENAME) = 'Width'");
																													$r_lebar = db2_fetch_assoc($sql_lebar);
																													echo $r_lebar['LEBAR'];
																												} else {
																													if ($cek1 > 0) {
																														echo round($r1['cuttablewidth']);
																													} else {
																														echo $rw['warna'];
																													}
																												}
																												?>">
		</div>
		<div class="col-sm-2">
			<input name="gramasi" required type="text" class="form-control" id="gramasi" placeholder="Gr/M2" value="<?php
																													$jn_mcng = $_GET['jn_mcng'];

																													if ($jn_mcng == "Matching Ulang NOW" or $jns_match == "Perbaikan NOW") {
																														$sql_gramasi = db2_exec($conn1, "SELECT
														ADSTORAGE.NAMENAME,
														TRIM(ADSTORAGE.VALUEDECIMAL) AS VALUEDECIMAL,
														PRODUCT.ITEMTYPECODE,
														PRODUCT.SUBCODE01,
														PRODUCT.SUBCODE02,
														PRODUCT.SUBCODE03,
														TRIM(PRODUCT.SUBCODE04) AS SUBCODE04,
														PRODUCT.SUBCODE05,
														PRODUCT.SUBCODE06,
														PRODUCT.SUBCODE07,
														PRODUCT.SUBCODE08,
														PRODUCT.SUBCODE09,
														PRODUCT.SUBCODE10
													FROM
														ADSTORAGE ADSTORAGE
													RIGHT JOIN PRODUCT PRODUCT ON ADSTORAGE.UNIQUEID = PRODUCT.ABSUNIQUEID
													WHERE TRIM(PRODUCT.SUBCODE01) = '$s1' 
														AND TRIM(PRODUCT.SUBCODE02) = '$s2' 
														AND TRIM(PRODUCT.SUBCODE03) = '$s3' 
														AND TRIM(PRODUCT.SUBCODE04) = '$s4' 
														AND TRIM(PRODUCT.SUBCODE05) = '$s5' 
														AND TRIM(PRODUCT.SUBCODE06) = '$s6' 
														AND TRIM(PRODUCT.SUBCODE07) = '$s7' 
														AND TRIM(PRODUCT.SUBCODE08) = '$s8'
														AND TRIM(PRODUCT.SUBCODE09) = '$s9'
														AND TRIM(PRODUCT.SUBCODE10) = '$s10'
														AND TRIM(ADSTORAGE.NAMENAME) = 'GSM'");
																														$r_gramasi = db2_fetch_assoc($sql_gramasi);
																														if ($itemtype == 'FKF') { // JIKA FLATKNIT
																															$Gramasi_fkf = explode("-", $r_gramasi['SUBCODE04'], 3);
																															echo $Gramasi_fkf[1];
																														} else {
																															echo $r_gramasi['VALUEDECIMAL'];
																														}
																													} else {
																														if ($cek1 > 0) {
																															echo round($r1['weight']);
																														} else {
																															echo $rw['warna'];
																														}
																													} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="benang" class="col-sm-2 control-label">Benang</label>
		<div class="col-sm-8">
			<?php
				$q_itxviewkk	= db2_exec($conn1, "SELECT * FROM ITXVIEWBONORDER i WHERE SALESORDERCODE = '$_GET[idk]' AND ORDERLINE = '$_GET[iditem]'");
				$d_itxviewkk	= db2_fetch_assoc($q_itxviewkk);

				if($d_itxviewkk['ITEMTYPEAFICODE'] == 'KFF'){
					$subcode04 = $d_itxviewkk['RESERVATION_SUBCODE04'];
				}elseif ($d_itxviewkk['ITEMTYPEAFICODE'] == 'FKF') {
					$subcode04 = $d_itxviewkk['SUBCODE04'];
				}else{
					$subcode04 = $d_itxviewkk['SUBCODE04'];
				}

				$q_rajut	= db2_exec($conn1, "SELECT
														*
													FROM
														ITXVIEW_RAJUT
													WHERE
														SUBCODE01 = '$d_itxviewkk[SUBCODE01]'
														AND SUBCODE02 = '$d_itxviewkk[SUBCODE02]'
														AND SUBCODE03 = '$d_itxviewkk[SUBCODE03]'
														AND SUBCODE04 = '$subcode04'
														AND ORIGDLVSALORDLINESALORDERCODE = '$_GET[idk]'
														AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
				$d_rajut	= db2_fetch_assoc($q_rajut);

				$q_booking_blm_ready_1	= db2_exec($conn1, "SELECT
																*
															FROM
																ITXVIEW_BOOKING_BLM_READY ibbr 
															WHERE
																SUBCODE01 = '$d_itxviewkk[SUBCODE01]'
																AND SUBCODE02 = '$d_itxviewkk[SUBCODE02]'
																AND SUBCODE03 = '$d_itxviewkk[SUBCODE03]'
																AND SUBCODE04 = '$subcode04'
																AND ORIGDLVSALORDLINESALORDERCODE = '$d_itxviewkk[ADDITIONALDATA]'-- NGAMBIL DARI ADDITIONAL DATA 
																AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
				$d_booking_blm_ready_1	= db2_fetch_assoc($q_booking_blm_ready_1);
				
				$q_booking_blm_ready_2	= db2_exec($conn1, "SELECT
																*
															FROM
																ITXVIEW_BOOKING_BLM_READY ibbr 
															WHERE
																SUBCODE01 = '$d_itxviewkk[SUBCODE01]'
																AND SUBCODE02 = '$d_itxviewkk[SUBCODE02]'
																AND SUBCODE03 = '$d_itxviewkk[SUBCODE03]'
																AND SUBCODE04 = '$subcode04'
																AND ORIGDLVSALORDLINESALORDERCODE = '$d_itxviewkk[ADDITIONALDATA2]'-- NGAMBIL DARI ADDITIONAL DATA 
																AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
				$d_booking_blm_ready_2	= db2_fetch_assoc($q_booking_blm_ready_2);
				
				$q_booking_blm_ready_3	= db2_exec($conn1, "SELECT
																*
															FROM
																ITXVIEW_BOOKING_BLM_READY ibbr 
															WHERE
																SUBCODE01 = '$d_itxviewkk[SUBCODE01]'
																AND SUBCODE02 = '$d_itxviewkk[SUBCODE02]'
																AND SUBCODE03 = '$d_itxviewkk[SUBCODE03]'
																AND SUBCODE04 = '$subcode04'
																AND ORIGDLVSALORDLINESALORDERCODE = '$d_itxviewkk[ADDITIONALDATA3]'-- NGAMBIL DARI ADDITIONAL DATA 
																AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
				$d_booking_blm_ready_3	= db2_fetch_assoc($q_booking_blm_ready_3);
				
				$q_booking_blm_ready_4	= db2_exec($conn1, "SELECT
																*
															FROM
																ITXVIEW_BOOKING_BLM_READY ibbr 
															WHERE
																SUBCODE01 = '$d_itxviewkk[SUBCODE01]'
																AND SUBCODE02 = '$d_itxviewkk[SUBCODE02]'
																AND SUBCODE03 = '$d_itxviewkk[SUBCODE03]'
																AND SUBCODE04 = '$subcode04'
																AND ORIGDLVSALORDLINESALORDERCODE = '$d_itxviewkk[ADDITIONALDATA4]'-- NGAMBIL DARI ADDITIONAL DATA 
																AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
				$d_booking_blm_ready_4	= db2_fetch_assoc($q_booking_blm_ready_4);
				
				$q_booking_blm_ready_5	= db2_exec($conn1, "SELECT
																*
															FROM
																ITXVIEW_BOOKING_BLM_READY ibbr 
															WHERE
																SUBCODE01 = '$d_itxviewkk[SUBCODE01]'
																AND SUBCODE02 = '$d_itxviewkk[SUBCODE02]'
																AND SUBCODE03 = '$d_itxviewkk[SUBCODE03]'
																AND SUBCODE04 = '$subcode04'
																AND ORIGDLVSALORDLINESALORDERCODE = '$d_itxviewkk[ADDITIONALDATA4]'-- NGAMBIL DARI ADDITIONAL DATA 
																AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
				$d_booking_blm_ready_5	= db2_fetch_assoc($q_booking_blm_ready_5);

				$q_booking_new	= db2_exec($conn1, "SELECT
														*
													FROM
														ITXVIEW_BOOKING_NEW ibn 
													WHERE
														SALESORDERCODE = '$_GET[idk]'
														AND ORDERLINE = '$_GET[iditem]'");
				$d_booking_new	= db2_fetch_assoc($q_booking_new);
			?>
			<textarea name="benang" rows="6" class="form-control" id="benang" required placeholder="Benang">
				<?php 
					if($d_rajut['SUMMARIZEDDESCRIPTION']){ 
						echo $d_rajut['SUMMARIZEDDESCRIPTION'].'&#13;&#10;'; 
					} 
				?>
				<?php 
					if($d_booking_blm_ready_1['SUMMARIZEDDESCRIPTION']){ 
						echo $d_booking_blm_ready_1['SUMMARIZEDDESCRIPTION'].' - '.$d_booking_blm_ready_1['ORIGDLVSALORDLINESALORDERCODE'].'&#13;&#10;'; 
					} 
				?>
				<?php 
					if($d_booking_blm_ready_2['SUMMARIZEDDESCRIPTION']){ 
						echo $d_booking_blm_ready_2['SUMMARIZEDDESCRIPTION'].' - '.$d_booking_blm_ready_2['ORIGDLVSALORDLINESALORDERCODE'].'&#13;&#10;'; 
					} 
				?>
				<?php 
					if($d_booking_blm_ready_3['SUMMARIZEDDESCRIPTION']){ 
						echo $d_booking_blm_ready_3['SUMMARIZEDDESCRIPTION'].' - '.$d_booking_blm_ready_3['ORIGDLVSALORDLINESALORDERCODE'].'&#13;&#10;'; 
					} 
				?>
				<?php 
					if($d_booking_blm_ready_4['SUMMARIZEDDESCRIPTION']){ 
						echo $d_booking_blm_ready_4['SUMMARIZEDDESCRIPTION'].' - '.$d_booking_blm_ready_4['ORIGDLVSALORDLINESALORDERCODE'].'&#13;&#10;'; 
					} 
				?>
				<?php 
					if($d_booking_blm_ready_5['SUMMARIZEDDESCRIPTION']){ 
						echo $d_booking_blm_ready_5['SUMMARIZEDDESCRIPTION'].' - '.$d_booking_blm_ready_5['ORIGDLVSALORDLINESALORDERCODE'].'&#13;&#10;'; 
					} 
				?>
				<?php 
					if($d_booking_new['SUMMARIZEDDESCRIPTION']){ 
						echo $d_booking_new['SUMMARIZEDDESCRIPTION'].'&#13;&#10;'; 
					} 
				?>
			</textarea>
		</div>
	</div>
	<!-- HIDDEN VALUE -->
	<div class="form-group">
		<label for="cocok_warna" class="col-sm-2 control-label">Cocok Warna</label>
		<div class="col-sm-8">
			<input name="cocok_warna" type="text" class="form-control" id="cocok_warna" value="<?= $r_cck['STDCCKWARNA']; ?>" placeholder="Cocok Warna">
		</div>
	</div>
	<div class="form-group">
		<label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery </label>
		<div class="col-sm-3">
			<?php
			$d_tgldelivery = db2_exec($conn1, "SELECT * FROM SALESORDERDELIVERY WHERE SALESORDERLINESALESORDERCODE = '$_GET[idk]' AND SALESORDERLINEORDERLINE = '$_GET[iditem]'");
			$r_delivery = db2_fetch_assoc($d_tgldelivery);
			?>
			<input name="tgl_delivery" type="text" value="<?php $date_deliv = date_create($r_delivery['DELIVERYDATE']);
															echo date_format($date_deliv, "Y-m-d"); ?>" class="form-control datepicker" id="tgl_delivery" placeholder="Tgl Delivery">
		</div>
	</div>
	<div class="form-group">
		<label for="salesman_sample" class="col-sm-2 control-label">Salesman Sample</label>
		<div class="col-sm-8">
			<input type="checkbox" id="salesman_sample" name="salesman_sample" <?php if ($cek > 0) {
																				} else {
																					if ($rw['salesman_sample'] == "1") {
																						echo "checked";
																					}
																				}  ?> value="1">
		</div>
	</div>
	<div class="form-group">
		<label for="qty" class="col-sm-2 control-label">Qty Order</label>
		<div class="col-sm-3">
			<?php
			$demand = $assoc_colorcode['DEMANDCODE'];
			$qry_berat = db2_exec($conn1, "SELECT SUM(USERPRIMARYQUANTITY) AS QTY_BRUTO FROM ITXVIEW_KGBRUTO 
													WHERE PROJECTCODE = '$_GET[idk]'
													AND	  ORIGDLVSALORDERLINEORDERLINE = '$_GET[iditem]'");
			$rw_berat = db2_fetch_assoc($qry_berat);
			?>
			<input name="qty" type="text" required class="form-control" id="qty" value="<?= $rw_berat['QTY_BRUTO']; ?>" placeholder="Qty Order">
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Buyer</label>
		<div class="col-sm-3">
			<select name="buyer" id="buyer" class="form-control selectBuyer3" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Lampu Buyer :</label>
		<div class="col-sm-10" id="lampu-buyer3">
			<!-- i do some magic here  -->
		</div>
	</div>
	<div class="form-group">
		<label for="proses" class="col-sm-2 control-label">Proses</label>
		<div class="col-sm-3">
			<?php // $jn_mcng = $_GET['jn_mcng']; if ($jn_mcng == "Matching Ulang NOW") : 
			?>
			<?php
			// $demand = $assoc_colorcode['DEMANDCODE'];
			// $sql_alurproses = db2_exec($conn1, "SELECT TRIM(OPERATIONCODE) AS OPERATIONCODE FROM PRODUCTIONDEMANDSTEP WHERE PRODUCTIONDEMANDCODE = '$demand'")
			?>
			<!-- <input name="proses" type="text" class="form-control" id="proses" value="-->
			<?php
			// while ($r_aluproses = db2_fetch_assoc($sql_alurproses)) {
			// 	echo $r_aluproses['OPERATIONCODE'].'-';
			// }
			?>
			<!-- " placeholder="Alur Proses"> -->
			<?php // else : 
			?>
			<select class="form-control selectProses3" required name="proses" id="proses" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
			<?php // endif; 
			?>
		</div>
	</div>

	<!-- Temp 1 -->
	<div class="form-group">
		<label for="temp_code" class="col-sm-2 control-label">Temp</label>
		<div class="col-sm-2">
			<select name="temp_code" id="temp_code" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi akan diisi otomatis oleh JS -->
			</select>
		</div>
	</div>

	<!-- Temp 2 (hanya tampil jika Dyestuff == DR) -->
	<div class="form-group" id="temp2-wrapper" style="display: none;">
		<label for="temp_code2" class="col-sm-2 control-label">Temp 2</label>
		<div class="col-sm-2">
			<select name="temp_code2" id="temp_code2" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi bisa diisi sama seperti temp_code jika perlu -->
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label">Suhu Chamber</label>
		<div class="col-sm-2">
			<!-- Checkbox hanya untuk menampilkan input -->
			<input type="checkbox" id="suhu_chamber" onchange="toggleInputSuhu()">
			<label class="form-check-label" for="suhu_chamber">Stempel Aktif</label>

			<!-- Input suhu yang akan dikirim -->
			<input type="number" id="input_suhu" name="suhu_chamber" class="form-control mt-1"
				placeholder="Isi suhu" style="display: none;" min="0">
		</div>
		<div class="col-sm-3">
			<!-- Checkbox None -->
			<label style="color: red;">
				<input type="checkbox" id="none_suhu_chamber" name="none_suhu_chamber" value="none" onchange="toggleNoneSuhu()" <?php if ($_GET['Dystf'] === 'R') echo 'checked'; ?>> ❌ None - Suhu Chamber
			</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Warna Flourescent</label>
		<div class="col-sm-2">
			<input type="checkbox" name="warna_fluorescent" id="warna_fluorescent" value="1">
			<label class="form-check-label" for="warna_fluorescent">Stempel Aktif</label>
		</div>
	</div>

	<script>
		function toggleInputSuhu() {
			const suhuCheckbox = document.getElementById('suhu_chamber');
			const inputSuhu = document.getElementById('input_suhu');
			const noneCheckbox = document.getElementById('none_suhu_chamber');

			if (suhuCheckbox.checked) {
				inputSuhu.style.display = 'block';
				inputSuhu.disabled = false;
				noneCheckbox.checked = false;
			} else {
				inputSuhu.style.display = 'none';
				inputSuhu.value = '';
			}
		}

		function toggleNoneSuhu() {
			const noneCheckbox = document.getElementById('none_suhu_chamber');
			const suhuCheckbox = document.getElementById('suhu_chamber');
			const inputSuhu = document.getElementById('input_suhu');

			if (noneCheckbox.checked) {
				suhuCheckbox.checked = false;
				inputSuhu.style.display = 'none';
				inputSuhu.value = 'none';
			}
		}
	</script>

	<div class="box-footer">
		<div class="col-sm-2">
			<button type="submit" class="btn btn-block btn-social btn-linkedin" name="simpan" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('.datepicker').datepicker({
			autoclose: true,
			format: 'yyyy-mm-dd',
			todayHighlight: true,
		})

		
		if ($('.form-control.ordercuy').val().length >= 12) {
			if (document.getElementById("jen_matching").value == "Matching Development") {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#Development').appendTo('#echoing_the_choice');
				$("#Development").show();
				toggleTemp2();
			}else{
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#Matching_ulang_perbaikan').appendTo('#echoing_the_choice');
				$("#Matching_ulang_perbaikan").show();
				toggleTemp2();
			}
			// console.log(document.getElementById("jen_matching").value);
		} else if ($('.form-control.ordernowcuy').val().length >= 6) {
			if ($('.form-control.ordernowcuyld').val().includes("LAB")) {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#LDNOW').appendTo('#echoing_the_choice');
				$("#LDNOW").show();
				toggleTemp2();
			} else {
				// console.log(document.getElementById("jen_matching").value);
				if (document.getElementById("jen_matching").value == "Matching Development") {
					$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
					$('#Development').appendTo('#echoing_the_choice');
					$("#Development").show();
					toggleTemp2();
				}else{
					$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
					$('#NowForm').appendTo('#echoing_the_choice');
					$("#NowForm").show();
					toggleTemp2();
				}
			}
		}

		let antrian = $('#shadow_no_resep').val();
		var no_resep_fix = $(this).find(":selected").val() + antrian;
		$('#no_resep').val(no_resep_fix);

		$('#Dyestuff').change(function() {
			var Q = $('#shadow_no_resep').val();
			var no_resep_fix = $(this).find(":selected").val() + Q;
			$('#no_resep').val(no_resep_fix);
		})

		$('#jen_matching').change(function() {
			if ($(this).find(":selected").val() == 'Matching Ulang' || $(this).find(":selected").val() == 'Perbaikan') {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#Matching_ulang_perbaikan').appendTo('#echoing_the_choice');
				$("#Matching_ulang_perbaikan").show();
				toggleTemp2();
			} else if ($(this).find(":selected").val() == 'L/D') {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#LD').appendTo('#echoing_the_choice');
				$("#LD").show();
				toggleTemp2();
			} else if ($(this).find(":selected").val() == 'LD NOW') {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#LDNOW').appendTo('#echoing_the_choice');
				$("#LDNOW").show();
				toggleTemp2();
			} else if ($(this).find(":selected").val() == "Matching Development") {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#Development').appendTo('#echoing_the_choice');
				$("#Development").show();
				toggleTemp2();
			} else if ($(this).find(":selected").val() == 'Matching Ulang NOW' || $(this).find(":selected").val() == 'Perbaikan NOW') {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#NowForm').appendTo('#echoing_the_choice');
				$("#NowForm").show();
				toggleTemp2();
			}
		})

		$('.selectNoItem').select2();

		$('.selectNoItemNOW').on('click', function() {
			$(this).select2({});
		})

		$('.selectBuyer1').on('click', function() {
			$(this).select2({
				minimumInputLength: 0,
				allowClear: true,
				placeholder: 'Insert keyword',
				ajax: {
					dataType: 'json',
					url: 'pages/ajax/get_distinc_buyer.php',
					delay: 300,
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function(data, page) {
						return {
							results: data
						};
					},
				}
			}).on('select2:select', function(evt) {
				var select_selected = $(this).find(':selected').val();
				$.ajax({
					dataType: "json",
					type: "POST",
					url: "pages/ajax/get_lampuFbuyer.php",
					data: {
						buyer: select_selected
					},
					success: function(response) {
						$('#lampu-buyer1').html('');
						$.each(response, function(key, value) {
							$('#lampu-buyer1').append('<div class="col-sm-2"><input class="form-control" value="' + value + '" readonly></div>')
						});
					},
					error: function() {
						alert("Hubungi Departement DIT !");
					}
				});
			});
		})

		$('.selectProses1').on('click', function() {
			$(this).select2({
				minimumInputLength: 0,
				allowClear: true,
				placeholder: 'Insert keyword',
				ajax: {
					dataType: 'json',
					url: 'pages/ajax/Get_List_process.php',
					delay: 300,
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function(data, page) {
						return {
							results: data
						};
					},
				}
			})
		})

		$('.selectBuyer2').on('click', function() {
			$(this).select2({
				minimumInputLength: 0,
				allowClear: true,
				placeholder: 'Insert keyword',
				ajax: {
					dataType: 'json',
					url: 'pages/ajax/get_distinc_buyer.php',
					delay: 300,
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function(data, page) {
						return {
							results: data
						};
					},
				}
			}).on('select2:select', function(evt) {
				var select_selected = $(this).find(':selected').val();
				$.ajax({
					dataType: "json",
					type: "POST",
					url: "pages/ajax/get_lampuFbuyer.php",
					data: {
						buyer: select_selected
					},
					success: function(response) {
						$('#lampu-buyer2').html('');
						$.each(response, function(key, value) {
							$('#lampu-buyer2').append('<div class="col-sm-2"><input class="form-control" value="' + value + '" readonly></div>')
						});
					},
					error: function() {
						alert("Hubungi Departement DIT !");
					}
				});
			});
		});

		$('.selectProses2').on('click', function() {
			$(this).select2({
				minimumInputLength: 0,
				allowClear: true,
				placeholder: 'Insert keyword',
				ajax: {
					dataType: 'json',
					url: 'pages/ajax/Get_List_process.php',
					delay: 300,
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function(data, page) {
						return {
							results: data
						};
					},
				}
			})
		})

		$('.selectBuyer3').on('click', function() {
			$(this).select2({
				minimumInputLength: 0,
				allowClear: true,
				placeholder: 'Insert keyword',
				ajax: {
					dataType: 'json',
					url: 'pages/ajax/get_distinc_buyer.php',
					delay: 300,
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function(data, page) {
						return {
							results: data
						};
					},
				}
			}).on('select2:select', function(evt) {
				var select_selected = $(this).find(':selected').val();
				$.ajax({
					dataType: "json",
					type: "POST",
					url: "pages/ajax/get_lampuFbuyer.php",
					data: {
						buyer: select_selected
					},
					success: function(response) {
						$('#lampu-buyer3').html('');
						$.each(response, function(key, value) {
							$('#lampu-buyer3').append('<div class="col-sm-2"><input class="form-control" value="' + value + '" readonly></div>')
						});
					},
					error: function() {
						alert("Hubungi Departement DIT !");
					}
				});
			});
		});

		$('.selectProses3').on('click', function() {
			$(this).select2({
				minimumInputLength: 0,
				allowClear: true,
				placeholder: 'Insert keyword',
				ajax: {
					dataType: 'json',
					url: 'pages/ajax/Get_List_process.php',
					delay: 300,
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function(data, page) {
						return {
							results: data
						};
					},
				}
			})
		})
	});
</script>
<script>
	function toggleTemp2() {
		const dyestuffSelect = document.getElementById('Dyestuff');  
		const jnsMachingSelect = document.getElementById('jen_matching');  
		const temp2Wrapper = document.getElementById('temp2-wrapper');
		const tempCode = document.getElementById('temp_code');
		const tempCode2 = document.getElementById('temp_code2');

		if (!dyestuffSelect) return;

		const dystf = dyestuffSelect.value;
		const jnsMtcg = jnsMachingSelect.value;

		if (dystf === 'DR') {
			temp2Wrapper.style.display = 'flex';
		} else {
			temp2Wrapper.style.display = 'none';
		}

		fetch('pages/ajax/get_suhu_options.php?Dystf=' + encodeURIComponent(dystf) + '&jnsMtcg=' + encodeURIComponent(jnsMtcg))
			.then(response => response.text())
			.then(data => {
				if (tempCode) {
					tempCode.innerHTML = '<option value="">Pilih...</option>' + data;
				}
				if (tempCode2) {
					if (dystf === 'DR') {
						tempCode2.innerHTML = '<option value="">Pilih...</option>' + data;
					} else {
						tempCode2.innerHTML = '<option value="">Pilih...</option>';
					}
				}
			});
	}

	document.addEventListener('DOMContentLoaded', function () {
		const dyestuffSelect = document.getElementById('Dyestuff');
		if (dyestuffSelect) {
			toggleTemp2();
			dyestuffSelect.addEventListener('change', toggleTemp2);
		}
	});
</script>
</html>