<?php
ini_set("error_reporting", 1);
session_start();
?>

<?php
//set base constant 
if (!isset($_SESSION['userLAB']) || !isset($_SESSION['passLAB'])) {
?>
  <script>
    setTimeout("location.href='login'", 500);
  </script>
<?php
  die('Illegal Acces');
}
//request page
$page  = isset($_GET['p']) ? $_GET['p'] : '';
$act  = isset($_GET['act']) ? $_GET['act'] : '';
$id    = isset($_GET['id']) ? $_GET['id'] : '';
$page  = strtolower($page);

?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<head>
  <!-- Created by Artisteer v4.3.0.60745 -->
  <meta charset="utf-8">
  <title>Home</title>
  <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
  <script src="plugins/highcharts/code/highcharts.js"></script>
  <script src="plugins/highcharts/code/highcharts-3d.js"></script>
  <script src="plugins/highcharts/code/modules/exporting.js"></script>
  <script src="plugins/highcharts/code/modules/export-data.js"></script>
  <style>
    .loader {
      margin: auto;
      padding: 5px 5px 5px 5px;
      border: 16px solid #f3f3f3;
      border-radius: 50%;
      border-top: 16px solid #3498db;
      width: 120px;
      height: 120px;
      -webkit-animation: spin 2s linear infinite;
      /* Safari */
      animation: spin 2s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
      0% {
        -webkit-transform: rotate(0deg);
      }

      100% {
        -webkit-transform: rotate(360deg);
      }
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>
  <style>
    .highcharts-figure,
    .highcharts-data-table table {
      min-width: 310px;
      max-width: 800px;
      margin: 1em auto;
    }

    #container {
      height: 450px;
    }

    .highcharts-data-table table {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #EBEBEB;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      max-width: 450px;
    }

    .highcharts-data-table caption {
      padding: 1em 0;
      font-size: 1.2em;
      color: #555;
    }

    .highcharts-data-table th {
      font-weight: 600;
      padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
      padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
      background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
      background: #f1f7ff;
    }
  </style>
  <style>
    .table-chart {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    .table-chart td,
    .table-chart th {
      border: 1px solid #ababab;
      padding: 8px;
      text-align: center;
    }

    .table-chart tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .table-chart tr:hover {
      background-color: #ddd;
    }

    .table-chart th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #0068c2;
      color: white;
    }

    .ui-datepicker-inline {
      display: "block";
      width: 100%;
      background-color: #00C76B;
    }

    .ui-datepicker-header {
      background-color: #00C76B;

    }
  </style>
  <style>
    .table-matcher {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    .table-matcher td,
    .table-matcher th {
      border: 1px solid #ababab;
      padding: 8px;
      text-align: center;
    }

    .table-matcher tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .table-matcher tr:hover {
      background-color: #ddd;
    }

    .table-matcher th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #4CAF50;
      color: white;
    }
  </style>
  <style>
    .table-colorist {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    .table-colorist td,
    .table-colorist th {
      border: 1px solid #ababab;
      padding: 8px;
      text-align: center;
    }

    .table-colorist tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .table-colorist tr:hover {
      background-color: #ddd;
    }

    .table-colorist th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #785bc9;
      color: white;
    }
  </style>


