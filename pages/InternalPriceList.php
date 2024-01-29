<?php
  ini_set("error_reporting", 1);
  session_start();
  include "koneksi.php";
?>
<?php
  $query_rate = "SELECT * FROM ITTWEEKLYEXCHANGERATE WHERE INITIALDATE BETWEEN '$date_curr' AND NOW() ORDER BY INITIALDATE ASC LIMIT 1";
  $date = date_create($_POST['date_start']);
  $date_curr = date_format($date, "Y-m-d");

  $rate = db2_exec($conn1, $query_rate);
  $cek_d_rate = db2_fetch_assoc($rate);
  if($cek_d_rate['WEEKLYEXCHANGERATE'] == 0){
    $rate = db2_exec($conn1, "SELECT * FROM ITTWEEKLYEXCHANGERATE ORDER BY INITIALDATE DESC LIMIT 1");
    $d_rate = db2_fetch_assoc($rate);
  }else{
    $rate = db2_exec($conn1, $query_rate);
    $d_rate = db2_fetch_assoc($rate);
  }

  $date = date_create($_POST['date_start']);
  $date_curr = date_format($date, "Y-m-d");
  $recipe_code  = $_POST['recipe_code'];
  
  $sql_curr = db2_exec($conn1, $query_rate);
  $d_curr = db2_fetch_assoc($sql_curr);
  if($d_curr['WEEKLYEXCHANGERATE']){                              
      $curr = $d_curr['WEEKLYEXCHANGERATE'];
  }else{
      $sql_curr = db2_exec($conn1, "SELECT * FROM ITTWEEKLYEXCHANGERATE ORDER BY INITIALDATE DESC LIMIT 1");
      $d_curr = db2_fetch_assoc($sql_curr);

      $curr = $d_curr['WEEKLYEXCHANGERATE'];
  }
  $sql_header = db2_exec($conn1, "SELECT 
                                    RECIPE_CODE,
                                    SUFFIX,
                                    SUM(PRICE) AS SUM_PRICE
                                  FROM (SELECT
                                      r.RECIPESUBCODE01 AS RECIPE_CODE,
                                      r.RECIPESUFFIXCODE AS SUFFIX,
                                      SUM(CASE
                                        WHEN i.CURRENCYCODE = 'IDR' THEN (i.PRICE/100)*r.CONSUMPTION / $curr
                                        ELSE (i.PRICE/100)*r.CONSUMPTION
                                      END) AS PRICE
                                  FROM
                                      RECIPECOMPONENT r
                                  INNER JOIN INTERNALPRICELISTLINE i ON i.SUBCODE01 = r.SUBCODE01 AND i.SUBCODE02 = r.SUBCODE02 AND i.SUBCODE03 = r.SUBCODE03
                                  WHERE 
                                    r.RECIPEITEMTYPECODE = 'RFD' AND r.RECIPESUFFIXCODE = '001' AND r.RECIPESUBCODE01 LIKE '%$recipe_code%' AND r.ITEMTYPEAFICODE = 'DYC' AND i.VALIDTODATE IS NULL
                                  GROUP BY
                                      r.RECIPESUBCODE01,
                                      r.RECIPESUFFIXCODE, i.CURRENCYCODE)
                                  GROUP BY 
                                    RECIPE_CODE,
                                    SUFFIX");
  $sql_header_sumprice = db2_exec($conn1, "SELECT
                                              SUM(CASE
                                                WHEN i.CURRENCYCODE = 'IDR' THEN (i.PRICE/100)*r.CONSUMPTION / $curr
                                                ELSE (i.PRICE/100)*r.CONSUMPTION
                                              END) AS PRICE
                                          FROM
                                              RECIPECOMPONENT r
                                          INNER JOIN INTERNALPRICELISTLINE i ON i.SUBCODE01 = r.SUBCODE01 AND i.SUBCODE02 = r.SUBCODE02 AND i.SUBCODE03 = r.SUBCODE03
                                          WHERE 
                                            r.RECIPEITEMTYPECODE = 'RFD' AND r.RECIPESUFFIXCODE = '001' AND r.RECIPESUBCODE01 LIKE '%$recipe_code%' AND r.ITEMTYPEAFICODE = 'DYC' AND i.VALIDTODATE IS NULL");
  $d_header = db2_fetch_assoc($sql_header);
  $d_header_sumprice = db2_fetch_assoc($sql_header_sumprice);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Status Matching</title>
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

  .input-xs {
    height: 22px !important;
    padding: 2px 5px;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 3px;
  }

  .input-group-xs>.form-control,
  .input-group-xs>.input-group-addon,
  .input-group-xs>.input-group-btn>.btn {
    height: 22px;
    padding: 1px 5px;
    font-size: 12px;
    line-height: 1.5;
  }

  div.dataTables_wrapper {
    width: 100%;
    margin: 0 auto;
  }
</style>
<script>
  function jenis_kain(){
    var jenis_kain    = document.getElementById("jenis_kain").value;
    var harga_dasar   = <?php echo number_format($d_header_sumprice['PRICE'], 8) * 1.1 ?>

    if(jenis_kain == 'cotton'){
      var total_price = 0.220 + harga_dasar;
    }else if(jenis_kain == 'cvc_tv_no_rc'){
      var total_price = 0.280 + harga_dasar;
    }else if(jenis_kain == 'cvc_tv_with_rc'){
      var total_price = 0.330 + harga_dasar;
    }else if(jenis_kain == 'poly'){
      var total_price = 0.120 + harga_dasar;
    }else{
      var total_price = 0;
    }
    document.getElementById("price").value = total_price;
  }
</script>
<body>
    <div class="row">
        <div class="box">
            <div class="box-header with-border">
              <div class="container-fluid">
                <form class="form-inline" method="POST" action="">
                  <div class="form-group mb-2">
                    <input type="date" class="form-control input-sm" name="date_start" id="date_start" value="<?php
                                                                                                                if ($_POST['submit']) {
                                                                                                                  $date = date_create($_POST['date_start']);
                                                                                                                  echo $date_curr = date_format($date, "Y-m-d");
                                                                                                                } else {
                                                                                                                  echo date('Y-m-d');
                                                                                                                } ?>">
                  </div>
                  <div class="form-group mb-2">
                    <i class="fa fa-share" aria-hidden="true"></i>
                  </div>
                  <div class="form-group mx-sm-3 mb-2">
                    <input type="text" class="form-control input-sm" name="recipe_code" placeholder="Recipe Code" value="<?php
                                                                                                                          if ($_POST['submit']) {
                                                                                                                            echo $_POST['recipe_code'];
                                                                                                                          } else {
                                                                                                                            echo '';
                                                                                                                          } ?>" required>
                  </div>
                  <button type="submit" name="submit" value="search" class="btn btn-primary btn-sm mb-2"><i class="fa fa-search" aria-hidden="true"></i>
                  </button>
                </form>
              </div>
              <div class="container-fluid">
                  <table class="table table-striped table-bordered" id="tableee" width="100%" style="margin-top: 10px;">
                    <tr>
                      <td class="text-center bg-green" width="10%">RECIPE CODE</td>
                      <td><?php if($recipe_code){ echo $d_header['RECIPE_CODE']; } ?> </td>
                    </tr>
                    <tr>
                      <td class="text-center bg-green" width="10%">SUFFIXCODE</td>
                      <td><?php if($recipe_code){ echo $d_header['SUFFIX']; } ?></td>
                    </tr>
                    <tr>
                      <td class="text-center bg-green" width="10%">RATE</td>
                      <td><?php if($recipe_code){ echo number_format($d_rate['WEEKLYEXCHANGERATE']); } ?></td>
                    </tr>
                    <tr>
                      <td class="text-center bg-green" width="10%">Jenis Kain</td>
                      <td>
                        <select class="form-control" style="width: 170px;" id="jenis_kain" onchange="jenis_kain();">
                          <option value="-" disabled selected>Pilih Jenis Kain</option>
                          <option value="cotton">Cotton</option>
                          <option value="cvc_tv_no_rc">CVC/TC no RC</option>
                          <option value="cvc_tv_with_rc">CVC/TC with RC</option>
                          <option value="poly">Poly</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-center bg-green" width="10%">TOTAL PRICE USD (x1.1)</td>
                      <td>
                        <input type="text" style="border: none; border-width: 0; box-shadow: none;" id="price">
                      </td>
                    </tr>
                  </table>
                  <hr>
                  <h6>*Detail</h6>
                  <table class="table table-striped table-bordered" id="tableee" width="100%" style="margin-top: 10px;">
                      <thead>
                        <tr>
                          <th class="bg-green">Recipe Code</th>
                          <th class="bg-green">Code Dyestuff</th>
                          <th class="bg-green">Product Name</th>
                          <th class="bg-green">Currency</th>
                          <th class="bg-green">Price</th>
                          <th class="bg-green">Consumtion</th>
                          <th class="bg-green">Harga Pemakaian</th>
                          <th class="bg-green">Total Harga</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                        if($_POST['submit']) {
                          $date = date_create($_POST['date_start']);
                          $date_curr = date_format($date, "Y-m-d");
                          $recipe_code  = $_POST['recipe_code'];
                          
                          $sql_curr = db2_exec($conn1, $query_rate);
                          $d_curr = db2_fetch_assoc($sql_curr);
                          if($d_curr['WEEKLYEXCHANGERATE']){                              
                              $curr = $d_curr['WEEKLYEXCHANGERATE'];
                          }else{
                              $sql_curr = db2_exec($conn1, "SELECT * FROM ITTWEEKLYEXCHANGERATE ORDER BY INITIALDATE DESC LIMIT 1");
                              $d_curr = db2_fetch_assoc($sql_curr);

                              $curr = $d_curr['WEEKLYEXCHANGERATE'];
                          }
                        $sql = db2_exec($conn1, "SELECT r.RECIPESUBCODE01,
                                                      TRIM(r.SUBCODE01) || '-' || TRIM(r.SUBCODE02) || '-' || TRIM(r.SUBCODE03) AS CODE_DYESTUFF,
                                                      TRIM(p.LONGDESCRIPTION) AS PRODUCT_NAME,
                                                      i.ITEMTYPEAFICODE AS DYESTUFF,
                                                      i.CURRENCYCODE AS CURRENCY,
                                                      (i.PRICE) AS PRICE,
                                                      r.CONSUMPTION AS CONSUMTION,
                                                      (i.PRICE/100)*r.CONSUMPTION AS HARGA_PEMAKAIAN,
                                                      CASE
                                                        WHEN i.CURRENCYCODE = 'IDR' THEN (i.PRICE/100)*r.CONSUMPTION / $curr
                                                        ELSE (i.PRICE/100)*r.CONSUMPTION
                                                      END AS PRICE_USD
                                                  FROM
                                                      RECIPECOMPONENT r
                                                  LEFT JOIN INTERNALPRICELISTLINE i ON i.SUBCODE01 = r.SUBCODE01 AND i.SUBCODE02 = r.SUBCODE02 AND i.SUBCODE03 = r.SUBCODE03
                                                  LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = r.ITEMTYPEAFICODE
                                                            AND p.SUBCODE01 = r.SUBCODE01
                                                            AND p.SUBCODE02 = r.SUBCODE02
                                                            AND p.SUBCODE03 = r.SUBCODE03
                                                  WHERE 
                                                  r.RECIPEITEMTYPECODE = 'RFD' AND r.RECIPESUFFIXCODE = '001' AND r.RECIPESUBCODE01 LIKE '%$recipe_code%' AND r.ITEMTYPEAFICODE = 'DYC' AND i.VALIDTODATE IS NULL
                                                  ORDER BY r.SEQUENCE"); 
                        ?>
                        <?php while($r = db2_fetch_assoc($sql)) : ?>
                          <?php if(empty($r['DYESTUFF'])) : ?>
                            <tr class="bg-red">
                              <td class="blink_me"><?= $r['RECIPESUBCODE01']; ?></td>
                              <td class="blink_me"><?= $r['CODE_DYESTUFF']; ?></td>
                              <td class="blink_me"><?= $r['PRODUCT_NAME']; ?></td>
                              <td colspan="3"><i>Code Dyestuff</i> tidak tersedia menu Internal Price List.</td>
                          <?php else : ?>
                            <tr>
                              <td><?= $r['RECIPESUBCODE01']; ?></td>
                              <td><?= $r['CODE_DYESTUFF']; ?></td>
                              <td><?= $r['PRODUCT_NAME']; ?></td>
                              <?php if($r['CURRENCY']) : ?>
                                <td><?= $r['CURRENCY']; ?></td>
                              <?php else : ?>
                                <td class="bg-red"><i>Currency</i> tidak tersedia menu Internal Price List.</td>
                              <?php endif; ?>
                              <td><?= $r['PRICE']; ?></td>
                              <td><?= $r['CONSUMTION']; ?></td>
                              <td><?= number_format($r['HARGA_PEMAKAIAN'], 8); ?></td>
                              <td><?= number_format(round($r['PRICE_USD'], 8), 8); ?></td>
                            </tr>
                          <?php endif; ?>
                        <?php endwhile; ?>
                      <?php } ?>
                      </tbody>
                  </table>
              </div>
            </div>
        </div>
    </div>
</body>

<script type="text/javascript">
  var spinner = new jQuerySpinner({
    parentId: 'block-full-page'
  });

  function disableScroll() {
    scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
      window.onscroll = function() {
        window.scrollTo(scrollLeft, scrollTop);
      };
  }

  function enableScroll() {
    window.onscroll = function() {};
  }

  function SpinnerShow() {
    spinner.show();
    disableScroll()
  }

  function SpinnerHide() {
    setTimeout(function() {
      spinner.hide();
      enableScroll();
      window.location.href = 'index1.php?p=Wait-approval';
    }, 1000);
  }
</script>
<script>
  $(document).ready(function() {
    // $('#Table-sm thead tr').clone(true).appendTo('#Table-sm thead');
    // $('#Table-sm thead tr:eq(1) th').each(function(i) {
    //   var title = $(this).text();
    //   if (i == 400) {
    //     $(this).html('');
    //   } else if (i == 3) {
    //     $(this).html('<input type="text" class="form-control input-xs" style="width: 100px;" placeholder="' + title + '" />');
    //   } else if (i == 5) {
    //     $(this).html('<input type="text" class="form-control input-xs" style="width: 200px;" placeholder="' + title + '" />');
    //   } else if (i == 6) {
    //     $(this).html('<input type="text" class="form-control input-xs" style="width: 200px;" placeholder="' + title + '" />');
    //   } else {
    //     $(this).html('<input type="text" class="form-control input-xs" style="width: 100px;" placeholder="Search ' + title + '" />');
    //   }

    //   $('input', this).on('keyup change', function() {
    //     if (table.column(i).search() !== this.value) {
    //       table
    //         .column(i)
    //         .search(this.value)
    //         .draw();
    //     }
    //   });
    // });

    var table = $('#Table-sm').DataTable({
      "scrollX": true,
      "scrollY": true,
      // orderCellsTop: true,
      pageLength: 20,
      "ordering": false,
      dom: 'Bfrtip',
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
      ],
      "columnDefs": [{
          "className": "align-center",
          "targets": []
        },
        {
          "targets": [],
          "orderable": false
        },
      ],
      "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        if (aData[38] == 'batal') {
          $('td', nRow).css('background-color', '#ff9494');
          $('td', nRow).css('color', 'black');
        } else {
          $('td', nRow).css('color', 'black');
        }
      },
    });
  });
</script>

</html>