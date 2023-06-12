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
                                    <th style="border: 1px solid #ddd;">PO greige</th>
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
                                    <th style="border: 1px solid #ddd;">L:R</th>
                                    <th style="border: 1px solid #ddd;">ph</th>
                                    <th style="border: 1px solid #ddd;">ket</th>
                                    <th style="border: 1px solid #ddd;">Lampu</th>
                                    <th style="border: 1px solid #ddd;">Proses</th>
                                    <th style="border: 1px solid #ddd;">id_status</th>
                                    <th style="border: 1px solid #ddd;">Text_status</th>
                                    <th style="border: 1px solid #ddd;">id_matching</th>
                                    <th style="border: 1px solid #ddd;">Benang Aktual</th>
                                    <th style="border: 1px solid #ddd;">Handle</th>
                                    <th style="border: 1px solid #ddd;">Why_batal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysqli_query($con,"SELECT *,  a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by
                                FROM tbl_status_matching a
                                INNER JOIN tbl_matching b ON a.idm = b.no_resep
                                where a.status in ('selesai', 'batal') and a.approve = 'NONE'
                                ORDER BY a.id desc;");
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
                                        echo "label-danger blink_me";
                                    } elseif ($r['status'] == "selesai") {
                                        echo "bg-black";
                                    } elseif ($r['status'] == "buka" and $r['tgl_mulai'] != "") {
                                        echo "label-success";
                                    } else {
                                        echo "label-default";
                                    } ?>"> <?php echo $r['status'] ?> </span>
                                            <p />
                                            <hr class="divider"> <span <?php if ($r['status'] == 'batal') echo "style='display: none;'" ?> class="label 
                      <?php if ($r['kt_status'] == "Urgent") {
                                        echo "label-warning blink_me";
                                    } else {
                                        echo "label-success";
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
                                        <td class="24"><?php echo $r['lebar_aktual'] ?></td>
                                        <td class="25"><?php echo $r['gramasi_aktual'] ?></td>
                                        <td class="26"><?php echo $r['cek_warna'] ?></td>
                                        <td class="27"><?php echo $r['cek_dye'] ?></td>
                                        <td class="28"><?php echo $r['koreksi_resep'] ?></td>
                                        <td class="29"><?php echo $r['cocok_warna'] ?></td>
                                        <td class="30"><?php echo $r['qty_order'] ?></td>
                                        <td class="31"><?php echo $r['tgl_delivery'] ?></td>
                                        <td class="32"><?php echo $r['lr'] ?></td>
                                        <td class="33"><?php echo $r['ph'] ?></td>
                                        <td class="34"><?php echo $r['ket'] ?></td>
                                        <td class="35">
                                            <?php
                                            $sqlLamp = mysqli_query($con,"SELECT group_concat(lampu) as lampus
                                                FROM db_laborat.vpot_lampbuy where buyer='$r[buyer]' group by buyer;");
                                            $Lamp = mysqli_fetch_array($sqlLamp);
                                            echo $Lamp['lampus'] . " (" . $r['buyer'] . ")";
                                            ?>
                                        </td>
                                        <td class="36"><?php echo $r['proses'] ?></td>
                                        <td class="37"><?php echo $r['id_status'] ?></td>
                                        <td class="38"><?php echo $r['status'] ?></td>
                                        <td class="39"><?php echo $r['id'] ?></td>
                                        <td class="40"><?php echo $r['benang_aktual'] ?></td>
                                        <td class="41">
                                            <div class="btn-group">
                                                <?php if ($_SESSION['lvlLAB'] != "3") { ?>
                                                    <?php if ($r['status'] == "batal") : ?>
                                                        <a href="?p=Detail-status-wait-approve&idm=<?php echo $r['id_status'] ?>" class="btn btn-xs btn-primary">Preview <i class="fa fa-search-plus"></i></a>
                                                        <!-- delete clear -->
                                                        <button type="button" style="color: black;" class="btn btn-xs btn-danger delete" id_status="<?php echo $r['id_status'] ?>" id_matching="<?php echo $r['id'] ?>" idm="<?php echo $r['idm'] ?>" no_order="<?php echo $r['no_order'] ?>" why_batal=<?php echo $r['why_batal'] ?>>Delete <i class="fa fa-trash"></i></button>
                                                        <!-- end delete -->
                                                        <!-- Keep -->
                                                        <button style="color: black;" class="btn btn-xs btn-info keep" id_status="<?php echo $r['id_status'] ?>" idm="<?php echo $r['idm'] ?>">Keep ! <i class="fa fa-floppy-o"></i></i> </a>
                                                            <!-- endkeep -->
                                                            <button type="button" style="color: black;" class="btn btn-xs btn-warning Revised" idm="<?php echo $r['idm'] ?>" id_status="<?php echo $r['id_status'] ?>">Revise ! <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                            </button>

                                                        <?php else : ?>

                                                            <a href="?p=Detail-status-wait-approve&idm=<?php echo $r['id_status'] ?>" class="btn btn-xs btn-primary">Preview <i class="fa fa-search-plus"></i></a>

                                                            <button type="button" style="color: black;" class="btn btn-xs btn-success approve" idm="<?php echo $r['idm'] ?>" id_status="<?php echo $r['id_status'] ?>" id_matching="<?php echo $r['id'] ?>" no_order="<?php echo $r['no_order'] ?>" benang="<?php echo $r['benang'] ?>">Approve <i class="fa fa-check-circle"></i></button>

                                                            <li style="display: none;">
                                                                <button style="color: black;" class="btn btn-xs btn-danger tutup" id_status="<?php echo $r['id_status'] ?>" idm="<?php echo $r['idm'] ?>">>Reject! <i class="fa fa-times" aria-hidden="true"></i>
                                                                </button>
                                                            </li>

                                                            <button type="button" style="color: black;" class="btn btn-xs btn-warning Revised" idm="<?php echo $r['idm'] ?>" id_status="<?php echo $r['id_status'] ?>">Revise ! <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                            </button>

                                                        <?php endif; ?>
                                                    <?php } else { ?>
                                                        <p class="text-danger">Anda tidak di izinkan !</p>
                                                    <?php } ?>
                                            </div>
                                        </td>
                                        <td class="42"> <?php echo $r['why_batal'] ?> </td>
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
        $(".btn.btn-xs.btn-danger.tutup").click(function() {
            var id_status = $(this).attr('id_status');
            var idm = $(this).attr('idm');
            var password = '<?php echo $_SESSION['passLAB'] ?>';
            Swal.fire({
                title: "Autentikasi",
                text: "Untuk me-Reject resep membutuhkan password setingkat SPV ?",
                input: 'password',
                showCancelButton: true,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    if (result.value == password) {
                        $.ajax({
                            dataType: "json",
                            type: "POST",
                            url: "pages/ajax/Keep_resep_even_hasCancel.php",
                            data: {
                                id_status: id_status,
                            },
                            success: function(response) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Matching ' + idm + ' berhasil di Keep ke database !',
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Password yang anda masukan salah !',
                        })
                    }
                } else if (result.value !== "") {
                    console.log('button cancel clicked !')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Password yang anda masukan salah !',
                    })
                }
            });

        })
    })
