<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Form Cycle Time</title>
</head>
<!-- style tabel checklist -->
<style>
    #Table-sm td,
    #Table-sm th {
        border: 1px solid #ddd;
        vertical-align: middle;
        text-align: center;
    }

    #Table-sm th {
        color: black;
        background: #4CAF50;
    }

    .lookupST td,
    .lookupST th {
        border: 1px solid black;
        padding: 2px;
    }


    .lookupST th {
        padding-top: 5px;
        padding-bottom: 5px;
        text-align: center;
        background-color: #4CAF50;
        color: white;
    }
</style>
<!-- style tabel sm ct -->
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

    #Table-sm-ct td,
    #Table-sm-ct th {
        border: 0.1px solid #ddd;
        vertical-align: middle;
        text-align: center;
    }

    #Table-sm-ct th {
        color: black;
        background: #4CAF50;
    }

    #Table-sm-ct tr:hover {
        background-color: rgb(151, 170, 212);
    }

    #Table-sm-ct>thead>tr>td {
        border: 1px solid #ddd;
    }
</style>
<?php
    require_once 'koneksi.php';
    $dataMainCycletime = mysqli_query($con, "SELECT * FROM tbl_cycletime WHERE id = '$_GET[id]'");
    $rowMainCycletime = mysqli_fetch_assoc($dataMainCycletime);

    if ($_GET['status'] == 'Normal') {
        $andstatus = "AND `status` = 'Normal'";
    } else {
        $andstatus = "AND `status` = 'Urgent'";
    }

    $dataMainCycletime_detail = mysqli_query($con, "SELECT * FROM tbl_cycletime_detail WHERE id_cycletime = '$_GET[id]' $andstatus ORDER BY id DESC LIMIT 1");
    $rowMainCycletime_detail = mysqli_fetch_assoc($dataMainCycletime_detail);
?>
<input type="hidden" value="<?= $rowMainCycletime_detail['start_number']; ?>" id="start_number">
<input type="hidden" value="<?= ($rowMainCycletime_detail['end_number'] == 0) ? $rowMainCycletime_detail['start_number'] : ($rowMainCycletime_detail['end_number']); ?>" id="end_number">

