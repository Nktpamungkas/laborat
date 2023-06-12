<!doctype html>
<html>

	<head>
		<meta charset="utf-8">
		<title>Form Matching</title>
	</head>

	<body>
		<?php
function nourut()
{
    $format = "M".date("ym");
    $sql=mysql_query("SELECT no_resep FROM tbl_matching WHERE substr(no_resep,1,5) like '%".$format."%' ORDER BY no_resep DESC LIMIT 1 ") or die(mysql_error());
    $d=mysql_num_rows($sql);
    if ($d>0) {
        $r=mysql_fetch_array($sql);
        $d=$r['no_resep'];
        $str=substr($d, 5, 3);
        $Urut = (int)$str;
    } else {
        $Urut = 0;
    }
    $Urut = $Urut + 1;
    $Nol="";
    $nilai=3-strlen($Urut);
    for ($i=1;$i<=$nilai;$i++) {
        $Nol= $Nol."0";
    }
    $no1 =$format.$Nol.$Urut;
    return $no1;
}
$nourut=nourut();

if ($_GET[idk]!="") {
    $sqlLot=mssql_query(" SELECT
			x.*,dbo.fn_StockMovementDetails_GetTotalWeightPCC(0, x.PCBID) as Weight,
      dbo.fn_StockMovementDetails_GetTotalRollPCC(0, x.PCBID) as RollCount
FROM( SELECT
				so.CustomerID, so.BuyerID,
				sod.ID as SODID, sod.ProductID, sod.UnitID, sod.WeightUnitID,
				pcb.ID as PCBID,pcb.UnitID as BatchUnitID,
				pcblp.DepartmentID,pcb.PCID,pcb.LotNo,pcb.ChildLevel,pcb.RootID
			FROM
				SalesOrders so INNER JOIN
				JobOrders jo ON jo.SOID=so.ID INNER JOIN
				SODetails sod ON so.ID = sod.SOID INNER JOIN
				SODetailsAdditional soda ON sod.ID = soda.SODID LEFT JOIN
				ProcessControlJO pcjo ON sod.ID = pcjo.SODID LEFT JOIN
				ProcessControlBatches pcb ON pcjo.PCID = pcb.PCID LEFT JOIN
				ProcessControlBatchesLastPosition pcblp ON pcb.ID = pcblp.PCBID LEFT JOIN
				ProcessFlowProcessNo pfpn ON pfpn.EntryType = 2 and pcb.ID = pfpn.ParentID AND pfpn.MachineType = 24 LEFT JOIN
				ProcessFlowDetailsNote pfdn ON pfpn.EntryType = pfdn.EntryType AND pfpn.ID = pfdn.ParentID
			WHERE jo.DocumentNo='$_GET[idk]' AND pcb.Gross<>'0'
				GROUP BY
					so.SONumber, so.SODate, so.CustomerID, so.BuyerID, so.PONumber, so.PODate,jo.DocumentNo,
					sod.ID, sod.ProductID, sod.Quantity, sod.UnitID, sod.Weight, sod.WeightUnitID,
					soda.RefNo,pcb.DocumentNo,pcb.Dated,sod.RequiredDate,
					pcb.ID, pcb.DocumentNo, pcb.Gross,
					pcb.Quantity, pcb.UnitID, pcb.ScheduledDate, pcb.ProductionScheduledDate,
					pcblp.DepartmentID,pcb.LotNo,pcb.PCID,pcb.ChildLevel,pcb.RootID
				) x INNER JOIN
				ProductMaster pm ON x.ProductID = pm.ID LEFT JOIN
				Departments dep ON x.DepartmentID  = dep.ID LEFT JOIN
				Departments pdep ON dep.RootID = pdep.ID LEFT JOIN
				Partners cust ON x.CustomerID = cust.ID LEFT JOIN
				Partners buy ON x.BuyerID = buy.ID LEFT JOIN
				UnitDescription udq ON x.UnitID = udq.ID LEFT JOIN
				UnitDescription udw ON x.WeightUnitID = udw.ID LEFT JOIN
				UnitDescription udb ON x.BatchUnitID = udb.ID
			ORDER BY
				x.SODID, x.PCBID ", $conn);
    $sLot=mssql_fetch_array($sqlLot);
    $cLot=mssql_num_rows($sqlLot);
    $child=$sLot[ChildLevel];

    if ($child > 0) {
        $sqlgetparent=mssql_query("select ID,LotNo from ProcessControlBatches where ID='$sLot[RootID]' and ChildLevel='0'");
        $rowgp=mssql_fetch_assoc($sqlgetparent);

        //$nomLot=substr("$row2[LotNo]",0,1);
        $nomLot=$rowgp[LotNo];
        $nomorLot="$nomLot/K$sLot[ChildLevel]&nbsp;";
    } else {
        $nomorLot=$sLot[LotNo];
    }

    $sqlLot1="Select count(*) as TotalLot From ProcessControlBatches where PCID='$sLot[PCID]' and RootID='0' and LotNo < '1000'";
    $qryLot1 = mssql_query($sqlLot1) or die('A error occured : ');
    $rowLot=mssql_fetch_assoc($qryLot1);

    $sqls=mssql_query("select salesorders.customerid,salesorders.buyerid from Joborders
left join salesorders on soid= salesorders.id
where JobOrders.documentno='$_GET[idk]'", $conn);
    $ssr=mssql_fetch_array($sqls);
    $cek=mssql_num_rows($sqls);
    $lgn1=mssql_query("select partnername from partners where id='$ssr[customerid]'", $conn);
    $ssr1=mssql_fetch_array($lgn1);
    $lgn2=mssql_query("select partnername from partners where id='$ssr[buyerid]'", $conn);
    $ssr2=mssql_fetch_array($lgn2);
}
     //

     ?>
		<?php
    $sqljkd=mssql_query("select processcontrol.id as pcid,processcontrolJO.SODID,salesorders.ponumber,joborders.documentno,
    processcontrol.productid,salesorders.customerid,
salesorders.buyerid,processcontrolbatches.lotno,productcode,productmaster.color,colorno,description,productmaster.weight,cuttablewidth,
	SOSampleColor.OtherDesc,SOSampleColor.Flag,hangerno from Joborders
    Left join salesorders on soid= salesorders.id
    Left join SOSampleColor on SOSampleColor.SOID=SalesOrders.id
		Left join SODetails on SalesOrders.id=SODetails.SOID
	  left join productmaster on productmaster.id= SODetails.productid
    left join productpartner on productpartner.productid= SODetails.productid
		left join processcontrolJO on processcontrolJO.joid = Joborders.id
		left join processcontrol on processcontrolJO.pcid = processcontrol.id
    left join processcontrolbatches on processcontrolbatches.pcid = processcontrol.id
    where productmaster.id='$_GET[iditem]' and processcontrol.productid='$_GET[iditem]' and JobOrders.documentno='$_GET[idk]' ", $conn);
    $r1=mssql_fetch_array($sqljkd);
    $cek1=mssql_num_rows($sqljkd);
                    ?>
		<?php
    if (isset($_POST[simpan])) {
        $kain=str_replace("'", "''", $_POST[kain]);
        $benang=str_replace("'", "''", $_POST[benang]);
        $cocok_warna=str_replace("'", "''", $_POST[cocok_warna]);
        $warna=str_replace("'", "''", $_POST[warna]);
        $langganan=str_replace("'", "''", $_POST[langganan]);

        $qry=mysql_query("INSERT INTO tbl_matching SET
		no_resep='$nourut',
		no_order='$_POST[no_order]',
		no_po='$_POST[no_po]',
		langganan='$langganan',
		no_item='$_POST[no_item]',
		jenis_kain='$kain',
		benang='$benang',
		matcher='$_POST[matcher]',
		target='$_POST[target]',
		cie_wi='$_POST[ciewi]',
		cie_tint='$_POST[cietint]',
		tgl_in=now(),
		cocok_warna='$cocok_warna',
		warna='$warna',
		no_warna='$_POST[no_warna]',
		lebar='$_POST[lebar]',
		qty_order='$_POST[qty]',
		gramasi='$_POST[gramasi]',
		lebara='$_POST[lebara]',
		gramasia='$_POST[gramasia]',
		ck_d65='$_POST[ck1]',
		ck_f02='$_POST[ck2]',
		ck_f11='$_POST[ck3]',
		ck_u35='$_POST[ck4]',
		ck_a='$_POST[ck5]',
		ck_rlight='$_POST[ck6]',
		ck_greige='$_POST[ckpro1]',
		ck_bleaching='$_POST[ckpro2]',
		ck_nh2o2='$_POST[ckpro3]',
		ck_preset='$_POST[ckpro4]',
		ck_npreset='$_POST[ckpro5]',
		ck_tarik='$_POST[ckpro6]',
		tgl_buat=now(),
		tgl_update=now()
		");
        if ($qry) {
            //echo "<script>alert('Data Tersimpan');window.open('pages/cetak/matching.php?idkk=$_POST[no_resep]');</script>";
            echo "<script>alert('Data Tersimpan');window.location.href='?p=form-matching-detail&&noresep=$nourut';</script>";
        } else {
            echo "There's been a problem: " . mysql_error();
        }
    }
?>

		<div class="row">
			<div class="col-md-12">
				<!-- Custom Tabs -->
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_1" data-toggle="tab">Input Order</a></li>
						<li><a href="#tab_2" data-toggle="tab">Detail Order</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
								<div class="box-body">
									<div class="form-group">
										<label for="order" class="col-sm-2 control-label">Rcode</label>
										<div class="col-sm-2">
											<input name="no_resep" type="text" class="form-control" id="no_resep" value="<?php echo $nourut;?>" placeholder="No Resep" required readonly>
										</div>
									</div>
									<div class="form-group">
										<label for="order" class="col-sm-2 control-label">No Order</label>
										<div class="col-sm-4">
											<input name="no_order" type="text" class="form-control" id="order" onchange="window.location='?p=Form-Matching&idk='+this.value" value="<?php if ($_GET[idk]!="") {
    echo $_GET[idk];
}?>" placeholder="No Order" required>
										</div>
									</div>
									<div class="form-group">
										<label for="langganan" class="col-sm-2 control-label">Langganan</label>
										<div class="col-sm-8">
											<input name="langganan" type="text" class="form-control" id="langganan" placeholder="Langganan" value="<?php if ($cek>0) {
    echo $ssr1['partnername']."/".$ssr2['partnername']; } else { echo $rw[langganan]; }?>" >
										</div>
									</div>
									<div class="form-group">
										<label for="no_item" class="col-sm-2 control-label">Item</label>
										<div class="col-sm-3">
											<select name="no_item1" class="form-control" id="no_item1" onchange="window.location='?p=Form-Matching&idk=<?php echo $_GET[idk];?>&iditem='+this.value" required>
												<?php
    $sqljk=mssql_query("select productmaster.id,productpartner.productcode,productmaster.color,colorno,hangerno from Joborders
    left join salesorders on soid= salesorders.id
	left join SODetails on SalesOrders.id=SODetails.SOID
	left join productmaster on productmaster.id= SODetails.productid
    left join productpartner on productpartner.productid= SODetails.productid
    where JobOrders.documentno='$_GET[idk]'
	GROUP BY productmaster.id,productpartner.productcode,productmaster.color,
	productmaster.colorno,productmaster.hangerno", $conn);
                    ?>
												<option value="">Pilih</option>
												<?php while ($r=mssql_fetch_array($sqljk)) {
                        ?>
												<option value="<?php echo $r[id]; ?>" <?php if ($_GET[iditem]==$r[id]) { echo "SELECTED" ; } ?>>
													<?php echo $r[hangerno]."-".$r[colorno]. " | ".$r[color]; ?>
												</option>
												<?php
                    } ?>
											</select>
											<input name="no_item" type="hidden" class="form-control" id="no_item" placeholder="No Item" value="<?php if ($cek1>0) {
                        if ($r1['hangerno']!="") {
                            echo $r1['hangerno'];
                        } else {
                            echo $r1['productcode'];
                        }
                    } else {
                        echo $rw[no_item];
                    }?>">
										</div>
									</div>
									<?php $ko=mssql_query("select  ko.KONo from
		ProcessControlJO pcjo inner join
		ProcessControl pc on pcjo.PCID = pc.ID left join
		KnittingOrders ko on pc.CID = ko.CID and pcjo.KONo = ko.KONo
	where
		pcjo.PCID = '$r1[pcid]'
group by ko.KONo", $conn);
                    $r2=mssql_fetch_array($ko);
                    ?>
									<div class="form-group">
										<label for="no_po" class="col-sm-2 control-label">PO Greige</label>

										<div class="col-sm-4">
											<input name="no_po" type="text" class="form-control" id="no_po" placeholder="No PO" value="<?php echo $r2[KONo];?>">
										</div>
									</div>
									<div class="form-group">
										<label for="kain" class="col-sm-2 control-label">Kain</label>
										<div class="col-sm-10">
											<input name="kain" type="text" class="form-control" id="kain" placeholder="Kain" value="<?php if ($cek1>0) {
                        echo htmlentities($r1['description'], ENT_QUOTES);
                    } else {
                        echo $rw[jenis_kain];
                    }?>">
										</div>
									</div>
									<div class="form-group">
										<label for="warna" class="col-sm-2 control-label">Warna</label>
										<div class="col-sm-6">
											<input name="warna" type="text" class="form-control" id="warna" placeholder="Warna" value="<?php if ($cek1>0) {
                        echo $r1['color'];
                    } else {
                        echo $rw[warna];
                    }?>">
										</div>
									</div>
									<div class="form-group">
										<label for="no_warna" class="col-sm-2 control-label">No Warna</label>
										<div class="col-sm-6">
											<input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="No Warna" value="<?php if ($cek1>0) {
                        echo $r1['colorno'];
                    } else {
                        echo $rw[no_warna];
                    }?>">
										</div>
									</div>
									<div class="form-group">
										<label for="gramasi" class="col-sm-2 control-label">Gramasi</label>
										<div class="col-sm-2">
											<input name="lebar" type="text" class="form-control" id="lebar" placeholder="Inci" value="<?php if ($cek1>0) {
                        echo round($r1['cuttablewidth']);
                    } else {
                        echo $rw[warna];
                    }?>">
										</div>
										<div class="col-sm-2">
											<input name="gramasi" type="text" class="form-control" id="gramasi" placeholder="Gr/M2" value="<?php if ($cek1>0) {
                        echo round($r1['weight']);
                    } else {
                        echo $rw[warna];
                    }?>">
										</div>
									</div>
									<?php
                    $bng=mssql_query("SELECT CAST(SODetailsAdditional.Note AS NVARCHAR(255)) as note from Joborders
    left join processcontrolJO on processcontrolJO.joid = Joborders.id
    left join SODetailsAdditional on processcontrolJO.sodid=SODetailsAdditional.sodid
WHERE  JobOrders.documentno='$_GET[idk]' and processcontrolJO.pcid='$r1[pcid]'", $conn);
                    $r3=mssql_fetch_array($bng);
                ?>
									<div class="form-group">
										<label for="benang" class="col-sm-2 control-label">Benang</label>
										<div class="col-sm-8">
											<textarea name="benang" rows="6" class="form-control" id="benang" placeholder="Benang"><?php echo htmlentities($r3[note], ENT_QUOTES);?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label for="cocok_warna" class="col-sm-2 control-label">Cocok Warna</label>
										<div class="col-sm-8">
											<input name="cocok_warna" type="text" class="form-control" id="cocok_warna" placeholder="Cocok Warna" value="<?php if ($r1['Flag']==" 1") { echo "Original Color" ; } elseif ($r1['Flag']=="2" ) { echo "Color LD" ; } else { echo
											  $r1['OtherDesc']; }?>" >
										</div>
									</div>
									<div class="form-group">
										<label for="qty" class="col-sm-2 control-label">Qty Order</label>
										<div class="col-sm-3">
											<input name="qty" type="text" class="form-control" id="qty" placeholder="Qty Order">
										</div>
									</div>
									<!--
                <div class="form-group">
                  <label for="matcher" class="col-sm-2 control-label">Matcher</label>
                  <div class="col-sm-3">
                    <input name="matcher" type="text" class="form-control" id="matcher" placeholder="Matcher" required >
                  </div>
                </div>
                <div class="form-group">
                <label for="target" class="col-sm-2 control-label">Target</label>
                <div class="col-sm-2">
                <div class="input-group date">
                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                <input type="text" name="target" class="form-control pull-right" id="datepicker" placeholder="Target" required />
                </div>
                </div>
                </div>
                <div class="form-group">
                  <label for="gramasia" class="col-sm-2 control-label">Gramasi Aktual</label>
                  <div class="col-sm-2">
                    <input name="lebara" type="text" class="form-control" id="lebara" placeholder="Inci" value="">
                  </div>
                  <div class="col-sm-2">
                    <input name="gramasia" type="text" class="form-control" id="gramsia" placeholder="Gr/M2" value="">
                  </div>
                </div>
                <div class="form-group">
                  <label for="ciewi" class="col-sm-2 control-label">CIE WI</label>
                  <div class="col-sm-3">
                    <input name="ciewi" type="text" class="form-control" id="ciewi" placeholder="CIE WI" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="cietint" class="col-sm-2 control-label">CIE TINT</label>
                  <div class="col-sm-3">
                    <input name="cietint" type="text" class="form-control" id="cietint" placeholder="CIE TINT" >
                  </div>
                </div>
                -->
									<div class="form-group">
										<label for="lampu" class="col-sm-2 control-label">Lampu</label>
										<div class="col-sm-2">
											<div class="checkbox">
												<label>
													<input type="checkbox" value="1" name="ck1"> D65
												</label>
												<label>
													<input type="checkbox" value="1" name="ck2"> F02
												</label>
												<label>
													<input type="checkbox" value="1" name="ck3"> F11
												</label>
												<label>
													<input type="checkbox" value="1" name="ck4"> U35
												</label>
												<label>
													<input type="checkbox" value="1" name="ck5"> A &nbsp;&nbsp;&nbsp;
												</label>
												<label>
													<input type="checkbox" value="1" name="ck6"> R. Light
												</label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="proses" class="col-sm-2 control-label">Proses</label>
										<div class="col-sm-3">
											<div class="checkbox">
												<label>
													<input type="checkbox" value="1" name="ckpro1">
													Greige</label>
												<label>
													<input type="checkbox" value="1" name="ckpro2">
													Bleaching&nbsp;&nbsp;</label>
												<label>
													<input type="checkbox" value="1" name="ckpro3">
													Non H2O2</label>
												<label>
													<input type="checkbox" value="1" name="ckpro4">
													Preset</label>
												<label>
													<input type="checkbox" value="1" name="ckpro5">
													Non Preset</label>
												<label>
													<input type="checkbox" value="1" name="ckpro6">
													Peach</label>
											</div>
										</div>
									</div>
									<div class="box-footer">
										<div class="col-sm-2">
											<button type="submit" class="btn btn-block btn-social btn-linkedin" name="simpan" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
										</div>
									</div>
								</div>

							</form>
							<!-- /.box-body -->
						</div>
						<!-- /.tab-pane -->
						<div class="tab-pane" id="tab_2">
							<?php
   $datatmp=mysql_query("SELECT * FROM tbl_matching WHERE no_order='$_GET[idk]' ORDER BY id DESC");
    $no=1;
    $n=1;
    $c=0;
     ?>
							<table width="100%" border="0" id="example2" class="table table-hover display" cellspacing="0">
								<thead class="btn-primary">
									<tr>
										<th>
											<div align="center">No Resep</div>
										</th>
										<th>
											<div align="center">Langganan</div>
										</th>
										<th>
											<div align="center">No. PO</div>
										</th>
										<th>
											<div align="center">No. Order</div>
										</th>
										<th>
											<div align="center">Item</div>
										</th>
										<th>
											<div align="center">Kain</div>
										</th>
										<th>
											<div align="center">Matcher</div>
										</th>
										<th>
											<div align="center">Warna</div>
										</th>
										<th>
											<div align="center">No. Warna</div>
										</th>
										<th>
											<div align="center">Aksi</div>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
      $col=0;
   while ($rowd=mysql_fetch_array($datatmp)) {
       $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
       $sjml=mysql_query(" SELECT count(*) as jml FROM `tbl_matching_detail` a
	   INNER JOIN `tbl_matching` b ON b.id=a.id_matching
	   WHERE b.no_resep='$rowd[no_resep]' ");
       $row=mysql_fetch_array($sjml); ?>
									<tr bgcolor="<?php echo $bgcolor; ?>">
										<td><a href="?p=form-matching-detail&noresep=<?php echo $rowd[no_resep]; ?>&id=<?php echo $rowd[id]; ?>">
												<?php echo $rowd[no_resep]; ?><span class="label label-danger">
													<?php echo $row[jml]; ?></span></a></td>
										<td>
											<?php echo $rowd[langganan]; ?>
										</td>
										<td align="center">
											<?php echo $rowd[no_po]; ?>
										</td>
										<td align="center">
											<?php echo $rowd[no_order]; ?>
										</td>
										<td align="center">
											<?php echo $rowd[no_item]; ?>
										</td>
										<td>
											<?php echo $rowd[jenis_kain]; ?>
										</td>
										<td>
											<?php echo $rowd[matcher]; ?>
										</td>
										<td>
											<?php echo $rowd[warna]; ?>
										</td>
										<td>
											<?php echo $rowd[no_warna]; ?>
										</td>
										<td>
											<div align="center"><a href="pages/cetak/matching.php?idkk=<?php echo $rowd[no_resep]; ?>" class="btn btn-info" target="_blank"><i class="fa fa-print"></i> Cetak</a> </div>
										</td>
									</tr>
									<?php
   } ?>
								</tbody>
							</table>
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

</html>