<body>
  <!-- <blockquote style="margin: 0px">
    <h1>Welcome back <php echo strtoupper($_SESSION['userLAB']); ?> at system laborat ITTI</h1>
  </blockquote> -->
  <div class="row">
    <?php $sql_ann = mysqli_query($con, "SELECT * from announcement where id = 1");
    $ann = mysqli_fetch_array($sql_ann); ?>
    <?php if ($ann['is_active'] == 1) : ?>
      <div class="alert" role="alert" style="background-color: #214d66;">
        <marquee style="color: white; font-style: italic; font-weight: bold;">
          <h4> <?php echo $ann['ann'] ?> </h4>
        </marquee>
      </div>
    <?php endif; ?>
    <div class="col-lg-3 col-xs-6" style="margin-top:10px;">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <?php $sql = mysqli_query($con, "SELECT idm from tbl_status_matching where `status` = 'selesai' and approve = 'TRUE'");
        $Total_resep = mysqli_num_rows($sql);
        ?>
        <div class="inner">
          <h3><?php echo $Total_resep; ?> <i class="fa fa-check"></i></h3>
          <p style="font-weight: bold;">Total Resep Aktif</p>
        </div>
        <div class="icon">
          <i class="fa fa-puzzle-piece"></i>
        </div>
        <a href="?p=DataBase-resep" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6" style="margin-top:10px;">
      <!-- small box -->
      <div class="small-box bg-green">
        <?php $sql_Dyes = mysqli_query($con, "SELECT code from tbl_dyestuff WHERE is_active = 'TRUE'");
        $Total_Dyes = mysqli_num_rows($sql_Dyes);
        ?>
        <div class="inner">
          <h3><?php echo intval($Total_Dyes) - 1; ?></h3>
          <p>Total Dyestuff Aktif</p>
        </div>
        <div class="icon">
          <i class="fa fa-hourglass-half"></i>
        </div>
        <a href="?p=Manage-Dyestuff" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6" style="margin-top:10px;">
      <?php $sql_totalmatcher = mysqli_query($con, "SELECT * FROM tbl_matcher where `status` = 'Aktif'"); ?>
      <?php $totalmatcher = mysqli_num_rows($sql_totalmatcher); ?>
      <?php $sql_totalcolorist = mysqli_query($con, "SELECT * FROM tbl_colorist where is_active = 'TRUE'"); ?>
      <?php $totalcolorist = mysqli_num_rows($sql_totalcolorist); ?>
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3><?php echo $totalmatcher . ' & ' . $totalcolorist ?></h3>
          <p style="font-weight: bold;">Matcher & Colorist</p>
        </div>
        <div class="icon">
          <i class="fa fa-cubes"></i>
        </div>
        <a href="?p=Matcher" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6" style="margin-top:10px;">
      <?php $sql_proses = mysqli_query($con, "SELECT * from master_proses where is_active = 'TRUE'"); ?>
      <?php $proses = mysqli_num_rows($sql_proses); ?>
      <div class="small-box bg-red">
        <div class="inner">
          <h3><?php echo $proses; ?></h3>
          <p>Jenis Varian Proses</p>
        </div>
        <div class="icon">
          <i class="fa fa-check-square-o"></i>
        </div>
        <a href="?p=Manage-Proses" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <!-- ./col -->
    <!-- chart -->
    <div class="row">
      <div class="col-md-8" id="container">
        <div class="col-md-12 box text-center">
          <p class="loader text-center">&nbsp;</p>
        </div>
      </div>
      <div class="col-md-4" id="pie_chart">
        <div class="col-md-12 box text-center">
          <p class="loader text-center">&nbsp;</p>
        </div>
      </div>
    </div>
    <!-- 2 Table Matching X GROUP X JENIS -->
    <div class="row" style="margin-top: 10px;" id="table_matching">
      <div class="col-md-12 box text-center">
        <p class="loader text-center">&nbsp;</p>
      </div>
    </div>
    <!-- End 2 Table Matching GROUP X JENIS-->

    <!-- table 23 -->
    <div class="row" style="margin-top: 10px;" id="before_switch_day">
      <div class="col-md-12 box text-center">
        <p class="loader text-center">&nbsp;</p>
      </div>
    </div>

    <div class="row" style="margin-top: 10px;" id="rekap_bon_order">
      <div class="col-md-12 box text-center">
        <p class="loader text-center">&nbsp;</p>
      </div>
    </div>

    <!-- MATCHER -->
    <!-- <div class="row" style="margin-top: 10px;" id="table_matcher">

    </div> -->
    <!-- END TABLE MATCHER -->


    <!-- COLORIST -->
    <!-- <div class="row" style="margin-top: 10px;" id="table_colorist">

    </div> -->
    <!-- END COLORIST -->
    <!-- /.box-header -->
    <!-- /.row -->
  </div>