</script>
<script>
    $(document).ready(function() {
        $(".btn.btn-xs.btn-info.keep").click(function() {
            var id_status = $(this).attr('id_status');
            var idm = $(this).attr('idm');
            var password = '<?php echo $_SESSION['passLAB'] ?>';
            Swal.fire({
                title: "Autentikasi",
                text: "Untuk Keep/Tutup resep membutuhkan password setingkat SPV ?",
                input: 'password',
                showCancelButton: true,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    if (result.value == password) {
                        $.ajax({
                            dataType: "json",
                            type: "POST",
                            url: "pages/ajax/Keep_resep_even_hasCancel.php",
                            data: {
                                id_status: id_status,
                            },
                            success: function(response) {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Matching ' + idm + ' berhasil di Keep ke database !',
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Password yang anda masukan salah !',
                        })
                    }
                } else if (result.value !== "") {
                    console.log('button cancel clicked !')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Password yang anda masukan salah !',
                    })
                }
            });

        })
    })
</script>
<script>
    $(document).ready(function() {
        $('.btn.btn-xs.btn-danger.delete').click(function() {
            var idm = $(this).attr('idm');
            var id_matching = $(this).attr('id_matching');
            var id_status = $(this).attr('id_status');
            var no_order = $(this).attr('no_order');
            var why_batal = $(this).attr('why_batal');
            var password = '<?php echo $_SESSION['passLAB'] ?>';
            Swal.fire({
                title: "Autentikasi",
                text: "Untuk menghapus resep membutuhkan password setingkat SPV ?",
                input: 'password',
                showCancelButton: true,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    if (result.value == password) {
                        $.ajax({
                            dataType: "json",
                            type: "POST",
                            url: "pages/ajax/Delete_by_3ID.php",
                            data: {
                                idm: idm,
                                id_matching: id_matching,
                                id_status: id_status,
                                no_order: no_order,
                                why_batal: why_batal
                            },
                            success: function(response) {
                                console.log(response)
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Matching ' + idm + ' berhasil di hapus !',
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Password yang anda masukan salah !',
                        })
                    }
                } else if (result.value !== "") {
                    console.log('button cancel clicked !')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Password yang anda masukan salah !',
                    })
                }
            });
        })
    })
