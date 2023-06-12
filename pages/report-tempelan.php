<?php
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Bahan Baku Masuk</title>

  </head>

  <body>
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"> Filter Laporan Tempelan Laborat</h3>
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
                <input type="text" name="awal" class="form-control pull-right" id="datepicker" placeholder="Tanggal Awal" />
              </div>
            </div>
            <!-- /.input group -->
          </div>
          <div class="form-group">
            <div class="col-sm-3">
              <div class="input-group date">
                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                <input type="text" name="akhir" class="form-control pull-right" id="datepicker1" placeholder="Tanggal Akhir" />
              </div>
            </div>
            <!-- /.input group -->
          </div>
          <div class="form-group">
            <div class="col-sm-3">
              <input name="no_order" type="text" class="form-control" id="order" value="" placeholder="No Order">
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <div class="col-sm-2">
              <button type="submit" class="btn btn-block btn-social btn-linkedin btn-sm" name="save" style="width: 60%">Search <i class="fa fa-search"></i></button>
            </div>
          </div>
          <!-- /.box-footer -->

        </div>
      </form>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Form Tempelan</h3><br>
            <a href="#" class="btn btn-success pull-right"><span class="fa fa-file-excel-o"></span> Cetak</a>
            <br>
            <?php if ($_POST['awal']!="" and $_POST['akhir']!="") {
    ?><b>Periode:
              <?php echo $_POST['awal']." to ".$_POST['akhir']; ?></b>
            <?php
} ?>
            <?php if ($_POST['no_order']!="") {
        ?><b>No Order:
              <?php echo $_POST['no_order']; ?></b>
            <?php
    } ?>
          </div>
          <div class="box-body">
            <table id="example2" class="table table-hover display" width="100%">
              <thead class="bg-red">
                <tr>
                  <th width="38">
                    <div align="center">No</div>
                  </th>
                  <th width="215">
                    <div align="center">Detail</div>
                  </th>
                  <th width="224">
                    <div align="center">Tanggal</div>
                  </th>
                  <th width="314">
                    <div align="center">No Resep</div>
                  </th>
                  <th width="404">
                    <div align="center">Langganan</div>
                  </th>
                  <th width="215">
                    <div align="center">No PO</div>
                  </th>
                  <th width="215">
                    <div align="center">No Order</div>
                  </th>
                  <th width="215">
                    <div align="center">Item</div>
                  </th>
                  <th width="215">
                    <div align="center">Warna</div>
                  </th>
                  <th width="215">
                    <div align="center">Kain</div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php
  $sql=mysqli_query($con," SELECT * FROM tbl_tempelan WHERE tgl_buat BETWEEN '$_POST[awal]' AND '$_POST[akhir]' OR no_order='$_POST[no_order]' ORDER BY id ASC ");
  while ($r=mysqli_fetch_array($sql)) {
      $no++;
      $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite'; ?>
                <tr bgcolor="<?php echo $bgcolor; ?>">
                  <td align="center">
                    <?php echo $no; ?>
                  </td>
                  <td align="center"><a href="#" class="open_detail" id="<?php echo $r['id']; ?>">
                      <span class="pull-right badge bg-blue">Lihat</span>
                    </a></td>
                  <td align="center">
                    <?php echo $r['tgl_buat']; ?>
                  </td>
                  <td align="center">
                    <?php echo $r['no_resep']; ?>
                  </td>
                  <td>
                    <?php echo $r['langganan']; ?>
                  </td>
                  <td align="center">
                    <?php echo $r['no_po']; ?>
                  </td>
                  <td align="center">
                    <?php echo $r['no_order']; ?>
                  </td>
                  <td align="center">
                    <?php echo $r['no_item']."-".$r['no_warna']; ?>
                  </td>
                  <td align="center">
                    <?php echo $r['warna']; ?>
                  </td>
                  <td>
                    <?php echo $r['jenis_kain']; ?>
                  </td>
                </tr>
                <?php
  } ?>
              </tbody>

            </table>
            </form>

          </div>
        </div>
      </div>
    </div>
    <div id="Detail" class="modal fade modal-rotate-from-bottom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    </div>
  </body>

</html>
