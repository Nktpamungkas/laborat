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
    vertical-align: middle;
    text-align: center;
  }

  #Table-sm th {
    color: black;
    background: #4CAF50;
  }

  #Table-sm tr:hover {
    background-color: rgb(151, 170, 212);
  }

  #Table-sm>thead>tr>td {
    border: 1px solid #ddd;
  }
</style>

<body>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <!-- <h3 class="box-title">Status Matching</h3>
          <a href="pages/cetak/cetak-data-permohonan.php" target="_blank" class="btn btn-sm btn-success pull-right"><span class="fa fa-file-pdf-o"></span> Cetak</a> -->
          <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
            <table id="Table-sm" class="table table-sm display compact" style="width: 100%;">
              <thead>
                <tr class="alert-success" style="border: 1px solid #ddd;">
                  <th style="border: 1px solid #ddd;">#</th>
                  <th style="border: 1px solid #ddd;">Stts</th>
                  <th style="border: 1px solid #ddd;">Ket.St</th>
                  <th style="border: 1px solid #ddd;">Grp</th>
                  <th style="border: 1px solid #ddd;">Matcher</th>
                  <th style="border: 1px solid #ddd;">Rcode</th>
                  <th style="border: 1px solid #ddd;">No.Order</th>
                  <th style="border: 1px solid #ddd;">Langganan</th>
                  <th style="border: 1px solid #ddd;">Warna</th>
                  <th style="border: 1px solid #ddd;">No.Warna</th>
                  <th style="border: 1px solid #ddd;">Jenis Kain</th>
                  <th style="border: 1px solid #ddd;">No.Item</th>
                  <th style="border: 1px solid #ddd;">timer</th>
                  <th style="border: 1px solid #ddd;">tgl_buat</th>
                  <th style="border: 1px solid #ddd;">tgl_mulai</th>
                  <th style="border: 1px solid #ddd;">created_by</th>
                  <th style="border: 1px solid #ddd;">status_created_by</th>
                  <th style="border: 1px solid #ddd;">tgl_selesai</th>
                  <th style="border: 1px solid #ddd;">jenis_matching</th>
                  <th style="border: 1px solid #ddd;">no_po</th>
                  <th style="border: 1px solid #ddd;">jenis_kain</th>
                  <th style="border: 1px solid #ddd;">benang</th>
                  <th style="border: 1px solid #ddd;">lebar</th>
                  <th style="border: 1px solid #ddd;">gramasi</th>
                  <th style="border: 1px solid #ddd;">lebara</th>
                  <th style="border: 1px solid #ddd;">gramasia</th>
                  <th style="border: 1px solid #ddd;">cek_warna</th>
                  <th style="border: 1px solid #ddd;">cek_dye</th>
                  <th style="border: 1px solid #ddd;">koreksi_resep</th>
                  <th style="border: 1px solid #ddd;">cocok_warna</th>
                  <th style="border: 1px solid #ddd;">qty_order</th>
                  <th style="border: 1px solid #ddd;">tgl_delivery</th>
                  <th style="border: 1px solid #ddd;">tgl_in</th>
                  <th style="border: 1px solid #ddd;">tgl_out</th>
                  <th style="border: 1px solid #ddd;">ket</th>
                  <th style="border: 1px solid #ddd;">Lampu</th>
                  <th style="border: 1px solid #ddd;">Proses</th>
                  <th style="border: 1px solid #ddd;">id_status</th>
                  <th style="border: 1px solid #ddd;">Handle</th>
                  <th style="border: 1px solid #ddd;">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                /*$sql = mysqli_query($con,"SELECT *, a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by
                                    FROM tbl_status_matching a
                                    JOIN tbl_matching b ON a.idm = b.no_resep
                                    where a.status in ('buka', 'mulai', 'hold', 'batal', 'revisi','tunggu')
                                    group by a.idm, b.no_resep
                                    ORDER BY a.id asc");*/
				$sql = mysqli_query($con,"SELECT *, a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by
                                    FROM tbl_status_matching a
                                    JOIN tbl_matching b ON a.idm = b.no_resep
                                    where a.status in ('buka', 'mulai', 'hold', 'revisi','tunggu')
                                    group by a.idm, b.no_resep
                                    ORDER BY a.id desc");  
                while ($r = mysqli_fetch_array($sql)) {
                  $no++;
                  $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
                ?>
                  <tr>
                    <td valign="center" class="details-control">
                      <!-- plush icon here -->
                    </td>
                    <td valign="center" align="center"><span class="label
                    <?php if ($r['status'] == "buka" and $r['tgl_mulai'] == "") {
                      echo "label-warning";
                    } elseif ($r['status'] == "mulai") {
                      echo "label-info";
                    } elseif ($r['status'] == "hold") {
                      echo "bg-purple";
                    } elseif ($r['status'] == "batal") {
                      echo "label-danger";
                    } elseif ($r['status'] == "selesai") {
                      echo "label-danger blink_me";
                    } elseif ($r['status'] == "buka" and $r['tgl_mulai'] != "") {
                      echo "label-success";
                    } else {
                      echo "label-default";
                    } ?>"> <?php echo $r['status'] ?> </span>
                      
                      <hr class="divider"> <span <?php if ($r['status'] == 'batal') echo "style='display: none;'" ?> class="label 
                      <?php if ($r['kt_status'] == "Urgent") {
                        echo "label-warning blink_me";
                      } else {
                        echo "label-success blink_me";
                      } ?>">
                        <?php echo $r['kt_status']; ?></span>
                    </td>
                    <td valign="center" align="center"><span class="label 
                    <?php if ($r['kt_status'] == "Urgent") {
                      echo "label-warning blink_me";
                    } else {
                      echo "label-success";
                    } ?>">
                        <?php echo $r['kt_status']; ?></span>
                    </td>
                    <td valign="center">
                      <?php echo $r['grp']; ?>
                    </td>
                    <td valign="center">
                      <?php echo $r['matcher']; ?>
                    </td>
                    <td valign="center">
                      <?php echo $r['idm']; ?>
                    </td>
                    <td valign="center">
                      <?php echo $r['no_order']; ?>
                    </td>
                    <td valign="center" align="left">
                      <?php echo $r['langganan']; ?></td>
                    <td valign="center">
                      <?php echo $r['warna']; ?>
                    </td>
                    <td valign="center">
                      <?php echo $r['no_warna']; ?>
                    </td>
                    <td valign="center">
                      <?php echo $r['jenis_kain']; ?>
                    </td>
                    <td valign="center">
                      <?php echo $r['no_item']; ?>
                    </td>
                    <td valign="center" align="center">
                      <?php
                      $awal  = strtotime($r['tgl_buat_status']);
                      $akhir = strtotime(date('Y-m-d H:i:s'));
                      $diff  = $akhir - $awal;

                      $hari  = floor($diff / (60 * 60 * 24));
                      $jam   = floor(($diff - ($hari * (60 * 60 * 24))) / (60 * 60));
                      $menit = ($diff - ($hari * (60 * 60 * 24))) - (($jam) * (60 * 60));

                      echo "<span>" . $hari . " Hari</span> : <span>" . $jam . " Jam</span> : <span>" . floor($menit / 60) . " Menit</span>";
                      ?>

                    </td>
                    <td valign="center" class="13"><?php echo $r['tgl_buat'] ?></td>
                    <td class="14"><?php echo $r['tgl_buat_status'] ?></td>
                    <td class="15"><?php echo $r['created_by'] ?></td>
                    <td class="16"><?php echo $r['status_created_by'] ?></td>
                    <td class="17"><?php echo $r['tgl_selesai'] ?></td>
                    <td class="18"><?php echo $r['jenis_matching'] ?></td>
                    <td class="19"><?php echo $r['no_po'] ?></td>
                    <td class="20"><?php echo $r['jenis_kain'] ?></td>
                    <td class="21"><?php echo $r['benang'] ?></td>
                    <td class="22"><?php echo $r['lebar'] ?></td>
                    <td class="23"><?php echo $r['gramasi'] ?></td>
                    <td class="24"><?php echo floatval($r['lebar_aktual']) ?></td>
                    <td class="25"><?php echo floatval($r['gramasi_aktual']) ?></td>
                    <td class="26"><?php echo $r['cek_warna'] ?></td>
                    <td class="27"><?php echo $r['cek_dye'] ?></td>
                    <td class="28"><?php echo $r['koreksi_resep'] ?></td>
                    <td class="29"><?php echo $r['cocok_warna'] ?></td>
                    <td class="30"><?php echo $r['qty_order'] ?></td>
                    <td class="31"><?php echo $r['tgl_delivery'] ?></td>
                    <td class="32"><?php echo $r['tgl_in'] ?></td>
                    <td class="33"><?php echo $r['tgl_out'] ?></td>
                    <td class="34"><?php echo $r['ket'] ?></td>
                    <td class="35"><?php
                                    if ($r['ck_d65'] == 1) {
                                      echo 'd65 - ';
                                    }
                                    if ($r['ck_f02'] == 1) {
                                      echo 'f02 - ';
                                    }
                                    if ($r['ck_f11'] == 1) {
                                      echo 'f11 - ';
                                    }
                                    if ($r['ck_u35'] == 1) {
                                      echo 'u35 - ';
                                    }
                                    if ($r['ck_a'] == 1) {
                                      echo 'A - ';
                                    }
                                    if ($r['ck_rlight'] == 1) {
                                      echo 'rlight - ';
                                    }
                                    if ($r['ck_tl83'] == 1) {
                                      echo 'tl83 - ';
                                    }
                                    ?></td>
                    <td class="36"><?php
                                    if ($r['ck_greige'] == 1) {
                                      echo 'Greige - ';
                                    }
                                    if ($r['ck_bleaching'] == 1) {
                                      echo 'Bleaching Lab - ';
                                    }
                                    if ($r['ck_bleaching_dye'] == 1) {
                                      echo 'Bleaching Dye - ';
                                    }
                                    if ($r['ck_preset'] == 1) {
                                      echo 'Preset - ';
                                    }
                                    if ($r['ck_npreset'] == 1) {
                                      echo 'Non Preset - ';
                                    }
                                    if ($r['ck_nh2o2'] == 1) {
                                      echo 'Non h2o2 - ';
                                    }
                                    if ($r['ck_tarik'] == 1) {
                                      echo 'Peach - ';
                                    }
                                    ?>
                    </td>
                    <td class="37"><?php echo $r['id_status'] ?></td>
                    <?php if ($r['status'] == 'batal') { ?>
                      <td class="38"><span class="btn bg-black btn-sm blink_me"><i class="fa fa-ban"></i>BATAL</span></td>
                    <?php } else if ($r['status'] == 'tunggu') { ?>
                      <td class="38">
                        <li style="font-weight: bold; color: black;"><a href="#" class="btn btn-xs btn-primary _lanjutkan" attribute="<?php echo $r['id_status'] ?>" codem="<?php echo $r['idm'] ?>">Lanjutkan <i class="fa fa-play" aria-hidden="true"></i>
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                          </a>
                        </li>
                        <br>
                        <?php $sqlWait = mysqli_query($con,"SELECT max(id) as maxid, `info` from log_status_matching where ids = '$r[id_status]'");
                        $Wait = mysqli_fetch_array($sqlWait);
                        echo '<span class="badge">' . $Wait['info'] . '</span>';
                        ?>
                      </td>
                    <?php } else { ?>
                      <td class="38">
                        <div class="btn-group-vertical">
                          <a style="color: black;" target="_blank" href="pages/cetak/matching.php?idkk=<?php echo $r['idm'] ?>" class="btn btn-xs btn-warning">Print ! &nbsp;<i class="fa fa-print"></i></a>
                          <?php if ($r['status'] == 'hold' or $r['status'] == 'revisi') { ?>
                            <a href="?p=Hold-Handle&idm=<?php echo $r['id_status'] ?>" class="btn btn-xs bg-purple">Lanjut <i class="fa fa-edit"></i></a>

                          <?php } else { ?></php>
                            <a style="color: white;" href="?p=Status-Handle&idm=<?php echo $r['id_status'] ?>" class="btn btn-xs btn-success">Resep! <i class="fa fa-pencil"></i></a>
                            <!-- <a style="color: white;" href="?p=Status-Handle-NOW&idm=<?php echo $r['id_status'] ?>" class="btn btn-xs btn-success">Resep NOW! <i class="fa fa-pencil"></i></a> -->
                            <a href="#" class="btn btn-xs btn-danger _ketstatus" value="<?= $r['kt_status'] ?>" attribute="<?= $r['id_status'] ?>" codem="<?= $r['idm'] ?>">Ket. Status <i class="fa fa-exchange" aria-hidden="true"></i>

                          <?php } ?>
                          <a href="#" class="btn btn-xs btn-info _tunggu" attribute="<?php echo $r['id_status'] ?>" codem="<?php echo $r['idm'] ?>">Tunggu <i class="fa fa-clock-o" aria-hidden="true"></i>
                          </a>
						  <!--<a style="color: white;" href="?p=Edit_Status_Matching&rcode=<?php echo $r['no_resep'] ?>" class="btn btn-xs btn-primary">Ubah! <i class="fa fa-edit"></i></a>-->

                          <!--<a style="color: black;" href="#" class="btn btn-xs btn-danger batalkan" attribute="<?php echo $r['id_status'] ?>" codem="<?php echo $r['idm'] ?>">Batalkan!</a>-->

                        </div>
                      </td>
                    <?php } ?>

                    <td class="39"><?php echo $r['status'] ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
            <!-- Modal Popup untuk Edit-->
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<script>
  $(document).ready(function() {
    var table = $('#Table-sm').DataTable({
      select: true,
      dom: 'Bfrtip',
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
      ],
      "columnDefs": [{
          "className": "align-center",
          "targets": [0, 3, 12, 38]
        },
        {
          "targets": [2, 10, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 39],
          "visible": false
        },
        {
          "targets": [0, 1, 2],
          "orderable": false
        },
      ],
      "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        if (aData[39] == 'revisi') {
          $('td', nRow).css('background-color', '#f9ff8f');
          $('td', nRow).css('color', 'black');
        } else if (aData[39] == 'tunggu') {
          $('td', nRow).css('background-color', '#f55b5b');
          $('td', nRow).css('color', 'black');
        } else {
          $('td', nRow).css('color', 'black');
        }
      },
    });

    new $.fn.dataTable.FixedHeader(table);

    $('#Table-sm tbody').on('click', 'td.details-control', function() {
      var tr = $(this).parents('tr');
      var row = table.row(tr);

      if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
      } else {
        // Open this row (the format() function would return the data to be shown)
        row.child(format(row.data())).show();
        tr.addClass('shown');
      }
    });

    function format(d) {
      return '<div class="col-md-12" style="background: #247fff;">' +
        '<div class="container-fluid">' +
        '<table class="table table-striped table-bordered" id="tableee" width="100%" style="margin-top: 10px;">' +
        '<tbody>' +
        // 1
        '<tr>' +
        '<th style="width:100px">Jenis Matching :</th>' +
        '<td>' + d[18] + '</td>' +
        '<th style="width:90px">PO Greige :</th>' +
        '<td colspan="5">' + d[19] + '</td>' +
        '</tr>' +
        // 2
        '<tr>' +
        '<th>Jenis Kain :</th>' +
        '<td>' + d[20] + '</td>' +
        '<th>Benang :</th>' +
        '<td colspan="5">' + d[21] + '</td>' +
        '</tr>' +
        // 4
        '<tr>' +
        '<th>Lampu :</th>' +
        '<td colspan="1">' + d[35] + '</td>' +
        '<th>Proses :</th>' +
        '<td colspan="5">' + d[36] + '</td>' +
        '</tr>' +
        // 
        '<tr>' +
        '<th>Tgl Generate Kartu Matching :</th>' +
        '<td>' + d[13] + '</td>' +
        '<th>Generate Kartu by :</th>' +
        '<td>' + d[15] + '</td>' +
        '<th>Tgl Mulai :</th>' +
        '<td>' + d[14] + '</td>' +
        '<th>Mulai By :</th>' +
        '<td>' + d[16] + '</td>' +
        '</tr>' +
        // 3
        '<tr>' +
        '<th>Lebar :</th>' +
        '<td>' + d[22] + '</td>' +
        '<th>Gramasi :</th>' +
        '<td>' + d[23] + '</td>' +
        '<th>Lebar Aktual :</th>' +
        '<td>' + d[24] + '</td>' +
        '<th>Gramasi Aktual :</th>' +
        '<td>' + d[25] + '</td>' +
        '</tr>' +
        // 5                
        '<tr>' +
        '<th>Qty Order :</th>' +
        '<td>' + d[30] + '</td>' +
        '<th>tgl delivery :</th>' +
        '<td>' + d[31] + '</td>' +
        '<th>Tanggal in :</th>' +
        '<td>' + d[32] + '</td>' +
        '<th>Tanggal out :</th>' +
        '<td>' + d[33] + '</td>' +
        '</tr>' +
        // 6
        '<tr>' +
        '<th>Keterangan :</th>' +
        '<td colspan="7">' + d[34] + '</td>' +
        // '<td align="center"><a href="pages/cetak/matching.php?idkk=' + d[5] + '" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print"></i> Print</a></td>' +
        // '<td align="center"><a href="?p=Status-Handle&idm=' + d[37] + '" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Handle</a></td>' +
        '</tr>' +
        '</tbody>' +
        '</table>' +
        '</div>' +
        '</div>';
    }
  });
