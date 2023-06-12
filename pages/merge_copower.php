<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
$id_matching = $_POST['id_matching'];
$id_status = $_POST['id_status'];
// mysqli_query("delete from tbl_matching_detail where id_matching = '$id_matching' and id_status = '$id_status'");
mysqli_query($con,"UPDATE `tbl_status_matching` SET `status` = 'hold' where id = '$id_status'");
if (isset($_POST['submit'])) {

    // define attribute from multipart form appart
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $master = 0;
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('txt');

    // validation file size format and erroring inside file. => upload into /uploads.
    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 524288) {
                $fname = $fileName;
                $rawBaseName = pathinfo($fname, PATHINFO_FILENAME);
                $extension = pathinfo($fname, PATHINFO_EXTENSION);
                $counter = 0;
                while (file_exists('uploads/' . $fname)) {
                    // rename apabila terdapat file yang sama !
                    $fname = $rawBaseName . '_(' . ($counter + 1) . ').' . $extension;
                    $counter++;
                    // echo " <script>alert('File dengan nama tersebut sudah ada !!!'); location.href='index1.php?p=Export_coPower';</script>";
                    // die;
                };
                move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $fname);
            } else {
                echo " <script>alert('File Maximal 500kb!'); location.href='index1.php?p=Status-Handle&idm=" . $id_status . "';</script>";
                die;
            }
        } else {
            echo " <script>alert('File Anda Mengandung Konten Berbahaya'); location.href='index1.php?p=Status-Handle&idm=" . $id_status . "';</script>";
            die;
        }
    } else {
        echo " <script>alert('Type File yang anda upload tidak di izinkan !'); location.href='index1.php?p=Status-Handle&idm=" . $id_status . "';</script>";
        die;
    }

    // define txt to object array/json.
    $file = new SplFileObject('uploads/' . $fname);
    // wrapping array per line.
    while (!$file->eof()) {
        $line = $file->fgets();
        $parts[] = preg_split('/  +/', $line);
    }

    // fetch array using index , first - 1 & last -1 , -1 + -1 = 2;
    $count = intval(count($parts) - 2);
    for ($i = 0; $i <= $count; $i++) {
        if ($i == $master) {
            $rcode = substr($parts[$i][0], 3);
            $color = $parts[$i][1];
        } else if ($i > $master) {
            for ($O = 0; $O <= 0; $O++) {
                $dyess = trim($parts[$i][0]);
                $qty = floatval(substr(trim($parts[$i][1]), 0, -1));
                $C_uom = substr(trim($parts[$i][1]), -1);

                // define name dyestuff
                $sql = mysqli_query($con,"SELECT `Product_Name` from tbl_dyestuff where `code` = '$dyess' LIMIT 1");
                $data = mysqli_fetch_array($sql);
                if ($C_uom == 'F') {
                    $uom = ' (%)';
                } else if ($C_uom == 'G') {
                    $uom = ' (Gr/L)';
                }
                // Define max flag from existing resep
                $sql_max = mysqli_query($con,"SELECT max(flag) as max_flag from tbl_matching_detail where id_matching = '$id_matching' and id_status = '$id_status'");
                $max = mysqli_fetch_array($sql_max);
                if (empty($max['max_flag'])) {
                    $flag = 1;
                } else {
                    $flag = $max['max_flag'] + 1;
                }

                mysqli_query($con,"INSERT into tbl_matching_detail set 
                        `id_matching` = '$id_matching',
                        `id_status` = '$id_status',
                        `flag` = '$flag',
                        `kode` = '$dyess',
                        `nama` = '$data[Product_Name] $uom',
                        `conc1` = '$qty',
                        `time_1` = now(),
                        `doby1` = '$_SESSION[userLAB]',
                        `remark` = 'from merge Co-power',
                        `inserted_at` = now(),
                        `inserted_by` = '$_SESSION[userLAB]'");
            }
        }
    }
    $sqlNoResep = mysqli_query($con,"SELECT idm from tbl_status_matching where id = '$id_status'");
    $NoResep = mysqli_fetch_array($sqlNoResep);
    $ip_num = $_SERVER['REMOTE_ADDR'];
    mysqli_query($con,"INSERT INTO log_status_matching SET
            `ids` = '$NoResep[idm]', 
            `status` = 'hold', 
            `info` = 'Merge data from $fname', 
            `do_by` = '$_SESSION[userLAB]', 
            `do_at` = '$time', 
            `ip_address` = '$ip_num'");

    echo "<script>location.href='index1.php?p=Hold-Handle&idm=" . $id_status . "';</script>";
}