<body>
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li><a href="#tab_1" data-toggle="tab">Detail Cycle Time</a></li>
                    <li class="active"><a href="#tab_2" data-toggle="tab">Input Cyle Time</a></li>
                    <li class="pull-right">
                        <button type="button" class="btn btn-block btn-social btn-linkedin" id="saveButton_closed">Simpan <i class="fa fa-save"></i></button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="tab_1">
                        <form class="form-horizontal">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="order" class="col-sm-2 control-label">Group Matching</label>
                                    <div class="col-sm-2">
                                        <select type="text" class="form-control" name="grp_matching" id="grp_matching" required>
                                            <option value="" selected disabled>Pilih...</option>
                                            <?php
                                            $dataGrpMatching = mysqli_query($con, "SELECT
                                                                                    a.grp,
                                                                                    COUNT(a.grp) AS jumlahdata
                                                                                FROM
                                                                                    tbl_status_matching a
                                                                                    JOIN tbl_matching b ON a.idm = b.no_resep 
                                                                                WHERE
                                                                                    a.STATUS IN ( 'buka', 'mulai', 'hold', 'revisi', 'tunggu' ) 
                                                                                GROUP BY
                                                                                    a.grp 
                                                                                ORDER BY
                                                                                    a.grp ASC");
                                            while ($rowGrpMatching = mysqli_fetch_array($dataGrpMatching)) {
                                            ?>
                                                <option value="<?= $rowGrpMatching['grp'] ?>" <?php if ($rowGrpMatching['grp'] == $rowMainCycletime['group_matching']) {
                                                                                                    echo "SELECTED";
                                                                                                } ?>><?= $rowGrpMatching['grp'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="order" class="col-sm-2 control-label">Shift</label>
                                    <div class="col-sm-2">
                                        <select type="text" class="form-control" name="shift" id="shift" required>
                                            <option value="" selected disabled>Pilih...</option>
                                            <option value="1" <?php if ('1' == $rowMainCycletime['shift']) {
                                                                    echo "SELECTED";
                                                                } ?>>Shift 1</option>
                                            <option value="2" <?php if ('2' == $rowMainCycletime['shift']) {
                                                                    echo "SELECTED";
                                                                } ?>>Shift 2</option>
                                            <option value="3" <?php if ('3' == $rowMainCycletime['shift']) {
                                                                    echo "SELECTED";
                                                                } ?>>Shift 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="order" class="col-sm-2 control-label">Nama Matcher</label>
                                    <div class="col-sm-2">
                                        <select type="text" class="form-control selectMatcher" name="nama_matcher" id="nama_matcher" required>
                                            <option value="" selected disabled>Pilih...</option>
                                            <?php
                                            $dataMatcher = mysqli_query($con, "SELECT * FROM tbl_matcher WHERE status = 'Aktif' ORDER BY nama ASC");
                                            while ($rowMatcher = mysqli_fetch_array($dataMatcher)) {
                                            ?>
                                                <option value="<?= $rowMatcher['nama'] ?>" <?php if ($rowMatcher['nama'] == $rowMainCycletime['nama_matcher']) {
                                                                                                echo "SELECTED";
                                                                                            } ?>><?= $rowMatcher['nama'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <div class="col-sm-2">
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="col-lg-6 overflow-auto table-responsive" style="overflow-x: auto;">
                                    <table id="Table-sm-ct" class="table table-sm-ct display compact" style="width: 100%;">
                                        <center><span style="font-size: 20px; font-weight: bold;">SUFFIX START</span></center>
                                        <thead>
                                            <tr class="alert-success" style="border: 1px solid #ddd;">
                                                <th style="border: 1px solid #ddd;">#</th>
                                                <th style="border: 1px solid #ddd;">SuffixCode</th>
                                                <th style="border: 1px solid #ddd;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = mysqli_query($con, "SELECT
                                                                                *
                                                                        FROM
                                                                            tbl_cycletime_suffix_start
                                                                        WHERE
                                                                            `status` = '$_GET[status]'
                                                                            AND id_cycletime = '$_GET[id]'
                                                                        ORDER BY 
                                                                            id 
                                                                        ASC");
                                            $no = 1;
                                            while ($r = mysqli_fetch_array($sql)) {
                                            ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?php echo $r['suffix'] ?></td>
                                                    <td><?php echo $r['status'] ?></td=>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-6 overflow-auto table-responsive" style="overflow-x: auto;">
                                    <table id="Table-sm-ct2" class="table table-sm-ct2 display compact" style="width: 100%;">
                                        <center><span style="font-size: 20px; font-weight: bold;">SUFFIX END</span></center>
                                        <thead>
                                            <tr class="alert-success" style="border: 1px solid #ddd;">
                                                <th style="border: 1px solid #ddd;">#</th>
                                                <th style="border: 1px solid #ddd;">SuffixCode</th>
                                                <th style="border: 1px solid #ddd;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = mysqli_query($con, "SELECT
                                                                                *
                                                                        FROM
                                                                            tbl_cycletime_suffix_end
                                                                        WHERE
                                                                            `status` = '$_GET[status]'
                                                                            AND id_cycletime = '$_GET[id]'
                                                                        ORDER BY 
                                                                            id 
                                                                        ASC");
                                            $no = 1;
                                            while ($r = mysqli_fetch_array($sql)) {
                                            ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?php echo $r['suffix'] ?></td>
                                                    <td><?php echo $r['status'] ?></td=>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane active" id="tab_2">
                        <div class="row">
                            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="order" class="col-sm-2 control-label">Status</label>
                                        <div class="col-sm-2">
                                            <input type="hidden" value="<?= $rowMainCycletime_detail['id']; ?>" id="id">
                                            <input type="hidden" value="<?= $_GET['id']; ?>" id="id_cycletime">
                                            <select type="text" class="form-control" name="status" id="status" required>
                                                <option value="" selected disabled>Pilih...</option>
                                                <option value="Normal" <?php if ($_GET['status'] == 'Normal') {
                                                                            echo "SELECTED";
                                                                        } ?>>Normal</option>
                                                <option value="Urgent" <?php if ($_GET['status'] == 'Urgent') {
                                                                            echo "SELECTED";
                                                                        } ?>>Urgent</option>
                                            </select>
                                        </div>
                                        <?php 
                                            if($rowMainCycletime_detail){
                                                echo '<input type="hidden" value="EndProses" id="prosesCycleTime">';
                                                echo '<button class="btn btn-xs" style="background-color: Red; color: white; margin-bottom: 10px;">End Proses </button>';
                                            }else {
                                                echo '<input type="hidden" value="StartProses" id="prosesCycleTime">';
                                                echo '<button class="btn btn-xs" style="background-color: Green; color: white; margin-bottom: 10px;">Start Proses </button>';
                                            }
                                        ?>
                                    </div>
                                    <div class=" form-group">
                                        <label for="order" class="col-sm-2 control-label">...</label>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-social" id="resetButton">Reset Checkbox <i class="fa fa-refresh"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table id="lookupmodal1" class="lookupST display nowrap" width="50%" style="padding-right: 16px;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>#</th>
                                                    <th>No Cycle</th>
                                                    <th>Keterangan</th>
                                                    <th>Point</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $dataMasterCycletime      = "SELECT * FROM master_cycletime ORDER BY id ASC";
                                                $resultMasterCycletime    = mysqli_query($con, $dataMasterCycletime);
                                                $rowCount = 0;  
                                                $colors = ['#b4c6e7', '#d0e4f7', '#f7e4e4'];

                                                while ($rowMasterCycletime = mysqli_fetch_array($resultMasterCycletime)) {
                                                    $bgColor = $colors[intval($rowCount / 7) % 7];
                                                ?>
                                                    <tr style="background-color: <?= $bgColor; ?>; color: black;">
                                                        <td align="Center"><?= $rowMasterCycletime['id']; ?></td>
                                                        <td align="Center">
                                                            <input type="checkbox" class="row-checkbox" data-id="<?= $rowMasterCycletime['id']; ?>" data-point="<?= $rowMasterCycletime['point']; ?>">
                                                        </td>
                                                        <td align="Center"><?= $rowMasterCycletime['no']; ?></td>
                                                        <td><?= $rowMasterCycletime['keterangan']; ?></td>
                                                        <td align="Center"><?= $rowMasterCycletime['point']; ?></td>
                                                    </tr>
                                                <?php $rowCount++; } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="summary-row">
                                                    <td colspan="4" align="right">Total Point:</td>
                                                    <td id="totalPoints" align="Center">0</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-3d-slit" id="confirmationModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Konfirmasi !</h4>
                </div>
                <div class="modal-body">
                    <span style="font-size: 15px;">Jika Anda menutup cycletime ini, data akan dipindahkan ke arsip.</span>
                    <br><br>
                    <button type="button" class="btn btn-danger" id="closeCycleTime">Tutup cycletime ini</button>
                    <button type="button" class="btn btn-secondary" id="keepOpen">Tidak, biarkan cycletime tetap terbuka untuk pengisian data yang urgent</button>
                </div>
            </div>
        </div>
    </div>

</body>
<!-- SPINNER LOADING FOR SHOW LOADER ON AJAX PROCESS // THIS VERY IMPORTANT to PREVENT DATA NOT SENDED ! -->
<script type="text/javascript">
    $(document).ready(function() {
        const myTable = $('#Table-sm').DataTable({
            "ordering": false,
            "pageLength": 20
        })
    });

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
        }, 4000);
    }
</script>
<script>
    $(document).ready(function() {
        $("#lookupmodal1").DataTable({
            ordering: false,
            searching: false,
            "lengthChange": false,
            "paging": false,
            "bInfo": false,
            // responsive: true
            // "scrollX": true
        })

        $('#grp_matching').prop("disabled", true);
        $('#shift').prop("disabled", true);
        $('#nama_matcher').prop("disabled", true);
        $('#status').prop("disabled", true);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const totalPointsDisplay = document.getElementById('totalPoints');
        const resetButton = document.getElementById('resetButton');
        const changeStatus = document.getElementById('grp_matching');
        let firstChecked = null;
        let lastChecked = null;
        let startnumber = document.getElementById('start_number').value - 1;
        let endnumber = document.getElementById('end_number').value - 1;

        // Fungsi untuk mengatur checkbox berdasarkan data dari database
        function setInitialCheckedStates() {
            checkboxes.forEach((checkbox, index) => {
                // yg di select 2 - 5. tapi 2 jadi 1 & 5 jadi 4 
                if (index >= startnumber && index <= endnumber) {
                    checkbox.checked = true; // Centang checkbox
                }
            });
            updateTotalPoints(); // Hitung total poin setelah mengatur checkbox yang dicentang
        }

        // Panggil fungsi setInitialCheckedStates saat halaman dimuat
        setInitialCheckedStates();

        // Event listener untuk klik checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('click', function() {
                if (!firstChecked) {
                    firstChecked = this;
                }

                if (firstChecked && this === firstChecked) {
                    lastChecked = null; // Reset jika checkbox yang sama diklik
                    return;
                }

                lastChecked = this;

                let start = Array.from(checkboxes).indexOf(firstChecked);
                let end = Array.from(checkboxes).indexOf(lastChecked);

                if (start > end) {
                    [start, end] = [end, start];
                }

                checkboxes.forEach((cb, index) => {
                    if (index >= start && index <= end) {
                        cb.checked = true; // Centang semua checkbox di antara
                    }
                });

                firstChecked = null; // Reset firstChecked
                lastChecked = null; // Reset lastChecked

                updateTotalPoints();
            });
        });

        // Event listener untuk perubahan pada checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotalPoints);
        });

        // Fungsi untuk mengupdate total poin
        function updateTotalPoints() {
            let totalPoints = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    totalPoints += parseFloat(checkbox.getAttribute('data-point'));
                }
            });
            totalPointsDisplay.textContent = totalPoints.toFixed(1);
        }

        // Event listener untuk tombol reset
        resetButton.addEventListener('click', function() {
            checkboxes.forEach(checkbox => {
                let firstChecked = null;
                let lastChecked = null;
                checkbox.checked = false;
            });
            updateTotalPoints(); // Reset total points to 0
        });
    });

    $('#saveButton_closed').click(function(e) {
        e.preventDefault();
        Preparation_insert_cycletime("Closed");
    });

    function Preparation_insert_cycletime(statusCycleTime) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        var status = document.getElementById('status').value;
        var proses_CycleTime = document.getElementById('prosesCycleTime').value;
        var id = document.getElementById('id').value;
        var idcycletime = document.getElementById('id_cycletime').value;
        let startNumber = null;
        let endNumber = null;
        let totalPoint = 0;
        let checkedCount = 0; // Variabel untuk menghitung jumlah checkbox yang dicentang

        checkboxes.forEach((checkbox, index) => {
            if (checkbox.checked) {
                checkedCount++; // Menambahkan jumlah checkbox yang dicentang
                if (startNumber === null) {
                    startNumber = index + 1; // Menyimpan nomor baris pertama yang dicentang
                }
                endNumber = index + 1; // Update endNumber ke indeks checkbox yang dicentang terakhir
                totalPoint += parseFloat(checkbox.dataset.point);
            }
        });

        // Jika hanya satu checkbox yang dicentang, set endNumber menjadi null
        if (checkedCount === 1) {
            endNumber = null;
        }

        if (status === null || status.trim() === '') {
            toastr.error('Status wajib diisi!');
            return false;
        } else {
            if (proses_CycleTime == 'StartProses' && startNumber == null) {
                toastr.error('Harap pilih cycle time MULAI anda!');
                return false;
            }else if(proses_CycleTime == 'EndProses' && endNumber == null){
                toastr.error('Harap pilih cycle time SELESAI anda!');
                return false;
            } else {
                insertInto_cycletime(id, idcycletime, status, startNumber, endNumber, totalPoint, statusCycleTime);
            }
        }
    }

    function insertInto_cycletime(id, idcycletime, status, startNumber, endNumber, totalPoint, statusCycleTime) {
        // SpinnerShow()
        $.ajax({
            dataType: "json",
            type: "POST",
            url: "pages/ajax/Insert_cycletime.php",
            data: {
                id: id,
                id_cycletime: idcycletime,
                status: status,
                start_number: startNumber,
                end_number: endNumber,
                total_point: totalPoint,
                status_cycletime: statusCycleTime
            },
            success: function(response) {
                if (response.session == "LIB_SUCCESS") {
                    toastr.success('Data berhasil di' + response.exp, 'Berhasil!');
                    // console.log(response);
                    setTimeout(function() {
                        window.location.href = '?p=Cycle-Time-Arsip';
                    }, 1500)
                } else if (response.session == "LIB_UPDATED") {
                    toastr.info('Data berhasil di' + response.exp, 'Berhasil!');
                    // console.log(response);
                    setTimeout(function() {
                        window.location.href = '?p=Cycle-Time-Arsip';
                    }, 1500)
                } else if (response.session == "LIB_DELETED") {
                    toastr.error('Data berhasil di' + response.exp, 'Terhapus!');
                    // console.log(response);
                    setTimeout(function() {
                        window.location.href = '?p=Cycle-Time-Arsip';
                    }, 2000)
                } else if (response.session == "LIB_INSERT_STATUS") {
                    toastr.success('Data berhasil di' + response.exp + ' dengan status yang berbeda', 'Berhasil!');
                    // console.log(response);
                    setTimeout(function() {
                        window.location.href = '?p=Cycle-Time-Arsip';
                    }, 2000)
                } else {
                    toastr.error("ajax error !")
                }
            },
            error: function() {
                alert("Error");
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        var table = $('#Table-sm-ct').DataTable({
            select: true,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            "pageLength": 5
        });
        var table = $('#Table-sm-ct2').DataTable({
            select: true,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            "pageLength": 5
        });
    });
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