<?php
    ini_set("error_reporting", 1);
    include "../../koneksi.php";
    session_start();
    $tgl_start  = date('Y-m-d H:i:s');
    $ip_num     = $_SERVER['REMOTE_ADDR'];

    if($_POST['end_number']){
        $tgl_end    = date('Y-m-d H:i:s');
    }else{
        $tgl_end    = NULL;
    }

    if($_POST['status'] == 'Normal'){
        $andstatus = "AND `status` = 'Normal'";
    }else{
        $andstatus = "AND `status` = 'Urgent'";
    }

    $dataMainCek = mysqli_query($con, "SELECT * FROM tbl_cycletime_detail WHERE id_cycletime = '".$_POST['id_cycletime']."' $andstatus ORDER BY id DESC LIMIT 1");
    $cek = mysqli_fetch_assoc($dataMainCek);

    $sqlCycleTime = mysqli_query($con, "SELECT * FROM tbl_cycletime WHERE id = '$_POST[id_cycletime]'");
    $rowMainCycletime = mysqli_fetch_array($sqlCycleTime);

    if($_POST['status_cycletime'] == 'Open'){
        mysqli_query($con, "UPDATE tbl_cycletime SET `status` = 'Open' WHERE id = '$_POST[id_cycletime]'");
    }elseif($_POST['status_cycletime'] == 'Closed'){
        mysqli_query($con, "UPDATE tbl_cycletime SET `status` = 'Closed' WHERE id = '$_POST[id_cycletime]'");
    }

    if($cek['end_number'] == '0'){
        $setUpdate = "UPDATE tbl_cycletime_detail 
                        SET `status` = '". $_POST['status'] ."',
                            `tgl_end` = '" . $tgl_end . "',
                            `start_number` = '" . $_POST['start_number'] . "', 
                            `end_number` = '" . $_POST['end_number'] . "', 
                            `total_point`= '" . $_POST['total_point'] . "'
                        WHERE
                            `id` = '".$_POST['id']."'";

        if (mysqli_query($con, $setUpdate)) {
            $LIB_SUCCESS = "LIB_SUCCESS";
            $response = array(
                'session' => $LIB_SUCCESS,
                'exp' => 'perbarui'
            );
        } else {
            $response = array(
                'session' => "LIB_ERROR",
                'message' => "Database error: " . mysqli_error($con)
            );
        }

        $mainInsertSuffix = mysqli_query($con, "INSERT INTO tbl_cycletime_suffix_end (id_cycletime, suffix, `status`)
                                                SELECT
                                                    '$_POST[id_cycletime]',
                                                    a.idm,
                                                    '$_POST[status]'
                                                FROM
                                                    tbl_status_matching a
                                                    JOIN tbl_matching b ON a.idm = b.no_resep 
                                                WHERE
                                                    a.STATUS IN ( 'buka', 'mulai', 'hold', 'revisi', 'tunggu' ) 
                                                    AND a.grp = '$rowMainCycletime[group_matching]'
                                                    AND a.kt_status = '$_POST[status]'
                                                GROUP BY
                                                    a.idm,
                                                    b.no_resep 
                                                ORDER BY
                                                    a.id DESC");

        echo json_encode($response);
    }else{
        if($cek['end_number'] > 0){
            $setUpdate = "UPDATE tbl_cycletime_detail 
                        SET `status` = '". $_POST['status'] ."',
                            `tgl_end` = '" . $tgl_end . "',
                            `start_number` = '" . $_POST['start_number'] . "', 
                            `end_number` = '" . $_POST['end_number'] . "', 
                            `total_point`= '" . $_POST['total_point'] . "'
                        WHERE
                            `id` = '".$_POST['id']."'";

            if (mysqli_query($con, $setUpdate)) {
                $LIB_SUCCESS = "LIB_SUCCESS";
                $response = array(
                    'session' => $LIB_SUCCESS,
                    'exp' => 'perbarui, tanpa menambahkan suffix'
                );
            } else {
                $response = array(
                    'session' => "LIB_ERROR",
                    'message' => "Database error: " . mysqli_error($con)
                );
            }
            echo json_encode($response);
        }else{
            $query = "INSERT INTO tbl_cycletime_detail SET 
                        `status` = '". $_POST['status'] ."',
                        `tgl_start` = '" . $tgl_start . "',
                        `tgl_end` = '" . $tgl_end . "',
                        `id_cycletime` = '" . $_POST['id_cycletime'] . "',
                        `start_number` = '" . $_POST['start_number'] . "', 
                        `end_number` = '" . $_POST['end_number'] . "', 
                        `total_point`= '" . $_POST['total_point'] . "'";

            if (mysqli_query($con, $query)) {
                $LIB_SUCCESS = "LIB_SUCCESS";
                $response = array(
                    'session' => $LIB_SUCCESS,
                    'exp' => 'simpan',
                );
            } else {
                $response = array(
                    'session' => "LIB_ERROR",
                    'message' => "Database error: " . mysqli_error($con)
                );
            }

            $mainInsertSuffix = mysqli_query($con, "INSERT INTO tbl_cycletime_suffix_start (id_cycletime, suffix, `status`)
                                                    SELECT
                                                        '$_POST[id_cycletime]',
                                                        a.idm,
                                                        '$_POST[status]'
                                                    FROM
                                                        tbl_status_matching a
                                                        JOIN tbl_matching b ON a.idm = b.no_resep 
                                                    WHERE
                                                        a.STATUS IN ( 'buka', 'mulai', 'hold', 'revisi', 'tunggu' ) 
                                                        AND a.grp = '$rowMainCycletime[group_matching]'
                                                        AND a.kt_status = '$_POST[status]'
                                                    GROUP BY
                                                        a.idm,
                                                        b.no_resep 
                                                    ORDER BY
                                                        a.id DESC");

            echo json_encode($response);
        }
    }
?>