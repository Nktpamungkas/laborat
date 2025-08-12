<?PHP
  ini_set("error_reporting", 1);
  session_start();
  include "../../koneksi.php";
  include "../../includes/Penomoran_helper.php";
  include('Response.php');

  $username = $_SESSION['userLAB'];
  $tanggal=date('Y-m-d H:i:s');

  $response = new Response();
  $response->setHTTPStatusCode(201);
  if (isset($_SESSION['userLAB'])) {
    if (isset($_POST['status'])) {
        $id = intval($_POST['id_dt']);
        if($_POST['status']=="konfirmasi" && $id != 0){
            $konfirm="1";
             $update = "UPDATE tbl_stock_opname_gk 
                 SET konfirmasi =? ,
                 konfirmasi_by = ? ,
                 konfirmasi_date =? 
                 WHERE id = ? LIMIT 1";
            $confirm=mysqli_prepare( $con, $update );
            mysqli_stmt_bind_param($confirm, "ssss", $konfirm,$username,$tanggal,$id );
            if(mysqli_stmt_execute($confirm)){ 
                $response->setSuccess(true);
                $response->addMessage("Berhasil Konfirmasi Stock Opname");
                $response->addMessage($id);
                $response->send();
            }
            else {
                $response->setSuccess(false);
                $response->addMessage("Gagal Konfirmasi Stock Opname : ".mysqli_error($con));
                $response->send();
            }
        }
        else if($_POST['status']=="cek_data"){
            $tgl_tutup = $_POST['tgl_tutup'];
            $warehouse = $_POST['warehouse'];
            $query = "SELECT id,qty_dus,total_stock,kategori,pakingan_standar,konfirmasi
                FROM tbl_stock_opname_gk 
                WHERE 
                    tgl_tutup = '$tgl_tutup'
                    AND LOGICALWAREHOUSECODE = '$warehouse'
                ORDER BY KODE_OBAT ASC";
            $stmt = mysqli_query($con, $query);
            if (!$stmt) {
                echo "<p class='text-danger'>Query gagal: " . mysqli_error($con) . "</p>";
                $response->setSuccess(false);
                $response->addMessage("Query gagal: ".$query." \nERROR : ". mysqli_error($con));
            }
            
            $num_rows_data=mysqli_num_rows($stmt);
                if ($num_rows_data > 0) {
                    $dataOpname=array();
                    while ($rowOpname = mysqli_fetch_assoc($stmt)) {
                        $tmp_data=array();
                        $tmp_data['id']=$rowOpname['id'];                        
                        $tmp_data['qty_dus']=Penomoran_helper::nilaiKeRibuan($rowOpname['qty_dus']);
                        $tmp_data['total_stock']=Penomoran_helper::nilaiKeRibuan($rowOpname['total_stock']);
                        $tmp_data['pakingan_standar']=Penomoran_helper::nilaiKeRibuan($rowOpname['pakingan_standar']);
                        if($rowOpname['konfirmasi']){
                            $tmp_data['konfirm']="<i class='fa fa-check' aria-hidden='true'></i> OK";
                        }else{
                            $tmp_data['konfirm']="<button class='btn btn-primary btn-sm confirm' title='Confirm' data-toggle='tooltip' ><i class='fa fa-check-square-o' aria-hidden='true'></i></button>";
                        }
                        $dataOpname[]=$tmp_data;
                    }
                    $response->setSuccess(true);
                    $response->addMessage("Berhasil Check Data");
                    $response->addMessage($num_rows_data);
                    $response->setData($dataOpname);
                }else{
                    $response->setSuccess(false);
                    $response->addMessage("Gagal Check Data");
                }
                $response->send();
        }
        else if($_POST['status']=="cek_data_m510"){
            $tgl_tutup = $_POST['tgl_tutup'];
            $warehouse = $_POST['warehouse'];
            $query = "SELECT id,qty_dus,total_stock,kategori,pakingan_standar,konfirmasi
                FROM tbl_stock_opname_gk 
                WHERE 
                    tgl_tutup = '$tgl_tutup'
                    AND LOGICALWAREHOUSECODE = '$warehouse'
                ORDER BY KODE_OBAT ASC";
            $stmt = mysqli_query($con, $query);
            if (!$stmt) {
                echo "<p class='text-danger'>Query gagal: " . mysqli_error($con) . "</p>";
                $response->setSuccess(false);
                $response->addMessage("Query gagal: ".$query." \nERROR : ". mysqli_error($con));
            }
            
            $num_rows_data=mysqli_num_rows($stmt);
                if ($num_rows_data > 0) {
                    $dataOpname=array();
                    while ($rowOpname = mysqli_fetch_assoc($stmt)) {
                        $tmp_data=array();
                        $tmp_data['id']=$rowOpname['id'];                        
                        $tmp_data['qty_dus']=ucfirst($rowOpname['kategori'])."<br/>Qty : ".Penomoran_helper::nilaiKeRibuan($rowOpname['qty_dus']);
                        $tmp_data['total_stock']=Penomoran_helper::nilaiKeRibuan($rowOpname['total_stock']);
                        $tmp_data['pakingan_standar']=Penomoran_helper::nilaiKeRibuan($rowOpname['pakingan_standar']);
                        if($rowOpname['konfirmasi']){
                            $tmp_data['konfirm']="<i class='fa fa-check' aria-hidden='true'></i> OK";
                        }else{
                            $tmp_data['konfirm']="<button class='btn btn-primary btn-sm confirm' title='Confirm' data-toggle='tooltip' ><i class='fa fa-check-square-o' aria-hidden='true'></i></button>";
                        }
                        $dataOpname[]=$tmp_data;
                    }
                    $response->setSuccess(true);
                    $response->addMessage("Berhasil Check Data");
                    $response->addMessage($num_rows_data);
                    $response->setData($dataOpname);
                }else{
                    $response->setSuccess(false);
                    $response->addMessage("Gagal Check Data");
                }
                $response->send();
        }
        else{
            $response->setSuccess(false);
            $response->addMessage("Error Status");
            $response->send();
        }
    }
  }
  if(isset($_SESSION['opname'])&&$_SESSION['opname']=="gk"){
    $id = intval($_POST['id_dt']);
    if($_POST['check']=="check_transaksi"){
        $prepare=db2_prepare ($conn1,"SELECT
                DECOSUBCODE01,
                DECOSUBCODE02,
                DECOSUBCODE03,
                LOTCODE 
            FROM
                STOCKTRANSACTION s
            WHERE 
                s.TRANSACTIONNUMBER = ? ");
        db2_execute($prepare,array(trim($_POST['val']," ")));
        
        $dataTransaksi=array();		  
        while($rowdb = db2_fetch_assoc($prepare)){
            $dataTransaksi['kode_obat']=trim($rowdb["DECOSUBCODE01"]," ")."-".trim($rowdb["DECOSUBCODE02"]," ")."-".trim($rowdb["DECOSUBCODE03"]," ");
            $dataTransaksi['lot']=trim($rowdb["LOTCODE"]," ");
        }
        if(count($dataTransaksi)>0){
            
            $tgl_tutup = $_POST['tgl_tutup'];
            $warehouse = $_POST['warehouse'];
            $check = mysqli_query($con,"select id from tbl_stock_opname_gk 
                WHERE 
                tgl_tutup = '$tgl_tutup'
                and not KODE_OBAT='E-1-000' ") ;
            $row_count=mysqli_num_rows($check);
            mysqli_free_result($check);
            if($row_count==0){
                $insert = mysqli_query($con,"INSERT INTO  tbl_stock_opname_gk (ITEMTYPECODE,KODE_OBAT,LONGDESCRIPTION,LOTCODE,LOGICALWAREHOUSECODE,tgl_tutup,total_qty,BASEPRIMARYUNITCODE,pakingan_standar)
                    SELECT 
                        ITEMTYPECODE,
                        KODE_OBAT,
                        LONGDESCRIPTION,
                        LOTCODE,
                        LOGICALWAREHOUSECODE,
                        tgl_tutup,
                        SUM(BASEPRIMARYQUANTITYUNIT) AS total_qty,
                        BASEPRIMARYUNITCODE,
                        '0'
                    FROM tblopname_11 o
                    WHERE 
                        tgl_tutup = '$tgl_tutup'
                        and not KODE_OBAT='E-1-000'
                    GROUP BY  
                        ITEMTYPECODE,
                        KODE_OBAT,
                        LONGDESCRIPTION,
                        LOTCODE,
                        LOGICALWAREHOUSECODE,
                        tgl_tutup,
                        BASEPRIMARYUNITCODE
                    ORDER BY KODE_OBAT ASC ") ;
            }
            if(trim($warehouse," ")=="M101"){
                $query = "SELECT *
                FROM tbl_stock_opname_gk 
                WHERE 
                    tgl_tutup = '$tgl_tutup'
                    AND LOGICALWAREHOUSECODE = '$warehouse'
                    AND KODE_OBAT = '".$dataTransaksi['kode_obat']."'
                    AND LOTCODE = '".$dataTransaksi['lot']."'
                ORDER BY KODE_OBAT ASC";
                $stmt = mysqli_query($con, $query);
                if (!$stmt) {
                    echo "<p class='text-danger'>Query gagal: " . mysqli_error($con) . "</p>";
                    $response->setSuccess(false);
                    $response->addMessage("Query gagal: ".$query." \nERROR : ". mysqli_error($con));
                }

                $num_rows_data=mysqli_num_rows($stmt);
                if ($num_rows_data > 0) {
                    $dataOpane=array();
                    while ($rowOpname = mysqli_fetch_assoc($stmt)) {
                        $dataOpane['id']=$rowOpname['id'];
                        $dataOpane['kode_obat']=$rowOpname['KODE_OBAT'];
                        $dataOpane['nama_obat']=$rowOpname['LONGDESCRIPTION'];
                        $dataOpane['lot']=$rowOpname['LOTCODE'];
                        $dataOpane['total_qty']=$rowOpname['total_qty'];
                        $dataOpane['qty_dus']=$rowOpname['qty_dus'];
                        $dataOpane['pakingan_standar']=$rowOpname['pakingan_standar'];
                        $dataOpane['total_stock']=$rowOpname['total_stock'];
                        $dataOpane['kategori']=$rowOpname['kategori'];
                        $dataOpane['c']=$rowOpname['konfirmasi'];
                        
                        $dataOpane['total_qty_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['total_qty']);
                        $dataOpane['qty_dus_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['qty_dus']);
                        $dataOpane['pakingan_standar_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['pakingan_standar']);
                        $dataOpane['total_stock_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['total_stock']);

                        //inisiasi data awal standar packaging
                        $dataOpane['ut']=0;
                        $dataOpane['tg']=0;
                        $dataOpane['bj']=0;
                        $dataOpane['bp']=0;
                        $dataOpane['bk']=0;
                    }
                    if($dataOpane['c']==1){
                        $response->setSuccess(false);
                        $response->addMessage("Data : ".$dataOpane['kode_obat']." lot ".$dataOpane['lot']." Dengan Qty Dus : ".$dataOpane['qty_dus_text']." dan Total Stock : ".$dataOpane['total_stock_text']." Sudah Di Konfirmasi");
                        $response->addMessage($dataTransaksi);
                    }else{
                        mysqli_free_result($stmt);
                        $sp = "SELECT * FROM tbl_standar_packaging s WHERE s.kode_erp = '".$dataOpane['kode_obat']."' limit 1";
                        $spResult = mysqli_query($con, $sp);
                        while ($rowSP = mysqli_fetch_assoc($spResult)) {
                            $dataOpane['ut']=$rowSP['pakingan_utuh'];
                            $dataOpane['tg']=$rowSP['tinggi_pakingan'];
                            $dataOpane['bj']=$rowSP['bj_pakingan'];
                            $dataOpane['bp']=$rowSP['berat_pakingan'];
                            $dataOpane['bk']=$rowSP['berat_pakingan_botol_kecil'];
                        }
                        $response->setSuccess(true);
                        $response->addMessage("Berhasil Menampilkan Tutup Buku");
                        $response->addMessage($dataTransaksi);
                        $response->addMessage($num_rows_data);
                        $response->setData($dataOpane);
                    }
                }else{
                    $response->setSuccess(false);
                    $response->addMessage("Data Tutup Buku Untuk ".$dataTransaksi['kode_obat']." lot ".$dataTransaksi['lot']." Tidak Tersedia");
                    $response->addMessage($dataTransaksi);
                }
                $response->send();
            }
            else if(trim($warehouse," ")=="M510"){
                $query = "SELECT *
                FROM tbl_stock_opname_gk 
                WHERE 
                    tgl_tutup = '$tgl_tutup'
                    AND LOGICALWAREHOUSECODE = '$warehouse'
                    AND KODE_OBAT = '".$dataTransaksi['kode_obat']."'
                    AND LOTCODE = '".$dataTransaksi['lot']."'
                ORDER BY KODE_OBAT ASC";
                $stmt = mysqli_query($con, $query);
                if (!$stmt) {
                    echo "<p class='text-danger'>Query gagal: " . mysqli_error($con) . "</p>";
                    $response->setSuccess(false);
                    $response->addMessage("Query gagal: ".$query." \nERROR : ". mysqli_error($con));
                }

                $num_rows_data=mysqli_num_rows($stmt);
                if ($num_rows_data > 0) {
                    $dataOpane=array();
                    while ($rowOpname = mysqli_fetch_assoc($stmt)) {
                        $dataOpane['id']=$rowOpname['id'];
                        $dataOpane['kode_obat']=$rowOpname['KODE_OBAT'];
                        $dataOpane['nama_obat']=$rowOpname['LONGDESCRIPTION'];
                        $dataOpane['lot']=$rowOpname['LOTCODE'];
                        $dataOpane['total_qty']=$rowOpname['total_qty'];
                        $dataOpane['qty_dus']=$rowOpname['qty_dus'];
                        $dataOpane['pakingan_standar']=$rowOpname['pakingan_standar'];
                        $dataOpane['total_stock']=$rowOpname['total_stock'];
                        $dataOpane['kategori']=$rowOpname['kategori'];
                        $dataOpane['c']=$rowOpname['konfirmasi'];
                        
                        $dataOpane['total_qty_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['total_qty']);
                        $dataOpane['qty_dus_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['qty_dus']);
                        $dataOpane['pakingan_standar_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['pakingan_standar']);
                        $dataOpane['total_stock_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['total_stock']);

                        //inisiasi data awal standar packaging
                        $dataOpane['ut']=0;
                        $dataOpane['tg']=0;
                        $dataOpane['bj']=0;
                        $dataOpane['bp']=0;
                        $dataOpane['bk']=0;
                    }
                    if($dataOpane['c']==1){
                        $response->setSuccess(false);
                        $response->addMessage("Data : ".$dataOpane['kode_obat']." lot ".$dataOpane['lot']." Dengan Qty Dus : ".$dataOpane['qty_dus_text']." dan Total Stock : ".$dataOpane['total_stock_text']." Sudah Di Konfirmasi");
                        $response->addMessage($dataTransaksi);
                    }else{
                        mysqli_free_result($stmt);
                        $sp = "SELECT * FROM tbl_standar_packaging s WHERE s.kode_erp = '".$dataOpane['kode_obat']."' limit 1";
                        $spResult = mysqli_query($con, $sp);
                        while ($rowSP = mysqli_fetch_assoc($spResult)) {
                            $dataOpane['ut']=$rowSP['pakingan_utuh'];
                            $dataOpane['tg']=$rowSP['tinggi_pakingan'];
                            $dataOpane['bj']=$rowSP['bj_pakingan'];
                            $dataOpane['bp']=$rowSP['berat_pakingan'];
                            $dataOpane['bk']=$rowSP['berat_pakingan_botol_kecil'];
                        }
                        $response->setSuccess(true);
                        $response->addMessage("Berhasil Menampilkan Tutup Buku");
                        $response->addMessage($dataTransaksi);
                        $response->addMessage($num_rows_data);
                        $response->setData($dataOpane);
                    }
                }else{
                    $response->setSuccess(false);
                    $response->addMessage("Data Tutup Buku Untuk ".$dataTransaksi['kode_obat']." lot ".$dataTransaksi['lot']." Tidak Tersedia");
                    $response->addMessage($dataTransaksi);
                }
                $response->send();
            }
        }else{
            $response->setSuccess(false);
            $response->addMessage("Data Transaksi Tidak Ditemukan");
        }
        $response->send();
    }
    else if($_POST['check']=="check_transaksi_multiple"){
        $prepare=db2_prepare ($conn1,"SELECT
                DECOSUBCODE01,
                DECOSUBCODE02,
                DECOSUBCODE03,
                LOTCODE 
            FROM
                STOCKTRANSACTION s
            WHERE 
                s.TRANSACTIONNUMBER = ? ");
        db2_execute($prepare,array(trim($_POST['val']," ")));
        
        $dataTransaksi=array();
        $ct=0;
        while($rowdb = db2_fetch_assoc($prepare)){
            $ct++;
            $dataTransaksi[$ct]['kode_obat']=trim($rowdb["DECOSUBCODE01"]," ")."-".trim($rowdb["DECOSUBCODE02"]," ")."-".trim($rowdb["DECOSUBCODE03"]," ");
            $dataTransaksi[$ct]['lot']=trim($rowdb["LOTCODE"]," ");
        }
        if($ct>0){
            $response->setSuccess(true);
            $response->addMessage("Berhasil Menampilkan Data Transaksi");
            $response->addMessage($ct);
            $response->setData($dataTransaksi);
        }else{
            $response->setSuccess(false);
            $response->addMessage("Data Transaksi Tidak Ditemukan");
        }
        $response->send();
    }
    else if($_POST['check']=="edit_data"){
        $tgl_tutup = $_POST['tgl_tutup'];
        $warehouse = $_POST['warehouse'];
        $check = mysqli_query($con,"select id from tbl_stock_opname_gk 
                WHERE 
                tgl_tutup = '$tgl_tutup'
                and not KODE_OBAT='E-1-000' ") ;
        $row_count=mysqli_num_rows($check);
        mysqli_free_result($check);
        if($row_count==0){
            $insert = mysqli_query($con,"INSERT INTO  tbl_stock_opname_gk (ITEMTYPECODE,KODE_OBAT,LONGDESCRIPTION,LOTCODE,LOGICALWAREHOUSECODE,tgl_tutup,total_qty,BASEPRIMARYUNITCODE,pakingan_standar)
                    SELECT 
                        ITEMTYPECODE,
                        KODE_OBAT,
                        LONGDESCRIPTION,
                        LOTCODE,
                        LOGICALWAREHOUSECODE,
                        tgl_tutup,
                        SUM(BASEPRIMARYQUANTITYUNIT) AS total_qty,
                        BASEPRIMARYUNITCODE,
                        '0'
                    FROM tblopname_11 o
                    WHERE 
                        tgl_tutup = '$tgl_tutup'
                        and not KODE_OBAT='E-1-000'
                    GROUP BY  
                        ITEMTYPECODE,
                        KODE_OBAT,
                        LONGDESCRIPTION,
                        LOTCODE,
                        LOGICALWAREHOUSECODE,
                        tgl_tutup,
                        BASEPRIMARYUNITCODE
                    ORDER BY KODE_OBAT ASC ") ;
        }
        $query = "SELECT *
                FROM tbl_stock_opname_gk 
                WHERE 
                    tgl_tutup = '$tgl_tutup'
                    AND LOGICALWAREHOUSECODE = '$warehouse'
                    AND KODE_OBAT = '".$_POST['kode_obat']."'
                    AND LOTCODE = '".$_POST['lot']."'
                ORDER BY KODE_OBAT ASC";
        $stmt = mysqli_query($con, $query);
        if (!$stmt) {
            $response->setSuccess(false);
            $response->addMessage("Query gagal: ".$query." \nERROR : ". mysqli_error($con));
        }

        $num_rows_data=mysqli_num_rows($stmt);
        if ($num_rows_data > 0) {
            $dataOpane=array();
            while ($rowOpname = mysqli_fetch_assoc($stmt)) {
                $dataOpane['id']=$rowOpname['id'];
                $dataOpane['kode_obat']=$rowOpname['KODE_OBAT'];
                $dataOpane['nama_obat']=$rowOpname['LONGDESCRIPTION'];
                $dataOpane['lot']=$rowOpname['LOTCODE'];
                $dataOpane['total_qty']=$rowOpname['total_qty'];
                $dataOpane['qty_dus']=$rowOpname['qty_dus'];
                $dataOpane['pakingan_standar']=$rowOpname['pakingan_standar'];
                $dataOpane['total_stock']=$rowOpname['total_stock'];
                $dataOpane['kategori']=$rowOpname['kategori'];
                $dataOpane['c']=$rowOpname['konfirmasi'];
                        
                $dataOpane['total_qty_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['total_qty']);
                $dataOpane['qty_dus_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['qty_dus']);
                $dataOpane['pakingan_standar_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['pakingan_standar']);
                $dataOpane['total_stock_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['total_stock']);

                //inisiasi data awal standar packaging
                $dataOpane['ut']=0;
                $dataOpane['tg']=0;
                $dataOpane['bj']=0;
                $dataOpane['bp']=0;
                $dataOpane['bk']=0;
            }
            if($dataOpane['c']==1){
                $response->setSuccess(false);
                $response->addMessage("Data : ".$dataOpane['kode_obat']." lot ".$dataOpane['lot']." Dengan Qty Dus : ".$dataOpane['qty_dus_text']." dan Total Stock : ".$dataOpane['total_stock_text']." Sudah Di Konfirmasi");
            }else{
                mysqli_free_result($stmt);
                $sp = "SELECT * FROM tbl_standar_packaging s WHERE s.kode_erp = '".$dataOpane['kode_obat']."' limit 1";
                $spResult = mysqli_query($con, $sp);
                while ($rowSP = mysqli_fetch_assoc($spResult)) {
                    $dataOpane['ut']=$rowSP['pakingan_utuh'];
                    $dataOpane['tg']=$rowSP['tinggi_pakingan'];
                    $dataOpane['bj']=$rowSP['bj_pakingan'];
                    $dataOpane['bp']=$rowSP['berat_pakingan'];
                    $dataOpane['bk']=$rowSP['berat_pakingan_botol_kecil'];
                }
                $response->setSuccess(true);
                $response->addMessage("Berhasil Menampilkan Tutup Buku");
                $response->addMessage($num_rows_data);
                $response->setData($dataOpane);
            }
        }else{
            $response->setSuccess(false);
            $response->addMessage("Data Tutup Buku Untuk ".$_POST['kode_obat']." lot ".$_POST['lot']." Tidak Tersedia");
        }
        $response->send();
    }
    else if($_POST['check']=="simpan_stock" && $id != 0){
        $update = "UPDATE tbl_stock_opname_gk 
                 SET qty_dus = ? ,
                 pakingan_standar = ?,
                 kategori = ?,
                 total_stock = ?
                 WHERE id = ? LIMIT 1";
        $confirm=mysqli_prepare( $con, $update );
        mysqli_stmt_bind_param($confirm, "dssds", $_POST['qty_dus'],$_POST['pakingan_standar'],$_POST['kategori'],$_POST['total_stock'],$id );
        if(mysqli_stmt_execute($confirm)){ 
            $response->setSuccess(true);
            $response->addMessage("Berhasil Save Stock");
            $response->addMessage($id);
            $response->send();
        }
        else {
            $response->setSuccess(false);
            $response->addMessage("Gagal Save Stock : ".mysqli_error($con));
            $response->send();
        }
    }
  }
  $response->setSuccess(false);
  $response->addMessage("Tidak ada sesion");
  $response->send();
  
  