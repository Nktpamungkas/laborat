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
            $query = "SELECT id,qty_dus,total_stock
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
                $insert = mysqli_query($con,"INSERT INTO  tbl_stock_opname_gk (ITEMTYPECODE,KODE_OBAT,LONGDESCRIPTION,LOTCODE,LOGICALWAREHOUSECODE,tgl_tutup,total_qty,BASEPRIMARYUNITCODE,pakingan_utuh)
                    SELECT 
                        ITEMTYPECODE,
                        KODE_OBAT,
                        LONGDESCRIPTION,
                        LOTCODE,
                        LOGICALWAREHOUSECODE,
                        tgl_tutup,
                        SUM(BASEPRIMARYQUANTITYUNIT) AS total_qty,
                        BASEPRIMARYUNITCODE,
                        (select pakingan_utuh from tbl_standar_packaging s where s.kode_erp = o.KODE_OBAT limit 1) pakingan_utuh
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
                        $dataOpane['pakingan_utuh']=$rowOpname['pakingan_utuh'];
                        $dataOpane['total_stock']=$rowOpname['total_stock'];
                        $dataOpane['c']=$rowOpname['konfirmasi'];
                        
                        $dataOpane['total_qty_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['total_qty']);
                        $dataOpane['qty_dus_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['qty_dus']);
                        $dataOpane['pakingan_utuh_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['pakingan_utuh']);
                        $dataOpane['total_stock_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['total_stock']);
                    }
                    if($dataOpane['c']==1){
                        $response->setSuccess(false);
                        $response->addMessage("Data : ".$dataOpane['kode_obat']." lot ".$dataOpane['lot']." Dengan Qty Dus : ".$dataOpane['qty_dus_text']." dan Total Stock : ".$dataOpane['total_stock_text']." Sudah Di Konfirmasi");
                        $response->addMessage($dataTransaksi);
                    }else{
                        $response->setSuccess(true);
                        $response->addMessage("Berhasil Menampilkan Tutup Buku");
                        $response->addMessage($dataTransaksi);
                        $response->addMessage($num_rows_data);
                        $response->setData($dataOpane);
                    }
                }else{
                    $response->setSuccess(false);
                    $response->addMessage("Data Tutup Buku Tidak Tersedia");
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
                        $dataOpane['pakingan_utuh']=$rowOpname['pakingan_utuh'];
                        $dataOpane['total_stock']=$rowOpname['total_stock'];
                        $dataOpane['c']=$rowOpname['konfirmasi'];
                        
                        $dataOpane['total_qty_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['total_qty']);
                        $dataOpane['qty_dus_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['qty_dus']);
                        $dataOpane['pakingan_utuh_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['pakingan_utuh']);
                        $dataOpane['total_stock_text']=Penomoran_helper::nilaiKeRibuan($rowOpname['total_stock']);
                    }
                    if($dataOpane['c']==1){
                        $response->setSuccess(false);
                        $response->addMessage("Data : ".$dataOpane['kode_obat']." lot ".$dataOpane['lot']." Dengan Qty Dus : ".$dataOpane['qty_dus_text']." dan Total Stock : ".$dataOpane['total_stock_text']." Sudah Di Konfirmasi");
                        $response->addMessage($dataTransaksi);
                    }else{
                        $response->setSuccess(true);
                        $response->addMessage("Berhasil Menampilkan Tutup Buku");
                        $response->addMessage($dataTransaksi);
                        $response->addMessage($num_rows_data);
                        $response->setData($dataOpane);
                    }
                }else{
                    $response->setSuccess(false);
                    $response->addMessage("Data Tutup Buku Tidak Tersedia");
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
    else if($_POST['check']=="simpan_stock" && $id != 0){
        $update = "UPDATE tbl_stock_opname_gk 
                 SET qty_dus = ? ,
                 total_stock = ?
                 WHERE id = ? LIMIT 1";
        $confirm=mysqli_prepare( $con, $update );
        mysqli_stmt_bind_param($confirm, "dds", $_POST['qty_dus'],$_POST['total_stock'],$id );
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
  else{
    $response->setSuccess(false);
    $response->addMessage("Tidak ada sesion");
    $response->send();
  }
  