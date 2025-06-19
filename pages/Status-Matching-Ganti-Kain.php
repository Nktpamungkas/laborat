<?php
    $Awal	= isset($_POST['awal']) ? $_POST['awal'] : '';
    $Akhir	= isset($_POST['akhir']) ? $_POST['akhir'] : '';
    $Order	= isset($_POST['order']) ? $_POST['order'] : '';
    $Hanger	= isset($_POST['hanger']) ? $_POST['hanger'] : '';
    $Masalah= isset($_POST['masalah']) ? $_POST['masalah'] : '';
    $Dept	= isset($_POST['dept']) ? $_POST['dept'] : '';	
?>

<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"> Filter Laporan Ganti Kain</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
  <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
    <div class="box-body">
      <div class="form-group">
        <div class="col-sm-3">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="awal" type="text" class="form-control pull-right" id="tglAwal" placeholder="Tanggal Awal" value="<?php echo $Awal; ?>" autocomplete="off"/>
          </div>
        </div>
        <!-- /.input group -->
      </div>
      <div class="form-group">
        <div class="col-sm-3">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="akhir" type="text" class="form-control pull-right" id="tglAkhir" placeholder="Tanggal Akhir" value="<?php echo $Akhir;  ?>" autocomplete="off"/>
          </div>
        </div>
        <!-- /.input group -->
      </div>
	  <div class="form-group">
        <div class="col-sm-3">
            <input name="order" type="text" class="form-control pull-right" id="order" placeholder="No Order" value="<?php echo $Order;  ?>" autocomplete="off"/>
          </div>
        <!-- /.input group -->
      </div>
	  <div class="form-group">
        <div class="col-sm-3">
            <input name="hanger" type="text" class="form-control pull-right" id="hanger" placeholder="No Hanger" value="<?php echo $Hanger;  ?>" autocomplete="off"/>
          </div>
        <!-- /.input group -->
      </div>
	  <div class="form-group">
        <div class="col-sm-3">
            <select class="form-control" name="dept" readonly>
                <option value="LAB" selected>LAB</option>
                </select>
            <input type="hidden" name="dept" value="LAB">
        </div>
        <!-- /.input group -->
      </div>	
	  <div class="form-group">
        <div class="col-sm-3">
            <input name="masalah" type="text" class="form-control pull-right" id="masalah" placeholder="Masalah" value="<?php echo $Masalah;  ?>" autocomplete="off"/>
          </div>
        <!-- /.input group -->
      </div>	
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <div class="col-sm-2">
        <button type="submit" class="btn btn-block btn-social btn-linkedin btn-sm" name="save" style="width: 60%">Search <i class="fa fa-search"></i></button>
      </div>
    </div>
    <!-- /.box-footer -->
  </form>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Data Ganti Kain</h3><br>
        <?php if($_POST['awal']!="") { ?><b>Periode: <?php echo $_POST['awal']." to ".$_POST['akhir']; ?></b>
		<?php } ?>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped nowrap" id="example3" style="width:100%">
        <thead class="bg-blue">
          <tr>
            <th><div align="center">No</div></th>
            <!-- <th><div align="center">&nbsp;&nbsp;&nbsp; Aksi &nbsp;&nbsp;&nbsp;&nbsp;</div></th> -->
            <th><div align="center">Tgl</div></th>
            <th><div align="center">Kategori</div></th>
            <th><div align="center">Prod. Order</div></th>
            <th><div align="center">Demand</div></th>
            <th><div align="center">Langganan</div></th>
            <th><div align="center">PO</div></th>
            <th><div align="center">Order</div></th>
            <th><div align="center">Hanger</div></th>
            <th><div align="center">Jenis Kain</div></th>
            <th><div align="center">Lebar &amp; Gramasi</div></th>
            <th><div align="center">Lot</div></th>
            <th><div align="center">Delivery</div></th>
            <th><div align="center">Warna</div></th>
            <th><div align="center">Qty Order</div></th>
            <th><div align="center">Qty Kirim</div></th>
            <th><div align="center">Qty Claim</div></th>
            <th><div>
              <div align="center">T Jawab 1</div>
            </div></th>
            <th><div>
              <div align="center">Qty 1</div>
            </div></th>
            <th><div>
              <div align="center">T Jawab 2</div>
            </div></th>
            <th><div>
              <div align="center">Qty 2</div>
            </div></th>
            <th><div>
              <div align="center">T Jawab 3</div>
            </div></th>
            <th><div>
              <div align="center">Qty 3</div>
            </div></th>
            <th><div>
              <div align="center">T Jawab 4</div>
            </div></th>
            <th><div>
              <div align="center">Qty 4</div>
            </div></th>
            <th><div>
              <div align="center">T Jawab 5</div>
            </div></th>
            <th><div>
              <div align="center">Qty 5</div>
            </div></th>
            <th><div align="center">Penyebab</div></th>
            <th><div align="center">Masalah</div></th>
            <th><div align="center">Ket</div></th>
            <th><div align="center">PIC Lab</div></th>
            <th><div align="center">Status</div></th>
            <th><div align="center">Aksi</div></th>
            </tr>
        </thead>
        <tbody>
          <?php
	$no=1;
			if($Awal!="" and $Dept!=""){ $Where =" WHERE DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' AND (dept= '$Dept' OR t_jawab='$Dept' OR t_jawab1='$Dept' OR t_jawab2='$Dept' OR t_jawab3='$Dept' OR t_jawab4='$Dept') "; }
			else
			if($Awal!=""){ $Where =" WHERE DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' "; }
			else
			if($Dept!=""){ $Where =" WHERE dept= '$Dept' OR t_jawab='$Dept' OR t_jawab1='$Dept' OR t_jawab2='$Dept' OR t_jawab3='$Dept' OR t_jawab4='$Dept'"; }
			else
			if($Order!=""){ $Where =" WHERE no_order= '$Order' "; }
			else
			if($Hanger!=""){ $Where =" WHERE no_hanger= '$Hanger' "; }
			else
			if($Masalah!=""){ $Where =" WHERE masalah LIKE '%$Masalah%' "; }
			else
			if($Awal=="" and $Order=="" and $Hanger=="" and $Masalah=="" and $Dept==""){ $Where =" WHERE DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' "; }
			$qry1=mysqli_query($cona,"SELECT * FROM tbl_gantikain $Where ORDER BY id ASC");
			while($row1=mysqli_fetch_array($qry1)){
			$sqlgk=mysqli_query($cona," SELECT * FROM tbl_bonkain WHERE id_nsp='$row1[id]' ORDER BY no_bon ASC");
  			$rgk=mysqli_num_rows($sqlgk);
			$rg=mysqli_fetch_array($sqlgk);	
			$qty1 = $rg['kg_bruto']*($row1['persen']/100);
			$qty2 = $rg['kg_bruto']*($row1['persen1']/100);	
			$qty3 = $rg['kg_bruto']*($row1['persen2']/100);	
			$qty4 = $rg['kg_bruto']*($row1['persen3']/100);	
			$qty5 = $rg['kg_bruto']*($row1['persen4']/100);	

            $getStatus = mysqli_query($con, "SELECT * FROM status_matching_ganti_kain WHERE id_gantikain = '$row1[id]'");
            $statusRow = mysqli_fetch_assoc($getStatus);

            $selectedPicLab = $statusRow['pic_lab'] ?? '';
            $selectedStatus = $statusRow['status_lab'] ?? '';
            $tombolLabel = $statusRow ? 'Perbarui' : 'Simpan';
            $tombolClass = $statusRow ? 'btn-warning' : 'btn-success';

			if($row1['kategori']=="0"){
				$kategori = " <span class='label label-info'>Internal</span> ";
			}else if($row1['kategori']=="1"){
				$kategori = " <span class='label label-warning'>External</span> ";
			}else if($row1['kategori']=="2"){
				$kategori = " <span class='label label-danger'>FOC</span> ";
			}	
			$dtArr=$row1['sebab'];
			$data = explode(",",$dtArr);
			if(in_array("Man",$data)){$sebab.=" <span class='label label-info'>Man</span> ";}
			if(in_array("Methode",$data)){$sebab.=" <span class='label label-warning'>Methode</span> ";}
			if(in_array("Machine",$data)){$sebab.=" <span class='label label-danger'>Machine</span> ";}
			if(in_array("Material",$data)){$sebab.=" <span class='label label-primary'>Material</span> ";}
			if(in_array("Environment",$data)){$sebab.=" <span class='label label-success'>Environment</span> ";}	
		 ?>
          <tr bgcolor="<?php echo $bgcolor; ?>">
            <td align="center"><?php echo $no; ?></td>
            <!-- <td align="center"><div class="btn-group"><a href="index1.php?p=input-bon-kain&id=<?php echo $row1['id']; ?>" class="btn btn-warning btn-xs <?php if($_SESSION['akses']=='biasa'){ echo "disabled"; } ?>" target="_blank"><i class="fa fa-plus"></i> </a>
     <a href="EditBon-<?php echo $row1['id']; ?>" class="btn btn-info btn-xs <?php if($_SESSION['akses10']=='biasa'){ echo "disabled"; }else{ echo "disabled"; } ?>" target="_blank"><i class="fa fa-edit"></i> </a>
     <a href="#" class="btn btn-danger btn-xs <?php if($_SESSION['akses']=='biasa' or $rgk>0){ echo "disabled"; } ?>" onclick="confirm_delete('index1.php?p=hapusdatagantikain&id=<?php echo $row1['id']; ?>');"><i class="fa fa-trash"></i> </a></div></td> -->
            <td align="center"><?php echo $row1['tgl_buat'];?></td>
            <td align="center"><?php echo $kategori;?></td>
            <td><?php echo $row1['nokk'];?></td>
            <td><?php echo $row1['nodemand'];?></td>
            <td><?php echo $row1['langganan'];?></td>
            <td align="center"><?php echo $row1['po'];?></td>
            <td align="center"><?php echo $row1['no_order'];?></td>
            <td align="center" valign="top"><?php echo $row1['no_hanger'];?></td>
            <td><?php echo $row1['jenis_kain'];?></td>
            <td align="center"><?php echo $row1['lebar']."x".$row1['gramasi'];?></td>
            <td align="center"><?php echo $row1['lot'];?></td>
            <td align="center"><?php echo $row1['tgl_delivery'];?></td>
            <td align="center"><?php echo $row1['warna'];?></td>
            <td align="right"><?php echo $row1['qty_order'];?></td>
            <td align="right"><?php echo $row1['qty_kirim'];?></td>
            <td align="right"><?php echo $row1['qty_claim'];?></td>
            <td align="center"><?php echo $row1['t_jawab'];?></td>
            <td align="right"><?php echo $qty1;?></td>
            <td align="center"><?php echo $row1['t_jawab1'];?></td>
            <td align="right"><?php echo $qty2;?></td>
            <td align="center"><?php echo $row1['t_jawab2'];?></td>
            <td align="right"><?php echo $qty3;?></td>
            <td align="center"><?php echo $row1['t_jawab3'];?></td>
            <td align="right"><?php echo $qty4;?></td>
            <td align="center"><?php echo $row1['t_jawab4'];?></td>
            <td align="right"><?php echo $qty5;?></td>
            <td align="center"><?php echo $sebab;?></td>
            <td><?php echo $row1['masalah'];?></td>
            <td><?php echo $row1['ket'];?></td>
            <?php 
                $qStatus = mysqli_query($con, "SELECT pic_lab, status_lab FROM status_matching_ganti_kain WHERE id_gantikain = '$row1[id]' LIMIT 1");
                $rStatus = mysqli_fetch_assoc($qStatus);
                $selectedPicLab = isset($rStatus['pic_lab']) ? $rStatus['pic_lab'] : '';
                $selectedStatus = isset($rStatus['status_lab']) ? $rStatus['status_lab'] : '';
            ?>

            <!-- Status dan PIC -->
            <td>
                <select class="form-control input-sm pic-lab" name="pic_lab" required>
                    <option value="">-- Pilih PIC --</option>
                    <option value="Cecen" <?php if ($selectedPicLab == 'Cecen') echo 'selected'; ?>>Cecen</option>
                    <option value="Ridho" <?php if ($selectedPicLab == 'Ridho') echo 'selected'; ?>>Ridho</option>
                    <option value="Riyan" <?php if ($selectedPicLab == 'Riyan') echo 'selected'; ?>>Riyan</option>
                    <option value="Flavia" <?php if ($selectedPicLab == 'Flavia') echo 'selected'; ?>>Flavia</option>
                </select>
                </td>
                <td>
                <select class="form-control input-sm status-lab" name="status_lab" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="OK" <?php if ($selectedStatus == 'OK') echo 'selected'; ?>>OK</option>
                    <option value="Matching Ulang" <?php if ($selectedStatus == 'Matching Ulang') echo 'selected'; ?>>Matching Ulang</option>
                </select>
                </td>
                <td>
                <button class="btn <?php echo $tombolClass; ?> btn-xs save-status" data-id="<?php echo $row1['id']; ?>">
                    <i class="fa fa-save"></i> <?php echo $tombolLabel; ?>
                </button>
            </td>

            </tr>
          <?php	$no++;  } ?>
        </tbody>
      </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal_del" tabindex="-1" >
  <div class="modal-dialog modal-sm" >
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
        <button type="button" class="close"  data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" style="text-align:center;">Are you sure to delete all data ?</h4>
      </div>

      <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
        <a href="#" class="btn btn-danger" id="delete_link">Delete</a>
        <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>	
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
    function confirm_delete(delete_url)
    {
      $('#modal_del').modal('show', {backdrop: 'static'});
      document.getElementById('delete_link').setAttribute('href' , delete_url);
    }