</script>
<script>
    $(document).ready(function() {
        $(".btn.btn-xs.btn-success.approve").click(function() {
            var idm = $(this).attr('idm');
            var id_status = $(this).attr('id_status');
            var id_matching = $(this).attr('id_matching');
            var no_order = $(this).attr('no_order');
            var benang = $(this).attr('benang');
            Swal.fire({
                title: 'Apakah anda yakin untuk approve ' + idm + ' ?',
                showCancelButton: true,
                confirmButtonText: `Save`,
                denyButtonText: `Don't save`,
            }).then((result) => {
                if (result.isConfirmed) {
                    // console.log(benang);
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Approve_resep.php",
                        data: {
                            id_status: id_status,
                        },
                        success: function(response) {
                            insertNomor_order(id_matching, id_status, idm, no_order, 'ORDER-ASAL', benang)
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }
            })
        })

        function insertNomor_order(id_matching, id_status, Rcode, no_order, lot, benang) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/insertNomor_order.php",
                data: {
                    id_matching: id_matching,
                    id_status: id_status,
                    Rcode: Rcode,
                    no_order: no_order,
                    lot: lot,
                    addt_benang: benang
                },
                success: function(response) {
                    if (response.session == "LIB_SUCCSS") {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Matching ' + Rcode + ' Sekarang telah approve !',
                            showConfirmButton: false,
                            // timer: 1500
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 1505);
                    } else {
                        toastr.error("ajax error !")
                    }
                },
                error: function() {
                    alert("Error hubungi DIT");
                }
            });
        }
    })
</script>
<script>
    $(document).ready(function() {
        $(".btn.btn-xs.btn-warning.Revised").click(function() {
            var idm = $(this).attr('idm')
            var id_status = $(this).attr('id_status')
            Swal.fire({
                icon: 'info',
                title: 'Revisi task dengan nomor resep ' + idm + '?',
                showCancelButton: true,
                confirmButtonText: 'Revisi',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Revisi_resep_and_GobackTo_Matcher.php",
                        data: {
                            idm: idm,
                            id_status: id_status,
                        },
                        success: function(response) {
                            console.log(response)
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Task ' + idm + ' Telah di kembalikan ke matcher!',
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
                    // Swal.fire(, '', 'success')
                }
            })
        })
    })
