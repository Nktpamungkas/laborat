<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();

$sql_log = mysqli_query($con, "SELECT * from log_status_matching where ids = '$_GET[idm]' order by id desc");

?>
<style>
    .timeline {
        list-style: none;
        padding: 20px 0 20px;
        position: relative;
    }

    .timeline:before {
        top: 0;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 3px;
        background-color: #eeeeee;
        left: 50%;
        margin-left: -1.5px;
    }

    .timeline>li {
        margin-bottom: 20px;
        position: relative;
    }

    .timeline>li:before,
    .timeline>li:after {
        content: " ";
        display: table;
    }

    .timeline>li:after {
        clear: both;
    }

    .timeline>li:before,
    .timeline>li:after {
        content: " ";
        display: table;
    }

    .timeline>li:after {
        clear: both;
    }

    .timeline>li>.timeline-panel {
        width: 46%;
        float: left;
        border: 1px solid #d4d4d4;
        border-radius: 2px;
        padding: 20px;
        position: relative;
        -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
    }

    .timeline>li>.timeline-panel:before {
        position: absolute;
        top: 26px;
        right: -15px;
        display: inline-block;
        border-top: 15px solid transparent;
        border-left: 15px solid #ccc;
        border-right: 0 solid #ccc;
        border-bottom: 15px solid transparent;
        content: " ";
    }

    .timeline>li>.timeline-panel:after {
        position: absolute;
        top: 27px;
        right: -14px;
        display: inline-block;
        border-top: 14px solid transparent;
        border-left: 14px solid #fff;
        border-right: 0 solid #fff;
        border-bottom: 14px solid transparent;
        content: " ";
    }

    .timeline>li>.timeline-badge {
        color: #fff;
        width: 50px;
        height: 50px;
        line-height: 50px;
        font-size: 1.4em;
        text-align: center;
        position: absolute;
        top: 16px;
        left: 50%;
        margin-left: -25px;
        background-color: #999999;
        z-index: 100;
        border-top-right-radius: 50%;
        border-top-left-radius: 50%;
        border-bottom-right-radius: 50%;
        border-bottom-left-radius: 50%;
    }

    .timeline>li.timeline-inverted>.timeline-panel {
        float: right;
    }

    .timeline>li.timeline-inverted>.timeline-panel:before {
        border-left-width: 0;
        border-right-width: 15px;
        left: -15px;
        right: auto;
    }

    .timeline>li.timeline-inverted>.timeline-panel:after {
        border-left-width: 0;
        border-right-width: 14px;
        left: -14px;
        right: auto;
    }

    .timeline-badge.primary {
        background-color: #2e6da4 !important;
    }

    .timeline-badge.success {
        background-color: #3f903f !important;
    }

    .timeline-badge.warning {
        background-color: #f0ad4e !important;
    }

    .timeline-badge.danger {
        background-color: #d9534f !important;
    }

    .timeline-badge.info {
        background-color: #5bc0de !important;
    }

    .timeline-title {
        margin-top: 0;
        color: inherit;
    }

    .timeline-body>p,
    .timeline-body>ul {
        margin-bottom: 0;
    }

    .timeline-body>p+p {
        margin-top: 5px;
    }

    @media (max-width: 767px) {
        ul.timeline:before {
            left: 40px;
        }

        ul.timeline>li>.timeline-panel {
            width: calc(100% - 90px);
            width: -moz-calc(100% - 90px);
            width: -webkit-calc(100% - 90px);
        }

        ul.timeline>li>.timeline-badge {
            left: 15px;
            margin-left: 0;
            top: 16px;
        }

        ul.timeline>li>.timeline-panel {
            float: right;
        }

        ul.timeline>li>.timeline-panel:before {
            border-left-width: 0;
            border-right-width: 15px;
            left: -15px;
            right: auto;
        }

        ul.timeline>li>.timeline-panel:after {
            border-left-width: 0;
            border-right-width: 14px;
            left: -14px;
            right: auto;
        }
    }
</style>
<div class="modal-content">
    <div class="modal-body">
        <div class="container">
            <ul class="timeline">
                <?php while ($li = mysqli_fetch_array($sql_log)) : ?>
                    <li <?php if (($i % 2) == 0) echo 'class="timeline-inverted"' ?>>
                        <div class="timeline-badge info"><i class="glyphicon glyphicon-tags"></i></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title"><?php echo $li['do_by'] ?> </h4>
                                <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?php echo date('Y F l H:i:s', strtotime($li['do_at'])); ?></small></p>
                            </div>
                            <div class="timeline-body">
                                <p> Status : <?php echo $li['status'] ?>
                                    <br /><?php echo $li['info'] ?>
                                </p>
                            </div>
                        </div>
                    </li>
                <?php $i++;
                endwhile; ?>
            </ul>
        </div>
    </div>
</div>