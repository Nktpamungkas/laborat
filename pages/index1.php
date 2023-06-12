<?php
session_start();
include_once('koneksi.php');
?>
<?php
if (!isset($_SESSION['userLAB'])) {
?>
    <script>
        setTimeout("location.href='login'", 500);
    </script>
<?php
    die('Illegal Acces');
} else if (!isset($_SESSION['passLAB'])) {
?>
    <script>
        setTimeout("location.href='lockscreen'", 500);
    </script>
<?php
    die('Illegal Acces');
}

$page = isset($_GET['p']) ? $_GET['p'] : '';
$act  = isset($_GET['act']) ? $_GET['act'] : '';
$id   = isset($_GET['id']) ? $_GET['id'] : '';
$page = strtolower($page);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laborat | <?php if ($_GET[p] != "") {
                            echo ucwords($_GET[p]);
                        } else {
                            echo "Home";
                        } ?></title>
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="bower_components/DataTable/jQuery-3.3.1/jQuery-3.3.1.min.js"></script>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="bower_components/xeditable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css">
    <link href="bower_components/toastr/toastr.css" rel="stylesheet">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="bower_components/custom-table.css">
    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="bower_components/DataTable/datatables.min.css">
    <link rel="stylesheet" href="bower_components/select2/css/select2.min.css">
    <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link href="bower_components/sweet-alert/dist/sweetalert2.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="bower_components/sweet-alert/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="bower_components/circle-indicator-spinner/dist/jquery-spinner.min.css">
    <script type="text/javascript" src="bower_components/circle-indicator-spinner/dist/jquery-spinner.min.js"></script>
    <style>
        .blink_me {
            animation: blinker 1s linear infinite;
        }

        .bulat {
            border-radius: 50%;
        }

        .border-dashed {
            border: 3px dashed #083255;
        }

        .border-dashed-tujuan {
            border: 3px dashed #FF0007;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }

        body {
            font-family: Calibri, "sans-serif", "Courier New";
            font-style: normal;
        }
    </style>

    <link rel="icon" type="image/png" href="dist/img/index.ico">
</head>

<body class="hold-transition skin-black-light layout-top-nav">
    <div class="row">
        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a href="?p=Home" class="navbar-brand"><b>Laborat</b></a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="<?php if ($_GET[p] == "Home" or $_GET[p] == "") {
                                            echo "active";
                                        } ?>"><a href="?p=Home"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                            </li>



                            <!-- GENERATE KARTU MATCHING -->
                            <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Other' or $_SESSION['jabatanLAB'] == 'Bon order') : ?>
                                <li class="<?php if ($_GET[p] == "Form-Matching") {
                                                echo "active";
                                            } ?>"><a href="?p=Form-Matching"><i class="fa fa-tag text-yellow"></i> <span>Kartu Matching</span></a>
                                </li>
                            <?php endif; ?>



                            <!-- ATUR SCHEDULE -->
                            <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Bon order') : ?>
                                <li class="<?php if ($_GET[p] == "Schedule-Matching") {
                                                echo "active";
                                            } ?>"><a href="?p=Schedule-Matching"><i class="fa fa-database text-green"></i><span> Atur Schedule</span></a>
                                </li>
                            <?php endif; ?>


                            <!-- STATUS & RESEP -->
                            <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Matcher' or $_SESSION['jabatanLAB'] == 'Bon order') : ?>
                                <li class="<?php if ($_GET[p] == "Status-Matching" or $_GET[p] == 'Status-Handle' or $_GET[p] == 'Hold-Handle') {
                                                echo "active";
                                            } ?>"><a href="?p=Status-Matching"><i class="fa fa-exchange text-purple"></i> <span>Status & Resep</span></a>
                                </li>
                            <?php endif; ?>



                            <!-- APPROVAL ? -->
                            <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Lab Head' or $_SESSION['jabatanLAB'] == 'Super matcher') : ?>
                                <li class="<?php if ($_GET[p] == "Wait-approval" or $_GET[p] == 'Detail-status-wait-approve') {
                                                echo "active";
                                            } ?>"><a href="?p=Wait-approval"><i class="fa fa-hourglass-half text-danger"></i> <span>Approval ?</span></a>
                                </li>
                            <?php endif; ?>


                            <!-- DATA BASE RESEP GROUP -->
                            <li class="dropdown <?php if ($_GET[p] == "Today-Approved" or $_GET[p] == 'Detail-status-approved' or $_GET[p] == "Today-Rejected" or $_GET[p] == 'Detail-status-rejected' or $_GET[p] == "Report-Matching" or $_GET[p] == 'DataBase-resep' or $_GET[p] == 'Report-Rejected' or $_GET[p] == "Dyestuff_Utilization" or $_GET[p] == "Perform-report") {
                                                    echo "active";
                                                } ?>">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-flask" aria-hidden="true"></i>
                                    <span>DataBase Resep</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-fw fa-angle-down pull-right"></i>
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="<?php if ($_GET[p] == "DataBase-resep" or $_GET[p] == "Detail-status-approved") {
                                                    echo "active";
                                                } ?>"><a href="?p=DataBase-resep"><i class="fa fa-fw fa-file-pdf-o text-success" aria-hidden="true"></i>
                                            <span>Resep</span></a>
                                    </li>
                                    <li class="<?php if ($_GET[p] == "Perform-report") {
                                                    echo "active";
                                                } ?>"><a href="?p=Perform-report"><i class="fa fa-fw fa-address-card text-primary" aria-hidden="true"></i>
                                            <span>Perform-report</span></a>
                                    </li>
                                    <li class="<?php if ($_GET[p] == "Report-Matching") {
                                                    echo "active";
                                                } ?>"><a href="?p=Report-Matching"><i class="fa fa-fw fa-calendar" aria-hidden="true"></i>
                                            <span>Search By date</span></a>
                                    </li>
                                    <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Lab Head' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Leader') : ?>
                                        <li class="<?php if ($_GET[p] == "Arsip") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Arsip"><i class="fa fa-fw fa-archive text-green" aria-hidden="true"></i>
                                                <span>Arsip</span></a>
                                        </li>
                                        <li class="<?php if ($_GET[p] == "Report-Rejected") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Report-Rejected"><i class="fa fa-fw fa-trash text-danger" aria-hidden="true"></i>
                                                <span>All Rejected</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <li class="<?php if ($_GET[p] == "Dyestuff_Utilization") {
                                                    echo "active";
                                                } ?>"><a href="?p=Dyestuff_Utilization"><i class="fa fa-code text-warning" aria-hidden="true"></i>
                                            <span>Dyestuff Utilization</span></a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Modifikasi Data -->
                            <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Lab Head' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Colorist' or $_SESSION['jabatanLAB'] == 'Bon order' or $_SESSION['jabatanLAB'] == 'Leader') : ?>
                                <li class="<?php if ($_GET[p] == "Join-No-Order" or $_GET[p] == 'Adjust_Resep_Lab') {
                                                echo "active";
                                            } ?>"><a href="?p=Join-No-Order"><i class="fa fa-adjust" aria-hidden="true"></i>
                                        <span>Modifikasi Data</span></a>
                                </li>
                            <?php endif; ?>

                            <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Bon order') : ?>
                                <li class="dropdown <?php if ($_GET[p] == "User" or $_GET[p] == "Matcher") {
                                                        echo "active";
                                                    } ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gears text-yellow"></i> <span>Lain-lain</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-fw fa-angle-down pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="<?php if ($_GET[p] == "Manage-Dyestuff") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Manage-Dyestuff"><i class="fa fa-plus-square"></i> <span>Manage-Dyestuff</span></a>
                                        </li>
                                        <li class="<?php if ($_GET[p] == "Lampu-Buyer") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Lampu-Buyer"><i class="fa fa-lightbulb-o"></i> <span>Lampu-Buyer</span></a>
                                        </li>
                                        <li class="<?php if ($_GET[p] == "Manage-Proses") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Manage-Proses"><i class="fa fa-spinner"></i> <span>Manage-Proses</span></a>
                                        </li>
                                        <li class="<?php if ($_GET[p] == "Log_Matching") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Log_Matching"><i class="glyphicon glyphicon-resize-small" aria-hidden="true"></i>
                                                <span>Log Perlakuan Resep</span></a>
                                        </li>
                                        <?php if ($_SESSION['jabatanLAB'] == 'Super admin') : ?>
                                            <li class="<?php if ($_GET[p] == "User") {
                                                            echo "active";
                                                        } ?>"><a href="?p=User"><i class="fa fa-user text-blue"></i> <span>User</span></a>
                                            </li>
                                            <li class="<?php if ($_GET[p] == "announcement") {
                                                            echo "active";
                                                        } ?>"><a href="?p=announcement"><i class="fa fa-volume-up" aria-hidden="true"></i>
                                                    <span>announcement</span></a>
                                            </li>
                                        <?php endif; ?>
                                        <li class="<?php if ($_GET[p] == "Matcher") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Matcher"><i class="fa fa-user text-green"></i> <span>Matcher</span></a>
                                        </li>
                                        <li class="<?php if ($_GET[p] == "Colorist") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Colorist"><i class="fa fa-user text-yellow"></i> <span>Colorist</span></a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Setting<span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="<?php if ($_GET[p] == "Change-password") {
                                                    echo "active";
                                                } ?>"><a href="?p=Change-password"><i class="fa fa-key"></i> <span>Password</span></a>
                                    </li>

                                    <li class="<?php if ($_GET[p] == "Petunjuk_penggunaan") {
                                                    echo "active";
                                                } ?>"><a href="?p=Petunjuk_penggunaan"><i class="fa fa-question text-primary" aria-hidden="true"></i>
                                            <span>Guide</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Messages: style can be found in dropdown.less-->
                            <li class="dropdown messages-menu">
                                <!-- Menu toggle button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-envelope-o"></i>
                                    <span class="label label-success">4</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 4 messages</li>
                                    <li>
                                        <!-- inner menu: contains the messages -->
                                        <ul class="menu">
                                            <li>
                                                <!-- start message -->
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <!-- User Image -->
                                                        <!-- <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
                                                    </div>
                                                    <!-- Message title and timestamp -->
                                                    <h4>
                                                        Support Team
                                                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                    </h4>
                                                    <!-- The message -->
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                            <!-- end message -->
                                        </ul>
                                        <!-- /.menu -->
                                    </li>
                                    <li class="footer"><a href="#">See All Messages</a></li>
                                </ul>
                            </li>
                            <!-- /.messages-menu -->

                            <!-- Notifications Menu -->
                            <li class="dropdown notifications-menu">
                                <!-- Menu toggle button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-bell-o"></i>
                                    <span class="label label-warning">10</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 10 notifications</li>
                                    <li>
                                        <!-- Inner Menu: contains the notifications -->
                                        <ul class="menu">
                                            <li>
                                                <!-- start notification -->
                                                <a href="#">
                                                    <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                                </a>
                                            </li>
                                            <!-- end notification -->
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="#">View all</a></li>
                                </ul>
                            </li>
                            <!-- Tasks Menu -->
                            <li class="dropdown tasks-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-flag-o"></i>
                                    <span class="label label-danger">9</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 9 tasks</li>
                                    <li>
                                        <!-- Inner menu: contains the tasks -->
                                        <ul class="menu">
                                            <li>
                                                <!-- Task item -->
                                                <a href="#">
                                                    <!-- Task title and progress text -->
                                                    <h3>
                                                        Design some buttons
                                                        <small class="pull-right">20%</small>
                                                    </h3>
                                                    <!-- The progress bar -->
                                                    <div class="progress xs">
                                                        <!-- Change the css width attribute to simulate progress -->
                                                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">20% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                        </ul>
                                    </li>
                                    <li class="footer">
                                        <a href="#">View all tasks</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- User Account Menu -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <!-- The user image in the navbar-->
                                    <img src="dist/img/<?php echo $_SESSION['fotoLAB']; ?>" class="user-image" alt="User Image">
                                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                    <span class="hidden-xs"><?php echo $_SESSION['userLAB'] ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- The user image in the menu -->
                                    <li class="user-header">
                                        <img src="dist/img/<?php echo $_SESSION['fotoLAB']; ?>" class="user-image" alt="User Image">

                                        <p>
                                            <?php echo $_SESSION['userLAB'] ?>
                                            <small>PT. Indo Taichen Textile Industry</small>
                                        </p>
                                    </li>
                                    <!-- Menu Body -->
                                    <li class="user-body">
                                        <div class="row">
                                        </div>
                                        <!-- /.row -->
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="javascript:void(0)" id="logout" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navbar-custom-menu -->
                </div>
                <!-- /.container-fluid -->
            </nav>
        </header>
        <!-- Full Width Column -->
        <div class="content-wrapper">
            <div class="row">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                </section>

                <!-- Main content -->
                <section class="content">
                    <?php
                    if (!empty($page) and !empty($act)) {
                        $files = 'pages/' . $page . '.' . $act . '.php';
                    } else if (!empty($page)) {
                        $files = 'pages/' . $page . '.php';
                    } else {
                        $files = 'pages/home.php';
                    }

                    if (file_exists($files)) {
                        include_once($files);
                    } else {
                        include_once("blank.php");
                    }
                    ?>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.container -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="container">
                <div class="pull-right hidden-xs">
                    <b>Laborat</b> V.2.0
                </div>
                <strong>Copyright &copy; 2020 <a href="javascript:void(0)">PT. Indotaichen Textile Industry</a>.</strong> DIT Departement
            </div>
            <!-- /.container -->
        </footer>
    </div>
    <!-- ./wrapper -->
