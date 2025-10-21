<?php
ini_set("error_reporting", 1);
session_start();
include_once 'koneksi.php';
?>
<?php
if (! isset($_SESSION['userLAB'])) {
?>
    <script>
        setTimeout("location.href='login'", 500);
    </script>
<?php
    die('Illegal Acces');
} else if (! isset($_SESSION['passLAB'])) {
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
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="bower_components/DataTable/jQuery-3.3.1/jQuery-3.3.1.min.js"></script>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php if ($_GET['p'] == 'Perform-report' or $_GET['p'] == 'Report-Matching' or $_GET['p'] == 'Form-Matching' or $_GET['p'] == 'Recap-Colorist' or $_GET['p'] == "Status-Matching-Ganti-Kain") { ?>

    <?php } else { ?>
        <link href="bower_components/xeditable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="plugins/jquery-ui-1.12.1/jquery-ui.min.css">
    <?php } ?>
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
    <style>
        /* Bon Order */
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu>.dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
            display: none;
        }

        .dropdown-submenu:hover>.dropdown-menu {
            display: block;
        }

        @media (max-width: 1400px) {
            .dropdown-submenu>.dropdown-menu {
                left: auto !important;
                right: 100% !important;
                margin-left: 0;
                margin-right: -1px;
                border-radius: 4px 0 0 4px;
            }
        }
    </style>

    <link rel="icon" type="image/png" href="dist/img/ITTI_Logo index.ico">
</head>

