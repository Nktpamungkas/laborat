<?php
    include "koneksi.php";
    $idstatus               = $_GET['idm']; // ID_STATUS_MATCHING
    $idmatching             = $_GET['id']; // ID_MATCHING
    $IMPORTAUTOCOUNTER      = $_GET['IMPORTAUTOCOUNTER'];
    $jenis_suffix           = $_GET['suffix'];
    $number_suffix          = $_GET['numbersuffix'];
    $userLogin              = $_GET['userLogin'];
    
    // PROSES EXPORT RECIPE
        $recipe = mysqli_query($con, "SELECT b.id AS id_matching, a.id AS id_status, b.recipe_code, a.idm AS SUFFIXCODE, b.warna, 
                                            case 
                                                when a.lr = 0 then substring(a.second_lr, 3) 
                                                else substring(a.lr, 3)
                                            end AS LR,
                                            SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 1), ' ', -1) as recipe_code_1,
                                            SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 2), ' ', -1) as recipe_code_2,
                                            CASE
                                                WHEN b.jenis_matching = 'LD NOW' THEN '001'
                                                WHEN b.jenis_matching = 'L/D' THEN '001'
                                                ELSE
                                                    case
                                                        when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
                                                        when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
                                                    end 
                                            END as no_resep_convert,
                                            b.created_by
                                        FROM tbl_status_matching a
                                        INNER JOIN tbl_matching b ON a.idm = b.no_resep
                                        WHERE a.id = '$idstatus'
                                        ORDER BY a.id desc limit 1");
        $delimiter = ","; 
        $filename = "Recipe_" . $_GET['rcode'] . ".csv";

        include("koneksi.php");
        while($r = mysqli_fetch_assoc($recipe)){ 
            if($jenis_suffix == "1"){
                $RECIPESUBCODE01 = $r['recipe_code_1'];
            }elseif($jenis_suffix == "2"){
                $RECIPESUBCODE01 = $r['recipe_code_2'];
            }
            $tgl = date('Y-m-d H:i:s');
            $insert_recipeBean  = db2_exec($conn1,"INSERT INTO RECIPEBEAN(
                                                                COMPANYCODE,
                                                                IMPORTAUTOCOUNTER,
                                                                NUMBERID,
                                                                RECIPETEMPLATECODE,
                                                                ITEMTYPECODE,
                                                                RECIPETYPE,
                                                                SUBCODE01,
                                                                SUFFIXCODE,
                                                                GENERICRECIPE,
                                                                LONGDESCRIPTION,
                                                                SHORTDESCRIPTION,
                                                                SEARCHDESCRIPTION,
                                                                REFRECIPENUMBERID,
                                                                VALIDFROMDATE,
                                                                VALIDTODATE,
                                                                LIMITINPOBYNUMBEROFUSES,
                                                                MAXNUMBEROFUSES,
                                                                NUMBEROFUSES,
                                                                SOLUTIONPASTEUMCODE,
                                                                SOLUTIONPASTEWEIGHT,
                                                                RECIPEINCIDENCE,
                                                                SOLUTIONPASTEUMWEIGHTUMCODE,
                                                                PRODUCTIONUMCODE,
                                                                PRODUCTIONUMWEIGHT,
                                                                PRODUCTIONUMWEIGHTUMCODE,
                                                                BATCHSTANDARDSIZE,
                                                                AVERAGELENGTH,
                                                                BATCHAVERAGEUMCODE,
                                                                DILUITIONPERCENTAGE,
                                                                PICKUPPERCENTAGE,
                                                                DRYRESIDUALPERCENTAGE,
                                                                DRYRESIDUALQUANTITY,
                                                                GLOBALWASTEPERCENTAGE,
                                                                BATHVOLUME,
                                                                RESIDUALBATHVOLUME,
                                                                VOLUMEUMCODE,
                                                                LIQUORRATIO,
                                                                MIXVOLUME,
                                                                PRODUCTIONRESERVATIONGROUPCODE,
                                                                USESUBRECIPEHEADERVALUES,
                                                                BINDERFLUIDSRATIO,
                                                                BINDERMINPERCENTAGE,
                                                                BINDERITEMTYPECODE,
                                                                BINDERGROUPTYPECODE,
                                                                FILLERGROUPTYPECODE,
                                                                STATUS,
                                                                WSOPERATION,
                                                                IMPORTSTATUS,
                                                                IMPORTDATETIME,
                                                                RETRYNR,
                                                                NEXTRETRY,
                                                                IMPORTID,
                                                                RELATEDDEPENDENTID,
                                                                IMPOPERATIONUSER) 
                                                        VALUES(
                                                                '100',
                                                                '$IMPORTAUTOCOUNTER',
                                                                '$IMPORTAUTOCOUNTER',
                                                                'FD',
                                                                'RFD',
                                                                '2',
                                                                '$RECIPESUBCODE01',
                                                                '$r[no_resep_convert]',
                                                                '0',
                                                                '$r[warna]',
                                                                '$r[warna]',
                                                                '$r[warna]',
                                                                '0',
                                                                '1970-01-01',
                                                                '2100-12-31',
                                                                '0',
                                                                '0',
                                                                '0',
                                                                'l',
                                                                '1',
                                                                '100',
                                                                'kg',
                                                                'kg',
                                                                '1',
                                                                'kg',
                                                                '1000',
                                                                '0',
                                                                'kg',
                                                                '0',
                                                                '0',
                                                                '0',
                                                                '0',
                                                                '0',
                                                                '1000',
                                                                '0',
                                                                'l',
                                                                '$r[LR]',
                                                                '0',
                                                                '001',
                                                                '0',
                                                                '0',
                                                                '0',
                                                                '0',
                                                                '0',
                                                                '0',
                                                                '2',
                                                                '5',
                                                                '6',
                                                                '$tgl',
                                                                '3',
                                                                '0',
                                                                '0',
                                                                '$IMPORTAUTOCOUNTER',
                                                                '$userLogin')");
            
        }
    // PROSES EXPORT RECIPE
 
    // PROSES EXPORT RECIPE COMPONENT
        if($jenis_suffix == "1"){
            $remark = "and (remark = 'from Co-power'";
        }elseif($jenis_suffix == "2"){
            $remark = "and (remark = 'from merge Co-power'";
        }
        if(substr($number_suffix, 0,1) == 'D'){
            $garam = ")";
        }else{
            $garam = "or kode = 'E-1-010')";
        }
        $recipe_cmp = mysqli_query($con, "SELECT a.id AS id_matching_detail,
                                            a.id_matching as id_matching,
                                            a.id_status as is_status,
                                            SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 1), ' ', -1) as recipe_code_1,
                                            SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 2), ' ', -1) as recipe_code_2,
                                            case
                                                when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
                                                when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
                                            end as no_resep_convert,
                                            case
                                                when tds.code_new is null then a.kode 
                                                else tds.code_new
                                            end as kode,
                                            tds.ket,
                                            tds.product_name as nama,
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
                                            remark as remark,
	                                        a.doby1
                                        FROM tbl_matching_detail a 
                                        LEFT JOIN tbl_matching b ON b.id = a.id_matching
                                        left join tbl_status_matching tsm on tsm.idm = b.no_resep 
                                        LEFT JOIN tbl_dyestuff tds ON tds.code = a.kode 
                                        WHERE a.id_matching = '$idmatching' AND a.id_status = '$idstatus' $remark $garam order by a.flag ASC");
        $delimiter = ","; 
        $filename = "RC_" . $_GET['rcode'] . ".csv"; 
        
        //autonumber for IMPORTAUTOCOUNTER
        $q_iac = mysqli_query($con, "SELECT nomor_urut FROM importautocounter");
        $d_IMPORTAUTOCOUNTER = mysqli_fetch_assoc($q_iac);
        
        $GROUP_NUMBER = 1;
        $SEQUENCE = 1;
        while($r_cmp = $recipe_cmp->fetch_assoc()){ 
            $_GROUP_NUMBER  = $GROUP_NUMBER++.'0';
            $_SEQUENCE      = $SEQUENCE++.'0';
            $dyestuff = mysqli_query($con, "SELECT * FROM tbl_dyestuff WHERE code = '$r_cmp[kode]'");
            $r_code = $dyestuff->fetch_assoc();

            if($r_code['Product_Unit'] == 1){
                $CONSUMPTIONTYPE = 2;
            }else{
                $CONSUMPTIONTYPE = 1;
            }

            $no_urut = $d_IMPORTAUTOCOUNTER['nomor_urut']++;
            if($r_cmp['kode'] == 'B-L-C'){
            // TAMBAH UNTUK BLEACHING
                $insert_recipeComponentBean_comment = db2_exec($conn1, "INSERT INTO RECIPECOMPONENTBEAN(FATHERID,
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
                                                                                                        COMMENTLINE,
                                                                                                        ASSEMBLYUOMCODE,
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
                VALUES('$IMPORTAUTOCOUNTER',
                        '$no_urut',
                        '0',
                        'RFD',
                        '$RECIPESUBCODE01',
                        '$r_cmp_suhu_bleaching_rc_soaping[no_resep_convert]',
                        '$_GROUP_NUMBER',
                        '100',
                        '3',
                        '$_SEQUENCE',
                        '10',
                        '100',
                        '0',
                        '0',
                        '0',
                        '0',
                        'DYC',
                        'BLEACHING LAB',
                        'l',
                        '0',
                        '0',
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
                        '$no_urut')");
            // TAMBAH UNTUK BLEACHING
            }elseif($r_cmp['ket'] == 'Suhu'){
            // TAMBAH UNTUK COMMENT DITENGAH-TENGAH RESEP
                $commentname = str_replace("'", "`", $r_cmp['nama']); 
                $insert_recipeComponentBean_comment = db2_exec($conn1, "INSERT INTO RECIPECOMPONENTBEAN(FATHERID,
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
                                                                                                    COMMENTLINE,
                                                                                                    CONSUMPTIONTYPE,
                                                                                                    ASSEMBLYUOMCODE,
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
                VALUES('$IMPORTAUTOCOUNTER',
                        '$no_urut',
                        '0',
                        'RFD',
                        '$RECIPESUBCODE01',
                        '$r_cmp_suhu_bleaching_rc_soaping[no_resep_convert]',
                        '$_GROUP_NUMBER',
                        '100',
                        '3',
                        '$_SEQUENCE',
                        '10',
                        '100',
                        '0',
                        '0',
                        '0',
                        '0',
                        'DYC',
                        '$commentname',
                        '$CONSUMPTIONTYPE',
                        'l',
                        '0',
                        '0',
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
                        '$no_urut')");
            // TAMBAH UNTUK COMMENT
            }else{
                if($jenis_suffix == "1"){
                    $RECIPESUBCODE01 = $r_cmp['recipe_code_1'];
                }elseif($jenis_suffix == "2"){
                    $RECIPESUBCODE01 = $r_cmp['recipe_code_2'];
                }
                $subcode01 = substr($r_code['code'], 0,1); 
                $subcode02 = substr($r_code['code'], 2,1);
                $subcode03 = substr($r_code['code'], 4);
                $insert_recipeComponentBean = db2_exec($conn1, "INSERT INTO RECIPECOMPONENTBEAN(FATHERID,
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
                                                                                    RELATEDDEPENDENTID,
                                                                                    IMPOPERATIONUSER) 
                VALUES(
                        '$IMPORTAUTOCOUNTER',
                        '$no_urut',
                        '0',
                        'RFD',
                        '$RECIPESUBCODE01', 
                        '$r_cmp[no_resep_convert]',
                        '$_GROUP_NUMBER', 
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
                        '$no_urut', 
                        '$userLogin')");
            }
        } 

        if($jenis_suffix == "1"){
            $where_suhu         = "case
                                        when left(tsm.idm, 2) = 'DR' then concat(trim(tsm.tside_c),'`C X ', trim(tsm.tside_min), ' MNT')
                                        when left(tsm.idm, 2) = 'R2' then concat(trim(tsm.cside_c),'`C X ', trim(tsm.cside_min), ' MNT')
                                        when left(tsm.idm, 2) = 'CD' then concat(trim(tsm.tside_c),'`C X ', trim(tsm.tside_min), ' MNT')
                                        when left(tsm.idm, 2) = 'D2' then concat(trim(tsm.tside_c),'`C X ', trim(tsm.tside_min), ' MNT')
                                        when left(tsm.idm, 2) = 'A2' then 
                                        case
                                            when tsm.tside_c = 0 and tsm.tside_min = 0 then concat(trim(tsm.cside_c),'`C X ', trim(tsm.cside_min), ' MNT')
                                            else concat(trim(tsm.tside_c),'`C X ', trim(tsm.tside_min), ' MNT')
                                        end
                                        when left(tsm.idm, 2) = 'OB' then 
                                        case
                                            when tsm.tside_c = 0 and tsm.tside_min = 0 then concat(trim(tsm.cside_c),'`C X ', trim(tsm.cside_min), ' MNT')
                                            else concat(trim(tsm.tside_c),'`C X ', trim(tsm.tside_min), ' MNT')
                                        end
                                    END	as COMMENTLINE";
            $where_soaping      = "and (left(tsm.idm, 2) = 'R2' or left(tsm.idm, 2) = 'A2') and (not left(tsm.idm, 2) = 'D2' or not left(tsm.idm, 2) = 'DR')";
            $where_rc           = "and (left(tsm.idm, 2) = 'CD' or left(tsm.idm, 2) = 'D2' or left(tsm.idm, 2) = 'DR' or left(tsm.idm, 2) = 'A2') and not tsm.rc_tm = 0";
            $where_bleaching    = "and (left(tsm.idm, 2) = 'CD' or left(tsm.idm, 2) = 'D2' or left(tsm.idm, 2) = 'DR') and not tsm.bleaching_tm = 0";
        }elseif($jenis_suffix == "2"){
            $where_suhu         = "concat(trim(tsm.cside_c),'`C X ', trim(tsm.cside_min), ' MNT') as COMMENTLINE";
            $where_soaping      = "and (left(tsm.idm, 2) = 'R2' or left(tsm.idm, 3) = 'DR2' or left(tsm.idm, 2) = 'A2')";
            $where_rc           = "and not (left(tsm.idm, 2) = 'CD' or left(tsm.idm, 2) = 'D2' or left(tsm.idm, 2) = 'DR') and not tsm.rc_tm = 0";
            $where_bleaching    = "and not (left(tsm.idm, 2) = 'CD' or left(tsm.idm, 2) = 'D2' or left(tsm.idm, 2) = 'DR') and not tsm.bleaching_tm = 0";
        }

        // // EXPORT COMMENT
        //     $sql_suhu_menit = mysqli_query($con, "SELECT 
        //                                                 b.recipe_code as recipe_code,
        //                                                 SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 1), ' ', -1) as recipe_code_1,
        //                                                 SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 2), ' ', -1) as recipe_code_2,
        //                                                 case
        //                                                     when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
        //                                                     when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
        //                                                 end as no_resep_convert,
        //                                                 $where_suhu
        //                                             from 
        //                                                 tbl_status_matching tsm 
        //                                             left join tbl_matching b on b.no_resep = tsm.idm
        //                                             left join tbl_matching_detail a on a.id_matching = b.id
        //                                             where tsm.idm = '$number_suffix'
        //                                             group by tsm.idm
        //                                             union 
        //                                             SELECT
        //                                                 b.recipe_code as recipe_code,
        //                                                 SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 1), ' ', -1) as recipe_code_1,
        //                                                 SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 2), ' ', -1) as recipe_code_2,
        //                                                 case
        //                                                     when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
        //                                                     when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
        //                                                 end as no_resep_convert,
        //                                                 CASE
        //                                                     WHEN trim(tsm.soaping_sh) = '80' THEN concat('CUCI PANAS ',trim(tsm.soaping_sh),'`C X ', trim(tsm.soaping_tm), ' MNT')
        //                                                     ELSE concat('SOAPING ',trim(tsm.soaping_sh),'`C X ', trim(tsm.soaping_tm), ' MNT')
        //                                                 END AS COMMENTLINE
        //                                             from 
        //                                                 tbl_status_matching tsm 
        //                                             left join tbl_matching b on b.no_resep = tsm.idm
        //                                             left join tbl_matching_detail a on a.id_matching = b.id
        //                                             where tsm.idm = '$number_suffix' $where_soaping
        //                                             group by b.no_resep
        //                                             union 
        //                                             SELECT
        //                                                 b.recipe_code as recipe_code,
        //                                                 SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 1), ' ', -1) as recipe_code_1,
        //                                                 SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 2), ' ', -1) as recipe_code_2,
        //                                                 case
        //                                                     when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
        //                                                     when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
        //                                                 end as no_resep_convert,
        //                                                 concat('RC ',trim(tsm.rc_sh),'`C X ', trim(tsm.rc_tm), ' MNT') as COMMENTLINE
        //                                             from 
        //                                                 tbl_status_matching tsm 
        //                                             left join tbl_matching b on b.no_resep = tsm.idm
        //                                             left join tbl_matching_detail a on a.id_matching = b.id
        //                                             where tsm.idm = '$number_suffix' $where_rc
        //                                             group by b.no_resep
        //                                             union 
        //                                             SELECT
        //                                                 b.recipe_code as recipe_code,
        //                                                 SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 1), ' ', -1) as recipe_code_1,
        //                                                 SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 2), ' ', -1) as recipe_code_2,
        //                                                 case
        //                                                     when SUBSTRING(b.no_resep, 1,2) = 'DR' or SUBSTRING(b.no_resep, 1,2) = 'CD' or SUBSTRING(b.no_resep, 1,2) = 'OB' then CONCAT(SUBSTRING(b.no_resep, 3), 'L')
        //                                                     when SUBSTRING(b.no_resep, 1,2) = 'D2' or SUBSTRING(b.no_resep, 1,2) = 'R2' or SUBSTRING(b.no_resep, 1,2) = 'A2' then CONCAT(SUBSTRING(b.no_resep, 2), 'L')
        //                                                 end as no_resep_convert,
        //                                                 concat('BLEACHING ',trim(tsm.bleaching_sh),'`C X ', trim(tsm.bleaching_tm), ' MNT') as COMMENTLINE
        //                                             from 
        //                                                 tbl_status_matching tsm 
        //                                             left join tbl_matching b on b.no_resep = tsm.idm
        //                                             left join tbl_matching_detail a on a.id_matching = b.id
        //                                             where tsm.idm = '$number_suffix' $where_bleaching
        //                                             group by b.no_resep");
            
        //     $GROUPNUMBER = 2;
        //     while($r_cmp_suhu_bleaching_rc_soaping = $sql_suhu_menit->fetch_assoc()){
        //         $no_urut = $d_IMPORTAUTOCOUNTER['nomor_urut']++;

        //         if($jenis_suffix == "1"){
        //             $RECIPESUBCODE01 = $r_cmp_suhu_bleaching_rc_soaping['recipe_code_1'];
        //         }elseif($jenis_suffix == "2"){
        //             $RECIPESUBCODE01 = $r_cmp_suhu_bleaching_rc_soaping['recipe_code_2'];
        //         }
        //         $_GROUPNUMBER = $GROUPNUMBER++.'0';

        //         if($r_cmp_suhu_bleaching_rc_soaping['COMMENTLINE'] != '0`C X 0 MNT' OR $r_cmp_suhu_bleaching_rc_soaping['COMMENTLINE'] != 'SOAPING 0`C X 0 MNT' OR $r_cmp_suhu_bleaching_rc_soaping['COMMENTLINE'] != 'BLEACHING 0`C X 0 MNT'){ //kalau suhu dan menitnya kosong maka tidak usah di export
        //             $insert_recipeComponentBean_comment = db2_exec($conn1, "INSERT INTO RECIPECOMPONENTBEAN(FATHERID,
        //                                                                                                     IMPORTAUTOCOUNTER,
        //                                                                                                     OWNEDCOMPONENT,
        //                                                                                                     RECIPEITEMTYPECODE,
        //                                                                                                     RECIPESUBCODE01,
        //                                                                                                     RECIPESUFFIXCODE,
        //                                                                                                     GROUPNUMBER,
        //                                                                                                     GROUPTYPECODE,
        //                                                                                                     LINETYPE,
        //                                                                                                     SEQUENCE,
        //                                                                                                     SUBSEQUENCE,
        //                                                                                                     COMPONENTINCIDENCE,
        //                                                                                                     REFRECIPEGROUPNUMBER,
        //                                                                                                     REFRECIPESEQUENCE,
        //                                                                                                     REFRECIPESUBSEQUENCE,
        //                                                                                                     REFRECIPESTATUS,
        //                                                                                                     ITEMTYPEAFICODE,
        //                                                                                                     COMMENTLINE,
        //                                                                                                     CONSUMPTIONTYPE,
        //                                                                                                     ASSEMBLYUOMCODE,
        //                                                                                                     CONSUMPTION,
        //                                                                                                     WATERMANAGEMENT,
        //                                                                                                     BINDERFILLERCOMPONENT,
        //                                                                                                     PRODUCED,
        //                                                                                                     COSTINGPLANTCODE,
        //                                                                                                     FINALENGINEERINGCHANGE,
        //                                                                                                     INITIALDATE,
        //                                                                                                     FINALDATE,
        //                                                                                                     ALLOWDELETEBINDERFILLER,
        //                                                                                                     WSOPERATION,
        //                                                                                                     IMPORTSTATUS,
        //                                                                                                     IMPORTDATETIME,
        //                                                                                                     RETRYNR,
        //                                                                                                     NEXTRETRY,
        //                                                                                                     IMPORTID,
        //                                                                                                     RELATEDDEPENDENTID) 
        //             VALUES('$IMPORTAUTOCOUNTER',
        //                     '$no_urut',
        //                     '0',
        //                     'RFD',
        //                     '$RECIPESUBCODE01',
        //                     '$r_cmp_suhu_bleaching_rc_soaping[no_resep_convert]',
        //                     '$_GROUPNUMBER',
        //                     '100',
        //                     '3',
        //                     '10',
        //                     '10',
        //                     '100',
        //                     '0',
        //                     '0',
        //                     '0',
        //                     '0',
        //                     'DYC',
        //                     '$r_cmp_suhu_bleaching_rc_soaping[COMMENTLINE]',
        //                     '$CONSUMPTIONTYPE',
        //                     'l',
        //                     '0',
        //                     '0',
        //                     '0',
        //                     '0',
        //                     '001',
        //                     '9999999999',
        //                     '1970-01-01',
        //                     '2100-12-31',
        //                     '0',
        //                     '1',
        //                     '0',
        //                     '$tgl',
        //                     '3',
        //                     '0',
        //                     '0',
        //                     '$no_urut')");
        //         }
        //     }

        //     $no_urut_terakhir = $no_urut+1;
        //     $q_update_no_urut = mysqli_query($con, "UPDATE importautocounter SET nomor_urut = '$no_urut_terakhir' WHERE id = '1'");
        // // EXPORT COMMENT
    // PROSES EXPORT RECIPE COMPONENT

    // PROSES EXPORT RECIPE ADDITIONAL DATA
        $recipe_add = mysqli_query($con, "SELECT
                                        a.id AS id_matching_detail,
                                        c.approve_at,
                                        b.no_warna,
                                        left(b.no_item, 3) AS no_item2,
                                        b.benang,
                                        b.no_resep,
                                        a.*, b.*, c.*
                                    FROM
                                        tbl_matching_detail a
                                        RIGHT JOIN tbl_matching b ON b.id = a.id_matching
                                        LEFT JOIN tbl_status_matching c ON c.idm = b.no_resep 
                                    WHERE
                                        id_matching = '$idmatching' 
                                        AND id_status = '$idstatus' 
                                    LIMIT 1");
        $d_add = mysqli_fetch_assoc($recipe_add);
        $date_approve = date_create($d_add['approve_at']);
        $tgl_approve = date_format($date_approve, 'Y-m-d');

            $insert_adstoragebean1 = db2_exec($conn1, "INSERT INTO ADSTORAGEBEAN(FATHERID,
                                                                                IMPORTAUTOCOUNTER,
                                                                                NAMEENTITYNAME,
                                                                                NAMENAME,
                                                                                FIELDNAME,
                                                                                VALUESTRING,
                                                                                VALUEINT,
                                                                                VALUEBOOLEAN,
                                                                                VALUEDATE,
                                                                                VALUEDECIMAL,
                                                                                VALUELONG,
                                                                                VALUETIME,
                                                                                VALUETIMESTAMP,
                                                                                WSOPERATION,
                                                                                IMPOPERATIONUSER,
                                                                                IMPORTSTATUS,
                                                                                IMPCREATIONDATETIME,
                                                                                IMPCREATIONUSER,
                                                                                IMPLASTUPDATEDATETIME,
                                                                                IMPLASTUPDATEUSER,
                                                                                IMPORTDATETIME,
                                                                                RETRYNR,
                                                                                NEXTRETRY,
                                                                                IMPORTID)
                                                            VALUES($IMPORTAUTOCOUNTER, 
                                                                    $d_add[id_matching_detail],
                                                                    'Recipe',
                                                                    'ApprovalDate',
                                                                    'ApprovalDate',
                                                                    NULL,
                                                                    0,
                                                                    0,
                                                                    '$tgl_approve',
                                                                    NULL,
                                                                    0,
                                                                    NULL,
                                                                    NULL,
                                                                    5,
                                                                    NULL,
                                                                    0,
                                                                    NULL,
                                                                    NULL,
                                                                    NULL,
                                                                    NULL,
                                                                    NULL,
                                                                    3,
                                                                    0,
                                                                    0)");
            $insert_adstoragebean2 = db2_exec($conn1, "INSERT INTO ADSTORAGEBEAN(FATHERID,
                                                                                IMPORTAUTOCOUNTER,
                                                                                NAMEENTITYNAME,
                                                                                NAMENAME,
                                                                                FIELDNAME,
                                                                                VALUESTRING,
                                                                                VALUEINT,
                                                                                VALUEBOOLEAN,
                                                                                VALUEDATE,
                                                                                VALUEDECIMAL,
                                                                                VALUELONG,
                                                                                VALUETIME,
                                                                                VALUETIMESTAMP,
                                                                                WSOPERATION,
                                                                                IMPOPERATIONUSER,
                                                                                IMPORTSTATUS,
                                                                                IMPCREATIONDATETIME,
                                                                                IMPCREATIONUSER,
                                                                                IMPLASTUPDATEDATETIME,
                                                                                IMPLASTUPDATEUSER,
                                                                                IMPORTDATETIME,
                                                                                RETRYNR,
                                                                                NEXTRETRY,
                                                                                IMPORTID)
                                                            VALUES($IMPORTAUTOCOUNTER, 
                                                                    $d_add[id_matching_detail]+1,
                                                                    'Recipe',
                                                                    'ArticleGroup',
                                                                    'ArticleGroupCode',
                                                                    '$d_add[no_item2]',
                                                                    0,
                                                                    0,
                                                                    NULL,
                                                                    NULL,
                                                                    0,
                                                                    NULL,
                                                                    NULL,
                                                                    5,
                                                                    NULL,
                                                                    0,
                                                                    NULL,
                                                                    NULL,
                                                                    NULL,
                                                                    NULL,
                                                                    NULL,
                                                                    3,
                                                                    0,
                                                                    0)");
        
            $insert_adstoragebean3 = db2_exec($conn1, "INSERT INTO ADSTORAGEBEAN(FATHERID,
                                                                                IMPORTAUTOCOUNTER,
                                                                                NAMEENTITYNAME,
                                                                                NAMENAME,
                                                                                FIELDNAME,
                                                                                VALUESTRING,
                                                                                VALUEINT,
                                                                                VALUEBOOLEAN,
                                                                                VALUEDATE,
                                                                                VALUEDECIMAL,
                                                                                VALUELONG,
                                                                                VALUETIME,
                                                                                VALUETIMESTAMP,
                                                                                WSOPERATION,
                                                                                IMPOPERATIONUSER,
                                                                                IMPORTSTATUS,
                                                                                IMPCREATIONDATETIME,
                                                                                IMPCREATIONUSER,
                                                                                IMPLASTUPDATEDATETIME,
                                                                                IMPLASTUPDATEUSER,
                                                                                IMPORTDATETIME,
                                                                                RETRYNR,
                                                                                NEXTRETRY,
                                                                                IMPORTID)
                                                            VALUES($IMPORTAUTOCOUNTER,
                                                                    $d_add[id_matching_detail]+2,
                                                                    'Recipe',
                                                                    'CarryOver',
                                                                    'CarryOver',
                                                                    NULL,
                                                                    0,
                                                                    0,
                                                                    NULL,
                                                                    NULL,
                                                                    0,
                                                                    NULL,
                                                                    NULL,
                                                                    5,
                                                                    NULL,
                                                                    0,
                                                                    NULL,
                                                                    NULL,
                                                                    NULL,
                                                                    NULL,
                                                                    NULL, 
                                                                    3,
                                                                    0,
                                                                    0)");
    
            $insert_adstoragebean4 = db2_exec($conn1, "INSERT INTO ADSTORAGEBEAN(FATHERID,
                                                                                IMPORTAUTOCOUNTER,
                                                                                NAMEENTITYNAME,
                                                                                NAMENAME,
                                                                                FIELDNAME,
                                                                                VALUESTRING,
                                                                                VALUEINT,
                                                                                VALUEBOOLEAN,
                                                                                VALUEDATE,
                                                                                VALUEDECIMAL,
                                                                                VALUELONG,
                                                                                VALUETIME,
                                                                                VALUETIMESTAMP,
                                                                                WSOPERATION,
                                                                                IMPOPERATIONUSER,
                                                                                IMPORTSTATUS,
                                                                                IMPCREATIONDATETIME,
                                                                                IMPCREATIONUSER,
                                                                                IMPLASTUPDATEDATETIME,
                                                                                IMPLASTUPDATEUSER,
                                                                                IMPORTDATETIME,
                                                                                RETRYNR,
                                                                                NEXTRETRY,
                                                                                IMPORTID)
                                                                    VALUES($IMPORTAUTOCOUNTER, 
                                                                            $d_add[id_matching_detail]+3,
                                                                            'Recipe',
                                                                            'Chroma',
                                                                            'Chroma',
                                                                            NULL,
                                                                            0,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            5,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            3,
                                                                            0,
                                                                            0)");
    
            $insert_adstoragebean5 = db2_exec($conn1, "INSERT INTO ADSTORAGEBEAN(FATHERID,
                                                                                IMPORTAUTOCOUNTER,
                                                                                NAMEENTITYNAME,
                                                                                NAMENAME,
                                                                                FIELDNAME,
                                                                                VALUESTRING,
                                                                                VALUEINT,
                                                                                VALUEBOOLEAN,
                                                                                VALUEDATE,
                                                                                VALUEDECIMAL,
                                                                                VALUELONG,
                                                                                VALUETIME,
                                                                                VALUETIMESTAMP,
                                                                                WSOPERATION,
                                                                                IMPOPERATIONUSER,
                                                                                IMPORTSTATUS,
                                                                                IMPCREATIONDATETIME,
                                                                                IMPCREATIONUSER,
                                                                                IMPLASTUPDATEDATETIME,
                                                                                IMPLASTUPDATEUSER,
                                                                                IMPORTDATETIME,
                                                                                RETRYNR,
                                                                                NEXTRETRY,
                                                                                IMPORTID)
                                                                    VALUES($IMPORTAUTOCOUNTER,
                                                                            $d_add[id_matching_detail]+4,
                                                                            'Recipe',
                                                                            'LDGrouping',
                                                                            'LDGrouping',
                                                                            '$d_add[no_warna]',
                                                                            0,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            5,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            3,
                                                                            0,
                                                                            0)");
    
            $insert_adstoragebean6 = db2_exec($conn1, "INSERT INTO ADSTORAGEBEAN(FATHERID,
                                                                                IMPORTAUTOCOUNTER,
                                                                                NAMEENTITYNAME,
                                                                                NAMENAME,
                                                                                FIELDNAME,
                                                                                VALUESTRING,
                                                                                VALUEINT,
                                                                                VALUEBOOLEAN,
                                                                                VALUEDATE,
                                                                                VALUEDECIMAL,
                                                                                VALUELONG,
                                                                                VALUETIME,
                                                                                VALUETIMESTAMP,
                                                                                WSOPERATION,
                                                                                IMPOPERATIONUSER,
                                                                                IMPORTSTATUS,
                                                                                IMPCREATIONDATETIME,
                                                                                IMPCREATIONUSER,
                                                                                IMPLASTUPDATEDATETIME,
                                                                                IMPLASTUPDATEUSER,
                                                                                IMPORTDATETIME,
                                                                                RETRYNR,
                                                                                NEXTRETRY,
                                                                                IMPORTID)
                                                                    VALUES($IMPORTAUTOCOUNTER,
                                                                            $d_add[id_matching_detail]+5,
                                                                            'Recipe',
                                                                            'OptionApproved',
                                                                            'OptionApproved',
                                                                            NULL,
                                                                            0,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            5,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            3,
                                                                            0,
                                                                            0)");
    
            $insert_adstoragebean7 = db2_exec($conn1, "INSERT INTO ADSTORAGEBEAN(FATHERID,
                                                                                IMPORTAUTOCOUNTER,
                                                                                NAMEENTITYNAME,
                                                                                NAMENAME,
                                                                                FIELDNAME,
                                                                                VALUESTRING,
                                                                                VALUEINT,
                                                                                VALUEBOOLEAN,
                                                                                VALUEDATE,
                                                                                VALUEDECIMAL,
                                                                                VALUELONG,
                                                                                VALUETIME,
                                                                                VALUETIMESTAMP,
                                                                                WSOPERATION,
                                                                                IMPOPERATIONUSER,
                                                                                IMPORTSTATUS,
                                                                                IMPCREATIONDATETIME,
                                                                                IMPCREATIONUSER,
                                                                                IMPLASTUPDATEDATETIME,
                                                                                IMPLASTUPDATEUSER,
                                                                                IMPORTDATETIME,
                                                                                RETRYNR,
                                                                                NEXTRETRY,
                                                                                IMPORTID)
                                                                    VALUES($IMPORTAUTOCOUNTER,
                                                                            $d_add[id_matching_detail]+6,
                                                                            'Recipe',
                                                                            'OriginalCustomer',
                                                                            'OriginalCustomerType',
                                                                            '',
                                                                            0,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            5,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            3,
                                                                            0,
                                                                            0)");
    
            $insert_adstoragebean8 = db2_exec($conn1, "INSERT INTO ADSTORAGEBEAN(FATHERID,
                                                                                IMPORTAUTOCOUNTER,
                                                                                NAMEENTITYNAME,
                                                                                NAMENAME,
                                                                                FIELDNAME,
                                                                                VALUESTRING,
                                                                                VALUEINT,
                                                                                VALUEBOOLEAN,
                                                                                VALUEDATE,
                                                                                VALUEDECIMAL,
                                                                                VALUELONG,
                                                                                VALUETIME,
                                                                                VALUETIMESTAMP,
                                                                                WSOPERATION,
                                                                                IMPOPERATIONUSER,
                                                                                IMPORTSTATUS,
                                                                                IMPCREATIONDATETIME,
                                                                                IMPCREATIONUSER,
                                                                                IMPLASTUPDATEDATETIME,
                                                                                IMPLASTUPDATEUSER,
                                                                                IMPORTDATETIME,
                                                                                RETRYNR,
                                                                                NEXTRETRY,
                                                                                IMPORTID)
                                                                    VALUES($IMPORTAUTOCOUNTER,
                                                                            $d_add[id_matching_detail]+7,
                                                                            'Recipe',
                                                                            'OriginalCustomer',
                                                                            'OriginalCustomerCode',
                                                                            '',
                                                                            0,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            5,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            3,
                                                                            0,
                                                                            0)");

            $benang = addslashes($d_add['benang']);
            $benang2 = db2_escape_string($benang);
            $insert_adstoragebean9 = db2_exec($conn1, "INSERT INTO ADSTORAGEBEAN(FATHERID,
                                                                                IMPORTAUTOCOUNTER,
                                                                                NAMEENTITYNAME,
                                                                                NAMENAME,
                                                                                FIELDNAME,
                                                                                VALUESTRING,
                                                                                VALUEINT,
                                                                                VALUEBOOLEAN,
                                                                                VALUEDATE,
                                                                                VALUEDECIMAL,
                                                                                VALUELONG,
                                                                                VALUETIME,
                                                                                VALUETIMESTAMP,
                                                                                WSOPERATION,
                                                                                IMPOPERATIONUSER,
                                                                                IMPORTSTATUS,
                                                                                IMPCREATIONDATETIME,
                                                                                IMPCREATIONUSER,
                                                                                IMPLASTUPDATEDATETIME,
                                                                                IMPLASTUPDATEUSER,
                                                                                IMPORTDATETIME,
                                                                                RETRYNR,
                                                                                NEXTRETRY,
                                                                                IMPORTID)
                                                                    VALUES($IMPORTAUTOCOUNTER,
                                                                            $d_add[id_matching_detail]+8,
                                                                            'Recipe',
                                                                            'YarnDescription',
                                                                            'YarnDescription',
                                                                            '$benang2',
                                                                            0,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            5,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            3,
                                                                            0,
                                                                            0)");
            $insert_adstoragebean10 = db2_exec($conn1, "INSERT INTO ADSTORAGEBEAN(FATHERID,
                                                                                IMPORTAUTOCOUNTER,
                                                                                NAMEENTITYNAME,
                                                                                NAMENAME,
                                                                                FIELDNAME,
                                                                                VALUESTRING,
                                                                                VALUEINT,
                                                                                VALUEBOOLEAN,
                                                                                VALUEDATE,
                                                                                VALUEDECIMAL,
                                                                                VALUELONG,
                                                                                VALUETIME,
                                                                                VALUETIMESTAMP,
                                                                                WSOPERATION,
                                                                                IMPOPERATIONUSER,
                                                                                IMPORTSTATUS,
                                                                                IMPCREATIONDATETIME,
                                                                                IMPCREATIONUSER,
                                                                                IMPLASTUPDATEDATETIME,
                                                                                IMPLASTUPDATEUSER,
                                                                                IMPORTDATETIME,
                                                                                RETRYNR,
                                                                                NEXTRETRY,
                                                                                IMPORTID)
                                                                    VALUES($IMPORTAUTOCOUNTER,
                                                                            $d_add[id_matching_detail]+9,
                                                                            'Recipe',
                                                                            'RCode',
                                                                            'RCode',
                                                                            '$d_add[no_resep]',
                                                                            0,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            5,
                                                                            NULL,
                                                                            0,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            NULL,
                                                                            3,
                                                                            0,
                                                                            0)");
    // PROSES EXPORT RECIPE ADDITIONAL DATA

    // STATUS EXPORT SAMPAI TAHAP APA
    if($insert_recipeBean AND $insert_recipeComponentBean && $insert_adstoragebean1 && $insert_adstoragebean2 && $insert_adstoragebean3 && $insert_adstoragebean4 && $insert_adstoragebean5 && $insert_adstoragebean6 && $insert_adstoragebean7 && $insert_adstoragebean8 && $insert_adstoragebean9){
        header("location: index1.php?p=Detail-status-approved&idm=$idstatus&upload=1&available=$warning"); // RECIPE & RECIPE COMPONENT & ADSTORAGE
    }elseif($insert_recipeBean){
        header("location: index1.php?p=Detail-status-approved&idm=$idstatus&upload=2&available=$warning"); // RECIPE
    }elseif($insert_recipeComponentBean){
        header("location: index1.php?p=Detail-status-approved&idm=$idstatus&upload=3&available=$warning"); // RECIPE COMPONENT
    }else{
        header("location: index1.php?p=Detail-status-approved&idm=$idstatus&upload=0&available=$warning");
    }
?>