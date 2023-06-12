<?php
    include "koneksi.php";
    $idstatus               = $_GET['idm']; // ID_STATUS_MATCHING
    $idmatching             = $_GET['id']; // ID_MATCHING
    $IMPORTAUTOCOUNTER      = $_GET['IMPORTAUTOCOUNTER'];
    $jenis_suffix           = $_GET['suffix'];
    $number_suffix          = $_GET['numbersuffix'];
    
    $recipe_add = mysqli_query($con, "SELECT
                                        a.id AS id_matching_detail,
                                        c.approve_at,
                                        b.no_warna,
                                        left(b.no_item, 3) AS no_item,
                                        b.benang,
                                        a.*, b.*, c.*
                                    FROM
                                        tbl_matching_detail a
                                        RIGHT JOIN tbl_matching b ON b.id = a.id_matching
                                        LEFT JOIN tbl_status_matching c ON c.idm = b.no_resep 
                                    WHERE
                                        id_matching = '$idmatching' 
                                        AND id_status = '$idstatus' 
                                    LIMIT 1");

    if($recipe_add->num_rows > 0){
        $d_add = mysqli_fetch_assoc($recipe_add);
        $date_approve = date_create($d_add['approve_at']);

        $rowdata1 = array($IMPORTAUTOCOUNTER, // FATHERID = IMPORTAUTOCOUNTER
                        $d_rmp['id_matching_detail'], // IMPORTAUTOCOUNTER = NOMOR URUT
                        'Recipe', // NAMEENTITYNAME
                        'ApprovalDate', // NAMENAME
                        'ApprovalDate', // FIELDNAME
                        NULL, // VALUESTRING
                        0, // VALUEINT
                        0, // VALUEBOOLEAN
                        date_format($date_approve, "Y-m-d"), // VALUEDATE -Here
                        NULL, // VALUEDECIMAL
                        0, // VALUELONG
                        NULL, // VALUETIME
                        NULL, // VALUETIMESTAMP
                        5, // WSOPERATION
                        NULL, // IMPOPERATIONUSER
                        0, // IMPORTSTATUS
                        NULL, // IMPCREATIONDATETIME
                        NULL, // IMPCREATIONUSER
                        NULL, // IMPLASTUPDATEDATETIME
                        NULL, // IMPLASTUPDATEUSER
                        NULL, // IMPORTDATETIME
                        3, // RETRYNR
                        0, // NEXTRETRY
                        0 // IMPORTID
                        );
        // fputcsv($f, $rowdata1, $delimiter);

        $rowdata2 = array($IMPORTAUTOCOUNTER, // FATHERID = IMPORTAUTOCOUNTER
                        $d_rmp['id_matching_detail']+1, // IMPORTAUTOCOUNTER = NOMOR URUT
                        'Recipe', // NAMEENTITYNAME
                        'ArticleGroup', // NAMENAME
                        'ArticleGroupCode', // FIELDNAME
                        substr($d_rmp['no_item'], 0, 3), // VALUESTRING -Here
                        0, // VALUEINT
                        0, // VALUEBOOLEAN
                        NULL, // VALUEDATE
                        NULL, // VALUEDECIMAL
                        0, // VALUELONG
                        NULL, // VALUETIME
                        NULL, // VALUETIMESTAMP
                        5, // WSOPERATION
                        NULL, // IMPOPERATIONUSER
                        0, // IMPORTSTATUS
                        NULL, // IMPCREATIONDATETIME
                        NULL, // IMPCREATIONUSER
                        NULL, // IMPLASTUPDATEDATETIME
                        NULL, // IMPLASTUPDATEUSER
                        NULL, // IMPORTDATETIME
                        3, // RETRYNR
                        0, // NEXTRETRY
                        0 // IMPORTID
                        );
        // fputcsv($f, $rowdata2, $delimiter);

        $rowdata3 = array($IMPORTAUTOCOUNTER, // FATHERID = IMPORTAUTOCOUNTER
                        $d_rmp['id_matching_detail']+2, // IMPORTAUTOCOUNTER = NOMOR URUT
                        'Recipe', // NAMEENTITYNAME
                        'CarryOver', // NAMENAME
                        'CarryOver', // FIELDNAME
                        NULL, // VALUESTRING
                        0, // VALUEINT
                        0, // VALUEBOOLEAN
                        NULL, // VALUEDATE
                        NULL, // VALUEDECIMAL -Here
                        0, // VALUELONG
                        NULL, // VALUETIME
                        NULL, // VALUETIMESTAMP
                        5, // WSOPERATION
                        NULL, // IMPOPERATIONUSER
                        0, // IMPORTSTATUS
                        NULL, // IMPCREATIONDATETIME
                        NULL, // IMPCREATIONUSER
                        NULL, // IMPLASTUPDATEDATETIME
                        NULL, // IMPLASTUPDATEUSER
                        NULL, // IMPORTDATETIME
                        3, // RETRYNR
                        0, // NEXTRETRY
                        0 // IMPORTID
                        );
        // fputcsv($f, $rowdata3, $delimiter);

        $rowdata4 = array($IMPORTAUTOCOUNTER, // FATHERID = IMPORTAUTOCOUNTER
                        $d_rmp['id_matching_detail']+3, // IMPORTAUTOCOUNTER = NOMOR URUT
                        'Recipe', // NAMEENTITYNAME
                        'Chroma', // NAMENAME
                        'Chroma', // FIELDNAME
                        NULL, // VALUESTRING -Here
                        0, // VALUEINT
                        0, // VALUEBOOLEAN
                        NULL, // VALUEDATE
                        NULL, // VALUEDECIMAL
                        0, // VALUELONG
                        NULL, // VALUETIME
                        NULL, // VALUETIMESTAMP
                        5, // WSOPERATION
                        NULL, // IMPOPERATIONUSER
                        0, // IMPORTSTATUS
                        NULL, // IMPCREATIONDATETIME
                        NULL, // IMPCREATIONUSER
                        NULL, // IMPLASTUPDATEDATETIME
                        NULL, // IMPLASTUPDATEUSER
                        NULL, // IMPORTDATETIME
                        3, // RETRYNR
                        0, // NEXTRETRY
                        0 // IMPORTID
                        );
        // fputcsv($f, $rowdata4, $delimiter);

        $rowdata5 = array($IMPORTAUTOCOUNTER, // FATHERID = IMPORTAUTOCOUNTER
                        $d_rmp['id_matching_detail']+4, // IMPORTAUTOCOUNTER = NOMOR URUT
                        'Recipe', // NAMEENTITYNAME
                        'LDGrouping', // NAMENAME
                        'LDGrouping', // FIELDNAME
                        NULL, // VALUESTRING -Here
                        0, // VALUEINT
                        0, // VALUEBOOLEAN
                        NULL, // VALUEDATE
                        NULL, // VALUEDECIMAL
                        0, // VALUELONG
                        NULL, // VALUETIME
                        NULL, // VALUETIMESTAMP
                        5, // WSOPERATION
                        NULL, // IMPOPERATIONUSER
                        0, // IMPORTSTATUS
                        NULL, // IMPCREATIONDATETIME
                        NULL, // IMPCREATIONUSER
                        NULL, // IMPLASTUPDATEDATETIME
                        NULL, // IMPLASTUPDATEUSER
                        NULL, // IMPORTDATETIME
                        3, // RETRYNR
                        0, // NEXTRETRY
                        0 // IMPORTID
                        );
        // fputcsv($f, $rowdata5, $delimiter);

        $rowdata6 = array($IMPORTAUTOCOUNTER, // FATHERID = IMPORTAUTOCOUNTER
                        $d_rmp['id_matching_detail']+5, // IMPORTAUTOCOUNTER = NOMOR URUT
                        'Recipe', // NAMEENTITYNAME
                        'OptionApproved', // NAMENAME
                        'OptionApproved', // FIELDNAME
                        NULL, // VALUESTRING -Here
                        0, // VALUEINT
                        0, // VALUEBOOLEAN
                        NULL, // VALUEDATE
                        NULL, // VALUEDECIMAL
                        0, // VALUELONG
                        NULL, // VALUETIME
                        NULL, // VALUETIMESTAMP
                        5, // WSOPERATION
                        NULL, // IMPOPERATIONUSER
                        0, // IMPORTSTATUS
                        NULL, // IMPCREATIONDATETIME
                        NULL, // IMPCREATIONUSER
                        NULL, // IMPLASTUPDATEDATETIME
                        NULL, // IMPLASTUPDATEUSER
                        NULL, // IMPORTDATETIME
                        3, // RETRYNR
                        0, // NEXTRETRY
                        0 // IMPORTID
                        );
        // fputcsv($f, $rowdata6, $delimiter);

        $rowdata7 = array($IMPORTAUTOCOUNTER, // FATHERID = IMPORTAUTOCOUNTER
                        $d_rmp['id_matching_detail']+6, // IMPORTAUTOCOUNTER = NOMOR URUT
                        'Recipe', // NAMEENTITYNAME
                        'OriginalCustomer', // NAMENAME
                        'OriginalCustomerType', // FIELDNAME
                        1, // VALUESTRING -Here
                        0, // VALUEINT
                        0, // VALUEBOOLEAN
                        NULL, // VALUEDATE
                        NULL, // VALUEDECIMAL
                        0, // VALUELONG
                        NULL, // VALUETIME
                        NULL, // VALUETIMESTAMP
                        5, // WSOPERATION
                        NULL, // IMPOPERATIONUSER
                        0, // IMPORTSTATUS
                        NULL, // IMPCREATIONDATETIME
                        NULL, // IMPCREATIONUSER
                        NULL, // IMPLASTUPDATEDATETIME
                        NULL, // IMPLASTUPDATEUSER
                        NULL, // IMPORTDATETIME
                        3, // RETRYNR
                        0, // NEXTRETRY
                        0 // IMPORTID
                        );
        // fputcsv($f, $rowdata7, $delimiter);

        $rowdata8 = array($IMPORTAUTOCOUNTER, // FATHERID = IMPORTAUTOCOUNTER
                        $d_rmp['id_matching_detail']+7, // IMPORTAUTOCOUNTER = NOMOR URUT
                        'Recipe', // NAMEENTITYNAME
                        'OriginalCustomer', // NAMENAME
                        'OriginalCustomerCode', // FIELDNAME
                        1, // VALUESTRING -Here
                        0, // VALUEINT
                        0, // VALUEBOOLEAN
                        NULL, // VALUEDATE
                        NULL, // VALUEDECIMAL
                        0, // VALUELONG
                        NULL, // VALUETIME
                        NULL, // VALUETIMESTAMP
                        5, // WSOPERATION
                        NULL, // IMPOPERATIONUSER
                        0, // IMPORTSTATUS
                        NULL, // IMPCREATIONDATETIME
                        NULL, // IMPCREATIONUSER
                        NULL, // IMPLASTUPDATEDATETIME
                        NULL, // IMPLASTUPDATEUSER
                        NULL, // IMPORTDATETIME
                        3, // RETRYNR
                        0, // NEXTRETRY
                        0 // IMPORTID
                        );
        // fputcsv($f, $rowdata8, $delimiter);

        $rowdata9 = array($IMPORTAUTOCOUNTER, // FATHERID = IMPORTAUTOCOUNTER
                        $d_rmp['id_matching_detail']+8, // IMPORTAUTOCOUNTER = NOMOR URUT
                        'Recipe', // NAMEENTITYNAME
                        'YarnDescription', // NAMENAME
                        'YarnDescription   ', // FIELDNAME
                        $d_rmp['benang'], // VALUESTRING -Here
                        0, // VALUEINT
                        0, // VALUEBOOLEAN
                        NULL, // VALUEDATE
                        NULL, // VALUEDECIMAL
                        0, // VALUELONG
                        NULL, // VALUETIME
                        NULL, // VALUETIMESTAMP
                        5, // WSOPERATION
                        NULL, // IMPOPERATIONUSER
                        0, // IMPORTSTATUS
                        NULL, // IMPCREATIONDATETIME
                        NULL, // IMPCREATIONUSER
                        NULL, // IMPLASTUPDATEDATETIME
                        NULL, // IMPLASTUPDATEUSER
                        NULL, // IMPORTDATETIME
                        3, // RETRYNR
                        0, // NEXTRETRY
                        0 // IMPORTID
                        );
        // fputcsv($f, $rowdata9, $delimiter);

        // // Move back to beginning of file 
        // fseek($f, 0); 

        // // Set headers to download file rather than displayed 
        // header('Content-Type: text/csv'); 
        // header('Content-Disposition: attachment; filename="' . $filename . '";'); 

        // //output all remaining data on a file pointer 
        // fpassthru($f);
    }
?>