<body class="hold-transition skin-black-light layout-top-nav" id="block-full-page" style="font-size: 12px;">
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
                            <?php if (
                                $_SESSION['jabatanLAB'] == 'Super admin'
                                or $_SESSION['jabatanLAB'] == 'Admin'
                                or $_SESSION['jabatanLAB'] == 'Spv'
                                or $_SESSION['jabatanLAB'] == 'Leader'
                                or $_SESSION['jabatanLAB'] == 'Matcher'
                                or $_SESSION['jabatanLAB'] == 'Super matcher'
                                or $_SESSION['jabatanLAB'] == 'Other'
                                or $_SESSION['jabatanLAB'] == 'Bon order'
                                or $_SESSION['jabatanLAB'] == 'Colorist'
                                or $_SESSION['jabatanLAB'] == 'Guest'
                                or $_SESSION['jabatanLAB'] == 'Lab Head'
                            ):
                            ?>
                                <li class="<?php if ($_GET['p'] == "Home" or $_GET['p'] == "") {
                                                echo "active";
                                            } ?>"><a href="?p=Home"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                                </li>
                            <?php endif; ?>

                            <!-- GENERATE KARTU MATCHING -->
                            <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Lab Head' or $_SESSION['jabatanLAB'] == 'MKT'): ?>
                                <li class="<?php if ($_GET['p'] == "Price" or $_GET['p'] == "") {
                                                echo "active";
                                            } ?>"><a href="?p=InternalPriceList"><i class="fa fa-money"></i> <span>Internal Price List</span></a>
                                </li>
                            <?php endif; ?>

                            <!-- GENERATE KARTU MATCHING -->
                            <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Other' or $_SESSION['jabatanLAB'] == 'Bon order'): ?>
                                <li class="<?php if ($_GET['p'] == "Form-Matching") {
                                                echo "active";
                                            } ?>"><a href="?p=Form-Matching"><i class="fa fa-tag text-yellow"></i> <span>Kartu Matching</span></a>
                                </li>
                            <?php endif; ?>



                            <!-- List SCHEDULE -->
                            <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Bon order'): ?>
                                <li class="<?php if ($_GET['p'] == "List-Schedule") {
                                                echo "active";
                                            } ?>"><a href="?p=List-Schedule"><i class="fa fa-cubes text-primary" aria-hidden="true"></i>
                                        <span> List Schedule</span></a>
                                </li>
                            <?php endif; ?>


                            <!-- ATUR SCHEDULE -->
                            <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Bon order'): ?>
                                <li class="<?php if ($_GET['p'] == "Schedule-Matching") {
                                                echo "active";
                                            } ?>"><a href="?p=Schedule-Matching"><i class="fa fa-database text-green"></i><span> Atur Schedule</span></a>
                                </li>
                            <?php endif; ?>


                            <!-- DB CycleTime
                            <li class="dropdown <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Matcher' or $_SESSION['jabatanLAB'] == 'Bon order'): ?>">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-flask" aria-hidden="true"></i>
                                    <span>DB CycleTime</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-fw fa-angle-down pull-right"></i>
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="<?php if ($_GET['p'] == "Cycle-Time" or $_GET['p'] == "Form-CycleTime") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Cycle-Time"><i class="fa fa-fw fa-file-pdf-o text-success" aria-hidden="true"></i>
                                            <span>Cycle Time</span></a>
                                    </li>
                                    <li class="<?php if ($_GET['p'] == "Cycle-Time-Arsip") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Cycle-Time-Arsip"><i class="fa fa-fw fa-archive text-green" aria-hidden="true"></i>
                                            <span>Arsip</span></a>
                                    </li>
                                </ul>
                            <?php endif; ?> -->

                            <!-- Cycle Time Schedule -->
                            <li class="dropdown <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Matcher' or $_SESSION['jabatanLAB'] == 'Bon order'): ?>">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-circle-o-notch" aria-hidden="true"></i>
                                    <span>Cycle Time</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-fw fa-angle-down pull-right"></i>
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php
                                        $roleCycletimeRaw = isset($_SESSION['role_cycletime']) ? $_SESSION['role_cycletime'] : '';
                                        $roleCycletimeArr = array_filter(explode(';', $roleCycletimeRaw)); // jadikan array [1,2,3,4]

                                        // Buat helper function
                                        function hasAccess($id, $roles)
                                        {
                                            return in_array($id, $roles);
                                        }

                                        // Buat variabel boolean untuk masing-masing menu
                                        $isGotAccessPreliminary = hasAccess(1, $roleCycletimeArr);
                                        $isGotAccessDispensing  = hasAccess(2, $roleCycletimeArr);
                                        $isGotAccessDyeing      = hasAccess(3, $roleCycletimeArr);
                                        $isGotAccessDarkStart   = hasAccess(4, $roleCycletimeArr);
                                        $isGotAccessDarkEnd     = hasAccess(5, $roleCycletimeArr);
                                    ?>
                                    <?php if ($isGotAccessPreliminary) { ?>
                                        <li class="<?php if ($_GET['p'] == "Preliminary-Schedule" or $_GET['p'] == "Form-Preliminary-Schedule") echo "active"; ?>">
                                            <a href="?p=Preliminary-Schedule"><i class="fa fa-clock-o"></i> <span>Preliminary Schedule</span></a>
                                        </li>
                                    <?php } ?>

                                    <?php if ($isGotAccessDispensing) { ?>
                                        <li class="<?php if ($_GET['p'] == "Dispensing-List" or $_GET['p'] == "Form-Dispensing-List") echo "active"; ?>">
                                            <a href="?p=Dispensing-List"><i class="fa fa-clock-o"></i> <span>Dispensing</span></a>
                                        </li>
                                    <?php } ?>

                                    <?php if ($isGotAccessDyeing) { ?>
                                        <li class="<?php if ($_GET['p'] == "Dyeing-List" or $_GET['p'] == "Form-Dyeing-List") echo "active"; ?>">
                                            <a href="?p=Dyeing-List"><i class="fa fa-clock-o"></i> <span>Dyeing</span></a>
                                        </li>
                                    <?php } ?>

                                    <?php if ($isGotAccessDarkStart) { ?>
                                        <li class="<?php if ($_GET['p'] == "Darkroom-Start" or $_GET['p'] == "Form-Darkroom-Start") echo "active"; ?>">
                                            <a href="?p=Darkroom-Start"><i class="fa fa-clock-o"></i> <span>Dark Room - Start</span></a>
                                        </li>
                                    <?php } ?>

                                    <?php if ($isGotAccessDarkEnd) { ?>
                                        <li class="<?php if ($_GET['p'] == "Darkroom-End" or $_GET['p'] == "Form-Darkroom-End") echo "active"; ?>">
                                            <a href="?p=Darkroom-End"><i class="fa fa-clock-o"></i> <span>Dark Room - End</span></a>
                                        </li>
                                    <?php } ?>

                                    <li class="divider"></li>

                                    <li class="<?php if ($_GET['p'] == "Hold-Data") echo "active"; ?>">
                                        <a href="?p=Hold-Data"><i class="fa fa-pause-circle text-orange" aria-hidden="true"></i>
                                            <span>Hold Data</span></a>
                                    </li>
                                    <li class="<?php if ($_GET['p'] == "End-Data") echo "active"; ?>">
                                        <a href="?p=End-Data"><i class="fa fa-pause-circle text-orange" aria-hidden="true"></i>
                                            <span>End Data</span></a>
                                    </li>
                                    <li class="<?php if ($_GET['p'] == "Cycle-Time-Log") echo "active"; ?>">
                                        <a href="?p=Cycle-Time-Log"><i class="fa fa-pause-circle text-orange" aria-hidden="true"></i>
                                            <span>Cycle Time Log</span></a>
                                    </li>

                                    <li class="divider"></li>

                                    <li class="<?php if ($_GET['p'] == "Summary-Preliminary") echo "active"; ?>">
                                        <a href="?p=Summary-Preliminary"><i class="fa fa-table text-green" aria-hidden="true"></i>
                                            <span>Summary Preliminary</span></a>
                                    </li>

                                    <li class="<?php if ($_GET['p'] == "Summary-Dispensing") echo "active"; ?>">
                                        <a href="?p=Summary-Dispensing"><i class="fa fa-table text-green" aria-hidden="true"></i>
                                            <span>Summary Dispensing</span></a>
                                    </li>

                                    <li class="<?php if ($_GET['p'] == "Summary-Dyeing") echo "active"; ?>">
                                        <a href="?p=Summary-Dyeing"><i class="fa fa-table text-green" aria-hidden="true"></i>
                                            <span>Summary Dyeing</span></a>
                                    </li>

                                    <li class="<?php if ($_GET['p'] == "Summary-Darkroom") echo "active"; ?>">
                                        <a href="?p=Summary-Darkroom"><i class="fa fa-table text-green" aria-hidden="true"></i>
                                            <span>Summary Darkroom</span></a>
                                    </li>
                                </ul>
                            <?php endif; ?>

                            <!-- STATUS & RESEP -->
                            <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Matcher' or $_SESSION['jabatanLAB'] == 'Bon order'): ?>
                            <li class="<?php if ($_GET['p'] == "Status-Matching" or $_GET['p'] == 'Status-Handle' or $_GET['p'] == 'Hold-Handle') {
                                            echo "active";
                                        } ?>"><a href="?p=Status-Matching"><i class="fa fa-exchange text-purple"></i> <span>Status & Resep</span></a>
                            </li>
                        <?php endif; ?>



                        <!-- APPROVAL ? -->
                        <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Lab Head' or $_SESSION['jabatanLAB'] == 'Super matcher'): ?>
                            <li class="<?php if ($_GET['p'] == "Wait-approval" or $_GET['p'] == 'Detail-status-wait-approve') {
                                            echo "active";
                                        } ?>"><a href="?p=Wait-approval"><i class="fa fa-hourglass-half text-danger"></i> <span>Approval ?</span></a>
                            </li>
                        <?php endif; ?>

                        <?php if ($_SESSION['jabatanLAB'] != "QAI") { ?>
                            <!-- DATA BASE RESEP GROUP -->
                            <li class="dropdown <?php if (
                                                    $_GET['p'] == "Today-Approved" or $_GET['p'] == 'Detail-status-approved' or $_GET['p']
                                                    == "Today-Rejected" or $_GET['p'] == 'Detail-status-rejected' or $_GET['p'] == "Report-Matching" or $_GET['p']
                                                    == 'DataBase-resep-new' or $_GET['p'] == 'Report-Rejected' or $_GET['p'] == "Dyestuff_Utilization" or $_GET['p']
                                                    == "Perform-report" or $_GET['p'] == "Recap-Colorist"
                                                ) {
                                                    echo "active";
                                                } ?>">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-flask" aria-hidden="true"></i>
                                    <span>DB Resep</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-fw fa-angle-down pull-right"></i>
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="<?php if ($_GET['p'] == "DataBase-resep-new" or $_GET['p'] == "Detail-status-approved") {
                                                    echo "active";
                                                } ?>"><a href="?p=DataBase-resep-new"><i class="fa fa-fw fa-file-pdf-o text-success" aria-hidden="true"></i>
                                            <span>Resep</span></a>
                                    </li>
                                    <?php if ($_SESSION['jabatanLAB'] == "Super admin") { ?>
                                        <li class="<?php if ($_GET['p'] == "Perform-report") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Perform-report"><i class="fa fa-fw fa-address-card text-primary" aria-hidden="true"></i>
                                                <span>Perform-report</span></a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "Recap-Colorist") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Recap-Colorist"><i class="fa fa-fw fa-glass text-warning" aria-hidden="true"></i>
                                                <span>Recap-Colorist</span></a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "Report-Matching") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Report-Matching"><i class="fa fa-fw fa-calendar" aria-hidden="true"></i>
                                                <span>Search By date</span></a>
                                        </li>
                                        <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Lab Head' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Other'): ?>
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
                                        <li class="<?php if ($_GET['p'] == "SearchLog_Perlakuan") {
                                                        echo "active";
                                                    } ?>"><a href="?p=SearchLog_Perlakuan"><i class="fa fa-search text-warning" aria-hidden="true"></i>
                                                <span>Search Log Additional Order</span></a>
                                        </li>
                                    <?php } ?>
                                    <li class="<?php if ($_GET['p'] == "DataBase-test-report") {
                                                    echo "active";
                                                } ?>"><a href="?p=DataBase-test-report"><i class="fa fa-fw fa-database text-success" aria-hidden="true"></i>
                                            <span>Database Test Report</span></a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Modifikasi Data -->
                            <li class="<?php if ($_GET['p'] == "Detect_benang") {
                                            echo "active";
                                        } ?>"><a href="?p=Detect_benang"><svg height="18px" viewBox="0 0 512 512" width="18px" xmlns="http://www.w3.org/2000/svg">
                                        <path d="m332.792969 424.425781-57.425781-340.953125c-2.394532-14.203125-14.582032-24.511718-28.984376-24.511718h-9.433593v-24.746094c0-18.867188-15.351563-34.214844-34.21875-34.214844h-71.761719c-18.867188 0-34.214844 15.347656-34.214844 34.214844v24.746094h-9.433594c-14.402343 0-26.59375 10.308593-28.988281 24.515624l-57.421875 340.949219c-1.4375 8.546875.945313 17.242188 6.542969 23.863281 5.59375 6.617188 13.777344 10.410157 22.445313 10.410157h27.023437v10.828125c0 23.417968 19.054687 42.472656 42.472656 42.472656h134.914063c23.417968 0 42.46875-19.054688 42.46875-42.472656v-10.824219h27.027344c8.667968 0 16.847656-3.796875 22.445312-10.414063 5.59375-6.617187 7.980469-15.316406 6.542969-23.863281zm-251.097657-185.371093h-29.296874l6.886718-40.894532 212.011719-18.511718 10.003906 59.40625h-119.65625c-5.519531 0-9.992187 4.472656-9.992187 9.992187s4.472656 9.992187 9.992187 9.992187h123.023438l9.914062 58.859376-252.382812-18.273438 6.835937-40.582031h32.660156c5.519532 0 9.996094-4.476563 9.996094-9.996094s-4.476562-9.992187-9.996094-9.992187zm-12.429687-100.144532h195.167969l3.53125 20.964844-205.25 17.921875zm-30.402344 180.511719 259.136719 18.765625 6.828125 40.566406h-275.957031zm77.875-285.207031c0-7.84375 6.382813-14.226563 14.230469-14.226563h71.761719c7.847656 0 14.230469 6.382813 14.230469 14.226563v24.746094h-100.222657zm-29.417969 44.734375h159.0625c4.609376 0 8.511719 3.300781 9.273438 7.84375l5.414062 32.128906h-188.4375l5.410157-32.128906c.765625-4.542969 4.667969-7.84375 9.277343-7.84375zm169.472657 390.578125c0 12.398437-10.089844 22.484375-22.484375 22.484375h-134.914063c-12.398437 0-22.484375-10.085938-22.484375-22.484375v-10.824219h179.882813zm54.195312-34.144532c-1.054687 1.246094-3.386719 3.332032-7.183593 3.332032h-273.90625c-3.800782 0-6.132813-2.085938-7.183594-3.332032-1.054688-1.246093-2.726563-3.894531-2.09375-7.636718l4.882812-29.003906h282.691406l4.886719 29.003906c.628907 3.746094-1.042969 6.390625-2.09375 7.636718zm0 0" />
                                        <path d="m116.125 257.304688c.539062.359374 1.117188.671874 1.71875.921874.605469.25 1.238281.4375 1.878906.566407.636719.132812 1.296875.203125 1.949219.203125.648437 0 1.308594-.070313 1.945313-.203125.640624-.128907 1.269531-.316407 1.871093-.566407.609375-.25 1.1875-.5625 1.726563-.921874.550781-.359376 1.058594-.777344 1.519531-1.238282 1.859375-1.859375 2.929687-4.4375 2.929687-7.066406 0-.648438-.070312-1.308594-.199218-1.957031-.121094-.640625-.320313-1.261719-.570313-1.871094-.25-.597656-.558593-1.175781-.921875-1.71875-.355468-.546875-.777344-1.058594-1.238281-1.527344-.457031-.460937-.96875-.871093-1.519531-1.238281-.539063-.359375-1.117188-.671875-1.726563-.921875-.601562-.25-1.230469-.4375-1.867187-.566406-1.28125-.261719-2.609375-.261719-3.898438 0-.640625.128906-1.269531.316406-1.878906.566406-.601562.25-1.179688.5625-1.71875.921875-.550781.367188-1.0625.777344-1.519531 1.238281-.460938.46875-.878907.980469-1.238281 1.527344-.371094.542969-.671876 1.121094-.921876 1.71875-.25.601563-.449218 1.230469-.566406 1.871094-.132812.648437-.203125 1.308593-.203125 1.957031 0 2.628906 1.070313 5.207031 2.929688 7.066406.457031.460938.96875.878906 1.519531 1.238282zm0 0" />
                                    </svg>
                                    <span></span></a>
                            </li>
                        <?php } ?>

                        <!-- Modifikasi Data -->
                        <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Lab Head' or $_SESSION['jabatanLAB'] == 'Super matcher' or $_SESSION['jabatanLAB'] == 'Colorist' or $_SESSION['jabatanLAB'] == 'Bon order' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Other'): ?>
                            <li class="<?php if ($_GET['p'] == "Join-No-Order-New" or $_GET['p'] == 'Adjust_Resep_Lab_New') {
                                            echo "active";
                                        } ?>"><a href="?p=Join-No-Order-New"><i class="fa fa-adjust" aria-hidden="true"></i>
                                    <span>Modifikasi</span></a>
                            </li>
                        <?php endif; ?>

                        <?php if ($_SESSION['jabatanLAB'] == 'Super admin' or $_SESSION['jabatanLAB'] == 'Admin' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader' or $_SESSION['jabatanLAB'] == 'Bon order' or $_SESSION['jabatanLAB'] == 'Matcher'): ?>
                            <li class="dropdown<?php if ($_GET['p'] == "User" or $_GET['p'] == "Matcher") {
                                                    echo "active";
                                                } ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gears text-yellow"></i> <span>Lain-lain</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-fw fa-angle-down pull-right"></i>
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php if ($_SESSION['pic_printrfid'] == '1'): ?>
                                        <li class="<?php if ($_GET['p'] == "PrintRFID") {
                                                        echo "active";
                                                    } ?>"><a href="?p=PrintRFID"><i class="fa fa-print" aria-hidden="true"></i> <span>Print RFID</span></a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($_SESSION['jabatanLAB'] != 'Matcher'): ?>
                                        <li class="<?php if ($_GET['p'] == "MasterSuhu") {
                                                        echo "active";
                                                    } ?>"><a href="?p=MasterSuhu"><i class="fa fa-thermometer-half" aria-hidden="true"></i> <span>Master Suhu </span></a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "MasterMesin") {
                                                        echo "active";
                                                    } ?>"><a href="?p=MasterMesin"><i class="fa fa-building-o" aria-hidden="true"></i> <span>Master Mesin</span></a>
                                        </li>
                                        <!-- <li class="<?php if ($_GET['p'] == "InsertSchedule") {
                                                            echo "active";
                                                        } ?>"><a href="?p=InsertSchedule"><i class="fa fa-indent" aria-hidden="true"></i></i> <span>Insert Schedule</span></a>
                                            </li> -->
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
                                    <?php endif; ?>
                                    <?php if ($_SESSION['jabatanLAB'] == 'Super admin'): ?>
                                        <li class="<?php if ($_GET['p'] == "User") {
                                                        echo "active";
                                                    } ?>"><a href="?p=User"><i class="fa fa-user text-blue"></i> <span>User</span></a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "announcement") {
                                                        echo "active";
                                                    } ?>"><a href="?p=announcement"><i class="fa fa-volume-up" aria-hidden="true"></i>
                                                <span>announcement</span></a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "Matcher") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Matcher"><i class="fa fa-user text-green"></i> <span>Matcher</span></a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "Colorist") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Colorist"><i class="fa fa-user text-yellow"></i> <span>Colorist</span></a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "UserResep") {
                                                        echo "active";
                                                    } ?>"><a href="?p=UserResep"><i class="fa fa-user text-red"></i> <span>User Resep</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <li class="<?php if ($_GET['p'] == "TestQCFinal") {
                                                    echo "active";
                                                } ?>"><a href="?p=TestQCFinal"><i class="fa fa-flask text-red"></i> <span>Test QC Final</span></a>
                                    </li>
                                    <?php if ($_SESSION['jabatanLAB'] != 'Matcher'): ?>
                                        <li class="<?php if ($_GET['p'] == "ApprovedTestReport") {
                                                        echo "active";
                                                    } ?>"><a href="?p=ApprovedTestReport"><i class="fa fa-check text-green"></i> <span>Approved Report</span></a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "Log_Qctest") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Log_Qctest"><i class="glyphicon glyphicon-resize-small"></i> <span>Log QC Test</span></a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "List-Schedule-Rekap") {
                                                        echo "active";
                                                    } ?>"><a href="?p=List-Schedule-Rekap"><i class="fa fa-archive text-fuchsia"></i> <span>List Schedule Rekap</span></a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "Status-Rekap") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Status-Rekap"><i class="fa fa-archive text-teal"></i> <span>Status & Resep Rekap</span></a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "Kartu-Riwayat") {
                                                        echo "active";
                                                    } ?>">
                                            <a href="?p=Kartu-Riwayat">
                                                <i class="fa fa-file-text text-green"></i> <span>Kartu Riwayat</span>
                                            </a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "Kartu-Riwayat-Activity") {
                                                        echo "active";
                                                    } ?>">
                                            <a href="?p=Kartu-Riwayat-Activity">
                                                <i class="fa fa-tasks text-yellow"></i> <span>Kartu Riwayat Activity</span>
                                            </a>
                                        </li>
                                        <li class="<?php if ($_GET['p'] == "Laporan-Kartu-Stock") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Laporan-Kartu-Stock"><i class="fa fa-list-alt text-primary"></i> <span>Laporan & Kartu Stock</span></a>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Bon Order Submenu -->
                                    <li class="dropdown-submenu <?php if (
                                                                    $_GET['p'] == "Approval-Bon-Order" ||
                                                                    $_GET['p'] == "Status-Matching-Bon-Order" ||
                                                                    $_GET['p'] == "Status-Matching-Ganti-kain" ||
                                                                    $_GET['p'] == "Rekap-Update-Status" ||
                                                                    $_GET['p'] == "Rekap-Data"
                                                                )
                                                                    echo "active"; ?>">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-credit-card text-danger"></i> <span>Bon Order</span>
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-right pull-right" style="margin-top: 3px;"></i>
                                            </span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="<?php if ($_GET['p'] == "Approval-Bon-Order")
                                                            echo "active"; ?>">
                                                <a href="?p=Approval-Bon-Order"><i class="fa fa-check"></i> <span>Approval Bon Order</span></a>
                                            </li>
                                            <li class="<?php if ($_GET['p'] == "Approval-Revisi-Bon-Order")
                                                            echo "active"; ?>">
                                                <a href="?p=Approval-Revisi-Bon-Order"><i class="fa fa-list-alt"></i> <span>Approval Revisi Bon Order</span></a>
                                            </li>
                                            <li class="<?php if ($_GET['p'] == "Status-Matching-Bon-Order")
                                                            echo "active"; ?>">
                                                <a href="?p=Status-Matching-Bon-Order"><i class="fa fa-file-text"></i> <span>Status Matching Bon
                                                        Order</span></a>
                                            </li>
                                            <li class="<?php if ($_GET['p'] == "Status-Matching-Ganti-kain")
                                                            echo "active"; ?>">
                                                <a href="?p=Status-Matching-Ganti-Kain"><i class="fa fa-tasks"></i> <span>Status Matching Ganti
                                                        Kain</span></a>
                                            </li>
                                            <li class="<?php if ($_GET['p'] == "Rekap-Update-Status")
                                                            echo "active"; ?>">
                                                <a href="?p=Rekap-Update-Status"><i class="fa fa-spinner"></i> <span>Rekap Update Status</span></a>
                                            </li>
                                            <li class="<?php if ($_GET['p'] == "Rekap-Data")
                                                            echo "active"; ?>">
                                                <a href="?p=Rekap-Data"><i class="fa fa-clipboard"></i> <span>Rekap Data</span></a>
                                            </li>
                                        </ul>
                                    </li>

                                    <!-- Penggunaan Obat Menu -->
                                    <li class="dropdown-submenu <?php if (
                                                                    $_GET['p'] == "pemakaian_obat" ||
                                                                    $_GET['p'] == "pemakaian_obat_category" ||
                                                                    $_GET['p'] == "tutup_harian_GK" ||
                                                                    $_GET['p'] == "stock_opname_GK"
                                                                )
                                                                    echo "active"; ?>">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-credit-card text-danger"></i> <span>Gudang Kimia</span>
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-right pull-right" style="margin-top: 3px;"></i>
                                            </span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="<?php if ($_GET['p'] == "pemakaian_obat")
                                                            echo "active"; ?>">
                                                <a href="?p=pemakaian_obat"><i class="fa fa-file-text"></i> <span>Laporan Summary Penggunaan
                                                        Obat</span></a>
                                            </li>
                                            <li class="<?php if ($_GET['p'] == "pemakaian_obat_category")
                                                            echo "active"; ?>">
                                                <a href="?p=pemakaian_obat_category"><i class="fa fa-list text-success"></i> <span>Laporan Penggunaan Obat
                                                        Kategori</span></a>
                                            </li>
                                            <li class="<?php if ($_GET['p'] == "tutup_harian_GK")
                                                            echo "active"; ?>">
                                                <a href="?p=tutup_harian_GK"><i class="fa fa-tasks"></i> <span>Laporan Tutup Transaksi </span></a>
                                            </li>
                                    </li>
                                    <li class="<?php if ($_GET['p'] == "stock_opname_GK")
                                                    echo "active"; ?>">
                                        <a href="?p=stock_opname_GK"><i class="fa fa-tasks"></i> <span>Stock Opname GK</span></a>
                                    </li>
                                    <li class="<?php if ($_GET['p'] == "stock_opname_GK_rekap")
                                                    echo "active"; ?>">
                                        <a href="?p=stock_opname_GK_rekap"><i class="fa fa-tasks"></i> <span>Rekap Stock Opname GK</span></a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['jabatanLAB'] == 'QAI'): ?>
                        <li class="<?php if ($_GET['p'] == "stock_opname_GK") {
                                        echo "active";
                                    } ?>"><a href="?p=stock_opname_GK"><i class="fa fa-tasks"></i> <span>Stock Opname GK</span></a></i>
                        </li>
                    <?php endif; ?>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Setting<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <!-- <li class="<php if ($_GET[p] == "Export_coPower") {
                                                        echo "active";
                                                    } ?>"><a href="?p=Export_coPower"><i class="fa fa-superscript text-success" aria-hidden="true"></i>
                                            <span>Export Co-Power File</span></a>
                                    </li> -->
                            <li class="<?php if ($_GET['p'] == "Change-password") {
                                            echo "active";
                                        } ?>"><a href="?p=Change-password"><i class="fa fa-key"></i> <span>Password</span></a>
                            </li>
                            <li class="<?php if ($_GET['p'] == "Petunjuk_penggunaan") {
                                            echo "active";
                                        } ?>"><a href="?p=Petunjuk_penggunaan"><i class="fa fa-question text-primary" aria-hidden="true"></i>
                                    <span>Guide</span></a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Bon Order<span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="<?php if ($_GET['p'] == "Approval-Bon-Order") {
                                                    echo "active";
                                                } ?>"><a href="?p=Approval-Bon-Order"><i class="fa fa-check"></i> <span>Approval Bon Order</span></a>
                                    </li>
                                    <li class="<?php if ($_GET['p'] == "Status-Matching-Bon-Order") {
                                                    echo "active";
                                                } ?>"><a href="?p=Status-Matching-Bon-Order"><i class="fa fa-file-text"></i> <span>Status Matching Bon Order</span></a>
                                    </li>
                                    <li class="<?php if ($_GET['p'] == "Status-Matching-Ganti-kain") {
                                                    echo "active";
                                                } ?>"><a href="?p=Status-Matching-Ganti-Kain"><i class="fa fa-tasks"></i> <span>Status Matching Ganti Kain</span></a>
                                    </li>
                                    <li class="<?php if ($_GET['p'] == "Rekap-Data") {
                                                    echo "active";
                                                } ?>"><a href="?p=Rekap-Data"><i class="fa fa-clipboard"></i> <span>Rekap Data</span></a>
                                    </li>
    
                                </ul>
                            </li> -->
                    </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Messages: style can be found in dropdown.less-->
                            <li class="dropdown messages-menu">
                                <?php
                                $hari_ini  = date('Y-m-d');
                                $sql_login = mysqli_query($con, "SELECT do_by, max(do_at) as do_at from tbl_log where DATE_FORMAT(do_at,'%Y-%m-%d') = '$hari_ini' and what = 'login'
                                group by do_by
                                order by do_at asc");
                                $count_login = mysqli_num_rows($sql_login);
                                ?>
                                <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-sign-in"></i>
                                    <span class="label label-success"><?php echo $count_login ?></span>
                                </a> -->

                                <!-- <ul class="dropdown-menu">
                                    <li class="header"> <?php echo $count_login ?> Orang Telah Melakukan login hari ini </li>
                                    <li>
                                        <?php while ($li = mysqli_fetch_array($sql_login)): ?>
                                            <ul class="menu">
                                                <li>
                                                    <a href="#">
                                                        <div class="pull-left">
                                                            <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                                            <i class="fa fa-window-maximize" aria-hidden="true"></i>
                                                        </div>

                                                        <h4>
                                                            <?php echo $li['do_by'] ?>
                                                            <small><i class="fa fa-clock-o"></i><?php echo substr($li['do_at'], 11, 5); ?></small>
                                                        </h4>

                                                        <p>Why not buy a new awesome theme?</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        <?php endwhile; ?>
                                    </li>
                                    <li class="footer"><a href="#">ALL LOGIN HERE</a></li>
                                </ul> -->

                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-sign-in"></i>
                                    <span class="label label-success" id="notifTBO"></span>
                                </a>

                                <!-- <ul class="dropdown-menu">
                                    <li class="header">
                                        <a href="/laborat/index1.php?p=Approval-Bon-Order" style="display: block; color: inherit; text-decoration: none;">
                                            <strong><span id="notifTBOText"></span></strong> bon order siap approved
                                        </a>
                                    </li>
                                    <li class="header">
                                        <a href="/laborat/index1.php?p=Approval-Revisi-Bon-Order" style="display: block; color: inherit; text-decoration: none;">
                                            <strong><span id="notifTBOText_revisi"></span></strong> bon order revisi siap approved
                                        </a>
                                    </li>
                                </ul> -->
                                <ul class="dropdown-menu">
                                    <li class="header">
                                        Ada <a href="/laborat/index1.php?p=Approval-Bon-Order" style="display: inline; padding: 3px 3px; font-weight: 700;">
                                            <span id="notifTBOText" style="color:#FF0007;"></span> Bon Order Baru
                                        </a> dan
                                        <a href="/laborat/index1.php?p=Approval-Revisi-Bon-Order" style="display: inline; padding: 3px 3px; font-weight: 700;">
                                            <span id="notifTBOText_revisi" style="color:#FF0007;"></span> Revisi Bon Order
                                        </a>
                                    </li>
                                    <li class="menu">
                                        <ul id="notifList" class="menu-list" style="max-height:320px;overflow:auto;margin:0;padding:0;list-style:none;"></ul>
                                    </li>
                                </ul>
                            </li>
                            <!-- /.messages-menu -->
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
                    if (! empty($page) and ! empty($act)) {
                        $files = 'pages/' . $page . '.' . $act . '.php';
                    } else if (! empty($page)) {
                        $files = 'pages/' . $page . '.php';
                    } else {
                        $files = 'pages/home.php';
                    }

                    if (file_exists($files)) {
                        include $files;
                    } else {
                        include "blank.php";
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
                    <b>Laborat</b> V.3.0
                </div>
                <strong>Copyright &copy; 2025 <a href="javascript:void(0)">PT. Indo Taichen Textile Industry</a>.</strong> DIT Departement
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
<?php if ($_GET['p'] == 'Perform-report' or $_GET['p'] == 'Report-Matching' or $_GET['p'] == 'Form-Matching' or $_GET['p'] == "Recap-Colorist" or $_GET['p'] == "Status-Matching-Ganti-Kain") { ?>

<?php } else { ?>
    <script type="text/javascript" src="bower_components/xeditable/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<?php } ?>
<script src="bower_components/jquery_validation/jquery.validate.min.js"></script>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////// -->
<script>
    //Initialize Select2 Elements
    $('.select2').select2();
    $('.select3').select2();
    $('.select').select2();
    $("select2").on("select3:select2", function(evt) {
        var element = evt.params.data.element;
        var $element = $(element);

        $element.detach();
        $(this).append($element);
        $(this).trigger("change");
    });
    //Date picker
    $('#datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
    });
    //Date picker
    $('#datepicker1').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
    });
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
        });
    $('.form-control.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });
</script>
<script>
    $(function() {

        $('#example1').DataTable({
            'scrollX': true,
            'paging': true,

        })
        $('#example2').DataTable({
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
    $(document).on('click', '.note_laborat_edit', function(e) {
        var m = $(this).attr("id");
        $.ajax({
            url: "pages/note_laborat_edit.php",
            type: "GET",
            data: {
                id: m,
            },
            success: function(ajaxData) {
                $("#NoteLaboratEdit").html(ajaxData);
                $("#NoteLaboratEdit").modal('show', {
                    backdrop: 'true'
                });
            }
        });
    });
    $(document).on('click', '.sts_laborat_edit', function(e) {
        var m = $(this).attr("id");
        $.ajax({
            url: "pages/sts_laborat_edit.php",
            type: "GET",
            data: {
                id: m,
            },
            success: function(ajaxData) {
                $("#StsLaboratEdit").html(ajaxData);
                $("#StsLaboratEdit").modal('show', {
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
</script>
<!-- <script>
$(document).ready(function() {
    $('#Table-obat').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
});
</script> -->

</html>
<script>
    $(document).ready(function() {
        let tboCount = 0;
        let tboRevisiCount = 0;

        // helper parsing angka aman
        function toInt(x) {
            try {
                if (typeof x === 'string' && x.trim().startsWith('{')) {
                    const obj = JSON.parse(x);
                    for (const k in obj) {
                        if (Object.hasOwn(obj, k) && !isNaN(parseInt(obj[k], 10))) {
                            return parseInt(obj[k], 10);
                        }
                    }
                }
            } catch (e) {}
            // fallback: ambil digit saja
            const n = parseInt(String(x).replace(/[^\d-]/g, ''), 10);
            return isNaN(n) ? 0 : n;
        }

        // function refreshNotif() {
        //     $.getJSON('pages/ajax/get_notif_tbo.php', function(resp) {
        //         const tboCount = resp.new.count;
        //         const tboRevisiCount = resp.revisi.count;

        //         $('#notifTBO').text(resp.total);
        //         $('#notifTBOText').text(tboCount);
        //         $('#notifTBOText_revisi').text(tboRevisiCount);

        //         const $list = $('#notifList').empty();
        //         resp.new.codes.forEach(code => $list.append(
        //             `<li style="padding:6px 12px; background-color: rgb(220, 220, 220);"><a href="/laborat/index1.php?p=Approval-Bon-Order&code=${encodeURIComponent(code)}">Bon Order Baru ${code}</a></li>`
        //         ));
        //         resp.revisi.codes.forEach(code => $list.append(
        //             `<li style="padding:6px 12px; background-color: rgb(250, 235, 215);"><a href="/laborat/index1.php?p=Approval-Revisi-Bon-Order&code=${encodeURIComponent(code)}">Revisi Bon Order ${code}</a></li>`
        //         ));
        //     });
        // }

        // refreshNotif();
        // setInterval(refreshNotif, 10000);

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