</script>

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
      window.location.href = 'index1.php?p=Status-Matching';
    }, 1000);
  }
</script>

<!-- swall batal -->
<script>
  $(document).ready(function() {
    $(document).on('click', '.batalkan', function() {
      var code = $(this).attr('codem');
      Swal.fire({
        title: "Jelaskan !",
        text: "Kenapa Resep " + code + " di batalkan ?",
        input: 'textarea',
        showCancelButton: true,
        allowOutsideClick: false,
      }).then((result) => {
        if (result.value) {
          $.ajax({
            dataType: "json",
            type: "POST",
            url: "pages/ajax/Batalkan_byID.php",
            data: {
              id_status: $(this).attr('attribute'),
              why: result.value
            },
            success: function(response) {
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Matching ' + code + ' telah di batalkan !',
                showConfirmButton: false,
                timer: 1500
              })
              setTimeout(function() {
                location.reload();
              }, 1505);
            },
            error: function() {
              alert("Error");
            }
          });
        } else if (result.value !== "") {
          consol.log('button cancel clicked !')
        } else {
          Swal.fire('Anda Harus mengisi Penjelasan kenapa Resep ' + code + ' di batalakan !')
        }
      });
    })
  })
</script>
<script>
  $(document).ready(function() {
    $(document).on('click', '._lanjutkan', function() {
      var code = $(this).attr('codem');
      Swal.fire({
        title: "Confirmation ",
        text: "Apakah anda yakin untuk melanjutkan Resep " + code + " ?",
        showCancelButton: true,
        allowOutsideClick: false,
      }).then((result) => {
        console.log(result)
        if (result.isConfirmed == true) {
          $.ajax({
            dataType: "json",
            type: "POST",
            url: "pages/ajax/Lanjutkan_byID.php",
            data: {
              id_status: $(this).attr('attribute'),
              why: result.value
            },
            success: function(response) {
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Matching ' + code + ' telah di rubah menjadi hold !',
                showConfirmButton: false,
                timer: 1500
              })
              setTimeout(function() {
                location.reload();
              }, 1505);
            },
            error: function() {
              alert("Error");
            }
          });
        } else {
          console.log('button cancel clicked !')
        }
      });
    })
  })
