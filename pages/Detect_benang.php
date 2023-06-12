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
    if ($_GET['idk'] != "") {
        $sqlLot = sqlsrv_query($conn,"SELECT
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
				x.SODID, x.PCBID");
        $sLot = sqlsrv_fetch_array($sqlLot);

        $cLot = sqlsrv_num_rows($sqlLot);
        $child = $sLot['ChildLevel'];

        if ($child > 0) {
            $sqlgetparent = sqlsrv_query($conn,"select ID,LotNo from ProcessControlBatches where ID='$sLot[RootID]' and ChildLevel='0'");
            $rowgp = sqlsrv_fetch_assoc($sqlgetparent);

            $nomLot = $rowgp['LotNo'];
            $nomorLot = "$nomLot/K$sLot[ChildLevel]&nbsp;";
        } else {
            $nomorLot = $sLot['LotNo'];
        }

        $sqlLot1 = "Select count(*) as TotalLot From ProcessControlBatches where PCID='$sLot[PCID]' and RootID='0' and LotNo < '1000'";
        $qryLot1 = sqlsrv_query($conn,$sqlLot1) or die('A error occured : ');
        $rowLot = sqlsrv_fetch_assoc($qryLot1);

        $sqls = sqlsrv_query($conn,"select salesorders.customerid,salesorders.buyerid from Joborders
							left join salesorders on soid= salesorders.id
							where JobOrders.documentno='$_GET[idk]'");
        $ssr = sqlsrv_fetch_array($sqls);
        $cek = sqlsrv_num_rows($sqls);
        $lgn1 = sqlsrv_query($conn,"select partnername from partners where id='$ssr[customerid]'");
        $ssr1 = sqlsrv_fetch_array($lgn1);
        $lgn2 = sqlsrv_query($conn,"select partnername from partners where id='$ssr[buyerid]'");
        $ssr2 = sqlsrv_fetch_array($lgn2);
    }
    //

    ?>
    <?php
    $sqljkd = sqlsrv_query($conn,"select processcontrol.id as pcid,processcontrolJO.SODID,salesorders.ponumber,joborders.documentno,
    processcontrol.productid,salesorders.customerid,CONVERT(varchar(10), SODetails.RequiredDate, 121) as RequiredDate,
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
    where productmaster.id='$_GET[iditem]' and processcontrol.productid='$_GET[iditem]' and JobOrders.documentno='$_GET[idk]' ");
    $r1 = sqlsrv_fetch_array($sqljkd);
    $cek1 = sqlsrv_num_rows($sqljkd);
    ?>

    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">Deteksi Benang</a></li>
                    <li class="list"><a href="#tab_2" data-toggle="tab">Hasil Celup</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
                            <div class="box-body">
                                <!-- <input type="hidden" value="<php echo $nourut; ?>" id="shadow_no_resep" name="shadow_no_resep"> -->
                                <div id="Matching_ulang_perbaikan">
                                    <div class="form-group">
                                        <div class="input-group col-md-4" style="margin-left: 200px;">
                                            <span class="input-group-btn">
                                                <a class="btn btn-success" href="index1.php?p=Detect_benang"><span class="glyphicon glyphicon-repeat" aria-hidden="true">
                                                    </span> Refresh-Page!</a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="order" class="col-sm-2 control-label">No Order</label>
                                        <div class="col-sm-4">
                                            <input name="no_order" placeholder="No order ..." type="text" class="form-control ordercuy" id="order" onchange="window.location='?p=Detect_benang&idk='+this.value+'&Dystf=1'" value="<?php if ($_GET['idk'] != "") {
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
                                            <select name="no_item" class="form-control selectNoItem" id="no_item" onchange="window.location='?p=Detect_benang&idk=<?php echo $_GET['idk']; ?>&iditem='+this.value+'&Dystf=1'" required style="width: 400px;">
                                                <?php
                                                $sqljk = sqlsrv_query($conn,"select productmaster.id,productpartner.productcode,productmaster.color,colorno,hangerno from Joborders
                                                                        left join salesorders on soid= salesorders.id
                                                                        left join SODetails on SalesOrders.id=SODetails.SOID
                                                                        left join productmaster on productmaster.id= SODetails.productid
                                                                        left join productpartner on productpartner.productid= SODetails.productid
                                                                        where JobOrders.documentno='$_GET[idk]'
                                                                        GROUP BY productmaster.id,productpartner.productcode,productmaster.color,
                                                                        productmaster.colorno,productmaster.hangerno");
                                                ?>
                                                <option value="">Pilih</option>
                                                <?php while ($r = sqlsrv_fetch_array($sqljk)) {
                                                ?>
                                                    <option value="<?php echo $r['id']; ?>" <?php if ($_GET['iditem'] == $r['id']) {
                                                                                                echo "SELECTED";
                                                                                            } ?>>
                                                        <?php echo $r['hangerno'] . "-" . $r['colorno'] . " | " . $r['color']; ?>
                                                    </option>
                                                <?php
                                                } ?>
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
                                    <?php $ko = sqlsrv_query($conn,"select  ko.KONo from
                                                            ProcessControlJO pcjo inner join
                                                            ProcessControl pc on pcjo.PCID = pc.ID left join
                                                            KnittingOrders ko on pc.CID = ko.CID and pcjo.KONo = ko.KONo
                                                            where
                                                            pcjo.PCID = '$r1[pcid]'
                                                            group by ko.KONo");
                                    $r2 = sqlsrv_fetch_array($ko);
                                    ?>
                                    <div class="form-group">
                                        <label for="no_po" class="col-sm-2 control-label">PO Greige</label>

                                        <div class="col-sm-4">
                                            <input name="no_po" type="text" class="form-control" id="no_po" placeholder="No PO" value="<?php echo $r2['KONo']; ?>">
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
                                        <label for="no_warna" class="col-sm-2 control-label">No Warna</label>
                                        <div class="col-sm-6">
                                            <input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="No Warna" value="<?php if ($cek1 > 0) {
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
                                    $bng = sqlsrv_query($conn,"SELECT CAST(SODetailsAdditional.Note AS NVARCHAR(255)) as note from Joborders
                                                        left join processcontrolJO on processcontrolJO.joid = Joborders.id
                                                        left join SODetailsAdditional on processcontrolJO.sodid=SODetailsAdditional.sodid
                                                        WHERE  JobOrders.documentno='$_GET[idk]' and processcontrolJO.pcid='$r1[pcid]'");
                                    $r3 = sqlsrv_fetch_array($bng);
                                    ?>
                                    <div class="form-group">
                                        <label for="benang" class="col-sm-2 control-label">Benang</label>
                                        <div class="col-sm-8">
                                            <textarea name="benang" rows="6" class="form-control" id="benang" placeholder="Benang"><?php echo htmlentities($r3['note'], ENT_QUOTES); ?></textarea>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="tab_2">
                        <h5 class="text-center" style="font-weight: bold; border-bottom: solid #F1F1F1 1px; padding:10px;">500 Hasil Celup terbaru Dengan Rcode-Laborat</h5>
                        <table id="table_hasil_celup" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead class="bg-primary">
                                <th>No.</th>
                                <th>No .Order</th>
                                <th>No .KK</th>
                                <th>Rcode</th>
                                <th>Lot</th>
                                <th>Qty</th>
                                <th>Loading</th>
                                <th>L:R</th>
                                <th>MC</th>
                                <th>Kesetabilan</th>
                                <th>Proses</th>
                                <th>Keterangan</th>
                                <th>Waktu</th>
                                <th>Bon Resep</th>
                            </thead>
                            <tbody>
                                <?php
                                $con1=mysqli_connect("10.0.1.91","dit","4dm1n");
                                $i = 1;
                                $sql_celup = mysqli_query($con1,"SELECT b.id, c.no_order, b.rcode, b.nokk, c.lot, b.k_resep, b.proses, b.lama_proses, b.status , c.no_resep, d.l_r, c.no_mesin, d.bruto, c.loading, b.tgl_buat
                                FROM db_dying.tbl_hasilcelup b
                                join db_laborat.tbl_status_matching a on a.idm = b.rcode
                                join db_dying.tbl_schedule c on b.nokk = c.nokk and b.proses = c.proses
                                join db_dying.tbl_montemp d on c.nokk = d.nokk
                                where b.rcode != '' and b.rcode is not null group by b.nokk order by b.tgl_update desc limit 512 "); ?>
                                <?php while ($row = mysqli_fetch_array($sql_celup)) { ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row['no_order'] ?></td>
                                        <td><?php echo $row['nokk'] ?></td>
                                        <td><?php echo $row['rcode'] ?></td>
                                        <td><?php echo $row['lot'] ?></td>
                                        <td><?php echo $row['bruto'] ?> Kg</td>
                                        <td><?php echo $row['loading'] ?>%</td>
                                        <td><?php echo $row['l_r'] ?></td>
                                        <td><?php echo $row['no_mesin'] ?></td>
                                        <td><?php echo $row['k_resep'] ?></td>
                                        <td><?php echo $row['proses'] ?></td>
                                        <td><?php echo $row['status'] ?></td>
                                        <td><?php echo $row['lama_proses'] ?></td>
                                        <td><?php echo '<a href="javascript:void(0)" data="pages/cetak/simpan_cetak.php?kk=' . $row["nokk"] . '&g=1" class="btn btn-xs btn-info bon_resep">Resep</a>' ?></td>
                                    </tr>
                                <?php $i++;
                                } ?>
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
        var dataTable = $('#table_hasil_celup').DataTable({
            responsive: true,
            "pageLength": 20,
            "ordering": false,
            "columnDefs": [{
                "className": "text-center",
                "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
            }],
            createdRow: function(row, data, rowIndex) {
                $.each($('td', row), function(colIndex) {
                    if (colIndex == 8) {
                        $(this).attr('data-name', 'edit_kesetabilan');
                        $(this).attr('class', 'edit_kesetabilan text-center text-primary');
                        $(this).attr('data-type', 'select');
                        $(this).attr('data-pk', data[0]);
                    }
                });
            }
        });

        new $.fn.dataTable.FixedHeader(dataTable);



        $(document).on('click', '.bon_resep', function() {
            var url_bon = $(this).attr('data');
            centeredPopup(url_bon, 'myWindow', '800', '400', 'yes');
        })
    });

    function centeredPopup(url, winName, w, h, scroll) {
        LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
        TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
        settings =
            'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable'
        popupWindow = window.open(url, winName, settings)
    }
</script>

</html>