</body>

<!-- REQUIRED JS SCRIPTS -->
<script src="dist/js/adminlte.min.js"></script>
<script src="bower_components/DataTable/datatables.min.js"></script>
<script src="bower_components/DataTable/dataTables.rowsGroup.js"></script>
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="bower_components/select2/js/select2.full.min.js"></script>
<script src="bower_components/toastr/toastr.js"></script>
<script type="text/javascript" src="bower_components/xeditable/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="bower_components/jquery_validation/jquery.validate.min.js"></script>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- <script>
    $(document).ready(function() {
      $(document).ready(function() {
        $('.select2').select2({
          placeholder: "Pilih...",
          tags: true
        });
      });
    })
  </script> -->
<script>
    //Date picker
    $('#datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        }),
        //Date picker
        $('#datepicker1').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        }),
        //Date picker
        $('#datepicker2').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        }),
        //Date picker
        $('#datepicker3').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        })
    $('.form-control.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    })
</script>
<script>
    $(function() {

        $('#example1').DataTable({
            'scrollX': true,
            'paging': true,

        })
        $('#example2').DataTable()
        $('#example3').DataTable({
            'scrollX': true,
            dom: 'Bfrtip',
            buttons: [
                'excel',
                {
                    orientation: 'portrait',
                    pageSize: 'LEGAL',
                    extend: 'pdf',
                    footer: true,
                },
            ]
        })
        $('#example4').DataTable({
            'paging': false,
        })

    })
</script>
<!-- Javascript untuk popup modal Edit-->
<script type="text/javascript">
    $(document).on('click', '.open_detail', function(e) {
        var m = $(this).attr("id");
        $.ajax({
            url: "pages/report-detail.php",
            type: "GET",
            data: {
                id: m,
            },
            success: function(ajaxData) {
                $("#Detail").html(ajaxData);
                $("#Detail").modal('show', {
                    backdrop: 'true'
                });
            }
        });
    });
    $(document).on('click', '.open_detail_matching', function(e) {
        var m = $(this).attr("id");
        $.ajax({
            url: "pages/report-detail-matching.php",
            type: "GET",
            data: {
                id: m,
            },
            success: function(ajaxData) {
                $("#DetailMatching").html(ajaxData);
                $("#DetailMatching").modal('show', {
                    backdrop: 'true'
                });
            }
        });
    });
    $(document).on('click', '.user_edit', function(e) {
        var m = $(this).attr("id");
        $.ajax({
            url: "pages/user_edit.php",
            type: "GET",
            data: {
                id: m,
            },
            success: function(ajaxData) {
                $("#UserEdit").html(ajaxData);
                $("#UserEdit").modal('show', {
                    backdrop: 'true'
                });
            }
        });
    });
    $(document).on('click', '.matcher_edit', function(e) {
        var m = $(this).attr("id");
        $.ajax({
            url: "pages/matcher_edit.php",
            type: "GET",
            data: {
                id: m,
            },
            success: function(ajaxData) {
                $("#MatcherEdit").html(ajaxData);
                $("#MatcherEdit").modal('show', {
                    backdrop: 'true'
                });
            }
        });
    });
    $(document).on('click', '.dataMatching_edit', function(e) {
        var m = $(this).attr("id");
        $.ajax({
            url: "pages/datamatching_edit.php",
            type: "GET",
            data: {
                id: m,
            },
            success: function(ajaxData) {
                $("#DataMatchingEdit").html(ajaxData);
                $("#DataMatchingEdit").modal('show', {
                    backdrop: 'true'
                });
            }
        });
    });
    $(document).on('click', '.groupa_edit', function(e) {
        var m = $(this).attr("id");
        $.ajax({
            url: "pages/group_edit.php",
            type: "GET",
            data: {
                id: m,
            },
            success: function(ajaxData) {
                $("#GroupAEdit").html(ajaxData);
                $("#GroupAEdit").modal('show', {
                    backdrop: 'true'
                });
            }
        });
    });
    $(document).on('click', '.groupb_edit', function(e) {
        var m = $(this).attr("id");
        $.ajax({
            url: "pages/group_edit.php",
            type: "GET",
            data: {
                id: m,
            },
            success: function(ajaxData) {
                $("#GroupBEdit").html(ajaxData);
                $("#GroupBEdit").modal('show', {
                    backdrop: 'true'
                });
            }
        });
    });
    $(document).on('click', '.groupc_edit', function(e) {
        var m = $(this).attr("id");
        $.ajax({
            url: "pages/group_edit.php",
            type: "GET",
            data: {
                id: m,
            },
            success: function(ajaxData) {
                $("#GroupCEdit").html(ajaxData);
                $("#GroupCEdit").modal('show', {
                    backdrop: 'true'
                });
            }
        });
    });
    $(document).on('click', '.groupd_edit', function(e) {
        var m = $(this).attr("id");
        $.ajax({
            url: "pages/group_edit.php",
            type: "GET",
            data: {
                id: m,
            },
            success: function(ajaxData) {
                $("#GroupDEdit").html(ajaxData);
                $("#GroupDEdit").modal('show', {
                    backdrop: 'true'
                });
            }
        });
    });
    $(function() {
        $("#tablee").dataTable({
            "pageLength": 15,
        });
        $("#lookup").dataTable({
            "pageLength": 15,
        });
    });
    $(document).on('click', '.pilih', function(e) {
        document.getElementById("no_resep").value = $(this).attr('data-resep');
        document.getElementById("no_resep").focus();
        $('#myModal').modal('hide');
    });
</script>

</html>
<script>
    $(document).ready(function() {
        $("#logout").click(function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "To leave Laborat application",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d9534f',
                cancelButtonColor: '#0275d8',
                confirmButtonText: 'Logout',
                cancelButtonText: 'Stay'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout'
                }
            })
        })
    })
</script>