<?php
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tempelan Laborat</title>

  </head>

  <body>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Form Tempelan Cetak</h3>
          </div>
          <div class="box-body">
						<div class="callout callout-info">
						                <h4>No. Tempelan <?php echo $_GET['idkk']; ?></h4>

						                <p>Data telah Tersimpan</p>
						</div>
          </div>
					<div class="box-footer">
					<a href="?p=Form-Tempelan" class="btn btn-primary">Kembali</a>
					<a href="pages/cetak/tempelan.php?idkk=<?php echo $_GET['idkk']; ?>" target="_blank" class="btn btn-success pull-right"><span class="fa fa-print"></span> Cetak</a>
					</div>
        </div>
      </div>
    </div>

  </body>

</html>
