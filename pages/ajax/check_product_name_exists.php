<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../../koneksi.php';
header('Content-Type: application/json');

// if (isset($_GET['product_name'])) {
//     $productName = mysqli_real_escape_string($con, $_GET['product_name']); 

//     $query = "SELECT COUNT(*) AS count FROM master_suhu WHERE product_name = '$productName'";
//     $result = mysqli_query($con, $query);
//     $row = mysqli_fetch_assoc($result);

//     if ($row['count'] > 0) {
//         echo json_encode(['status' => 'exists']);
//     } else {
//         echo json_encode(['status' => 'not_exists']);
//     }
// } else {
//     echo json_encode(['status' => 'error', 'message' => 'Product name tidak valid']);
// }

// if (isset($_GET['product_name'])) {
//     // Normalisasi: trim spasi dan ubah ke lowercase
//     $productName = strtolower(trim($_GET['product_name']));
//     $productName = mysqli_real_escape_string($con, $productName);

//     // Query: abaikan case dan spasi ekstra
//     $query = "SELECT COUNT(*) AS count FROM master_suhu WHERE LOWER(TRIM(product_name)) = '$productName'";

//     $result = mysqli_query($con, $query);

//     if ($result) {
//         $row = mysqli_fetch_assoc($result);
//         if ($row['count'] > 0) {
//             echo json_encode(['status' => 'exists']);
//         } else {
//             echo json_encode(['status' => 'not_exists']);
//         }
//     } else {
//         echo json_encode(['status' => 'error', 'message' => 'Query gagal: ' . mysqli_error($con)]);
//     }
// } else {
//     echo json_encode(['status' => 'error', 'message' => 'Product name tidak valid']);
// }

if (isset($_GET['product_name'])) {
    $input = $_GET['product_name'];

    // Ambil hanya angka dari product_name (misalnya 60°C X 30 MNT → 6030)
    $justNumbers = preg_replace('/\D+/', '', $input);
    
    $query = "SELECT product_name FROM master_suhu";
    $result = mysqli_query($con, $query);

    $duplicate = false;

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $dbNumber = preg_replace('/\D+/', '', $row['product_name']); // ambil angka dari DB
            if ($dbNumber === $justNumbers) {
                $duplicate = true;
                break;
            }
        }

        if ($duplicate) {
            echo json_encode(['status' => 'exists']);
        } else {
            echo json_encode(['status' => 'not_exists']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Query gagal: ' . mysqli_error($con)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Product name tidak valid']);
}


