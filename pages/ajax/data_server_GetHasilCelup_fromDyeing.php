<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
$time = date('Y-m-d H:i:s');

$requestData = $_REQUEST;
$columns = array(
    0 => 'id',
    1 => 'no_order',
    2 => 'nokk',
    3 => 'lot',
    4 => 'bruto',
    5 => 'loading',
    6 => 'k_resep',
    7 => 'proses',
    8 => 'lama_proses',
    9 => 'status',
    10 => 'benang',
    11 => 'ket',
    12 => 'no_resep',
    13 => 'l_r',
    14 => 'no_mesin',
	15 => 'tgl_update',
	16 => 'analisa',
);
// set_order_type("desc");
$sql = "SELECT 
            b.id, 
            c.no_order, 
            d.tgl_update, 
            b.nokk, 
            c.lot, 
            b.k_resep, 
            b.proses, 
            b.lama_proses, 
            b.status, 
            b.analisa,  
            c.no_resep, 
            d.l_r, 
            c.no_mesin, 
            d.bruto, 
            ((d.bruto/c.kapasitas) * 100 ) as loading_fix, 
            z.jenis_note, 
            b.analisa_resep,
            z.note,
            b.ket, 
            d.benang
        FROM db_laborat.tbl_status_matching a
        join db_laborat.tbl_matching x on a.idm = x.no_resep
        join db_dying.tbl_hasilcelup b on a.idm = b.rcode
        join db_dying.tbl_montemp d on b.id_montemp = d.id
        join db_dying.tbl_schedule c on d.id_schedule = c.id
        left join tbl_note_celup z on b.nokk = z.kk
        where a.idm = '$_POST[r_code]' and b.rcode = '$_POST[r_code]'";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;
if (!empty($requestData['search']['value'])) {
    $sql .= "and order LIKE '" . $requestData['search']['value'] . "%' ";
}
//----------------------------------------------------------------------------------
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku1");
$totalFiltered = mysqli_num_rows($query);
$sql .= " GROUP BY b.nokk ,b.id ORDER BY b.id desc " . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'] . "  LIMIT "
    . $requestData['start'] . " ," . $requestData['length'] . "   ";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku2");
//----------------------------------------------------------------------------------
$data = array();
$no = 1;
while ($row = mysqli_fetch_array($query)) {
    $idkk = $row["nokk"];

    // $siquel = sqlsrv_query($conn,"SELECT stockmovement.dono,stockmovement.documentno as no_doku,processcontrolbatches.documentno,lotno,customerid,
    //                                 processcontrol.productid ,processcontrol.id as pcid, 
    //                                 sum(stockmovementdetails.weight) as berat,
    //                                 count(stockmovementdetails.weight) as roll,processcontrolbatches.dated as tgllot
    //                                 from stockmovement 
    //                                 LEFT join stockmovementdetails on StockMovement.id=stockmovementdetails.StockmovementID
    //                                 left join processcontrolbatches on processcontrolbatches.id=stockmovement.pcbid
    //                                 left join processcontrol on processcontrol.id=processcontrolbatches.pcid
    //                                 where wid='12' and processcontrolbatches.documentno='$idkk' and (transactiontype='7' or transactiontype='4')
    //                                 group by stockmovement.DocumentNo,processcontrolbatches.DocumentNo,processcontrolbatches.LotNo,stockmovement.dono,
    //                                 processcontrol.CustomerID,processcontrol.ProductID,processcontrol.ID,processcontrolbatches.Dated") or die("gagal");
    // $sqls = sqlsrv_query($conn,"SELECT processcontrolJO.SODID,salesorders.ponumber,processcontrol.productid,salesorders.customerid,joborders.documentno,
    //                                 salesorders.buyerid,processcontrolbatches.lotno,productcode,productmaster.color,colorno,description,weight,cuttablewidth from Joborders 
    //                                 left join processcontrolJO on processcontrolJO.joid = Joborders.id
    //                                 left join salesorders on soid= salesorders.id
    //                                 left join processcontrol on processcontrolJO.pcid = processcontrol.id
    //                                 left join processcontrolbatches on processcontrolbatches.pcid = processcontrol.id
    //                                 left join productmaster on productmaster.id= processcontrol.productid
    //                                 left join productpartner on productpartner.productid= processcontrol.productid
    //                                 where processcontrolbatches.documentno='$idkk'");
    // $ssr = sqlsrv_fetch_array($sqls);
    // $r = sqlsrv_fetch_array($siquel);
    // $bng11 = sqlsrv_query($conn,"SELECT CAST(SODetailsAdditional.Note AS NVARCHAR(255)) as note from Joborders left join processcontrolJO on processcontrolJO.joid = Joborders.id
    //                             left join SODetailsAdditional on processcontrolJO.sodid=SODetailsAdditional.sodid
    //                             WHERE  JobOrders.documentno='$ssr[documentno]' and processcontrolJO.pcid='$r[pcid]'");
    // $r3 = sqlsrv_fetch_array($bng11);


    $nestedData = array();
    if ($_POST['p'] == 'Detail-status-approved') {
        $index = $no++;
        $data_action = '<strong style="border-bottom: solid #808080 1px;">LAB : ' . $row['note'] . ' <br> <br> DYE : ' . $row['analisa_resep'] . '</strong>';
    } else {
        $index = $no++ .  '.&nbsp;&nbsp; <a hreff="javascript:void(0)" data-pk="' . $row["id"] . '" class="btn btn-xs btn-danger delete_celup"><i class="fa fa-trash" aria-hidden="true"></i>
        </a>';
        $data_action = '<strong style="border-bottom: solid #808080 1px;">LAB : ' . $row['note'] . ' <br> <br> DYE : ' . $row['analisa_resep'] . ' </strong> <br /><a href="javascript:void(0)" class="btn btn-xs btn-warning _addnoteclp" data-kk="' . $row["nokk"] . '"><i class="fa fa-edit"></i></a>';
    }

    $nestedData[] = $row["id"];
    $nestedData[] = $index;
    $nestedData[] = $row["no_order"];
	/*$nestedData[] = $row["nokk"];*/
    // $nestedData[] = '<a href="javascript:void(0)" data="pages/cetak/posisikk.php?id=' . $row["nokk"] . '" class="posisi_kk">'. $row["nokk"] .'</a>';
    $nestedData[] = '<a target="_BLANK" href="http://10.0.0.10/laporan/ppc_filter_steps.php?prod_order='.$row["nokk"].'">'. $row["nokk"] .'</a>';
    $nestedData[] = $row["lot"];
    $nestedData[] = $row["bruto"] . ' Kg';
    $nestedData[] = round($row["loading_fix"], 4) . ' %';
    $nestedData[] = $row["l_r"];
    $nestedData[] = $row["no_mesin"];
    $nestedData[] = $row["k_resep"];
    $nestedData[] = $row["proses"];
    $nestedData[] = $row["status"];
    $nestedData[] = '';
    $nestedData[] = $row["ket"];
    $nestedData[] = $row["lama_proses"];
    $nestedData[] = '<a href="javascript:void(0)" data="pages/cetak/simpan_cetak.php?kk=' . $row["nokk"] . '&g=1" class="btn btn-xs btn-info bon_resep">Resep</a>';	
    $nestedData[] = $data_action;
	$nestedData[] = $row["tgl_update"];
	$nestedData[] = $row["analisa"];


    $data[] = $nestedData;
}
//----------------------------------------------------------------------------------
$json_data = array(
    "draw"            => intval($requestData['draw']),
    "recordsTotal"    => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data"            => $data
);
//----------------------------------------------------------------------------------
echo json_encode($json_data);