</script>	
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<script>
    $(document).ready(function () {
        $('#tglAwal').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        });
        $('#tglAkhir').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        });
        $('#tglAwal').on('changeDate', function () {
            console.log('Tanggal Awal:', $(this).val());
        });
        console.log('Datepicker status:', $('#tglAwal').data('datepicker'));
    });
  </script>

<script>
    $(document).ready(function() {
        $('.save-status').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var row = btn.closest('tr');
            var id = btn.data('id');
            var pic_lab = row.find('.pic-lab').val();
            var status_lab = row.find('.status-lab').val();

            // Validasi
            if (!pic_lab || !status_lab) {
                alert("Harap pilih PIC Lab dan Status terlebih dahulu.");
                return;
            }

            $.ajax({
                url: 'pages/ajax/save_status_matching_ganti_kain.php',
                type: 'POST',
                data: {
                id_gantikain: id,
                pic_lab: pic_lab,
                status_lab: status_lab
                },
                success: function(response) {
                btn.removeClass('btn-success btn-warning').addClass('btn-default');
                btn.html('<i class="fa fa-check"></i> Tersimpan');
                setTimeout(function() {
                    btn.removeClass('btn-default').addClass('btn-warning');
                    btn.html('<i class="fa fa-save"></i> Perbarui');
                }, 2000);
                },
                error: function(xhr) {
                alert('Gagal menyimpan: ' + xhr.responseText);
                }
            });
        });
    });
</script>