</body>
<script>
  $(document).ready(function() {
    async function loadAllAjax() {
      await $.ajax({
        url: "pages/ajax/home_chart_data.php",
        type: "GET",
        data: {
          days: 12
        }, // samakan dengan kebutuhan
        dataType: 'json',
        cache: false,
        success: renderCharts,
        error: function(xhr, status, err) {
          console.error('Gagal load chart data:', status, err, xhr.responseText);
        }
      });

      await $.ajax({
        url: "pages/ajax/tbl_sum_matching.php",
        type: "GET",
        success: function(ajaxData) {
          setTimeout(function() {
            $("#table_matching").html(ajaxData);
          }, 1000);
        }
      });

      await $.ajax({
        url: "pages/ajax/before_switch_day.php",
        type: "GET",
        success: function(ajaxData) {
          $("#before_switch_day").html(ajaxData);
        }
      });

      await $.ajax({
        url: "pages/ajax/rekap_bon_order.php",
        type: "GET",
        success: function(ajaxData) {
          $("#rekap_bon_order").html(ajaxData);
        }
      });

      // TABLE MATCHER
      // await $.ajax({
      //   url: "pages/ajax/tbl_sum_matcher.php",
      //   type: "GET",
      //   success: function(ajaxData) {
      //     $("#table_matcher").html(ajaxData);
      //   }
      // });

      // TABLE COLORIST
      // await $.ajax({
      //   url: "pages/ajax/tbl_sum_colorist.php",
      //   type: "GET",
      //   success: function(ajaxData) {
      //     $("#table_colorist").html(ajaxData);
      //   }
      // });
    }
    
    function renderCharts(d) {
      // Column chart (timeline)
      Highcharts.chart('container', {
        chart: {
          type: 'column'
        },
        title: {
          text: 'Persentase Status Akhir Resep -12 Hari'
        },
        subtitle: {
          text: 'Source: DB 10.0.0.10/laborat'
        },
        xAxis: {
          categories: d.xCategories,
          crosshair: true
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Data Resep'
          }
        },
        tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} Rcode</b></td></tr>',
          footerFormat: '</table>',
          shared: true,
          useHTML: true
        },
        plotOptions: {
          column: {
            pointPadding: 0.2,
            borderWidth: 0
          }
        },
        series: [{
            name: 'Approved',
            data: d.series.selesai
          },
          {
            name: 'Rejected',
            data: d.series.closed,
            color: '#d9534f'
          },
          {
            name: 'Arsip',
            data: d.series.arsip,
            color: 'Orange'
          }
        ]
      });

      // Pie color radialize (tetap sama seperti kode lama)
      Highcharts.setOptions({
        colors: Highcharts.map(Highcharts.getOptions().colors, function(color) {
          return {
            radialGradient: {
              cx: 0.5,
              cy: 0.3,
              r: 0.7
            },
            stops: [
              [0, color],
              [1, Highcharts.color(color).brighten(-0.3).get('rgb')]
            ]
          };
        })
      });

      // Pie chart (pakai data dari AJAX)
      Highcharts.chart('pie_chart', {
        chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
        },
        title: {
          text: 'Persentase Status Resep Laborat'
        },
        tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
          point: {
            valueSuffix: '%'
          }
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: true,
              format: '<b>{point.name}</b>: {point.y:.1f} .',
              connectorColor: 'silver'
            }
          }
        },
        series: [{
          name: 'Resep',
          data: [{
              name: 'Aktif',
              y: d.pie.aktif
            },
            {
              name: 'Rejected',
              y: d.pie.rejected,
              color: 'red'
            },
            {
              name: 'Arsip',
              y: d.pie.arsip
            }
          ]
        }]
      });
    }

    setTimeout(function() {
      loadAllAjax();
    }, 2000); // 2 Seconds after all contents loaded

    setTimeout(function() {
      window.location.reload(1);
    }, 600000);
  })
</script>

</html>