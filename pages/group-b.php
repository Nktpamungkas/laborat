<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Group B</title>
</head>

<body>
  <?php
  $datauser = mysqli_query($con,"SELECT
    a.id,
   	a.idm,
   	a.matcher,
   	a.tgl_masuk,
	a.tgl_siap_kain,
	a.tgl_mulai,
	a.tgl_selesai,
   	a.`status`,
     a.kt_status,
   	a.grp,
   	b.langganan,
     b.no_order,
     b.no_item,
     b.warna,
     b.no_warna,
     b.jenis_kain
   FROM
   	tbl_status_matching a
   INNER JOIN tbl_matching b ON a.idm = b.no_resep
   WHERE grp='B' and a.status != 'selesai' and a.status != 'tutup'
   ORDER BY
   	a.id DESC");
  $no = 1;
  $n = 1;
  $c = 0;
  ?>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
        </div>
        <div class="box-body">
          <table id="example2" width="100%" class="table table-bordered table-hover display">
            <thead class="bg-green">
              <tr>
                <th width="5%">No</th>
                <th width="15%">Kode</th>
                <th width="13%">Status</th>
                <th width="15%">Ket. St</th>
                <th width="15%">Matcher</th>
                <th width="15%">Langganan</th>
                <th width="15%">No Order</th>
                <th width="15%">JenisKain</th>
                <th width="15%">Warna</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $col = 0;
              while ($rowd = mysqli_fetch_array($datauser)) {
                $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite'; ?>
                <tr bgcolor="<?php echo $bgcolor; ?>">
                  <td>
                    <?php echo $no; ?>
                  </td>
                  <td>
                    <?php echo $rowd['idm']; ?>
                  </td>
                  <td>
                    <span class="label
                    <?php if ($rowd['status'] == "buka" and $rowd['tgl_mulai'] == "") {
                      echo "label-warning";
                    } elseif ($rowd['status'] == "mulai") {
                      echo "label-info";
                    } elseif ($rowd['status'] == "batal") {
                      echo "bg-purple";
                    } elseif ($rowd['status'] == "tahan") {
                      echo "bg-black";
                    } elseif ($rowd['status'] == "selesai" and $rowd['tgl_selesai'] != "") {
                      echo "label-danger blink_me";
                    } elseif ($rowd['status'] == "buka" and $rowd['tgl_mulai'] != "") {
                      echo "label-success";
                    } else {
                      echo "label-default";
                    } ?>">
                      <?php if ($rowd['status'] == "buka" and $rowd['tgl_mulai'] == "") {
                        echo "Buka";
                      } elseif ($rowd['status'] == "mulai") {
                        echo $rowd['status'];
                      } elseif ($rowd['status'] == "batal") {
                        echo $rowd['status'];
                      } elseif ($rowd['status'] == "tahan") {
                        echo $rowd['status'];
                      } elseif ($rowd['status'] == "selesai" and $rowd['tgl_selesai'] != "") {
                        echo "Selesai";
                      } elseif ($rowd['status'] == "buka" and $rowd['tgl_mulai'] != "") {
                        echo "Sedang Jalan";
                      } else {
                        echo "label-default";
                      } ?></span>
                  </td>
                  <td><span class="label <?php if ($rowd['kt_status'] == "Urgent") {
                                            echo "label-warning blink_me";
                                          } else {
                                            echo "label-success";
                                          } ?>">
                      <?php echo $rowd['kt_status']; ?></span>
                  </td>
                  <td>
                    <?php echo $rowd['matcher']; ?>
                  </td>
                  <td>
                    <?php echo $rowd['langganan']; ?>
                  </td>
                  <td>
                    <?php echo $rowd['no_order']; ?>
                  </td>
                  <td>
                    <?php echo $rowd['jenis_kain']; ?>
                  </td>
                  <td>
                    <?php echo $rowd['warna']; ?>
                  </td>
                  <td><a href="#" id='<?php echo $rowd['id'] ?>' class="btn btn-sm btn-info groupb_edit"><i class="fa fa-edit"></i> </a></th>
                </tr>
              <?php
                $no++;
              } ?>
            </tbody>
          </table>
          <!-- Modal Popup untuk Edit-->
          <div id="GroupBEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

          </div>
</body>

</html>