</script>
<script>
    $(document).ready(function() {
        var table = $('#Table-sm').DataTable({
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
                    "targets": [2, 10, 13, 14, 15, 16, 17, 18, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 42],
                    "visible": false
                },
                {
                    "targets": [0, 1, 2],
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
            if (d[38] == 'batal') {
                fn_getResep_by_idstatus_idmatching(d[37], d[39])
                return '<div class="col-md-12" style="background: #ff9494;">' +
                    '<div class="container-fluid">' +
                    '<table class="table table-striped table-bordered" id="tableee" width="100%" style="margin-top: 10px;">' +
                    '<tbody>' +
                    // 1
                    '<tr>' +
                    '<th>Alasan di batalakan :</th>' +
                    '<td>' + d[42] + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<th style="width:100px">Jenis Matching :</th>' +
                    '<td>' + d[18] + ' (' + d[5] + ')</td>' +
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
                    '<th>Std Cocok Warna :</th>' +
                    '<td>' + d[29] + '</td>' +
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
                    '<th>L:R</th>' +
                    '<td>' + d[32] + '</td>' +
                    '<th>Kadar Ph :</th>' +
                    '<td>' + d[33] + '</td>' +
                    '</tr>' +
                    // 6
                    '<tr>' +
                    '<th>Benang Aktual :</th>' +
                    '<td colspan="1">' + d[40] + '</td>' +
                    '<th>Keterangan :</th>' +
                    '<td colspan="5">' + d[34] + '</td>' +
                    // '<td align="center"><a href="pages/cetak/matching.php?idkk=' + d[5] + '" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print"></i> Print</a></td>' +
                    // '<td align="center"><a href="?p=Status-Handle&idm=' + d[37] + '" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Handle</a></td>' +
                    '</tr>' +
                    '</tbody>' +
                    '</table>' +
                    '<hr />' +
                    '<table class="table table-sm table-bordered">' +
                    '<tr>' +
                    '<th>#</th>' +
                    '<th>Kode</th>' +
                    '<th>Desc Kode</th>' +
                    '<th>Lab</th>' +
                    '<th>Adjust-1</th>' +
                    '<th>Adjust-2</th>' +
                    '<th>Adjust-3</th>' +
                    '<th>Adjust-4</th>' +
                    '<th>Adjust-5</th>' +
                    '<th>Adjust-6</th>' +
                    '<th>Adjust-7</th>' +
                    '<th>Adjust-8</th>' +
                    '<th>Adjust-9</th>' +
                    '</tr>' +
                    '<tbody id="' + 'rowresep_' + d[37] + '">' +
                    // APPEND HERE BUDS
                    '</tbody>' +
                    '</table>' +
                    '</div>' +
                    '</div>';
            } else {
                fn_getResep_by_idstatus_idmatching(d[37], d[39])
                return '<div class="col-md-12" style="background: #0275d8;">' +
                    '<div class="container-fluid">' +
                    '<table class="table table-striped table-bordered" id="tableee" width="100%" style="margin-top: 10px;">' +
                    '<tbody>' +
                    // 1
                    '<th style="width:100px">Jenis Matching :</th>' +
                    '<td>' + d[18] + ' (' + d[5] + ')</td>' +
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
                    '<th>Std Cocok Warna :</th>' +
                    '<td>' + d[29] + '</td>' +
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
                    '<th>L:R</th>' +
                    '<td>' + d[32] + '</td>' +
                    '<th>Kadar Ph :</th>' +
                    '<td>' + d[33] + '</td>' +
                    '</tr>' +
                    // 6
                    '<tr>' +
                    '<th>Benang Aktual :</th>' +
                    '<td colspan="1">' + d[40] + '</td>' +
                    '<th>Keterangan :</th>' +
                    '<td colspan="5">' + d[34] + '</td>' +
                    // '<td align="center"><a href="pages/cetak/matching.php?idkk=' + d[5] + '" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print"></i> Print</a></td>' +
                    // '<td align="center"><a href="?p=Status-Handle&idm=' + d[37] + '" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Handle</a></td>' +
                    '</tr>' +
                    '</tbody>' +
                    '</table>' +
                    '<hr />' +
                    '<table class="table table-sm table-bordered">' +
                    '<tr>' +
                    '<th>#</th>' +
                    '<th>Kode</th>' +
                    '<th>Desc Kode</th>' +
                    '<th>Lab</th>' +
                    '<th>Adjust-1</th>' +
                    '<th>Adjust-2</th>' +
                    '<th>Adjust-3</th>' +
                    '<th>Adjust-4</th>' +
                    '<th>Adjust-5</th>' +
                    '<th>Adjust-6</th>' +
                    '<th>Adjust-7</th>' +
                    '<th>Adjust-8</th>' +
                    '<th>Adjust-9</th>' +
                    '</tr>' +
                    '<tbody id="' + 'rowresep_' + d[37] + '">' +
                    // APPEND HERE BUDS
                    '</tbody>' +
                    '</table>' +
                    '</div>' +
                    '</div>';
            }
        }

        function fn_getResep_by_idstatus_idmatching(id_status, id_matching) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: 'pages/ajax/fn_getResep_by_idstatus_idmatching.php',
                data: {
                    id_status: id_status,
                    id_matching: id_matching
                },
                success: function(response) {
                    var lastitem = '0';
                    var i;
                    var tr = $("#rowresep_" + id_status);
                    tr.empty();
                    $.each(response, function(index, item) {
                        tr.append(
                            '<tr>' +
                            '<td>' + item[0] + '</td>' +
                            '<td>' + item[1] + '</td>' +
                            '<td>' + item[12] + '</td>' +
                            '<td class="' + item[2] + '">' + item[2] + '</td>' +
                            '<td class="' + item[3] + '">' + item[3] + '</td>' +
                            '<td class="' + item[4] + '">' + item[4] + '</td>' +
                            '<td class="' + item[5] + '">' + item[5] + '</td>' +
                            '<td class="' + item[6] + '">' + item[6] + '</td>' +
                            '<td class="' + item[7] + '">' + item[7] + '</td>' +
                            '<td class="' + item[8] + '">' + item[8] + '</td>' +
                            '<td class="' + item[9] + '">' + item[9] + '</td>' +
                            '<td class="' + item[10] + '">' + item[10] + '</td>' +
                            '<td class="' + item[11] + '">' + item[11] + '</td>' +
                            '</tr>'
                        )
                    });
                },
                error: function() {
                    alert("Error");
                }
            });
        }
    });
</script>

</html>