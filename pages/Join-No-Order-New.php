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
        font-size: 9pt;
    }

    #Table-sm td,
    #Table-sm th {
        border: 0.1px solid #ddd;
    }

    #Table-sm th {
        color: black;
        background: #4CAF50;
    }

    #Table-sm_filter label input.form-control {
        width: 500px;
    }

    #Table-sm tr:hover {
        background-color: rgb(151, 170, 212);
    }

    #Table-sm>thead>tr>td {
        border: 1px solid #ddd;
    }

    .btn-circle {
        border-radius: 10px;
        color: black;
        font-weight: 800;
    }

    .btn-grp>a,
    .btn-grp>button {
        margin-top: 2px;
    }
</style>
<?php
    $Nowarna	= isset($_POST['nowarna']) ? $_POST['nowarna'] : '';
    $Item	    = isset($_POST['item']) ? $_POST['item'] : '';
    $JMatching	= isset($_POST['jenis_matching']) ? $_POST['jenis_matching'] : '';
    $RCode	    = isset($_POST['rcode']) ? $_POST['rcode'] : '';
    $Warna	    = isset($_POST['warna']) ? $_POST['warna'] : '';
    $Order	    = isset($_POST['order']) ? $_POST['order'] : '';
?>
<body>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"> Filter Data</h3>
                    <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-2">
                                <input name="rcode" type="text" class="form-control pull-right" id="rcode" placeholder="RCode" value="<?php echo $RCode;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="nowarna" type="text" class="form-control pull-right" id="nowarna" placeholder="No Warna" value="<?php echo $Nowarna;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="item" type="text" class="form-control pull-right" id="item" placeholder="No Item" value="<?php echo $Item;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="jenis_matching" type="text" class="form-control pull-right" id="jenis_matching" placeholder="Jenis Matching" value="<?php echo $JMatching;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="warna" type="text" class="form-control pull-right" id="warna" placeholder="Warna" value="<?php echo $Warna;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="order" type="text" class="form-control pull-right" id="order" placeholder="No Order" value="<?php echo $Order;  ?>" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-block btn-social btn-linkedin btn-sm" name="save" style="width: 60%">Search <i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                        <?php
                        if($Nowarna!="" or $Item!="" or $JMatching!="" or $RCode!="" or $Warna!="" or $Order!=""){
                        $sql = mysqli_query($con,"SELECT a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by,
                                            b.jenis_matching, a.matcher, a.idm, b.no_order, b.langganan, b.no_warna, b.warna, b.no_item, b.no_po, b.cocok_warna, a.approve_at, a.status, b.benang
                                            FROM tbl_status_matching a
                                            JOIN tbl_matching b ON a.idm = b.no_resep
                                            WHERE a.approve = 'TRUE' AND a.status = 'selesai' AND a.idm LIKE '%$RCode%' AND b.no_warna LIKE '%$Nowarna%' AND b.no_item LIKE '%$Item%' AND b.jenis_matching LIKE '%$JMatching%' AND b.warna LIKE '%$Warna%' AND b.no_order LIKE '%$Order%' ORDER BY a.id DESC");
                        }else{
                        $sql = mysqli_query($con,"SELECT a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by,
                                            b.jenis_matching, a.matcher, a.idm, b.no_order, b.langganan, b.no_warna, b.warna, b.no_item, b.no_po, b.cocok_warna, a.approve_at, a.status, b.benang
                                            FROM tbl_status_matching a
                                            JOIN tbl_matching b ON a.idm = b.no_resep
                                            WHERE a.approve = 'TRUE' AND a.status = 'selesai' ORDER BY a.id DESC LIMIT 100");    
                        }
                        ?>
                        <table id="Table-sm" class="table table-sm display compact" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="9%" align="center">####</th>
                                    <th width="8%" align="center">J. Matching</th>
                                    <th style="font-size: 14px;" width="8%">Rcode</th>
                                    <th width="8%">No. Order</th>
                                    <th width="15%">Langganan</th>
                                    <th width="15%">Warna</th>
                                    <th width="8%">No. Warna</th>
                                    <th width="8%">No. Item</th>
                                    <th width="8%">Benang</th>
                                    <th width="15%">Cck-warna</th>
                                    <th width="10%">Tg. Apprv</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while ($row = mysqli_fetch_array($sql)) { ?>
                                    <tr>
                                        <td align="center">
                                            <?php echo $no;  
                                            if ($_SESSION['jabatanLAB'] == 'Lab Head' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader') { ?>
                                            <a href="javascript:void(0)" class="btn btn-xs btn-success _arsip" data-rcode="<?php echo $row['idm']; ?>" data-pk="<?php echo $row['id_status']; ?>" title="Arsipkan"><i class="fa fa-archive"></i></a>
                                            <?php } else if ($_SESSION['jabatanLAB'] == 'Colorist' or $_SESSION['jabatanLAB'] == 'Other') { ?>
                                                <li class="btn-group" role="group">
                                                    <a href="index1.php?p=Adjust_Resep_Lab_New&idm=<?php echo $row['id_status'];?>" class="btn btn-warning btn-xs" title="Perbarui Resep">
                                                    <i class="fa fa-pencil"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-xs btn-success _arsip" data-rcode="<?php echo $row['idm']; ?>" data-pk="<?php echo $row['id_status']; ?>" title="Arsipkan"><i class="fa fa-archive"></i></a>
                                                </li>
                                            <?php } else if ($_SESSION['jabatanLAB'] == 'Bon order') {?>
                                                <li class="btn-group" role="group">
                                                    <a href="javascript:void(0)" data-attribute="<?php echo $row['id_status']; ?>" class="btn btn-info btn-xs _merge" title="Add Order"><i class="fa fa-link"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-xs btn-success _arsip" data-rcode="<?php echo $row['idm']; ?>" data-pk="<?php echo $row['id_status']; ?>" title="Arsipkan"><i class="fa fa-archive"></i></a>
                                                </li>
                                            <?php } else { ?>
                                                <li class="btn-group" role="group">
                                                    <a href="javascript:void(0)" data-attribute="<?php echo $row['id_status']; ?>" class="btn btn-info btn-xs _merge" title="Add Order"><i class="fa fa-link"></i></a>
                                                    <a href="index1.php?p=Adjust_Resep_Lab_New&idm=<?php echo $row['id_status']; ?>" class="btn btn-warning btn-xs" title="Perbarui Resep"><i class="fa fa-pencil"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-xs btn-success _arsip" data-rcode="<?php echo $row['idm']; ?>" data-pk="<?php echo $row['id_status']; ?>" title="Arsipkan"><i class="fa fa-archive"></i></a>
                                                </li>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $row['jenis_matching'] ?></td>
                                        <td style="font-size: 14px;"><strong><i><?php echo $row['idm'] ?></i></strong></td>
                                        <td><?php echo $row['no_order'] ?></td>
                                        <td><?php echo $row['langganan'] ?></td>
                                        <td><?php echo $row['warna'] ?></td>
                                        <td><?php echo $row['no_warna'] ?></td>
                                        <td><?php echo $row['no_item'] ?></td>
                                        <td><?php echo $row['benang'] ?></td>
                                        <td><?php echo $row['cocok_warna'] ?></td>
                                        <td><?php echo substr($row['approve_at'], 0, 10) ?></td>
                                    </tr>
                                <?php $no++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-3d-slit" id="ModalMergeOrder" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div id="body_ModalMergeOrder" class="modal-dialog" style="width:95%">

        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function() {
        const myTable = $('#Table-sm').DataTable({
            "ordering": false,
            "pageLength": 25,
            responsive: true,
            language: {
                searchPlaceholder: "Search..."
            },
        })

        $(document).on('click', '._hapus', function() {
            let rcode = $(this).closest('tr').find('td:eq(1)').text()
            let tr = $(this).closest('tr');

            Swal.fire({
                title: 'Apakah anda yakin ?',
                text: `Untuk Menghapus matching dengan Kode ${rcode}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/delete_schedule_matching.php",
                        data: {
                            rcode: rcode
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                Swal.fire(
                                    'Deleted!',
                                    'Your data has been deleted.',
                                    'success'
                                )
                                myTable.row(tr).remove().draw();;
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }
            })
        })

        $(document).on('click', '._bagikan', function() {
            let rcode = $(this).closest('tr').find('td:eq(1)').text()

            Swal.fire({
                title: 'Apakah anda yakin ?',
                text: `untuk membagikan resep dengan kode ${rcode}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, bagikan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/bagikan_schedule_matching.php",
                        data: {
                            rcode: rcode
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                Swal.fire(
                                    'Berhasil!',
                                    'Data resep telah siap untuk di bagikan',
                                    'success'
                                )
                                setTimeout(function() {
                                    window.location.reload(1);
                                }, 1000);
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }
            })
        })

        // $(document).on('click', '._tunggukan', function() {
        //     let rcode = $(this).closest('tr').find('td:eq(1)').text()

        //     Swal.fire({
        //         title: 'Apakah anda yakin ?',
        //         text: `untuk mengubah status menjadi tunggu ${rcode}`,
        //         icon: 'info',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, Tunggukan!'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 dataType: "json",
        //                 type: "POST",
        //                 url: "pages/ajax/tunggukan_schedule_matching.php",
        //                 data: {
        //                     rcode: rcode
        //                 },
        //                 success: function(response) {
        //                     if (response.session == "LIB_SUCCSS") {
        //                         Swal.fire(
        //                             'Berhasil!',
        //                             'Data resep telah berubah status menjadi tunggu',
        //                             'success'
        //                         )
        //                         setTimeout(function() {
        //                             window.location.reload(1);
        //                         }, 1000);
        //                     } else {
        //                         toastr.error("ajax error !")
        //                     }
        //                 },
        //                 error: function() {
        //                     alert("Error");
        //                 }
        //             });
        //         }
        //     })
        // })

        $(document).on('click', '._tunggukan', function() {
            let rcode = $(this).closest('tr').find('td:eq(1)').text()
            Swal.fire({
                title: "Status tunggu !",
                text: "Berikan alasan mengapa status > tunggu ",
                input: 'textarea',
                inputPlaceholder: 'Beri alasan kenapa status di ubah menjadi tunggu ...',
                showCancelButton: true,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/tunggukan_schedule_matching.php",
                        data: {
                            rcode: rcode,
                            why: result.value
                        },
                        success: function(response) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Matching ' + rcode + ' telah di rubah menjadi Tunggu !',
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
                    Swal.fire('keterangan wajib di isi !')
                }
            });
        })

        $(document).on('click', '._merge', function(e) {
            var m = $(this).attr("data-attribute");
            $.ajax({
                url: "pages/ajax/modal_merge_order.php",
                type: "GET",
                data: {
                    idm: m,
                },
                success: function(ajaxData) {
                    $("#body_ModalMergeOrder").html(ajaxData);
                    $("#ModalMergeOrder").modal('show', {
                        backdrop: 'false'
                    });
                }
            });
        });

        $(document).on('click', '._arsip', function(e) {
            let id_status = $(this).attr('data-pk');
            let r_code = $(this).attr('data-rcode');
            Swal.fire({
                title: 'Arsipkan Status ' + r_code,
                input: 'select',
                inputOptions: {
                    'Status': {
                        selesai: 'selesai',
                        arsip: 'arsip',
                    }
                },
                inputPlaceholder: '-Pilih Status-',
                showCancelButton: true,
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value === 'arsip') {
                            $.ajax({
                                dataType: "json",
                                type: "POST",
                                url: 'pages/ajax/action_arsip_resep.php',
                                data: {
                                    id_status: id_status,
                                    arsip: value,
                                },
                                success: function(response) {
                                    toastr.success('Resep telah di arsipkan');
                                    Swal.close()
                                    Location.reload()
                                },
                                error: function() {
                                    alert("Error");
                                }
                            });

                        } else {
                            resolve('You need to select arsip !')
                        }
                    })
                },
            })
        });
    })
</script>

<script>
    $(document).ready(function() {
        $("#ModalMergeOrder").on("hidden.bs.modal", function() {
            $("#body_ModalMergeOrder").empty();
        });
    })
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
            window.location.href = 'index1.php?p=Wait-approval';
        }, 1000);
    }
</script>