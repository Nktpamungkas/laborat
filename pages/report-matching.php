<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Status Matching</title>
</head>
<style>
  td.details-control {
    background: url('bower_components/DataTable/img/details_open.png') no-repeat center center;
    cursor: pointer;
  }

  tr.shown td.details-control {
    background: url('bower_components/DataTable/img/details_close.png') no-repeat center center;
  }

  th {
    font-size: 10pt;
  }

  td {
    font-size: 10pt;
  }

  #Table-sm td,
  #Table-sm th {
    border: 0.1px solid #ddd;
  }

  #Table-sm th {
    color: black;
    background: #4CAF50;
  }

  #Table-sm tr:hover {
    background-color: rgb(151, 170, 212);
  }

  .input-xs {
    height: 22px !important;
    padding: 2px 5px;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 3px;
  }

  .input-group-xs>.form-control,
  .input-group-xs>.input-group-addon,
  .input-group-xs>.input-group-btn>.btn {
    height: 22px;
    padding: 1px 5px;
    font-size: 12px;
    line-height: 1.5;
  }

  div.dataTables_wrapper {
    width: 100%;
    margin: 0 auto;
  }
</style>

<body>
  <div class="row">
    <div class="box">
      <div class="box-header with-border">
        <div class="container-fluid">
          <form class="form-inline" method="POST" action="">
            <div class="form-group mb-2">
              <input type="text" class="form-control input-sm date-picker" name="date_start" id="date_start" value="<?php
                                                                                                                    if ($_POST['submit']) {
                                                                                                                      echo $_POST['date_start'];
                                                                                                                    } else {
                                                                                                                      echo date('Y-m-d');
                                                                                                                    } ?>">
            </div>
            <div class="form-group mb-2">
              <input type="text" class="form-control input-sm time-picker" name="time_start" id="time_start" value="<?php
                                                                                                                    if ($_POST['submit']) {
                                                                                                                      echo $_POST['time_start'];
                                                                                                                    } else {
                                                                                                                      echo "23:00";
                                                                                                                    } ?>" placeholder="00:00" maxlength="5">
            </div>
            <div class="form-group mb-2">
              <i class="fa fa-share" aria-hidden="true"></i>
            </div>
            <div class="form-group mx-sm-3 mb-2">
              <input type="text" class="form-control input-sm date-picker" name="date_end" id="date_end" value="<?php
                                                                                                                if ($_POST['submit']) {
                                                                                                                  echo $_POST['date_end'];
                                                                                                                } else {
                                                                                                                  echo date('Y-m-d', strtotime($day . ' + 1 day'));
                                                                                                                } ?>">
            </div>
            <div class="form-group mb-2">
              <input type="text" class="form-control input-sm time-picker" name="time_end" id="time_end" value="<?php
                                                                                                                if ($_POST['submit']) {
                                                                                                                  echo $_POST['time_end'];
                                                                                                                } else {
                                                                                                                  echo "23:00";
                                                                                                                } ?>" placeholder="00:00" maxlength="5">
            </div>
            <button type="submit" name="submit" value="search" class="btn btn-primary btn-sm mb-2"><i class="fa fa-search" aria-hidden="true"></i>
            </button>
          </form>
          <hr />
        </div>
        <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
          <table id="Table-sm" class="table table-sm display compact" style="width: 100%;">
            <thead>
              <tr class="bg-green">
                <th class="text-center">no_resep</th>
                <th class="text-center">no_order</th>
                <th class="text-center">no_po</th>
                <th class="text-center">langganan</th>
                <th class="text-center">no_item</th>
                <th class="text-center">jenis_kain</th>
                <th class="text-center">benang</th>
                <th class="text-center">cocok_warna</th>
                <th class="text-center">warna</th>
                <th class="text-center">no_warna</th>
                <th class="text-center">lebar</th>
                <th class="text-center">gramasi</th>
                <th class="text-center">qty_order</th>
                <th class="text-center">status_bagi</th>
                <th class="text-center">tgl_in</th>
                <th class="text-center">tgl_out</th>
                <th class="text-center">proses</th>
                <th class="text-center">buyer</th>
                <th class="text-center">tgl_delivery</th>
                <th class="text-center">note</th>
                <th class="text-center">jenis_matching</th>
                <th class="text-center">tgl_buat</th>
                <th class="text-center">created_by</th>
                <th class="text-center">tgl_update</th>
                <th class="text-center">last_update_by</th>
                <th class="text-center">grup</th>
                <th class="text-center">matcher</th>
                <th class="text-center">cek_warna</th>
                <th class="text-center">cek_dye</th>
                <th class="text-center">status</th>
                <th class="text-center">kt_status</th>
                <th class="text-center ">koreksi_resep 1</th>
                <th class="text-center ">koreksi_resep 1</th>
                <th class="text-center ">koreksi_resep 2</th>
                <th class="text-center ">koreksi_resep 2</th>
                <th class="text-center ">koreksi_resep 3</th>
                <th class="text-center ">koreksi_resep 3</th>
                <th class="text-center ">koreksi_resep 4</th>
                <th class="text-center ">koreksi_resep 4</th>
                <th class="text-center">percobaan_ke</th>
                <th class="text-center">percobaan_berapa_kali</th>
                <th class="text-center">benang_aktual</th>
                <th class="text-center">lebar_aktual</th>
                <th class="text-center">gramasi_aktual</th>
                <th class="text-center">ph</th>
                <th class="text-center">soaping_sh</th>
                <th class="text-center">soaping_tm</th>
                <th class="text-center">rc_sh</th>
                <th class="text-center">rc_tm</th>
                <th class="text-center">lr</th>
                <th class="text-center">cie_wi</th>
                <th class="text-center">cie_tint</th>
                <th class="text-center">spektro_r</th>
                <th class="text-center">ket</th>
                <th class="text-center">cside_c</th>
                <th class="text-center">cside_min</th>
                <th class="text-center">tside_c</th>
                <th class="text-center">tside_min</th>
                <th class="text-center">done_matching</th>
                <th class="text-center">created_at</th>
                <th class="text-center">created_by</th>
                <th class="text-center">edited_at</th>
                <th class="text-center">edited_by</th>
                <th class="text-center">target_selesai</th>
                <th class="text-center">mulai_by</th>
                <th class="text-center">mulai_at</th>
                <th class="text-center">selesai_by</th>
                <th class="text-center">selesai_at</th>
                <th class="text-center">approve_by</th>
                <th class="text-center">approve_at</th>
                <th class="text-center">approve</th>
                <th class="text-center">hold_at</th>
                <th class="text-center">hold_by</th>
                <th class="text-center">timer</th>
                <th class="text-center">why_batal</th>
                <th class="text-center">revisi_at</th>
                <th class="text-center">revisi_by</th>
                <th class="text-center">kadar_air</th>
                <th class="text-center">final_matcher</th>
                <th class="text-center ">colorist 1</th>
                <th class="text-center ">colorist 1</th>
                <th class="text-center ">colorist 2</th>
                <th class="text-center ">colorist 2</th>
                <th class="text-center ">colorist 3</th>
                <th class="text-center ">colorist 3</th>
                <th class="text-center ">colorist 4</th>
                <th class="text-center ">colorist 4</th>
                <th class="text-center">penanggung_jawab</th>
                <th class="text-center">bleaching_tm</th>
                <th class="text-center">bleaching_sh</th>
                <th class="text-center">second_lr</th>
                <th class="text-center">remark_dye</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $date = date('Y-m-d');
              $date_s = $_POST['date_start'];
              $date_e = $_POST['date_end'];
              $time_s = $_POST['time_start'];
              $time_e = $_POST['time_end'];

              if (empty($_POST['submit'])) {
                $sql = mysqli_query($con, "SELECT *,  a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by
                  FROM tbl_status_matching a
                  INNER JOIN tbl_matching b ON a.idm = b.no_resep
                  where a.status = 'selesai' and a.approve = 'TRUE' AND DATE_FORMAT(a.approve_at,'%Y-%m-%d') = '$date'
                  ORDER BY a.id desc limit 50");
              } else {
                $sql = mysqli_query($con, "SELECT *,  a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by
                                          FROM tbl_status_matching a
                                          INNER JOIN tbl_matching b ON a.idm = b.no_resep
                                          where a.status = 'selesai' and a.approve = 'TRUE' and
                                          DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') >= '$date_s $time_s' AND DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') < '$date_e $time_e'
                                          ORDER BY a.id desc");
                //				  $sql = mysqli_query($con,"SELECT *,  a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by
                //                                          FROM tbl_status_matching a
                //                                          INNER JOIN tbl_matching b ON a.idm = b.no_resep
                //                                          where a.status = 'selesai' and a.approve = 'TRUE' and
                //                                          DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') between '$date_s $time_s' AND '$date_e $time_e'
                //                                          ORDER BY a.id desc");
              }
              while ($r = mysqli_fetch_array($sql)) {
                $no++;
              ?>
                <tr>
                  <td> <?php echo $r['no_resep'] ?></td>
                  <td> <?php echo $r['no_order'] ?></td>
                  <td> <?php echo $r['no_po'] ?></td>
                  <td> <?php echo $r['langganan'] ?></td>
                  <td> <?php echo $r['no_item'] ?></td>
                  <td> <?php echo $r['jenis_kain'] ?></td>
                  <td> <?php echo $r['benang'] ?></td>
                  <td> <?php echo $r['cocok_warna'] ?></td>
                  <td> <?php echo $r['warna'] ?></td>
                  <td> <?php echo $r['no_warna'] ?></td>
                  <td> <?php echo $r['lebar'] ?></td>
                  <td> <?php echo $r['gramasi'] ?></td>
                  <td> <?php echo $r['qty_order'] ?></td>
                  <td> <?php echo $r['status_bagi'] ?></td>
                  <td> <?php echo $r['tgl_in'] ?></td>
                  <td> <?php echo $r['tgl_out'] ?></td>
                  <td> <?php echo $r['proses'] ?></td>
                  <td> <?php echo $r['buyer'] ?></td>
                  <td> <?php echo $r['tgl_delivery'] ?></td>
                  <td> <?php echo $r['note'] ?></td>
                  <td> <?php echo $r['jenis_matching'] ?></td>
                  <td> <?php echo $r['tgl_buat'] ?></td>
                  <td> <?php echo $r['created_by'] ?></td>
                  <td> <?php echo $r['tgl_update'] ?></td>
                  <td> <?php echo $r['last_update_by'] ?></td>
                  <td><?php echo $r['grp'] ?></td>
                  <td><?php echo $r['matcher'] ?></td>
                  <td><?php echo $r['cek_warna'] ?></td>
                  <td><?php echo $r['cek_dye'] ?></td>
                  <td><?php echo $r['status'] ?></td>
                  <td><?php echo $r['kt_status'] ?></td>
                  <td><?php echo $r['koreksi_resep'] ?></td>
                  <td><?php echo $r['koreksi_resep2'] ?></td>
                  <td><?php echo $r['koreksi_resep3'] ?></td>
                  <td><?php echo $r['koreksi_resep4'] ?></td>
                  <td><?php echo $r['koreksi_resep5'] ?></td>
                  <td><?php echo $r['koreksi_resep6'] ?></td>
                  <td><?php echo $r['koreksi_resep7'] ?></td>
                  <td><?php echo $r['koreksi_resep8'] ?></td>
                  <td><?php echo $r['percobaan_ke'] ?></td>
                  <td><?php echo $r['howmany_percobaan_ke'] ?></td>
                  <td><?php echo $r['benang_aktual'] ?></td>
                  <td><?php echo $r['lebar_aktual'] ?></td>
                  <td><?php echo $r['gramasi_aktual'] ?></td>
                  <td><?php echo $r['ph'] ?></td>
                  <td><?php echo $r['soaping_sh'] ?></td>
                  <td><?php echo $r['soaping_tm'] ?></td>
                  <td><?php echo $r['rc_sh'] ?></td>
                  <td><?php echo $r['rc_tm'] ?></td>
                  <td><?php echo $r['lr'] ?></td>
                  <td><?php echo $r['cie_wi'] ?></td>
                  <td><?php echo $r['cie_tint'] ?></td>
                  <td><?php echo $r['spektro_r'] ?></td>
                  <td><?php echo $r['ket'] ?></td>
                  <td><?php echo $r['cside_c'] ?></td>
                  <td><?php echo $r['cside_min'] ?></td>
                  <td><?php echo $r['tside_c'] ?></td>
                  <td><?php echo $r['tside_min'] ?></td>
                  <td><?php echo $r['done_matching'] ?></td>
                  <td><?php echo $r['created_at'] ?></td>
                  <td><?php echo $r['created_by'] ?></td>
                  <td><?php echo $r['edited_at'] ?></td>
                  <td><?php echo $r['edited_by'] ?></td>
                  <td><?php echo $r['target_selesai'] ?></td>
                  <td><?php echo $r['mulai_by'] ?></td>
                  <td><?php echo $r['mulai_at'] ?></td>
                  <td><?php echo $r['selesai_by'] ?></td>
                  <td><?php echo $r['selesai_at'] ?></td>
                  <td><?php echo $r['approve_by'] ?></td>
                  <td><?php echo $r['approve_at'] ?></td>
                  <td><?php echo $r['approve'] ?></td>
                  <td><?php echo $r['hold_at'] ?></td>
                  <td><?php echo $r['hold_by'] ?></td>
                  <td><?php echo $r['timer'] ?></td>
                  <td><?php echo $r['why_batal'] ?></td>
                  <td><?php echo $r['revisi_at'] ?></td>
                  <td><?php echo $r['revisi_by'] ?></td>
                  <td><?php echo $r['kadar_air'] ?></td>
                  <td><?php echo $r['final_matcher'] ?></td>
                  <td><?php echo $r['colorist1'] ?></td>
                  <td><?php echo $r['colorist2'] ?></td>
                  <td><?php echo $r['colorist3'] ?></td>
                  <td><?php echo $r['colorist4'] ?></td>
                  <td><?php echo $r['colorist5'] ?></td>
                  <td><?php echo $r['colorist6'] ?></td>
                  <td><?php echo $r['colorist7'] ?></td>
                  <td><?php echo $r['colorist8'] ?></td>
                  <td><?php echo $r['penanggung_jawab'] ?></td>
                  <td><?php echo $r['bleaching_tm'] ?></td>
                  <td><?php echo $r['bleaching_sh'] ?></td>
                  <td><?php echo $r['second_lr'] ?></td>
                  <td><?php echo $r['remark_dye'] ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  </div>
</body>
<script type="text/javascript">
  var spinner = new jQuerySpinner({
    parentId: 'block-full-page'
  });

  function disableScroll() {
    scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
      window.onscroll = function() {
        window.scrollTo(scrollLeft, scrollTop);
      };
  }

  function enableScroll() {
    window.onscroll = function() {};
  }

  function SpinnerShow() {
    spinner.show();
    disableScroll()
  }

  function SpinnerHide() {
    setTimeout(function() {
      spinner.hide();
      enableScroll();
      window.location.href = 'index1.php?p=Wait-approval';
    }, 1000);
  }
</script>
<script>
  $(document).ready(function() {
    $('#Table-sm thead tr').clone(true).appendTo('#Table-sm thead');
    $('#Table-sm thead tr:eq(1) th').each(function(i) {
      var title = $(this).text();
      if (i == 400) {
        $(this).html('');
      } else if (i == 3) {
        $(this).html('<input type="text" class="form-control input-xs" style="width: 100px;" placeholder="' + title + '" />');
      } else if (i == 5) {
        $(this).html('<input type="text" class="form-control input-xs" style="width: 200px;" placeholder="' + title + '" />');
      } else if (i == 6) {
        $(this).html('<input type="text" class="form-control input-xs" style="width: 200px;" placeholder="' + title + '" />');
      } else {
        $(this).html('<input type="text" class="form-control input-xs" style="width: 100px;" placeholder="Search ' + title + '" />');
      }

      $('input', this).on('keyup change', function() {
        if (table.column(i).search() !== this.value) {
          table
            .column(i)
            .search(this.value)
            .draw();
        }
      });
    });

    var table = $('#Table-sm').DataTable({
      "scrollX": true,
      "scrollY": true,
      orderCellsTop: true,
      pageLength: 3,
      "ordering": false,
      dom: 'Bfrtip',
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
      ],
      "columnDefs": [{
          "className": "align-center",
          "targets": []
        },
        {
          "targets": [],
          "orderable": false
        },
      ],
      "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        if (aData[38] == 'batal') {
          $('td', nRow).css('background-color', '#ff9494');
          $('td', nRow).css('color', 'black');
        } else {
          $('td', nRow).css('color', 'black');
        }
      },
    });
  });
</script>

</html>