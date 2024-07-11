<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Approval Testing QC Final</title>
</head>
<style>
    td.details-control {
        background: url('bower_components/DataTable/img/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('bower_components/DataTable/img/details_close.png') no-repeat center center;
    }

    th {
        font-size: 10pt;
    }

    td {
        font-size: 10pt;
    }

    #Table-sm td,
    #Table-sm th {
        border: 0.1px solid #ddd;
    }

    #Table-sm th {
        color: black;
        background: #4CAF50;
    }

    #Table-sm tr:hover {
        background-color: rgb(151, 170, 212);
    }
</style>

<body>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Approval Testing</h3>
                </div>
                <div class="box-body">
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                        <table id="Table-sm" class="table table-sm display compact" style="width: 100%;">
                            <thead>
                                <tr class="alert-success" style="border: 1px solid #ddd;">
                                    <th style="border: 1px solid #ddd;">#</th>
                                    <th style="border: 1px solid #ddd;">Suffix</th>
                                    <th style="border: 1px solid #ddd;">No Counter</th>
                                    <th style="border: 1px solid #ddd;">Jenis Testing</th>
                                    <th style="border: 1px solid #ddd;">Treatment</th>
                                    <th style="border: 1px solid #ddd;">Buyer</th>
                                    <th style="border: 1px solid #ddd;">No Warna</th>
                                    <th style="border: 1px solid #ddd;">Nama Warna</th>
                                    <th style="border: 1px solid #ddd;">Item</th>
                                    <th style="border: 1px solid #ddd;">Jenis Kain</th>
                                    <th style="border: 1px solid #ddd;">Personil Testing</th>
                                    <th style="border: 1px solid #ddd;">Permintaan Testing</th>
                                    <th style="border: 1px solid #ddd;">Testing Selesai</th>
                                    <th style="border: 1px solid #ddd;">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $sql = mysqli_query($con, "SELECT * FROM tbl_test_qc WHERE sts_laborat='Waiting Approval Parsial' OR sts_laborat='Waiting Approval Full' ");
                                while ($r = mysqli_fetch_array($sql)) {
                                    $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
                                    $detail2 = explode(",", $r['permintaan_testing']);
                                ?>
                                    <tr>
                                        <td valign="center">
                                            <?php echo $no++; ?>
                                        </td>
                                        <td valign="center" align="center"><?php echo $r['suffix']; ?></td>
                                        <td valign="center" align="center"><?php echo $r['no_counter']; ?>
                                            <hr class="divider">
                                            <div class="btn-group"><a href="pages/cetak/cetak_result_lab.php?idkk=<?php echo $r['id']; ?>&noitem=<?php echo $r['no_item']; ?>&nohanger=" id="<?php echo $r['id']; ?>" class="btn btn-xs btn-danger" target="_blank" title="Result"> <i class="fa fa-print" aria-hidden="true"></i></a><a href="pages/cetak/cetak_label.php?idkk=<?php echo $r['id']; ?>" id='<?php echo $r['id'] ?>' class="btn btn-xs btn-warning" target="_blank" title="Label"> <i class="fa fa-file" aria-hidden="true"></i></a></div>
                                        </td>
                                        <td valign="center"><?php echo $r['jenis_testing']; ?></td>
                                        <td valign="center"><?php echo $r['treatment']; ?></td>
                                        <td valign="center"><?php echo $r['buyer']; ?></td>
                                        <td valign="center" align="left"><?php echo $r['no_warna']; ?></td>
                                        <td valign="center"><?php echo $r['warna']; ?></td>
                                        <td valign="center"><?php echo $r['no_item']; ?></td>
                                        <td valign="center"><?php echo $r['jenis_kain']; ?></td>
                                        <td valign="center"><?php echo $r['nama_personil_test']; ?></td>
                                        <td valign="center" align="left"><?php echo $r['permintaan_testing'] == '' ? 'Full Test' : $r['permintaan_testing']; ?></td>
                                        <?php

                                        if ($r['id'] != null or  $r['id'] != '') {
                                            $id_nokk = mysqli_real_escape_string($con, $r['id']);
                                            $query = "SELECT
                                                *,
                                                CONCAT_WS(', ',
                                                    CASE WHEN COALESCE(wash_temp, '') <> '' OR COALESCE(wash_colorchange, '') <> '' OR COALESCE(wash_acetate, '') <> '' OR COALESCE(wash_cotton, '') <> '' OR COALESCE(wash_nylon, '') <> ''  OR COALESCE(wash_poly, '') <> ''  OR COALESCE(wash_acrylic, '') <> '' OR COALESCE(wash_wool, '') <> '' OR COALESCE(wash_staining, '') <> '' THEN 'Washing' ELSE NULL END,
                                                    CASE WHEN COALESCE(acid_colorchange, '') <> '' OR COALESCE(acid_acetate, '') <> '' OR COALESCE(acid_cotton, '') <> '' OR COALESCE(acid_nylon, '') <> '' OR COALESCE(acid_poly, '') <> '' OR COALESCE(acid_acrylic, '') <> '' OR COALESCE(acid_wool, '') <> '' OR COALESCE(acid_staining, '') <> '' THEN 'Perspiration Acid' ELSE NULL END,
                                                    CASE WHEN COALESCE(alkaline_colorchange, '') <> '' OR COALESCE(alkaline_acetate, '') <> '' OR COALESCE(alkaline_cotton, '') <> '' OR COALESCE(alkaline_nylon, '') <> '' OR COALESCE(alkaline_poly, '') <> '' OR COALESCE(alkaline_acrylic, '') <> '' OR COALESCE(alkaline_wool, '') <> '' OR COALESCE(alkaline_staining, '') <> '' THEN 'Perspiration Alkaline' ELSE NULL END,
                                                    CASE WHEN COALESCE(water_colorchange, '') <> '' OR COALESCE(water_acetate, '') <> '' OR COALESCE(water_cotton, '') <> '' OR COALESCE(water_nylon, '') <> '' OR COALESCE(water_poly, '') <> ''  OR COALESCE(water_acrylic, '') <> ''  OR COALESCE(water_wool, '') <> '' OR COALESCE(water_staining, '') <> '' THEN 'Water' ELSE NULL END,
                                                    CASE WHEN COALESCE(crock_len1, '') <> '' OR COALESCE(crock_wid1, '') <> '' OR COALESCE(crock_len2, '') <> '' OR COALESCE(crock_wid2, '') <> '' THEN 'Crocking' ELSE NULL END,
                                                    CASE WHEN COALESCE(phenolic_colorchange, '') <> '' THEN 'Phenolic Yellowing' ELSE NULL END,
                                                    CASE WHEN COALESCE(light_rating1, '') <> '' OR COALESCE(light_rating2, '') <> '' THEN 'Light' ELSE NULL END,
                                                    CASE WHEN COALESCE(cm_printing_colorchange, '') <> '' OR COALESCE(cm_printing_staining, '') <> '' THEN 'Color Migration Oven' ELSE NULL END,
                                                    CASE WHEN COALESCE(cm_dye_temp, '') <> '' OR COALESCE(cm_dye_colorchange, '') <> '' OR COALESCE(cm_dye_stainingface, '') <> '' OR COALESCE(cm_dye_stainingback, '') <> '' THEN 'Color Migration' ELSE NULL END,
                                                    CASE WHEN COALESCE(light_pers_colorchange, '') <> '' THEN 'Light Perspiration' ELSE NULL END,
                                                    CASE WHEN COALESCE(saliva_staining, '') <> '' THEN 'Saliva' ELSE NULL END,
                                                    CASE WHEN COALESCE(bleeding, '') <> '' THEN 'Bleeding' ELSE NULL END,
                                                    CASE WHEN COALESCE(chlorin, '') <> '' OR COALESCE(nchlorin1, '') <> '' OR COALESCE(nchlorin2, '') <> '' THEN 'Chlorin' ELSE NULL END,
                                                    CASE WHEN COALESCE(dye_tf_cstaining, '') <> '' OR COALESCE(dye_tf_acetate, '') <> '' OR COALESCE(dye_tf_cotton, '') <> '' OR COALESCE(dye_tf_nylon, '') <> '' OR COALESCE(dye_tf_poly, '') <> '' OR COALESCE(dye_tf_acrylic, '') <> '' OR COALESCE(dye_tf_wool, '') <> '' OR COALESCE(dye_tf_sstaining, '') <> '' THEN 'Dye Transfer' ELSE NULL END
                                                ) AS test_done
                                            FROM tbl_tq_test
                                            WHERE id_nokk = '$id_nokk'";

                                            $test = mysqli_query($con, $query);

                                            $row = mysqli_fetch_assoc($test);

                                            $test_done = $row['test_done'];
                                        }
                                        ?>
                                        <td valign="center" align="left"><?php echo ($r['id'] != null or  $r['id'] != '') ? $test_done : '-'; ?></td>
                                        <td valign="center" class="13"><?php if ($r['sts_laborat'] == "Waiting Approval Full") { ?>
                                                <a href="#" class="sts_laborat_edit" id="<?php echo $r['id']; ?>"> <span class="label label-primary">Approved Full</span></a><?php
                                                                                                                                                                            } else if ($r['sts_laborat'] == "Waiting Approval Parsial") { ?>
                                                <a href="#" class="sts_laborat_edit" id="<?php echo $r['id']; ?>"> <span class="label label-warning">Approved Parsial</span></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Popup untuk Edit-->
    <div id="NoteLaboratEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    </div>
    <div id="StsLaboratEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    </div>
</body>

</html>
<script>
    $(document).ready(function() {
        var table = $('#Table-sm').DataTable({
            "ordering": true,
            "pageLength": 15,
            responsive: true,
            language: {
                searchPlaceholder: "Search..."
            },
            select: true,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],


        });
    });
</script>