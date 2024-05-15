<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
//$con=mysqli_connect("localhost","root","");
//$db=mysqli_select_db("db_qc",$con)or die("Gagal Koneksi");
//$idkk=$_GET['idkk'];
//$noitem=$_GET['noitem'];
//$nohanger=$_GET['nohanger'];
//--
$idkk = $_REQUEST['idkk'];
$noitem = $_REQUEST['noitem'];
$nohanger = $_REQUEST['nohanger'];
$act = $_GET['g'];
$data = mysqli_query($con, "SELECT *,
    CONCAT_WS(' ',fc_note,ph_note, abr_note, bas_note, dry_note, fla_note, fwe_note, fwi_note, burs_note,repp_note,wick_note,absor_note,apper_note,fiber_note,pillb_note,pillm_note,pillr_note,thick_note,growth_note,recover_note,stretch_note,sns_note,snab_note,snam_note,snap_note,wash_note,water_note,acid_note,alkaline_note,crock_note,phenolic_note,cm_printing_note,cm_dye_note,light_note,light_pers_note,saliva_note,h_shrinkage_note,fibre_note,pilll_note,soil_note,apperss_note,bleeding_note,chlorin_note,dye_tf_note,humidity_note,odour_note) AS note_g FROM tbl_tq_test WHERE id_nokk='$idkk' ORDER BY id DESC LIMIT 1");
$rcek1 = mysqli_fetch_array($data);
$sqlCekR = mysqli_query($con, "SELECT *,
    CONCAT_WS(' ',rfc_note,rph_note, rabr_note, rbas_note, rdry_note, rfla_note, rfwe_note, rfwi_note, rburs_note,rrepp_note,rwick_note,rabsor_note,rapper_note,rfiber_note,rpillb_note,rpillm_note,rpillr_note,rthick_note,rgrowth_note,rrecover_note,rstretch_note,rsns_note,rsnab_note,rsnam_note,rsnap_note,rwash_note,rwater_note,racid_note,ralkaline_note,rcrock_note,rphenolic_note,rcm_printing_note,rcm_dye_note,rlight_note,rlight_pers_note,rsaliva_note,rh_shrinkage_note,rfibre_note,rpilll_note,rsoil_note,rapperss_note,rbleeding_note,rchlorin_note,rdye_tf_note,rhumidity_note,rodour_note) AS rnote_g FROM tbl_tq_randomtest WHERE no_item='$noitem' OR no_hanger='$nohanger'");
$rcekR = mysqli_fetch_array($sqlCekR);
$sqlCekD = mysqli_query($con, "SELECT *,
    CONCAT_WS(' ',dfc_note,dph_note, dabr_note, dbas_note, ddry_note, dfla_note, dfwe_note, dfwi_note, dburs_note,drepp_note,dwick_note,dabsor_note,dapper_note,dfiber_note,dpillb_note,dpillm_note,dpillr_note,dthick_note,dgrowth_note,drecover_note,dstretch_note,dsns_note,dsnab_note,dsnam_note,dsnap_note,dwash_note,dwater_note,dacid_note,dalkaline_note,dcrock_note,dphenolic_note,dcm_printing_note,dcm_dye_note,dlight_note,dlight_pers_note,dsaliva_note,dh_shrinkage_note,dfibre_note,dpilll_note,dsoil_note,dapperss_note,dbleeding_note,dchlorin_note,ddye_tf_note,dhumidity_note,dodour_note) AS dnote_g FROM tbl_tq_disptest WHERE id_nokk='$idkk' ORDER BY id DESC LIMIT 1");
$rcekD = mysqli_fetch_array($sqlCekD);
// $data1 = mysqli_query($con, "SELECT nodemand,nokk FROM tbl_tq_nokk WHERE id='$idkk'");
// $rd = mysqli_fetch_array($data1);
?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="styles_cetak.css" rel="stylesheet" type="text/css">
  <title>Print Results</title>
  <style>
    td {
      border-top: 0px #000000 solid;
      border-bottom: 0px #000000 solid;
      border-left: 0px #000000 solid;
      border-right: 0px #000000 solid;
    }
  </style>
</head>


<body>
  <table width="100%" border="0" style="width: 5.5in;">
    <tbody>
      <tr>
        <td align="left" valign="top" style="height: 1.6in;">
        COLORFASTNESS
          <hr>
          <table class="table" width="100%">
            <?php if ($rcek1['wash_temp'] != "" or $rcek1['wash_colorchange'] != "" or $rcek1['wash_acetate'] != "" or $rcek1['wash_cotton'] != "" or $rcek1['wash_nylon'] != "" or $rcek1['wash_poly'] != "" or $rcek1['wash_acrylic'] != "" or $rcek1['wash_wool'] != "" or $rcek1['wash_staining'] != "") { ?>
              <tr>
                <th rowspan="5" align="left" style="font-size: 7px;">Washing</th>
                <th align="left" style="font-size: 7px;">Suhu</th>
                <td colspan="4" style="font-size: 7px; <?= $rcek1['stat_wf'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wf'] == "RANDOM") {
                    echo $rcekR['rwash_temp'];
                  } else {
                    echo $rcek1['wash_temp'];
                  } ?>&deg;
                </td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px;"><strong>CC</strong></td>
                <td style="font-size: 7px;"><strong>Ace</strong></td>
                <td style="font-size: 7px;"><strong>Cot</strong></td>
                <td colspan="2" style="font-size: 7px;"><strong>Nyl</strong></td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px; <?= $rcek1['stat_wf'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wf'] == "RANDOM") {
                    echo $rcekR['rwash_colorchange'];
                  } else {
                    echo $rcek1['wash_colorchange'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_wf'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wf'] == "RANDOM") {
                    echo $rcekR['rwash_acetate'];
                  } else {
                    echo $rcek1['wash_acetate'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_wf'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wf'] == "RANDOM") {
                    echo $rcekR['rwash_cotton'];
                  } else {
                    echo $rcek1['wash_cotton'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_wf'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wf'] == "RANDOM") {
                    echo $rcekR['rwash_nylon'];
                  } else {
                    echo $rcek1['wash_nylon'];
                  } ?>
                </td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px;"><strong>Poly</strong></td>
                <td style="font-size: 7px;"><strong>Acr</strong></td>
                <td style="font-size: 7px;"><strong>Wool</strong></td>
                <td colspan="2" style="font-size: 7px;"><strong>Sta</strong></td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px; <?= $rcek1['stat_wf'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wf'] == "RANDOM") {
                    echo $rcekR['rwash_poly'];
                  } else {
                    echo $rcek1['wash_poly'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_wf'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wf'] == "RANDOM") {
                    echo $rcekR['rwash_acrylic'];
                  } else {
                    echo $rcek1['wash_acrylic'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_wf'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wf'] == "RANDOM") {
                    echo $rcekR['rwash_wool'];
                  } else {
                    echo $rcek1['wash_wool'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_wf'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wf'] == "RANDOM") {
                    echo $rcekR['rwash_staining'];
                  } else {
                    echo $rcek1['wash_staining'];
                  } ?>
                </td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
            <?php } ?>
            <?php if ($rcek1['acid_colorchange'] != "" or $rcek1['acid_acetate'] != "" or $rcek1['acid_cotton'] != "" or $rcek1['acid_nylon'] != "" or $rcek1['acid_poly'] != "" or $rcek1['acid_acrylic'] != "" or $rcek1['acid_wool'] != "" or $rcek1['acid_staining'] != "") { ?>
              <tr>
                <th rowspan="4" align="left" style="font-size: 7px;">Perspiration Acid</th>
                <td style="font-size: 7px;"><strong>CC</strong></td>
                <td style="font-size: 7px;"><strong>Ace</strong></td>
                <td style="font-size: 7px;"><strong>Cot</strong></td>
                <td colspan="2" style="font-size: 7px;"><strong>Nyl</strong></td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px; <?= $rcek1['stat_pac'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pac'] == "RANDOM") {
                    echo $rcekR['racid_colorchange'];
                  } else {
                    echo $rcek1['acid_colorchange'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_pac'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pac'] == "RANDOM") {
                    echo $rcekR['racid_acetate'];
                  } else {
                    echo $rcek1['acid_acetate'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_pac'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pac'] == "RANDOM") {
                    echo $rcekR['racid_cotton'];
                  } else {
                    echo $rcek1['acid_cotton'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_pac'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pac'] == "RANDOM") {
                    echo $rcekR['racid_nylon'];
                  } else {
                    echo $rcek1['acid_nylon'];
                  } ?>
                </td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px;"><strong>Poly</strong></td>
                <td style="font-size: 7px;"><strong>Acr</strong></td>
                <td style="font-size: 7px;"><strong>Wool</strong></td>
                <td colspan="2" style="font-size: 7px;"><strong>Sta</strong></td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px; <?= $rcek1['stat_pac'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pac'] == "RANDOM") {
                    echo $rcekR['racid_poly'];
                  } else {
                    echo $rcek1['acid_poly'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_pac'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pac'] == "RANDOM") {
                    echo $rcekR['racid_acrylic'];
                  } else {
                    echo $rcek1['acid_acrylic'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_pac'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pac'] == "RANDOM") {
                    echo $rcekR['racid_wool'];
                  } else {
                    echo $rcek1['acid_wool'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_pac'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pac'] == "RANDOM") {
                    echo $rcekR['racid_staining'];
                  } else {
                    echo $rcek1['acid_staining'];
                  } ?>
                </td>
                <td style="font-size: 7px;">&nbsp;</td>
                <!--<td colspan="2"><?php echo $rcek1['acid_staining']; ?></td>-->
              </tr>
            <?php } ?>
            <?php if ($rcek1['alkaline_colorchange'] != "" or $rcek1['alkaline_acetate'] != "" or $rcek1['alkaline_cotton'] != "" or $rcek1['alkaline_nylon'] != "" or $rcek1['alkaline_poly'] != "" or $rcek1['alkaline_acrylic'] != "" or $rcek1['alkaline_wool'] != "" or $rcek1['alkaline_staining'] != "") { ?>
              <tr>
                <th rowspan="4" align="left" style="font-size: 7px;">Perspiration Alkaline</th>
                <td style="font-size: 7px;"><strong>CC</strong></td>
                <td style="font-size: 7px;"><strong>Ace</strong></td>
                <td style="font-size: 7px;"><strong>Cot</strong></td>
                <td colspan="2" style="font-size: 7px;"><strong>Nyl</strong></td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px; <?= $rcek1['stat_pal'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pal'] == "RANDOM") {
                    echo $rcekR['ralkaline_colorchange'];
                  } else {
                    echo $rcek1['alkaline_colorchange'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_pal'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pal'] == "RANDOM") {
                    echo $rcekR['ralkaline_acetate'];
                  } else {
                    echo $rcek1['alkaline_acetate'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_pal'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pal'] == "RANDOM") {
                    echo $rcekR['ralkaline_cotton'];
                  } else {
                    echo $rcek1['alkaline_cotton'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_pal'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pal'] == "RANDOM") {
                    echo $rcekR['ralkaline_nylon'];
                  } else {
                    echo $rcek1['alkaline_nylon'];
                  } ?>
                </td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px;"><strong>Poly</strong></td>
                <td style="font-size: 7px;"><strong>Acr</strong></td>
                <td style="font-size: 7px;"><strong>Wool</strong></td>
                <td colspan="2" style="font-size: 7px;"><strong>Sta</strong></td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px; <?= $rcek1['stat_pal'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pal'] == "RANDOM") {
                    echo $rcekR['ralkaline_poly'];
                  } else {
                    echo $rcek1['alkaline_poly'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_pal'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pal'] == "RANDOM") {
                    echo $rcekR['ralkaline_acrylic'];
                  } else {
                    echo $rcek1['alkaline_acrylic'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_pal'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pal'] == "RANDOM") {
                    echo $rcekR['ralkaline_wool'];
                  } else {
                    echo $rcek1['alkaline_wool'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_pal'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_pal'] == "RANDOM") {
                    echo $rcekR['ralkaline_staining'];
                  } else {
                    echo $rcek1['alkaline_staining'];
                  } ?>
                </td>
                <td style="font-size: 7px;">&nbsp;</td>
                <!--<td colspan="2"><?php echo $rcek1['alkaline_staining']; ?></td>-->
              </tr>
            <?php } ?>
            <?php if ($rcek1['water_colorchange'] != "" or $rcek1['water_acetate'] != "" or $rcek1['water_cotton'] != "" or $rcek1['water_nylon'] != "" or $rcek1['water_poly'] != "" or $rcek1['water_acrylic'] != "" or $rcek1['water_wool'] != "" or $rcek1['water_staining'] != "") { ?>
              <tr>
                <th rowspan="4" align="left" style="font-size: 7px;">Water</th>
                <td style="font-size: 7px;"><strong>CC</strong></td>
                <td style="font-size: 7px;"><strong>Ace</strong></td>
                <td style="font-size: 7px;"><strong>Cot</strong></td>
                <td colspan="2" style="font-size: 7px;"><strong>Nyl</strong></td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px; <?= $rcek1['stat_wtr'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wtr'] == "RANDOM") {
                    echo $rcekR['rwater_colorchange'];
                  } else {
                    echo $rcek1['water_colorchange'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_wtr'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wtr'] == "RANDOM") {
                    echo $rcekR['rwater_acetate'];
                  } else {
                    echo $rcek1['water_acetate'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_wtr'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wtr'] == "RANDOM") {
                    echo $rcekR['rwater_cotton'];
                  } else {
                    echo $rcek1['water_cotton'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_wtr'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wtr'] == "RANDOM") {
                    echo $rcekR['rwater_nylon'];
                  } else {
                    echo $rcek1['water_nylon'];
                  } ?>
                </td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px;"><strong>Poly</strong></td>
                <td style="font-size: 7px;"><strong>Acr</strong></td>
                <td style="font-size: 7px;"><strong>Wool</strong></td>
                <td colspan="2" style="font-size: 7px;"><strong>Sta</strong></td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size: 7px; <?= $rcek1['stat_wtr'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wtr'] == "RANDOM") {
                    echo $rcekR['rwater_poly'];
                  } else {
                    echo $rcek1['water_poly'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_wtr'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wtr'] == "RANDOM") {
                    echo $rcekR['rwater_acrylic'];
                  } else {
                    echo $rcek1['water_acrylic'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_wtr'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wtr'] == "RANDOM") {
                    echo $rcekR['rwater_wool'];
                  } else {
                    echo $rcek1['water_wool'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_wtr'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_wtr'] == "RANDOM") {
                    echo $rcekR['rwater_staining'];
                  } else {
                    echo $rcek1['water_staining'];
                  } ?>
                </td>
                <td style="font-size: 7px;">&nbsp;</td>
                <!--<td><?php echo $rcek1['water_staining']; ?></td>-->
              </tr>
            <?php } ?>
            <?php if ($rcek1['crock_len1'] != "" or $rcek1['crock_wid1'] != "" or $rcek1['crock_len2'] != "" or $rcek1['crock_wid2'] != "") { ?>
              <tr>
                <th rowspan="3" align="left" style="font-size: 7px;">Crocking</th>
                <th align="left" style="font-size: 7px;">Srt</th>
                <th align="left" style="font-size: 7px;">Dry</th>
                <th align="left" style="font-size: 7px;">Wet</th>
              </tr>
              <tr>
                <th align="left" style="font-size: 7px;">Len</th>
                <td style="font-size: 7px; <?= $rcek1['stat_cr'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_cr'] == "RANDOM") {
                    echo $rcekR['rcrock_len1'];
                  } else {
                    echo $rcek1['crock_len1'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_cr'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_cr'] == "RANDOM") {
                    echo $rcekR['rcrock_len2'];
                  } else {
                    echo $rcek1['crock_len2'];
                  } ?>
                </td>
              </tr>
              <tr>
                <th align="left" style="font-size: 7px;">Wid</th>
                <td style="font-size: 7px; <?= $rcek1['stat_cr'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_cr'] == "RANDOM") {
                    echo $rcekR['rcrock_wid1'];
                  } else {
                    echo $rcek1['crock_wid1'];
                  } ?>
                </td>
                <td colspan="3" style="font-size: 7px; <?= $rcek1['stat_cr'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_cr'] == "RANDOM") {
                    echo $rcekR['rcrock_wid2'];
                  } else {
                    echo $rcek1['crock_wid2'];
                  } ?>
                </td>
              </tr>
            <?php } ?>
            <?php if ($rcek1['phenolic_colorchange'] != "") { ?>
              <tr>
                <th align="left" style="font-size: 7px;">Phenolic Yellowing</th>
                <th align="left" style="font-size: 7px;">CC</th>
                <td colspan="4" style="font-size: 7px; <?= $rcek1['stat_py'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_py'] == "RANDOM") {
                    echo $rcekR['rphenolic_colorchange'];
                  } else {
                    echo $rcek1['phenolic_colorchange'];
                  } ?>
                </td>
              </tr>
            <?php } ?>
            <?php if ($rcek1['light_rating1'] != "" or $rcek1['light_rating2'] != "") { ?>
              <tr>
                <th align="left" style="font-size: 7px;">Light</th>
                <th align="left" style="font-size: 7px;">&nbsp;</th>
                <td style="font-size: 7px; <?= $rcek1['stat_lg'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_lg'] == "RANDOM") {
                    echo $rcekR['rlight_rating1'];
                  } else {
                    echo $rcek1['light_rating1'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_lg'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_lg'] == "RANDOM") {
                    echo $rcekR['rlight_rating2'];
                  } else {
                    echo $rcek1['light_rating2'];
                  } ?>
                </td>
              </tr>
            <?php } ?>
            <?php if ($rcek1['cm_printing_colorchange'] != "" or $rcek1['cm_printing_staining'] != "") { ?>
              <tr>
                <th align="left" style="font-size: 7px;">Color Migration Oven</th>
                <th align="left" style="font-size: 7px;">&nbsp;</th>
                <td colspan="3" style="font-size: 7px; <?= $rcek1['stat_cmo'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_cmo'] == "RANDOM") {
                    echo $rcekR['rcm_printing_colorchange'];
                  } else {
                    echo $rcek1['cm_printing_colorchange'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_cmo'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_cmo'] == "RANDOM") {
                    echo $rcekR['rcm_printing_staining'];
                  } else {
                    echo $rcek1['cm_printing_staining'];
                  } ?>
                </td>
              </tr>
            <?php } ?>
            <?php if ($rcek1['cm_dye_temp'] != "" or $rcek1['cm_dye_colorchange'] != "" or $rcek1['cm_dye_stainingface'] != "" or $rcek1['cm_dye_stainingback'] != "") { ?>
              <tr>
                <th rowspan="3" align="left" style="font-size: 7px;">Color Migration</th>
                <th align="left" style="font-size: 7px;">Suhu</th>
                <td colspan="4" style="font-size: 7px; <?= $rcek1['stat_cm'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_cm'] == "RANDOM") {
                    echo $rcekR['rcm_dye_temp'];
                  } else {
                    echo $rcek1['cm_dye_temp'];
                  } ?>&deg;
                </td>
              </tr>
              <tr>
                <th style="font-size: 7px;">&nbsp;</th>
                <td style="font-size: 7px;"><strong>CC</strong></td>
                <td style="font-size: 7px;"><strong>Sta</strong></td>
              </tr>
              <tr>
                <th align="left" style="font-size: 7px;">&nbsp;</th>
                <td style="font-size: 7px; <?= $rcek1['stat_cm'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_cm'] == "RANDOM") {
                    echo $rcekR['rcm_dye_colorchange'];
                  } else {
                    echo $rcek1['cm_dye_colorchange'];
                  } ?>
                </td>
                <td colspan="4" style="font-size: 7px; <?= $rcek1['stat_cm'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_cm'] == "RANDOM") {
                    echo $rcekR['rcm_dye_stainingface'];
                  } else {
                    echo $rcek1['cm_dye_stainingface'];
                  } ?>
                </td>
                <!--<td><?php echo $rcek1['cm_dye_stainingback']; ?></td>-->
              </tr>
            <?php } ?>
            <?php if ($rcek1['light_pers_colorchange'] != "") { ?>
              <tr>
                <th align="left" style="font-size: 7px;">Light Perspiration</th>
                <th align="left" style="font-size: 7px;">&nbsp;</th>
                <td colspan="4" style="font-size: 7px; <?= $rcek1['stat_lp'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_lp'] == "RANDOM") {
                    echo $rcekR['rlight_pers_colorchange'];
                  } else {
                    echo $rcek1['light_pers_colorchange'];
                  } ?>
                </td>
              </tr>
            <?php } ?>
            <?php if ($rcek1['saliva_staining'] != "") { ?>
              <tr>
                <th align="left" style="font-size: 7px;">Saliva</th>
                <th align="left" style="font-size: 7px;">&nbsp;</th>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_slv'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_slv'] == "RANDOM") {
                    echo $rcekR['rsaliva_staining'];
                  } else {
                    echo $rcek1['saliva_staining'];
                  } ?>
                </td>
              </tr>
            <?php } ?>
            <?php if ($rcek1['bleeding'] != "") { ?>
              <tr>
                <th align="left" style="font-size: 7px;">Bleeding</th>
                <th align="left" style="font-size: 7px;">&nbsp;</th>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_bld'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_bld'] == "RANDOM") {
                    echo $rcekR['rbleeding'];
                  } else {
                    echo $rcek1['bleeding'];
                  } ?>
                </td>
              </tr>
            <?php } ?>
            <?php if ($rcek1['chlorin'] != "" or $rcek1['nchlorin1'] != "" or $rcek1['nchlorin2'] != "") { ?>
              <tr>
                <th align="left" style="font-size: 7px;">Chlorin</th>
                <th align="left" style="font-size: 7px;">&nbsp;</th>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_chl'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_chl'] == "RANDOM") {
                    echo $rcekR['rchlorin'];
                  } else {
                    echo $rcek1['chlorin'];
                  } ?>
                </td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <th align="left" style="font-size: 7px;">Non-Chlorin</th>
                <th align="left" style="font-size: 7px;">&nbsp;</th>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_nchl'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_nchl'] == "RANDOM") {
                    echo $rcekR['rnchlorin1'];
                  } else {
                    echo $rcek1['nchlorin1'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_nchl'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_nchl'] == "RANDOM") {
                    echo $rcekR['rnchlorin2'];
                  } else {
                    echo $rcek1['nchlorin2'];
                  } ?>
                </td>
              </tr>
            <?php } ?>
            <?php if ($rcek1['dye_tf_cstaining'] != "" or $rcek1['dye_tf_acetate'] != "" or $rcek1['dye_tf_cotton'] != "" or $rcek1['dye_tf_nylon'] != "" or $rcek1['dye_tf_poly'] != "" or $rcek1['dye_tf_acrylic'] != "" or $rcek1['dye_tf_wool'] != "" or $rcek1['dye_tf_sstaining'] != "") { ?>
              <tr>
                <th rowspan="4" colspan="2" align="left" style="font-size: 7px;">Dye Transfer</th>
                <td style="font-size: 7px;"><strong>Ace</strong></td>
                <td style="font-size: 7px;" colspan="2"><strong>Cot</strong></td>
                <td style="font-size: 7px;"><strong>Nyl</strong></td>
                <td colspan="2" style="font-size: 7px;"><strong>Poly</strong></td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <th align="left" style="font-size: 7px; <?= $rcek1['stat_dye'] == "FAIL" ? 'color: red;' : '' ?>">&nbsp;</th>
                <td style="font-size: 7px;">
                  <?php if ($rcek1['stat_dye'] == "RANDOM") {
                    echo $rcekR['rdye_tf_acetate'];
                  } else {
                    echo $rcek1['dye_tf_acetate'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_dye'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_dye'] == "RANDOM") {
                    echo $rcekR['rdye_tf_cotton'];
                  } else {
                    echo $rcek1['dye_tf_cotton'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_dye'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_dye'] == "RANDOM") {
                    echo $rcekR['rdye_tf_nylon'];
                  } else {
                    echo $rcek1['dye_tf_nylon'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_dye'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_dye'] == "RANDOM") {
                    echo $rcekR['rdye_tf_poly'];
                  } else {
                    echo $rcek1['dye_tf_poly'];
                  } ?>
                </td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <th align="left" style="font-size: 7px;">&nbsp;</th>
                <td style="font-size: 7px;"><strong>Acr</strong></td>
                <td colspan="2" style="font-size: 7px;"><strong>Wool</strong></td>
                <td style="font-size: 7px;"><strong>C.Sta</strong></td>
                <td colspan="2" style="font-size: 7px;"><strong>S.Sta</strong></td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
              <tr>
                <th align="left" style="font-size: 7px;">&nbsp;</th>
                <td style="font-size: 7px; <?= $rcek1['stat_dye'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_dye'] == "RANDOM") {
                    echo $rcekR['rdye_tf_acrylic'];
                  } else {
                    echo $rcek1['dye_tf_acrylic'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_dye'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_dye'] == "RANDOM") {
                    echo $rcekR['rdye_tf_wool'];
                  } else {
                    echo $rcek1['dye_tf_wool'];
                  } ?>
                </td>
                <td style="font-size: 7px; <?= $rcek1['stat_dye'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_dye'] == "RANDOM") {
                    echo $rcekR['rdye_tf_cstaining'];
                  } else {
                    echo $rcek1['dye_tf_cstaining'];
                  } ?>
                </td>
                <td colspan="2" style="font-size: 7px; <?= $rcek1['stat_dye'] == "FAIL" ? 'color: red;' : '' ?>">
                  <?php if ($rcek1['stat_dye'] == "RANDOM") {
                    echo $rcekR['rdye_tf_sstaining'];
                  } else {
                    echo $rcek1['dye_tf_sstaining'];
                  } ?>
                </td>
                <td style="font-size: 7px;">&nbsp;</td>
              </tr>
            <?php } ?>
          </table>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" bgcolor="#ECE8E8">Note:
          <?php
          if ($rcek1['ss_cmt'] == '1') {
            echo trim($rcek1['note_g']);
          } else {
            // ketika ss_cmt tidak di checklist maka hilanhkan apperss_note
            echo str_replace($rcek1['apperss_note'], "", $rcek1['note_g']);
          }
          ?>
        </td>
      </tr>
      <tr>
        <td align="right" valign="top">
          <div style="">
          <!-- No Prod Order : -->
            <?php //echo $rd['nokk']; ?> 
            <!-- | Demand : -->
            <?php //echo trim($rd['nodemand']); ?>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>