</script>
<script>
  $(document).ready(function() {
    $(document).on('click', '._ketstatus', function() {
      var code = $(this).attr('codem');
      let previousStatus = this.getAttribute('value'); // Mengambil keterangan status
      Swal.fire({
        title: "Keterangan Status !",
        text: "Ubah keterangan status anda",
        input: 'select',  // Mengubah tipe input menjadi 'select'
        inputOptions: {  // Mendefinisikan opsi untuk dropdown
            'Normal': 'Normal',
            'Urgent': 'Urgent'
        },
        inputValue: previousStatus,  // Pra-pilih status sebelumnya
        inputPlaceholder: 'Pilih Keterangan anda ...',  // Memperbarui teks placeholder
        showCancelButton: true,
        allowOutsideClick: false,
      }).then((result) => {
        if (result.value) {
          $.ajax({
            dataType: "json",
            type: "POST",
            url: "pages/ajax/ChangeKetStatus.php",
            data: {
              id_status: $(this).attr('attribute'),
              idm: $(this).attr('codem'),
              newStatus: result.value
            },
            success: function(response) {
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Status Matching ' + code + ' telah di rubah. !',
                showConfirmButton: false,
                timer: 1500
              })
              setTimeout(function() {
                location.reload();
              }, 1505);
            },
            error: function() {
              alert("Error");
            }
          });
        } else if (result.value !== "") {
          consol.log('button cancel clicked !')
        } else {
          Swal.fire('Status Tidak di pilih !')
        }
      });
    })
    
    $(document).on('click', '._tunggu', function() {
      var code = $(this).attr('codem');
      Swal.fire({
        title: "Keterangan Status tunggu !",
        text: "Berikan Keterangan anda",
        input: 'textarea',
        inputPlaceholder: 'Ketikan Keterangan anda ...',
        showCancelButton: true,
        allowOutsideClick: false,
      }).then((result) => {
        if (result.value) {
          $.ajax({
            dataType: "json",
            type: "POST",
            url: "pages/ajax/Wait_byID.php",
            data: {
              id_status: $(this).attr('attribute'),
              why: result.value
            },
            success: function(response) {
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Matching ' + code + ' telah di rubah menjadi Tunggu !',
                showConfirmButton: false,
                timer: 1500
              })
              setTimeout(function() {
                location.reload();
              }, 1505);
            },
            error: function() {
              alert("Error");
            }
          });
        } else if (result.value !== "") {
          consol.log('button cancel clicked !')
        } else {
          Swal.fire('Status Tidak di pilih !')
        }
      });
    })
  })
</script>

</html>