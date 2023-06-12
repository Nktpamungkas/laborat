<?php
    //Memanggil conn.php yang telah kita buat sebelumnya
    include_once "../koneksi.php";
    $order = $_GET['order'];

    //LANGGANAN
    $query_langganan = db2_exec($conn1, "SELECT s.PROJECTCODE, ip.LANGGANAN AS langganan
                                            FROM SALESORDER s 
                                                LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s.PROJECTCODE 
                                            WHERE s.PROJECTCODE LIKE '%$order%'");
    while($dt_langganan = db2_fetch_array($query_langganan)){
        $item  =   $dt_langganan[1];
    }
    $json = array(
        'langganan'    => $item_pelanggan
    );
    header('Content-Type: application/json');
    echo json_encode($json);
    
?>