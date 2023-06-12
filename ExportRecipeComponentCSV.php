<?php
    include "koneksi.php";
    $idstatus               = $_GET['idm']; // ID_STATUS_MATCHING
    $idmatching             = $_GET['id']; // ID_MATCHING
    $IMPORTAUTOCOUNTER      = $_GET['IMPORTAUTOCOUNTER'];
    $jenis_suffix           = $_GET['suffix'];
    $number_suffix          = $_GET['numbersuffix'];
    $tgl = date('Y-m-d H:i:s');

    if($jenis_suffix == "1"){
        $recipe_cmp = mysqli_query($con, "SELECT a.id AS id_matching_detail,
                                            a.id_matching as id_matching,
                                            a.id_status as is_status,
                                            b.recipe_code as recipe_code,
                                            case
                                                when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
                                                when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
                                            end as no_resep_convert,
                                            kode as kode,
                                            nama as nama,
                                            case
                                                when conc10 != 0 then conc10
                                                when conc9 != 0 then conc9
                                                when conc8 != 0 then conc8
                                                when conc7 != 0 then conc7
                                                when conc6 != 0 then conc6
                                                when conc5 != 0 then conc5
                                                when conc4 != 0 then conc4
                                                when conc3 != 0 then conc3
                                                when conc2 != 0 then conc2
                                                when conc1 != 0 then conc1
                                            end as conc,
                                            remark as remark 
                                        FROM tbl_matching_detail a 
                                        LEFT JOIN tbl_matching b ON b.id = a.id_matching
                                        left join tbl_status_matching tsm on tsm.idm = b.no_resep 
                                        WHERE a.id_matching = '$idmatching' AND a.id_status = '$idstatus' and remark = 'from Co-power' order by a.flag ASC");
        $delimiter = ","; 
        $filename = "RC_" . $_GET['rcode'] . ".csv"; 
        
        // Create a file pointer 
        $f = fopen('php://memory', 'w'); 

        // Set column headers 
        $fields = array('FATHERID',
                        'IMPORTAUTOCOUNTER',
                        'OWNEDCOMPONENT',
                        'RECIPEITEMTYPECODE',
                        'RECIPESUBCODE01',
                        'RECIPESUBCODE02',
                        'RECIPESUBCODE03',
                        'RECIPESUBCODE04',
                        'RECIPESUBCODE05',
                        'RECIPESUBCODE06',
                        'RECIPESUBCODE07',
                        'RECIPESUBCODE08',
                        'RECIPESUBCODE09',
                        'RECIPESUBCODE10',
                        'RECIPESUFFIXCODE',
                        'GROUPNUMBER',
                        'GROUPTYPECODE',
                        'LINETYPE',
                        'SEQUENCE',
                        'ALTERNATIVE',
                        'SUBSEQUENCE',
                        'COMPONENTINCIDENCE',
                        'REFRECIPEGROUPNUMBER',
                        'REFRECIPESEQUENCE',
                        'REFRECIPEALTERNATIVE',
                        'REFRECIPESUBSEQUENCE',
                        'REFRECIPESTATUS',
                        'ITEMTYPEAFICODE',
                        'SUBCODE01',
                        'SUBCODE02',
                        'SUBCODE03',
                        'SUBCODE04',
                        'SUBCODE05',
                        'SUBCODE06',
                        'SUBCODE07',
                        'SUBCODE08',
                        'SUBCODE09',
                        'SUBCODE10',
                        'SUFFIXCODE',
                        'COMMENTLINE',
                        'CONSUMPTIONTYPE',
                        'ASSEMBLYUOMCODE',
                        'COMPONENTUOMCODE',
                        'COMPONENTUOMTYPE',
                        'CONSUMPTION',
                        'COMPOSITIONCOMPONENTCODE',
                        'CONSFORMIXLABEL',
                        'CONSPERBATCHLABEL',
                        'CONSPERLABEL',
                        'WATERMANAGEMENT',
                        'BINDERFILLERCOMPONENT',
                        'PRODUCED',
                        'PRICELISTCODE',
                        'COSTINGPLANTCODE',
                        'INITIALENGINEERINGCHANGE',
                        'FINALENGINEERINGCHANGE',
                        'INITIALDATE',
                        'FINALDATE',
                        'UNITARYBATCHSTANDARDSIZE',
                        'ALLOWDELETEBINDERFILLER',
                        'TOTALCOSTTEXT',
                        'WSOPERATION',
                        'IMPOPERATIONUSER',
                        'IMPORTSTATUS',
                        'IMPCREATIONDATETIME',
                        'IMPCREATIONUSER',
                        'IMPLASTUPDATEDATETIME',
                        'IMPLASTUPDATEUSER',
                        'IMPORTDATETIME',
                        'RETRYNR',
                        'NEXTRETRY',
                        'IMPORTID',
                        'RELATEDDEPENDENTID'); 
        fputcsv($f, $fields, $delimiter);

        //autonumber for IMPORTAUTOCOUNTER
        $q_iac = mysqli_query($con, "SELECT nomor_urut FROM importautocounter");
        $d_IMPORTAUTOCOUNTER = mysqli_fetch_assoc($q_iac);
        
        // Output each row of the data, format line as csv and write to file pointer 
        $SEQUENCE = 1;
        while($r_cmp = $recipe_cmp->fetch_assoc()){ 
            $dyestuff = mysqli_query($con, "SELECT * FROM tbl_dyestuff WHERE code = '$r_cmp[kode]'");
            $r_code = $dyestuff->fetch_assoc();

            if($r_code['Product_Unit'] == 1){
                $CONSUMPTIONTYPE = 1;
            }else{
                $CONSUMPTIONTYPE = 2;
            }
            // if(substr($r_code['code'], 0,1) == 'E'){
            //     $CONSUMPTIONTYPE = 1;
            // }else{
            //     $CONSUMPTIONTYPE = 2;
            // }
            $no_urut = $d_IMPORTAUTOCOUNTER['nomor_urut']++;

            if($jenis_suffix == "1"){
                $RECIPESUBCODE01 = substr($r_cmp['recipe_code'],0,14);
            }elseif($jenis_suffix == "2"){
                $RECIPESUBCODE01 = substr($r_cmp['recipe_code'],15);
            }
            $_SEQUENCE = $SEQUENCE++.'0';
            $subcode01 = substr($r_code['code'], 0,1); 
            $subcode02 = substr($r_code['code'], 2,1);
            $subcode03 = substr($r_code['code'], 4);
            // $insert_recipeComponentBean = db2_exec($conn1, );
            echo "INSERT INTO RECIPECOMPONENTBEAN(FATHERID,
            IMPORTAUTOCOUNTER,
            OWNEDCOMPONENT,
            RECIPEITEMTYPECODE,
            RECIPESUBCODE01,
            RECIPESUFFIXCODE,
            GROUPNUMBER,
            GROUPTYPECODE,
            LINETYPE,
            SEQUENCE,
            SUBSEQUENCE,
            COMPONENTINCIDENCE,
            REFRECIPEGROUPNUMBER,
            REFRECIPESEQUENCE,
            REFRECIPESUBSEQUENCE,
            REFRECIPESTATUS,
            ITEMTYPEAFICODE,
            SUBCODE01,
            SUBCODE02,
            SUBCODE03,
            CONSUMPTIONTYPE,
            ASSEMBLYUOMCODE,
            COMPONENTUOMCODE,
            COMPONENTUOMTYPE,
            CONSUMPTION,
            WATERMANAGEMENT,
            BINDERFILLERCOMPONENT,
            PRODUCED,
            COSTINGPLANTCODE,
            FINALENGINEERINGCHANGE,
            INITIALDATE,
            FINALDATE,
            ALLOWDELETEBINDERFILLER,
            WSOPERATION,
            IMPORTSTATUS,
            IMPORTDATETIME,
            RETRYNR,
            NEXTRETRY,
            IMPORTID,
            RELATEDDEPENDENTID) 
    VALUES(
            '$IMPORTAUTOCOUNTER',
            '$no_urut',
            '0',
            'RFD',
            '$RECIPESUBCODE01', 
            '$r_cmp[no_resep_convert]',
            '10', 
            '001',
            '1',
            '$_SEQUENCE',
            '10',
            '100', 
            '0',
            '0', 
            '0', 
            '0', 
            'DYC', 
            '$subcode01', 
            '$subcode02', 
            '$subcode03', 
            '$CONSUMPTIONTYPE', 
            'l', 
            'g', 
            '1', 
            '$r_cmp[conc]', 
            '1', 
            '0', 
            '0', 
            '001', 
            '9999999999', 
            '1970-01-01', 
            '2100-12-31', 
            '0', 
            '1', 
            '0', 
            '$tgl', 
            '3', 
            '0', 
            '0', 
            '$no_urut' -- RELATEDDEPENDENTID
            )";
            // $lineData = array($IMPORTAUTOCOUNTER, // FATHERID
                //                 $no_urut, // NOTNULL : IMPORTAUTOCOUNTER
                //                 '0', // OWNEDCOMPONENT
                //                 'RFD', // RECIPEITEMTYPECODE
                //                 substr($r_cmp['recipe_code'],0,15), // RECIPESUBCODE01
                //                 '', //RECIPESUBCODE02
                //                 '', //RECIPESUBCODE03
                //                 '', //RECIPESUBCODE04
                //                 '', //RECIPESUBCODE05
                //                 '', //RECIPESUBCODE06
                //                 '', //RECIPESUBCODE07
                //                 '', //RECIPESUBCODE08
                //                 '', //RECIPESUBCODE09
                //                 '', //RECIPESUBCODE10
                //                 $r_cmp['no_resep_convert'], // RECIPESUFFIXCODE
                //                 '10', //GROUPNUMBER UNTUK CHEMICAL 10
                //                 '001', //GROUPTYPECODE UNTUK CHEMICAL 001, COMMENT LINE 100
                //                 '1', // LINETYPE UNTUK DYC 1, UNTUK COMMENT 3
                //                 $SEQUENCE++.'0', // SEQUENCE URUT DARI 10 - 20 ....
                //                 '', // ALTERNATIVE
                //                 '10', //SUBSEQUENCE SEMENTARA DI SETTING 10 DULU
                //                 '100', //COMPONENTINCIDENCE
                //                 '0', //REFRECIPEGROUPNUMBER
                //                 '0', //REFRECIPESEQUENCE
                //                 '', //REFRECIPEALTERNATIVE
                //                 '0', //REFRECIPESUBSEQUENCE
                //                 '0', //REFRECIPESTATUS
                //                 'DYC', //ITEMTYPEAFICODE
                //                 substr($r_code['code'], 0,1), //SUBCODE01
                //                 substr($r_code['code'], 2,1), //SUBCODE02
                //                 substr($r_code['code'], 4), ////SUBCODE03
                //                 '', //SUBCODE04
                //                 '', //SUBCODE05
                //                 '', //SUBCODE06
                //                 '', //SUBCODE07
                //                 '', //SUBCODE08
                //                 '', //SUBCODE09
                //                 '', //SUBCODE10
                //                 NULL, //SUFFIXCODE
                //                 '', // COMMENTLINE
                //                 $CONSUMPTIONTYPE, //CONSUMPTIONTYPE D = 2, E = 1
                //                 'l', //ASSEMBLYUOMCODE
                //                 'g', //COMPONENTUOMCODE
                //                 '1', //COMPONENTUOMTYPE
                //                 $r_cmp['conc'], //CONSUMPTION
                //                 '', // COMPOSITIONCOMPONENTCODE
                //                 '', // CONSFORMIXLABEL
                //                 '', // CONSPERBATCHLABEL
                //                 '', // CONSPERLABEL
                //                 '1', // WATERMANAGEMENT KALAU COMMENT DIA 0, LAINNYA 1
                //                 '0', // BINDERFILLERCOMPONENT
                //                 '0', // PRODUCED
                //                 '', // PRICELISTCODE
                //                 '001', // COSTINGPLANTCODE
                //                 '', // INITIALENGINEERINGCHANGE
                //                 '9999999999', // FINALENGINEERINGCHANGE
                //                 '1970-01-01', // INITIALDATE
                //                 '2100-12-31', // FINALDATE
                //                 '', // UNITARYBATCHSTANDARDSIZE
                //                 '0', // ALLOWDELETEBINDERFILLER
                //                 '', // TOTALCOSTTEXT
                //                 '1', // WSOPERATION
                //                 '', // IMPOPERATIONUSER
                //                 '0', // IMPORTSTATUS
                //                 '', // IMPCREATIONDATETIME
                //                 '', // IMPCREATIONUSER
                //                 '', // IMPLASTUPDATEDATETIME
                //                 '', // IMPLASTUPDATEUSER
                //                 date('Y-m-d H:i:s'), // NOTNULL : IMPORTDATETIME
                //                 '3', // NOTNULL : RETRYNR
                //                 '0', // NOTNULL : NEXTRETRY
                //                 '0', // NOTNULL : IMPORTID
                //                 $no_urut // RELATEDDEPENDENTID
                //                 ); 
            // fputcsv($f, $lineData, $delimiter);
        } 

        $sql_suhu_menit = mysqli_query($con, "SELECT 
                                                    b.recipe_code as recipe_code,
                                                    case
                                                        when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
                                                        when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
                                                    end as no_resep_convert,
                                                    concat(trim(tsm.tside_c),'`C X ', trim(tsm.tside_min), ' MNT') as COMMENTLINE
                                                from 
                                                    tbl_status_matching tsm 
                                                left join tbl_matching b on b.no_resep = tsm.idm
                                                left join tbl_matching_detail a on a.id_matching = b.id
                                                where tsm.idm = '$number_suffix'
                                                group by tsm.idm
                                                union 
                                                SELECT
                                                    b.recipe_code as recipe_code,
                                                    case
                                                        when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
                                                        when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
                                                    end as no_resep_convert,
                                                    concat('SOAPING ',trim(tsm.soaping_sh),'`C X ', trim(tsm.soaping_tm), ' MNT') as COMMENTLINE
                                                from 
                                                    tbl_status_matching tsm 
                                                left join tbl_matching b on b.no_resep = tsm.idm
                                                left join tbl_matching_detail a on a.id_matching = b.id
                                                where tsm.idm = '$number_suffix' and not tsm.soaping_sh = 0 and not tsm.soaping_tm = 0
                                                group by b.no_resep
                                                union 
                                                SELECT
                                                    b.recipe_code as recipe_code,
                                                    case
                                                        when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
                                                        when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
                                                    end as no_resep_convert,
                                                    concat('RC ',trim(tsm.rc_sh),'`C X ', trim(tsm.rc_tm), ' MNT') as COMMENTLINE
                                                from 
                                                    tbl_status_matching tsm 
                                                left join tbl_matching b on b.no_resep = tsm.idm
                                                left join tbl_matching_detail a on a.id_matching = b.id
                                                where tsm.idm = '$number_suffix' and not tsm.rc_sh = 0 and not tsm.rc_tm = 0
                                                group by b.no_resep
                                                union 
                                                SELECT
                                                    b.recipe_code as recipe_code,
                                                    case
                                                        when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
                                                        when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
                                                    end as no_resep_convert,
                                                    concat('BLEACHING ',trim(tsm.bleaching_sh),'`C X ', trim(tsm.bleaching_tm), ' MNT') as COMMENTLINE
                                                from 
                                                    tbl_status_matching tsm 
                                                left join tbl_matching b on b.no_resep = tsm.idm
                                                left join tbl_matching_detail a on a.id_matching = b.id
                                                where tsm.idm = '$number_suffix' and not tsm.bleaching_sh = 0 and not tsm.bleaching_tm = 0
                                                group by b.no_resep");
        
        $GROUPNUMBER = 2;
        while($r_cmp_suhu_bleaching_rc_soaping = $sql_suhu_menit->fetch_assoc()){
            $no_urut = $d_IMPORTAUTOCOUNTER['nomor_urut']++;

            if($jenis_suffix == "1"){
                $RECIPESUBCODE01 = substr($r_cmp_suhu_bleaching_rc_soaping['recipe_code'],0,14);
            }elseif($jenis_suffix == "2"){
                $RECIPESUBCODE01 = substr($r_cmp_suhu_bleaching_rc_soaping['recipe_code'],15);
            }
            $_GROUPNUMBER = $GROUPNUMBER++.'0';

            // $insert_recipeComponentBean_comment = db2_exec($conn1, "INSERT INTO RECIPECOMPONENTBEAN(FATHERID,
            //                                                 IMPORTAUTOCOUNTER,
            //                                                 OWNEDCOMPONENT,
            //                                                 RECIPEITEMTYPECODE,
            //                                                 RECIPESUBCODE01,
            //                                                 RECIPESUFFIXCODE,
            //                                                 GROUPNUMBER,
            //                                                 GROUPTYPECODE,
            //                                                 LINETYPE,
            //                                                 SEQUENCE,
            //                                                 SUBSEQUENCE,
            //                                                 COMPONENTINCIDENCE,
            //                                                 REFRECIPEGROUPNUMBER,
            //                                                 REFRECIPESEQUENCE,
            //                                                 REFRECIPESUBSEQUENCE,
            //                                                 REFRECIPESTATUS,
            //                                                 ITEMTYPEAFICODE,
            //                                                 COMMENTLINE,
            //                                                 CONSUMPTIONTYPE,
            //                                                 ASSEMBLYUOMCODE,
            //                                                 CONSUMPTION,
            //                                                 WATERMANAGEMENT,
            //                                                 BINDERFILLERCOMPONENT,
            //                                                 PRODUCED,
            //                                                 COSTINGPLANTCODE,
            //                                                 FINALENGINEERINGCHANGE,
            //                                                 INITIALDATE,
            //                                                 FINALDATE,
            //                                                 ALLOWDELETEBINDERFILLER,
            //                                                 WSOPERATION,
            //                                                 IMPORTSTATUS,
            //                                                 IMPORTDATETIME,
            //                                                 RETRYNR,
            //                                                 NEXTRETRY,
            //                                                 IMPORTID,
            //                                                 RELATEDDEPENDENTID) 
            //                                         VALUES(
            //                                                 '$IMPORTAUTOCOUNTER',
            //                                                 '$no_urut',
            //                                                 '0',
            //                                                 'RFD',
            //                                                 '$RECIPESUBCODE01',
            //                                                 '$r_cmp_suhu_bleaching_rc_soaping[no_resep_convert]',
            //                                                 '$_GROUPNUMBER',
            //                                                 '100',
            //                                                 '3',
            //                                                 '10',
            //                                                 '10',
            //                                                 '100',
            //                                                 '0',
            //                                                 '0',
            //                                                 '0',
            //                                                 '0',
            //                                                 'DYC',
            //                                                 '$r_cmp_suhu_bleaching_rc_soaping[COMMENTLINE]',
            //                                                 '$CONSUMPTIONTYPE',
            //                                                 'l',
            //                                                 '0',
            //                                                 '0',
            //                                                 '0',
            //                                                 '0',
            //                                                 '001',
            //                                                 '9999999999',
            //                                                 '1970-01-01',
            //                                                 '2100-12-31',
            //                                                 '0',
            //                                                 '1',
            //                                                 '0',
            //                                                 '$tgl',
            //                                                 '3',
            //                                                 '0',
            //                                                 '0',
            //                                                 '$no_urut')");
            
            // $lineDataSuhu_rc_bleaching_soaping = array($IMPORTAUTOCOUNTER, // FATHERID
                //                                 $no_urut, // NOTNULL : IMPORTAUTOCOUNTER
                //                                 '0', // OWNEDCOMPONENT
                //                                 'RFD', // RECIPEITEMTYPECODE
                //                                 substr($r_cmp_suhu_bleaching_rc_soaping['recipe_code'],0,15), // RECIPESUBCODE01
                //                                 '', //RECIPESUBCODE02
                //                                 '', //RECIPESUBCODE03
                //                                 '', //RECIPESUBCODE04
                //                                 '', //RECIPESUBCODE05
                //                                 '', //RECIPESUBCODE06
                //                                 '', //RECIPESUBCODE07
                //                                 '', //RECIPESUBCODE08
                //                                 '', //RECIPESUBCODE09
                //                                 '', //RECIPESUBCODE10
                //                                 $r_cmp_suhu_bleaching_rc_soaping['no_resep_convert'], // RECIPESUFFIXCODE
                //                                 $GROUPNUMBER++.'0', //GROUPNUMBER UNTUK CHEMICAL 10, GROUPNUMBER UNTUK SUHU 20
                //                                 '100', //GROUPTYPECODE UNTUK CHEMICAL 001, UNTUK COMMENTLINE 100
                //                                 '3', // LINETYPE UNTUK DYC 1, UNTUK COMMENT 3
                //                                 '10', // SEQUENCE URUT DARI 10 - 20 ....
                //                                 '', // ALTERNATIVE
                //                                 '10', //SUBSEQUENCE SEMENTARA DI SETTING 10 DULU
                //                                 '100', //COMPONENTINCIDENCE
                //                                 '0', //REFRECIPEGROUPNUMBER
                //                                 '0', //REFRECIPESEQUENCE
                //                                 '', //REFRECIPEALTERNATIVE
                //                                 '0', //REFRECIPESUBSEQUENCE
                //                                 '0', //REFRECIPESTATUS
                //                                 'DYC', //ITEMTYPEAFICODE
                //                                 '', //SUBCODE01
                //                                 '', //SUBCODE02
                //                                 '', //SUBCODE03
                //                                 '', //SUBCODE04
                //                                 '', //SUBCODE05
                //                                 '', //SUBCODE06
                //                                 '', //SUBCODE07
                //                                 '', //SUBCODE08
                //                                 '', //SUBCODE09
                //                                 '', //SUBCODE10
                //                                 NULL, //SUFFIXCODE
                //                                 $r_cmp_suhu_bleaching_rc_soaping['COMMENTLINE'], // COMMENTLINE UNTUK SUHU, RC, SOAPING, BLEACHING
                //                                 NULL, //CONSUMPTIONTYPE D = 2, E = 1, COMMENTLINE = NULL
                //                                 'l', //ASSEMBLYUOMCODE
                //                                 NULL, //COMPONENTUOMCODE COMMENTLINE = NULL
                //                                 NULL, //COMPONENTUOMTYPE
                //                                 '0', //CONSUMPTION
                //                                 '', // COMPOSITIONCOMPONENTCODE
                //                                 '', // CONSFORMIXLABEL
                //                                 '', // CONSPERBATCHLABEL
                //                                 '', // CONSPERLABEL
                //                                 '0', // WATERMANAGEMENT KALAU COMMENT DIA 0, LAINNYA 1
                //                                 '0', // BINDERFILLERCOMPONENT
                //                                 '0', // PRODUCED
                //                                 '', // PRICELISTCODE
                //                                 '001', // COSTINGPLANTCODE
                //                                 '', // INITIALENGINEERINGCHANGE
                //                                 '9999999999', // FINALENGINEERINGCHANGE
                //                                 '1970-01-01', // INITIALDATE
                //                                 '2100-12-31', // FINALDATE
                //                                 '', // UNITARYBATCHSTANDARDSIZE
                //                                 '0', // ALLOWDELETEBINDERFILLER
                //                                 '', // TOTALCOSTTEXT
                //                                 '1', // WSOPERATION
                //                                 '', // IMPOPERATIONUSER
                //                                 '0', // IMPORTSTATUS
                //                                 '', // IMPCREATIONDATETIME
                //                                 '', // IMPCREATIONUSER
                //                                 '', // IMPLASTUPDATEDATETIME
                //                                 '', // IMPLASTUPDATEUSER
                //                                 date('Y-m-d H:i:s'), // NOTNULL : IMPORTDATETIME
                //                                 '3', // NOTNULL : RETRYNR
                //                                 '0', // NOTNULL : NEXTRETRY
                //                                 '0', // NOTNULL : IMPORTID
                //                                 $no_urut // RELATEDDEPENDENTID
                //                                 ); 
            // fputcsv($f, $lineDataSuhu_rc_bleaching_soaping, $delimiter); 
        }
        $no_urut_terakhir = $no_urut+1;
        $q_update_no_urut = mysqli_query($con, "UPDATE importautocounter SET nomor_urut = '$no_urut_terakhir' WHERE id = '1'");

        if($insert_recipeComponentBean_comment && $insert_recipeComponentBean){
            echo "Berhasil upload ke Recipe Component Bean";
        }else{
            echo "Data tidak dapat diupload ke Recipe Component Bean. Silahkan periksa kembali";
        }

        // Move back to beginning of file 
        // fseek($f, 0); 

        // Set headers to download file rather than displayed 
        // header('Content-Type: text/csv'); 
        // header('Content-Disposition: attachment; filename="' . $filename . '";'); 

        //output all remaining data on a file pointer 
        // fpassthru($f);
    }elseif($jenis_suffix == "2"){
        $recipe_cmp = mysqli_query($con, "SELECT a.id AS id_matching_detail,
                                            a.id_matching as id_matching,
                                            a.id_status as is_status,
                                            b.recipe_code as recipe_code,
                                            case
                                                when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
                                                when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
                                            end as no_resep_convert,
                                            kode as kode,
                                            nama as nama,
                                            case
                                                when conc10 != 0 then conc10
                                                when conc9 != 0 then conc9
                                                when conc8 != 0 then conc8
                                                when conc7 != 0 then conc7
                                                when conc6 != 0 then conc6
                                                when conc5 != 0 then conc5
                                                when conc4 != 0 then conc4
                                                when conc3 != 0 then conc3
                                                when conc2 != 0 then conc2
                                                when conc1 != 0 then conc1
                                            end as conc,
                                            remark as remark 
                                        FROM tbl_matching_detail a 
                                        LEFT JOIN tbl_matching b ON b.id = a.id_matching
                                        left join tbl_status_matching tsm on tsm.idm = b.no_resep 
                                        WHERE a.id_matching = '$idmatching' AND a.id_status = '$idstatus' and remark = 'from merge Co-power' order by a.flag ASC");
        $delimiter = ","; 
        $filename = "RC_" . $_GET['rcode'] . ".csv"; 
        
        // Create a file pointer 
        $f = fopen('php://memory', 'w'); 

        // Set column headers 
        $fields = array('FATHERID',
                        'IMPORTAUTOCOUNTER',
                        'OWNEDCOMPONENT',
                        'RECIPEITEMTYPECODE',
                        'RECIPESUBCODE01',
                        'RECIPESUBCODE02',
                        'RECIPESUBCODE03',
                        'RECIPESUBCODE04',
                        'RECIPESUBCODE05',
                        'RECIPESUBCODE06',
                        'RECIPESUBCODE07',
                        'RECIPESUBCODE08',
                        'RECIPESUBCODE09',
                        'RECIPESUBCODE10',
                        'RECIPESUFFIXCODE',
                        'GROUPNUMBER',
                        'GROUPTYPECODE',
                        'LINETYPE',
                        'SEQUENCE',
                        'ALTERNATIVE',
                        'SUBSEQUENCE',
                        'COMPONENTINCIDENCE',
                        'REFRECIPEGROUPNUMBER',
                        'REFRECIPESEQUENCE',
                        'REFRECIPEALTERNATIVE',
                        'REFRECIPESUBSEQUENCE',
                        'REFRECIPESTATUS',
                        'ITEMTYPEAFICODE',
                        'SUBCODE01',
                        'SUBCODE02',
                        'SUBCODE03',
                        'SUBCODE04',
                        'SUBCODE05',
                        'SUBCODE06',
                        'SUBCODE07',
                        'SUBCODE08',
                        'SUBCODE09',
                        'SUBCODE10',
                        'SUFFIXCODE',
                        'COMMENTLINE',
                        'CONSUMPTIONTYPE',
                        'ASSEMBLYUOMCODE',
                        'COMPONENTUOMCODE',
                        'COMPONENTUOMTYPE',
                        'CONSUMPTION',
                        'COMPOSITIONCOMPONENTCODE',
                        'CONSFORMIXLABEL',
                        'CONSPERBATCHLABEL',
                        'CONSPERLABEL',
                        'WATERMANAGEMENT',
                        'BINDERFILLERCOMPONENT',
                        'PRODUCED',
                        'PRICELISTCODE',
                        'COSTINGPLANTCODE',
                        'INITIALENGINEERINGCHANGE',
                        'FINALENGINEERINGCHANGE',
                        'INITIALDATE',
                        'FINALDATE',
                        'UNITARYBATCHSTANDARDSIZE',
                        'ALLOWDELETEBINDERFILLER',
                        'TOTALCOSTTEXT',
                        'WSOPERATION',
                        'IMPOPERATIONUSER',
                        'IMPORTSTATUS',
                        'IMPCREATIONDATETIME',
                        'IMPCREATIONUSER',
                        'IMPLASTUPDATEDATETIME',
                        'IMPLASTUPDATEUSER',
                        'IMPORTDATETIME',
                        'RETRYNR',
                        'NEXTRETRY',
                        'IMPORTID',
                        'RELATEDDEPENDENTID'); 
        fputcsv($f, $fields, $delimiter);

        //autonumber for IMPORTAUTOCOUNTER
        $q_iac = mysqli_query($con, "SELECT nomor_urut FROM importautocounter");
        $d_IMPORTAUTOCOUNTER = mysqli_fetch_assoc($q_iac);
        
        // Output each row of the data, format line as csv and write to file pointer 
        $SEQUENCE = 1;
        while($r_cmp = $recipe_cmp->fetch_assoc()){ 
            $dyestuff = mysqli_query($con, "SELECT * FROM tbl_dyestuff WHERE code = '$r_cmp[kode]'");
            $r_code = $dyestuff->fetch_assoc();

            if($r_code['Product_Unit'] == 1){
                $CONSUMPTIONTYPE = 1;
            }else{
                $CONSUMPTIONTYPE = 2;
            }
            
            // if(substr($r_code['code'], 0,1) == 'E'){
            //     $CONSUMPTIONTYPE = 1;
            // }else{
            //     $CONSUMPTIONTYPE = 2;
            // }
            $no_urut = $d_IMPORTAUTOCOUNTER['nomor_urut']++;
            $lineData = array($IMPORTAUTOCOUNTER, // FATHERID
                            $no_urut, // NOTNULL : IMPORTAUTOCOUNTER
                            '0', // OWNEDCOMPONENT
                            'RFD', // RECIPEITEMTYPECODE
                            substr($r_cmp['recipe_code'],17), // RECIPESUBCODE01
                            '', //RECIPESUBCODE02
                            '', //RECIPESUBCODE03
                            '', //RECIPESUBCODE04
                            '', //RECIPESUBCODE05
                            '', //RECIPESUBCODE06
                            '', //RECIPESUBCODE07
                            '', //RECIPESUBCODE08
                            '', //RECIPESUBCODE09
                            '', //RECIPESUBCODE10
                            $r_cmp['no_resep_convert'], // RECIPESUFFIXCODE
                            '10', //GROUPNUMBER UNTUK CHEMICAL 10
                            '001', //GROUPTYPECODE UNTUK CHEMICAL 001
                            '1', // LINETYPE
                            $SEQUENCE++.'0', // SEQUENCE URUT DARI 10 - 20 ....
                            '', // ALTERNATIVE
                            '10', //SUBSEQUENCE SEMENTARA DI SETTING 10 DULU
                            '100', //COMPONENTINCIDENCE
                            '0', //REFRECIPEGROUPNUMBER
                            '0', //REFRECIPESEQUENCE
                            '', //REFRECIPEALTERNATIVE
                            '0', //REFRECIPESUBSEQUENCE
                            '0', //REFRECIPESTATUS
                            'DYC', //ITEMTYPEAFICODE
                            substr($r_code['code'], 0,1), //SUBCODE01
                            substr($r_code['code'], 2,1), //SUBCODE02
                            substr($r_code['code'], 4), ////SUBCODE03
                            '', //SUBCODE04
                            '', //SUBCODE05
                            '', //SUBCODE06
                            '', //SUBCODE07
                            '', //SUBCODE08
                            '', //SUBCODE09
                            '', //SUBCODE10
                            NULL, //SUFFIXCODE
                            '', // COMMENTLINE
                            $CONSUMPTIONTYPE, //CONSUMPTIONTYPE D = 2, E = 1
                            'l', //ASSEMBLYUOMCODE
                            'g', //COMPONENTUOMCODE
                            '1', //COMPONENTUOMTYPE
                            $r_cmp['conc'], //CONSUMPTION
                            '', // COMPOSITIONCOMPONENTCODE
                            '', // CONSFORMIXLABEL
                            '', // CONSPERBATCHLABEL
                            '', // CONSPERLABEL
                            '1', // WATERMANAGEMENT KALAU COMMENT DIA 0, LAINNYA 1
                            '0', // BINDERFILLERCOMPONENT
                            '0', // PRODUCED
                            '', // PRICELISTCODE
                            '001', // COSTINGPLANTCODE
                            '', // INITIALENGINEERINGCHANGE
                            '9999999999', // FINALENGINEERINGCHANGE
                            '1970-01-01', // INITIALDATE
                            '2100-12-31', // FINALDATE
                            '', // UNITARYBATCHSTANDARDSIZE
                            '0', // ALLOWDELETEBINDERFILLER
                            '', // TOTALCOSTTEXT
                            '1', // WSOPERATION
                            '', // IMPOPERATIONUSER
                            '0', // IMPORTSTATUS
                            '', // IMPCREATIONDATETIME
                            '', // IMPCREATIONUSER
                            '', // IMPLASTUPDATEDATETIME
                            '', // IMPLASTUPDATEUSER
                            date('Y-m-d H:i:s'), // NOTNULL : IMPORTDATETIME
                            '3', // NOTNULL : RETRYNR
                            '0', // NOTNULL : NEXTRETRY
                            '0', // NOTNULL : IMPORTID
                            $no_urut // RELATEDDEPENDENTID
                            ); 
            fputcsv($f, $lineData, $delimiter);
        } 

        $sql_suhu_menit = mysqli_query($con, "SELECT 
                                                    b.recipe_code as recipe_code,
                                                    case
                                                        when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
                                                        when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
                                                    end as no_resep_convert,
                                                    concat(trim(tsm.cside_c),'''C X ', trim(tsm.cside_min), ' MNT') as COMMENTLINE
                                                from 
                                                    tbl_status_matching tsm 
                                                left join tbl_matching b on b.no_resep = tsm.idm
                                                left join tbl_matching_detail a on a.id_matching = b.id
                                                where tsm.idm = '$number_suffix'
                                                group by tsm.idm
                                                union 
                                                SELECT
                                                    b.recipe_code as recipe_code,
                                                    case
                                                        when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
                                                        when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
                                                    end as no_resep_convert,
                                                    concat('SOAPING ',trim(tsm.soaping_sh),'''C X ', trim(tsm.soaping_tm), ' MNT') as COMMENTLINE
                                                from 
                                                    tbl_status_matching tsm 
                                                left join tbl_matching b on b.no_resep = tsm.idm
                                                left join tbl_matching_detail a on a.id_matching = b.id
                                                where tsm.idm = '$number_suffix' and not tsm.soaping_sh = 0 and not tsm.soaping_tm = 0
                                                group by b.no_resep
                                                union 
                                                SELECT
                                                    b.recipe_code as recipe_code,
                                                    case
                                                        when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
                                                        when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
                                                    end as no_resep_convert,
                                                    concat('RC ',trim(tsm.rc_sh),'''C X ', trim(tsm.rc_tm), ' MNT') as COMMENTLINE
                                                from 
                                                    tbl_status_matching tsm 
                                                left join tbl_matching b on b.no_resep = tsm.idm
                                                left join tbl_matching_detail a on a.id_matching = b.id
                                                where tsm.idm = '$number_suffix' and not tsm.rc_sh = 0 and not tsm.rc_tm = 0
                                                group by b.no_resep
                                                union 
                                                SELECT
                                                    b.recipe_code as recipe_code,
                                                    case
                                                        when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
                                                        when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
                                                    end as no_resep_convert,
                                                    concat('BLEACHING ',trim(tsm.bleaching_sh),'''C X ', trim(tsm.bleaching_tm), ' MNT') as COMMENTLINE
                                                from 
                                                    tbl_status_matching tsm 
                                                left join tbl_matching b on b.no_resep = tsm.idm
                                                left join tbl_matching_detail a on a.id_matching = b.id
                                                where tsm.idm = '$number_suffix' and not tsm.bleaching_sh = 0 and not tsm.bleaching_tm = 0
                                                group by b.no_resep");
        
        $GROUPNUMBER = 2;
        while($r_cmp_suhu_bleaching_rc_soaping = $sql_suhu_menit->fetch_assoc()){
            $no_urut = $d_IMPORTAUTOCOUNTER['nomor_urut']++;
            $lineDataSuhu_rc_bleaching_soaping = array($IMPORTAUTOCOUNTER, // FATHERID
                                            $no_urut, // NOTNULL : IMPORTAUTOCOUNTER
                                            '0', // OWNEDCOMPONENT
                                            'RFD', // RECIPEITEMTYPECODE
                                            substr($r_cmp_suhu_bleaching_rc_soaping['recipe_code'],17), // RECIPESUBCODE01
                                            '', //RECIPESUBCODE02
                                            '', //RECIPESUBCODE03
                                            '', //RECIPESUBCODE04
                                            '', //RECIPESUBCODE05
                                            '', //RECIPESUBCODE06
                                            '', //RECIPESUBCODE07
                                            '', //RECIPESUBCODE08
                                            '', //RECIPESUBCODE09
                                            '', //RECIPESUBCODE10
                                            $r_cmp_suhu_bleaching_rc_soaping['no_resep_convert'], // RECIPESUFFIXCODE
                                            $GROUPNUMBER++.'0', //GROUPNUMBER UNTUK CHEMICAL 10, GROUPNUMBER UNTUK SUHU 20
                                            '100', //GROUPTYPECODE UNTUK CHEMICAL 001, UNTUK COMMENTLINE 100
                                            '3', // LINETYPE UNTUK DYC 1, UNTUK COMMENT 3
                                            '10', // SEQUENCE URUT DARI 10 - 20 ....
                                            '', // ALTERNATIVE
                                            '10', //SUBSEQUENCE SEMENTARA DI SETTING 10 DULU
                                            '100', //COMPONENTINCIDENCE
                                            '0', //REFRECIPEGROUPNUMBER
                                            '0', //REFRECIPESEQUENCE
                                            '', //REFRECIPEALTERNATIVE
                                            '0', //REFRECIPESUBSEQUENCE
                                            '0', //REFRECIPESTATUS
                                            'DYC', //ITEMTYPEAFICODE
                                            '', //SUBCODE01
                                            '', //SUBCODE02
                                            '', //SUBCODE03
                                            '', //SUBCODE04
                                            '', //SUBCODE05
                                            '', //SUBCODE06
                                            '', //SUBCODE07
                                            '', //SUBCODE08
                                            '', //SUBCODE09
                                            '', //SUBCODE10
                                            NULL, //SUFFIXCODE
                                            $r_cmp_suhu_bleaching_rc_soaping['COMMENTLINE'], // COMMENTLINE UNTUK SUHU, RC, SOAPING, BLEACHING
                                            NULL, //CONSUMPTIONTYPE D = 2, E = 1, COMMENTLINE = NULL
                                            'l', //ASSEMBLYUOMCODE
                                            NULL, //COMPONENTUOMCODE COMMENTLINE = NULL
                                            NULL, //COMPONENTUOMTYPE
                                            '0', //CONSUMPTION
                                            '', // COMPOSITIONCOMPONENTCODE
                                            '', // CONSFORMIXLABEL
                                            '', // CONSPERBATCHLABEL
                                            '', // CONSPERLABEL
                                            '0', // WATERMANAGEMENT KALAU COMMENT DIA 0, LAINNYA 1
                                            '0', // BINDERFILLERCOMPONENT
                                            '0', // PRODUCED
                                            '', // PRICELISTCODE
                                            '001', // COSTINGPLANTCODE
                                            '', // INITIALENGINEERINGCHANGE
                                            '9999999999', // FINALENGINEERINGCHANGE
                                            '1970-01-01', // INITIALDATE
                                            '2100-12-31', // FINALDATE
                                            '', // UNITARYBATCHSTANDARDSIZE
                                            '0', // ALLOWDELETEBINDERFILLER
                                            '', // TOTALCOSTTEXT
                                            '1', // WSOPERATION
                                            '', // IMPOPERATIONUSER
                                            '0', // IMPORTSTATUS
                                            '', // IMPCREATIONDATETIME
                                            '', // IMPCREATIONUSER
                                            '', // IMPLASTUPDATEDATETIME
                                            '', // IMPLASTUPDATEUSER
                                            date('Y-m-d H:i:s'), // NOTNULL : IMPORTDATETIME
                                            '3', // NOTNULL : RETRYNR
                                            '0', // NOTNULL : NEXTRETRY
                                            '0', // NOTNULL : IMPORTID
                                            $no_urut // RELATEDDEPENDENTID
                                            ); 
            fputcsv($f, $lineDataSuhu_rc_bleaching_soaping, $delimiter); 
        }
        $no_urut_terakhir = $no_urut+1;
        $q_update_no_urut = mysqli_query($con, "UPDATE importautocounter SET nomor_urut = '$no_urut_terakhir' WHERE id = '1'");

        // Move back to beginning of file 
        fseek($f, 0); 

        // Set headers to download file rather than displayed 
        header('Content-Type: text/csv'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 

        //output all remaining data on a file pointer 
        fpassthru($f);
    }
    exit;
?>