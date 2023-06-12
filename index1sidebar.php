<?php
ini_set("error_reporting", 1);
session_start();
include ('koneksi.php');
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
  <title>Laborat | <?php if ($_GET['p'] != "") {
                      echo ucwords($_GET['p']);
                    } else {
                      echo "Home";
                    } ?></title>
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="bower_components/DataTable/jQuery-3.3.1/jQuery-3.3.1.min.js"></script>
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link href="bower_components/toastr/toastr.css" rel="stylesheet">
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bower_components/custom-table.css">
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="bower_components/DataTable/datatables.min.css">
  <link rel="stylesheet" href="bower_components/select2/css/select2.min.css">
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="dist/css/skins/skin-purple.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
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

<body class="hold-transition skin-purple sidebar-mini fixed sidebar-collapse" id="block-full-page">
  <div class="wrapper">

    <header class="main-header ">

      <a href="?p=Home" class="logo">
        <span class="logo-mini"><b>Lab</b></span>
        <span class="logo-lg"><b>Lab</b>orat</span>
      </a>
      <?php $qryMt = mysqli_query($con,"SELECT COUNT(*) as jml from tbl_status_matching WHERE `status`='buka'");
      $rMt = mysqli_fetch_array($qryMt);
      ?>
      <?php $qryGp = mysqli_query("SELECT
	sum(if(grp='A',1,0)) AS grpa,
  sum(if(grp='B',1,0)) AS grpb,
  sum(if(grp='C',1,0)) AS grpc,
  sum(if(grp='D',1,0)) AS grpd
FROM
	tbl_status_matching
WHERE
	`status` = 'mulai'");
      $rGp = mysqli_fetch_array($qryGp);
      ?>
      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Notifications Menu -->
            <li class="dropdown notifications-menu">
              <!-- Menu toggle button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-info"><?php echo $rMt['jml']; ?></span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have <?php echo $rMt['jml']; ?> notifications</li>
                <li>
                  <!-- Inner Menu: contains the notifications -->
                  <ul class="menu">
                    <?php if ($rGp['grpa'] > 0) { ?>
                      <li>
                        <!-- start notification -->
                        <a href="?p=Group-A">
                          <i class="fa text-orange">A</i> <strong><?php echo $rGp['grpa']; ?></strong> new matching
                        </a>
                      </li>
                    <?php } ?>
                    <?php if ($rGp['grpb'] > 0) { ?>
                      <li>
                        <!-- start notification -->
                        <a href="?p=Group-B">
                          <i class="fa text-green">B</i> <strong><?php echo $rGp['grpb']; ?></strong> new matching
                        </a>
                      </li>
                    <?php } ?>
                    <?php if ($rGp['grpc'] > 0) { ?>
                      <li>
                        <a href="?p=Group-C">
                          <i class="fa text-purple">C</i> <strong><?php echo $rGp['grpc']; ?></strong> new matching
                        </a>
                      </li>
                    <?php } ?>
                    <?php if ($rGp['grpd'] > 0) { ?>
                      <li>
                        <a href="?p=Group-D">
                          <i class="fa  text-aqua">D</i> <strong><?php echo $rGp['grpd']; ?></strong> new matching
                        </a>
                      </li>
                    <?php } ?>
                    <!-- end notification -->
                  </ul>
                </li>
                <li class="footer"><a href="?p=Status-Matching">View all</a></li>
              </ul>
            </li>
            <!-- Tasks Menu -->
            <!-- User Account Menu -->
            <li class="dropdown user user-menu" title="logout">
              <!-- Menu Toggle Button -->
              <a href="#" id="logout">
                <!-- The user image in the navbar-->
                <img src="dist/img/<?php echo $_SESSION['fotoLAB']; ?>" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"><?php echo strtoupper($_SESSION['userLAB']); ?> <i class="fa fa-sign-out" aria-hidden="true"></i>
                </span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="dist/img/<?php echo $_SESSION['fotoLAB']; ?>" class="img-circle" alt="User Image">

                  <p>
                    <?php echo strtoupper($_SESSION['userLAB']); ?> - <?php echo $_SESSION['jabatanLAB']; ?>
                    <small>Member since <?php echo $_SESSION['mamberLAB']; ?></small>
                  </p>
                </li>
                </a>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="lockscreen" class="btn btn-default btn-flat">LockScreen</a>
                  </div>
                  <div class="pull-right">
                    <a href="logout" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
            <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="dist/img/<?php echo $_SESSION['fotoLAB']; ?>" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?php echo strtoupper($_SESSION['userLAB']); ?></p>
            <!-- Status -->
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">HEADER</li>
          <!-- Optionally, you can add icons to the links -->

          <li class="<?php if ($_GET['p'] == "Home" or $_GET['p'] == "") {
                        echo "active";
                      } ?>"><a href="?p=Home"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
          </li>



          <!-- GENERATE KARTU MATCHING -->
          <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Other' or $_SESSION['jabatanLAB'] == 'Bon order') : ?>
            <li class="<?php if ($_GET['p'] == "Form-Matching") {
                          echo "active";
                        } ?>"><a href="?p=Form-Matching"><i class="fa fa-tag text-yellow"></i> <span>Generate Kartu Matching</span></a>
            </li>
          <?php endif; ?>



          <!-- ATUR SCHEDULE -->
          <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Bon order') : ?>
            <li class="<?php if ($_GET['p'] == "Schedule-Matching") {
                          echo "active";
                        } ?>"><a href="?p=Schedule-Matching"><i class="fa fa-database text-green"></i><span>Atur Schedule</span></a>
            </li>
          <?php endif; ?>


          <!-- STATUS & RESEP -->
          <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Matcher' or $_SESSION['jabatanLAB'] == 'Bon order') : ?>
            <li class="<?php if ($_GET['p'] == "Status-Matching" or $_GET['p'] == 'Status-Handle' or $_GET['p'] == 'Hold-Handle') {
                          echo "active";
                        } ?>"><a href="?p=Status-Matching"><i class="fa fa-exchange text-purple"></i> <span>Status & Resep</span></a>
            </li>
          <?php endif; ?>



          <!-- APPROVAL ? -->
          <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Lab Head' or $_SESSION['jabatanLAB'] == 'Super matcher') : ?>
            <li class="<?php if ($_GET['p'] == "Wait-approval" or $_GET['p'] == 'Detail-status-wait-approve') {
                          echo "active";
                        } ?>"><a href="?p=Wait-approval"><i class="fa fa-hourglass-half text-danger"></i> <span>Approval ?</span></a>
            </li>
          <?php endif; ?>


          <!-- DATA BASE RESEP GROUP -->
          <li class="treeview <?php if ($_GET['p'] == "Today-Approved" or $_GET['p'] == 'Detail-status-approved' or $_GET['p'] == "Today-Rejected" or $_GET['p'] == 'Detail-status-rejected' or $_GET['p'] == "Report-Matching" or $_GET['p'] == 'DataBase-resep' or $_GET['p'] == 'Report-Rejected' or $_GET['p'] == "Dyestuff_Utilization" or $_GET['p'] == "Perform-report") {
                                echo "active";
                              } ?>">
            <a href="#"><i class="fa fa-flask" aria-hidden="true"></i>
              <span>DataBase Resep</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="<?php if ($_GET['p'] == "DataBase-resep" or $_GET['p'] == "Detail-status-approved") {
                            echo "active";
                          } ?>"><a href="?p=DataBase-resep"><i class="fa fa-fw fa-file-pdf-o text-success" aria-hidden="true"></i>
                  <span>Resep</span></a>
              </li>
              <li class="<?php if ($_GET['p'] == "Perform-report") {
                            echo "active";
                          } ?>"><a href="?p=Perform-report"><i class="fa fa-fw fa-address-card text-primary" aria-hidden="true"></i>
                  <span>Perform-report</span></a>
              </li>
              <li class="<?php if ($_GET['p'] == "Report-Matching") {
                            echo "active";
                          } ?>"><a href="?p=Report-Matching"><i class="fa fa-fw fa-calendar" aria-hidden="true"></i>
                  <span>Search By date</span></a>
              </li>
              <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Lab Head' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Leader') : ?>
                <li class="<?php if ($_GET['p'] == "Arsip") {
                              echo "active";
                            } ?>"><a href="?p=Arsip"><i class="fa fa-fw fa-archive text-green" aria-hidden="true"></i>
                    <span>Arsip</span></a>
                </li>
                <li class="<?php if ($_GET['p'] == "Report-Rejected") {
                              echo "active";
                            } ?>"><a href="?p=Report-Rejected"><i class="fa fa-fw fa-trash text-danger" aria-hidden="true"></i>
                    <span>All Rejected</span></a>
                </li>
              <?php endif; ?>
              <li class="<?php if ($_GET['p'] == "Dyestuff_Utilization") {
                            echo "active";
                          } ?>"><a href="?p=Dyestuff_Utilization"><i class="fa fa-code text-warning" aria-hidden="true"></i>
                  <span>Dyestuff Utilization</span></a>
              </li>
            </ul>
          </li>

          <!-- Modifikasi Data -->
          <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Lab Head' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Colorist' or $_SESSION['jabatanLAB'] == 'Bon order' or $_SESSION['jabatanLAB'] == 'Leader') : ?>
            <li class="<?php if ($_GET['p'] == "Join-No-Order" or $_GET['p'] == 'Adjust_Resep_Lab') {
                          echo "active";
                        } ?>"><a href="?p=Join-No-Order"><i class="fa fa-adjust" aria-hidden="true"></i>
                <span>Modifikasi Data</span></a>
            </li>
          <?php endif; ?>

          <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Bon order') : ?>
            <li class="treeview <?php if ($_GET['p'] == "User" or $_GET['p'] == "Matcher") {
                                  echo "active";
                                } ?>">
              <a href="#"><i class="fa fa-gears text-yellow"></i> <span>Lain-lain</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if ($_GET['p'] == "Manage-Dyestuff") {
                              echo "active";
                            } ?>"><a href="?p=Manage-Dyestuff"><i class="fa fa-plus-square"></i> <span>Manage-Dyestuff</span></a>
                </li>
                <li class="<?php if ($_GET['p'] == "Lampu-Buyer") {
                              echo "active";
                            } ?>"><a href="?p=Lampu-Buyer"><i class="fa fa-lightbulb-o"></i> <span>Lampu-Buyer</span></a>
                </li>
                <li class="<?php if ($_GET['p'] == "Manage-Proses") {
                              echo "active";
                            } ?>"><a href="?p=Manage-Proses"><i class="fa fa-spinner"></i> <span>Manage-Proses</span></a>
                </li>
                <li class="<?php if ($_GET['p'] == "Log_Matching") {
                              echo "active";
                            } ?>"><a href="?p=Log_Matching"><i class="glyphicon glyphicon-resize-small" aria-hidden="true"></i>
                    <span>Log Perlakuan Resep</span></a>
                </li>
                <?php if ($_SESSION['jabatanLAB'] == 'Super admin') : ?>
                  <li class="<?php if ($_GET['p'] == "User") {
                                echo "active";
                              } ?>"><a href="?p=User"><i class="fa fa-user text-blue"></i> <span>User</span></a>
                  </li>
                  <li class="<?php if ($_GET['p'] == "announcement") {
                                echo "active";
                              } ?>"><a href="?p=announcement"><i class="fa fa-volume-up" aria-hidden="true"></i>
                      <span>announcement</span></a>
                  </li>
                <?php endif; ?>
                <li class="<?php if ($_GET['p'] == "Matcher") {
                              echo "active";
                            } ?>"><a href="?p=Matcher"><i class="fa fa-user text-green"></i> <span>Matcher</span></a>
                </li>
                <li class="<?php if ($_GET['p'] == "Colorist") {
                              echo "active";
                            } ?>"><a href="?p=Colorist"><i class="fa fa-user text-yellow"></i> <span>Colorist</span></a>
                </li>
              </ul>
            </li>
          <?php endif; ?>

          <li class="<?php if ($_GET['p'] == "Change-password") {
                        echo "active";
                      } ?>"><a href="?p=Change-password"><i class="fa fa-key"></i> <span>Change-password</span></a>
          </li>

          <li class="<?php if ($_GET['p'] == "Petunjuk_penggunaan") {
                        echo "active";
                      } ?>"><a href="?p=Petunjuk_penggunaan"><i class="fa fa-question text-primary" aria-hidden="true"></i>
              <span>Petunjuk-penggunaan</span></a>
          </li>




        </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <li style="margin-left: 50px; height: 8px; margin-top: 3px; font-weight: bold;"> <i class="fa fa-fw fa-map-signs" aria-hidden="true"></i>

        <?php echo ucwords($_GET['p']); ?></li>
      <!-- Main content -->
      <section class="content container-fluid">

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
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <div class="pull-right hidden-xs">
        DIT
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2018 <a href="#">Indo Taichen Textile Industry</a>.</strong> All rights reserved.
    </footer>
    <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED JS SCRIPTS -->
  <script src="dist/js/adminlte.min.js"></script>
  <script src="bower_components/DataTable/datatables.min.js"></script>
  <script src="bower_components/DataTable/dataTables.rowsGroup.js"></script>
  <script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <script src="bower_components/select2/js/select2.full.min.js"></script>
  <script src="bower_components/toastr/toastr.js"></script>
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
</body>

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