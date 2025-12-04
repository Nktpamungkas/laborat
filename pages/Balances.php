<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Balances</title>
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

  #table-balance td,
  #table-balance th {
    border: 0.1px solid #ddd;
    vertical-align: middle;
    text-align: center;
  }

  #table-balance th {
    color: black;
    background: #4CAF50;
  }

  #table-balance tr:hover {
    background-color: rgb(151, 170, 212);
  }

  #table-balance>thead>tr>td {
    border: 1px solid #ddd;
  }

  .btn-circle {
    border-radius: 10px;
    color: black;
    font-weight: 800;
  }

  .btn-grp>a,
  .btn-grp>button {
    margin-top: 2px;
  }

  #table-balance tbody tr.qty-zero {
    background-color: #ffcccc !important;
  }

  #table-balance tbody tr.qty-zero:hover {
    background-color: #ff9999 !important;
  }
</style>

<body>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"> Balance Elements</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
          <div class="box-body">
            <div class="form-group" style="            
                align-items: center;
                display: flex;
                width: 100%;">
              <!-- 🔽 Filter Status -->
              <div class="col-sm-4" style="display: flex; align-items: center; gap: 14px">
                <select id="status_filter" class="form-control input-sm">
                  <option value="">-- All Status --</option>
                  <option value="available">Available</option>
                  <option value="matching">On Matching</option>
                  <option value="expired">Expired</option>
                </select>
                <div style="min-width:220px;">
                  <select id="qty_filter_select" class="form-control input-sm" style="width:100%;">
                    <option value="nonzero">Only qty &gt; 0 (default)</option>
                    <option value="include_zero">Include zero qty</option>
                    <option value="only_zero">Only zero qty</option>
                  </select>
                </div>
              </div>

              <!-- ADD ELEMENT BUTTON (ujung kanan) -->
              <div class="col-sm-2 pull-right text-right" style="margin-left: auto;">
                <button type="button" id="btn-add-element" class="btn btn-success btn-sm">
                  <i class="fa fa-plus" style="margin-right: 4px;"></i> Create New Element
                </button>
              </div>
            </div>
          </div>
          <div class="box-footer">

          </div>
          <!-- /.box-footer -->
        </form>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
            <table id="table-balance" class="table table-sm display compact" style="width: 100%;">
              <thead>
                <tr>
                  <th>Item Code</th>
                  <!-- <th>Item Description</th> -->
                  <th>Lot</th>
                  <th>Zone</th>
                  <th>Location</th>
                  <th>Elements</th>
                  <th>Qty (Kg)</th>
                  <th>Qty (Gr)</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="ModalReturnElement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <form id="ModalReturnElementForm" action="#" method="POST">
          <div class="modal-header">
            <h5 class="modal-title">Return Element</h5>
          </div>

          <div class="modal-body">

            <!-- Elements Code -->
            <div class="mb-3">
              <label class="form-label fw-semibold" style="display: inline-block; width: 150px;">Elements Code :</label>
              <div class="form-control-plaintext" id="ModalReturnElementCode" style="display: inline;"></div>
            </div>


            <!-- Curr Qty (kg) -->
            <div class="mb-3">
              <label class="form-label fw-semibold" style="display: inline-block; width: 150px;">Qty Awal :</label>
              <div class="form-control-plaintext" id="ModalReturnElementInitialQty" style="display: inline;"></div>
            </div>

            <!-- Element Code (center focus) -->
            <div style="display: flex;">
              <label class="form-label fw-semibold" style="display: inline-block; width: 150px;">Qty Return</label>
              <div style="display: inline; width: calc(100% - 160px);">
                <div class="input-group">
                  <input
                    type="number"
                    class="form-control style-ph"
                    name="ModalReturnElementQty"
                    id="ModalReturnElementQty"
                    placeholder="Qty return"
                    autocomplete="off"
                    step="any">
                  <span class="input-group-addon">gr</span>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <div class="modal fade" id="ModalDetailWaste" tabindex="-1" role="dialog" aria-labelledby="ModalDetailWasteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalDetailWasteLabel">Element Transactions</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table id="table-waste" class="table table-bordered table-sm display compact" style="width:100%">
              <thead>
                <tr>
                  <th>Waste Qty (kg)</th>
                  <th>Waste Qty (gr)</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="ModalDetailTransactions" tabindex="-1" role="dialog" aria-labelledby="ModalDetailTransactionsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalDetailTransactionsLabel">Element Transactions</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table id="table-transactions" class="table table-bordered table-sm display compact" style="width:100%">
              <thead>
                <tr>
                  <th>No. Resep</th>
                  <th>Total Qty (kg)</th>
                  <th>Total Qty (gr)</th>
                  <th>Last Activity</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="ModalAddElement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="width: 1000px;">
      <div class="modal-content">

        <form id="ModalAddElementForm" action="pages/ajax/add_element.php" method="POST">
          <div class="modal-header">
            <h4 class="modal-title" id="ModalAddElementTitle">Add New Element</h4>
          </div>

          <div class="modal-body">

            <div class="row">
              <h5><strong>Decosub Code</strong></h5>
              <div class="col-sm-12" style="margin-bottom:10px;">
                <div class="form-group">
                  <label>Search Item Code</label>
                  <select id="decosub_search_select" class="form-control" style="width:100%;">
                    <option value="">Cari item code...</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>Fabric Type</label>
                  <input type="text" max="20" name="decosub01" class="form-control" placeholder="Decosubcode 01" readonly>
                </div>
              </div>

              <div class="col-sm-3">
                <div class="form-group">
                  <label>Article Group</label>
                  <input type="text" max="10" name="decosub02" class="form-control" placeholder="Decosubcode 02" readonly>
                </div>
              </div>

              <div class="col-sm-3">
                <div class="form-group">
                  <label>Article Code</label>
                  <input type="text" max="10" name="decosub03" class="form-control" placeholder="Decosubcode 03" readonly>
                </div>
              </div>

              <div class="col-sm-3">
                <div class="form-group">
                  <label>Variant</label>
                  <input type="text" name="decosub04" class="form-control" placeholder="Decosubcode 04" readonly>
                </div>
              </div>
            </div>

            <hr style="margin: 0 0;">

            <div class="row">
              <h5><strong>Warehouse</strong></h5>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Zone Code</label>
                  <input type="text" max="3" name="warehouse_zone_code" class="form-control" placeholder="" readonly>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Location Code</label>
                  <select name="warehouse_location_code" id="warehouse_location_select" class="form-control" style="width: 100%;">
                    <option value="">Pilih...</option>
                  </select>
                </div>
              </div>

            </div>

            <hr style="margin: 0 0;">

            <div class="row">
              <h5><strong>Additional Info</strong></h5>
              <div class="col-sm-4">
                <div class="form-group">
                  <label>Lot Code</label>
                  <select name="lot_code" id="lot_code_select" class="form-control" style="width: 100%;">
                    <option value="">Pilih lot code...</option>
                  </select>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="form-group">
                  <label>Project Code</label>
                  <select name="project_code" id="project_code_select" class="form-control" style="width: 100%;">
                    <option value="">Pilih...</option>
                  </select>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="form-group">
                  <label>Greige/BLC</label>
                  <select name="g_b" id="g_b_select" class="form-control" style="width: 100%;">
                    <option value="">Pilih...</option>
                  </select>
                </div>
              </div>

            </div>

            <hr style="margin: 0 0;">

            <div class="row" id="QuantitySection">
              <h5><strong>Quantity</strong></h5>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Primary Quantity</label>
                  <div class="input-group">
                    <input
                      type="number"
                      class="form-control style-ph"
                      name="primary_quantity"
                      id="primary_quantity"
                      placeholder="0.00"
                      autocomplete="off"
                      value="0"
                      step="any">
                    <span class="input-group-addon">kg</span>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Secondary Quantity</label>
                  <div class="input-group">
                    <input
                      type="number"
                      class="form-control style-ph"
                      name="secondary_quantity"
                      id="secondary_quantity"
                      placeholder="0.00"
                      autocomplete="off"
                      readonly>
                    <span class="input-group-addon">yd</span>
                  </div>
                  <small id="secondary_factor_info" class="help-block text-muted" style="display:none;margin-top:6px;"></small>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>

      </div>
    </div>
  </div>
</body>

</html>
<script>
  $(document).ready(function() {
    // INIT DATATABLE
    var table = $("#table-balance").DataTable({
      processing: true,
      pagination: true,
      serverSide: false,
      searching: true,
      order: [],
      ajax: {
        url: "pages/ajax/get_balances.php",
        type: "POST",
        data: function(d) {
          d.status = $("#status_filter").val();
          d.qty_filter = $('#qty_filter_select').val() || 'nonzero';
        }
      },
      columns: [{
          data: "item_code"
        },
        {
          data: "lot_code"
        },
        {
          data: "warehouse_zone_code"
        },
        {
          data: "warehouse_location_code"
        },
        {
          data: "element_code"
        },
        {
          data: "base_primary_quantity_unit",
          render: function(data, type, row) {
            return new Intl.NumberFormat('en-US', {
              minimumFractionDigits: 0,
              maximumFractionDigits: 6
            }).format(data)
          },
          createdCell: function(td, cellData, rowData, row, col) {
            $(td).attr('style', 'text-align: right;');
          }
        },
        {
          data: "base_primary_quantity_unit",
          render: function(data, type, row) {
            return new Intl.NumberFormat('en-US', {
              minimumFractionDigits: 0,
              maximumFractionDigits: 6
            }).format(data * 1000)
          },
          createdCell: function(td, cellData, rowData, row, col) {
            $(td).attr('style', 'text-align: right;');
          }
        },
        {
          data: "on_matching",
          render: function(data, type, row) {
            // expired takes precedence
            var expired = row.expired_date;
            var today = new Date().toISOString().slice(0, 10); // YYYY-MM-DD
            if (expired && expired < today) {
              return '<span class="label label-danger">Expired</span>';
            }

            return data == 1 ?
              '<span class="label label-warning">On Matching</span>' :
              '<span class="label label-success">Available</span>';
          }
        },
        {
          data: 'on_matching',
          orderable: false,
          searchable: false,
          render: function(data, type, row) {
            // button selalu muncul
            let btnDefault = `
              <button 
                type="button" 
                class="btn btn-xs btn-primary btn-detail"
                data-element="${row.element_code}"
                data-element-id="${row.element_id}"
                title="Detail"
              >
                <i class="fa fa-eye"></i>
              </button>
              <button 
                type="button" 
                class="btn btn-xs btn-primary btn-waste"
                data-element="${row.element_code}"
                data-element-id="${row.element_id}"
                title="Waste"
              >
                <i class="fa fa-recycle"></i>
              </button>
              <button 
                type="button" 
                class="btn btn-xs btn-warning btn-edit"
                data-element="${row.element_code}"
                data-element-id="${row.element_id}"
                title="Edit"
              >
                <i class="fa fa-edit"></i>
              </button>
              <button 
                type="button" 
                class="btn btn-xs btn-info btn-info"
                data-element="${row.element_code}"
                data-element-id="${row.element_id}"
                title="Sticker"
              >
                <i class="fa fa-print"></i>
              </button>
            `;

            let btnOnMatching = "";
            if (data == 1) {
              btnOnMatching = `
                <button 
                  type="button" 
                  class="btn btn-xs btn-success btn-return-element"
                  data-element="${row.element_id}"
                  title="Return"
                >
                  <i class="fa fa-undo"></i>
                </button>
              `;
            }

            let btnExpired = "";
            // expired takes precedence
            var expired = row.expired_date;
            var isExpired = false;
            var today = new Date().toISOString().slice(0, 10); // YYYY-MM-DD
            if (expired && expired < today) {
              isExpired = true
            }
            
            if (isExpired == true) {
              btnExpired = `
                <button 
                  type="button" 
                  class="btn btn-xs btn-danger btn-expired-waste"
                  data-element="${row.element_code}"
                  data-element-id="${row.element_id}"
                  title="Expired Waste"
                >
                  <i class="fa fa-hourglass-end"></i>
                </button>
              `;
            }

            return btnDefault + btnOnMatching + btnExpired;
          }
        }
      ],
      rowCallback: function(row, data, index) {
        // Highlight row merah jika qty = 0
        if (parseFloat(data.base_primary_quantity_unit) === 0) {
          $(row).addClass('qty-zero');
        } else {
          $(row).removeClass('qty-zero');
        }
      }
    })

    // qty filter changed -> reload table
    $(document).on('change', '#qty_filter_select', function() {
      table.ajax.reload();
    });

    // status filter changed -> reload table (same behavior as qty filter)
    $(document).on('change', '#status_filter', function() {
      table.ajax.reload();
    });

    // editing state
    let isEditing = false;
    let editingElementId = null;

    // ➕ Add Element → open modal
    $("#btn-add-element").on("click", function() {
      // reset native form fields
      $("#ModalAddElementForm")[0].reset();

      // reset readonly decosub inputs
      $('input[name="decosub01"]').val('');
      $('input[name="decosub02"]').val('');
      $('input[name="decosub03"]').val('');
      $('input[name="decosub04"]').val('');

      // clear Select2 fields (if initialized)
      try {
        $('#decosub_search_select').val(null).trigger('change');
        $('#warehouse_location_select').val(null).trigger('change');
        $('#lot_code_select').val(null).trigger('change');
        $('#project_code_select').val(null).trigger('change');
        $('#g_b_select').val(null).trigger('change');
      } catch (e) {
        // ignore if select2 not yet initialized
      }

      // reset quantity and factor UI
      secondary_calculation = null;
      $("#secondary_quantity").val('');
      $("#secondary_factor_info").hide().text('');
      $("#primary_quantity").prop('disabled', true).val('0');

      // clear editing state
      isEditing = false;
      editingElementId = null;

      // reset modal UI to create mode
      $('#ModalAddElementTitle').text('Add New Element');
      $('#QuantitySection').show();

      // show modal
      $("#ModalAddElement").modal("show");
    });

    // Separate Select2 search for SUBCODE04 (fills decosub01..04 on select)
    $('#decosub_search_select').select2({
      placeholder: 'Cari item code...',
      allowClear: true,
      minimumInputLength: 0,
      dropdownParent: $('#ModalAddElement'),
      ajax: {
        url: 'pages/ajax/get_products_subcodes.php',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            search: params.term
          };
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    }).on('select2:select', function(e) {
      const d = e.params.data;
      if (d) {
        // populate readonly fields
        $('input[name="decosub01"]').val(d.subcode01 || '');
        $('input[name="decosub02"]').val(d.subcode02 || '');
        $('input[name="decosub03"]').val(d.subcode03 || '');
        $('input[name="decosub04"]').val(d.subcode04 || '');

        // trigger factor lookup
        checkSecondaryQtyCalculation();
      }
    }).on('select2:clear', function() {
      $('input[name="decosub01"]').val('');
      $('input[name="decosub02"]').val('');
      $('input[name="decosub03"]').val('');
      $('input[name="decosub04"]').val('');
      secondary_calculation = null;
      $('#primary_quantity').prop('disabled', true);
      $('#secondary_quantity').val('');
      $('#secondary_factor_info').hide().text('');
      // also clear lot code when decosub is cleared
      $('#lot_code_select').val(null).trigger('change');
    });

    // Initialize Select2 for warehouse location
    $('#warehouse_location_select').select2({
      placeholder: 'Cari lokasi...',
      allowClear: true,
      minimumInputLength: 0,
      dropdownParent: $('#ModalAddElement'),
      ajax: {
        url: 'pages/ajax/get_warehouse_locations.php',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            search: params.term
          };
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    }).on('select2:select', function(e) {
      const d = e.params.data;
      if (d) {
        $('input[name="warehouse_zone_code"]').val(d.location_zone || '');
      }
    }).on('select2:clear', function() {
      $('input[name="warehouse_zone_code"]').val('');
    });

    // Initialize Select2 for project code (AJAX)
    $('#project_code_select').select2({
      placeholder: 'Pilih project code...',
      allowClear: true,
      minimumInputLength: 0,
      dropdownParent: $('#ModalAddElement'),
      ajax: {
        url: 'pages/ajax/get_projects.php',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            search: params.term
          };
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });

    // Initialize Select2 for Greige/BLC (G_B) options
    $('#g_b_select').select2({
      placeholder: 'Pilih Greige/BLC...',
      allowClear: true,
      minimumInputLength: 0,
      dropdownParent: $('#ModalAddElement'),
      ajax: {
        url: 'pages/ajax/get_gb_options.php',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            search: params.term
          };
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });

    // Initialize Select2 for lot code (requires all 4 decosub codes to be filled)
    $('#lot_code_select').select2({
      placeholder: 'Pilih lot code...',
      allowClear: true,
      minimumInputLength: 0,
      dropdownParent: $('#ModalAddElement'),
      ajax: {
        url: 'pages/ajax/get_lot_codes.php',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          // Gather all 4 decosub codes from inputs
          const d01 = $('input[name="decosub01"]').val() || '';
          const d02 = $('input[name="decosub02"]').val() || '';
          const d03 = $('input[name="decosub03"]').val() || '';
          const d04 = $('input[name="decosub04"]').val() || '';

          return {
            search: params.term,
            decosubcode01: d01,
            decosubcode02: d02,
            decosubcode03: d03,
            decosubcode04: d04
          };
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: false
      }
    });

    $(document).on("input", "input[max]", function() {
      let max = parseInt($(this).attr("max"));
      let val = $(this).val();

      if (val.length > max) {
        $(this).val(val.substring(0, max));
      }
    });

    // secondary factor (unknown until lookup)
    let secondary_calculation = null;

    $("#primary_quantity").on("input", function() {
      let primary = parseFloat($(this).val()) || 0;

      if (secondary_calculation === null) {
        // factor not available - clear secondary
        $("#secondary_quantity").val('');
        return;
      }

      // Konversi menggunakan faktor secondary_calculation
      let secondary = primary * (parseFloat(secondary_calculation) || 1);

      $("#secondary_quantity").val(secondary.toFixed(3));
    });

    function checkSecondaryQtyCalculation() {
      const s1 = $('input[name="decosub01"]').val() || '';
      const s2 = $('input[name="decosub02"]').val() || '';
      const s3 = $('input[name="decosub03"]').val() || '';
      // decosub04 is a readonly input now; get its value
      const s4 = $('input[name="decosub04"]').val() || '';

      // require ALL 4 subcodes to attempt lookup
      if (!s1 || !s2 || !s3 || !s4) {
        // disable primary until user fills all subcodes
        secondary_calculation = null;
        $("#primary_quantity").prop('disabled', true);
        $("#secondary_quantity").val('');
        $("#secondary_factor_info").show().text('Fill all 4 decosub codes to lookup factor.');
        return;
      }
      $.ajax({
        url: "pages/ajax/get_balance_secondary_qty_calculation.php",
        type: "GET",
        data: {
          itemtypecode: 'KGF',
          subcode01: s1,
          subcode02: s2,
          subcode03: s3,
          subcode04: s4
        },
        dataType: "json",
        success: function(res) {
          if (res && res.success === true && res.factor) {
            secondary_calculation = parseFloat(res.factor) || null;
            $("#primary_quantity").prop('disabled', false);
            $("#secondary_factor_info").show().text('Factor: ' + secondary_calculation);
          } else {
            // factor not found -> disable primary
            secondary_calculation = null;
            $("#primary_quantity").prop('disabled', true);
            $("#secondary_factor_info").show().text('Factor not found for given subcodes.');
          }
          // recalculate secondary shown value
          $("#primary_quantity").trigger('input');
        },
        error: function() {
          // keep default and recalc
          $("#primary_quantity").trigger('input');
        }
      });
    }

    $(document).on("click", ".btn-return-element", function(e) {
      e.preventDefault();
      const element = $(this).data("element");

      if (!element) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian',
          text: 'Element ID tidak valid!'
        });
        return;
      }

      $.ajax({
        url: "pages/ajax/get_element_return.php",
        type: "POST",
        data: {
          element_id: element
        },
        dataType: "json",
        success: function(res) {
          if (res.success === true && res.data) {
            const initialStockKg =
              new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 6
              }).format(res.data.initial_stock)

            const initialStockGr =
              new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 6
              }).format(res.data.initial_stock * 1000)

            // populate modal values
            $("#ModalReturnElementCode").text(res.data.element_code || "-");
            $("#ModalReturnElementInitialQty").text(`${initialStockKg || "0"} Kg / ${initialStockGr || "0"} gr`);

            // populate resep select
            // var select = $("#ModalReturnElementSelectResep");
            // select.empty();
            // select.append($('<option/>').val('').text('-- Select Resep --'));
            // var list = res.data.no_resep_list || [];
            // if (list.length === 0 && res.data.no_resep) {
            //   // backward compatibility: if single no_resep returned
            //   list = [res.data.no_resep];
            // }
            // list.forEach(function(nr) {
            //   select.append($('<option/>').val(nr).text(nr));
            // });

            // enable qty only after user selects a resep
            // $("#ModalReturnElementQty").prop('disabled', true).val('');

            // store element_id for submit
            $("#ModalReturnElementForm").data('element_id', element);

            // show modal
            $("#ModalReturnElement").modal('show', {
              backdrop: 'false'
            });

          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: res.message || 'Unknown error'
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi error server: ' + error
          });
        }
      });
    });

    // Edit element -> open modal populated with existing values
    $(document).on('click', '.btn-edit', function(e) {
      e.preventDefault();
      const elementId = $(this).data('element-id');
      if (!elementId) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian',
          text: 'Element ID tidak tersedia'
        });
        return;
      }

      // fetch element details
      $.ajax({
        url: 'pages/ajax/get_element_for_edit.php',
        type: 'GET',
        data: {
          element_id: elementId
        },
        dataType: 'json',
        success: function(res) {
          if (!res || res.success !== true || !res.data) {
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: res.message || 'Data tidak ditemukan'
            });
            return;
          }

          const d = res.data;

          // set editing state
          isEditing = true;
          editingElementId = d.element_id;

          // populate decosub readonly inputs
          $('input[name="decosub01"]').val(d.decosub01 || '');
          $('input[name="decosub02"]').val(d.decosub02 || '');
          $('input[name="decosub03"]').val(d.decosub03 || '');
          $('input[name="decosub04"]').val(d.decosub04 || '');

          // set decosub_search_select visible value (so select2 shows current text)
          if (d.item_text && d.item_id) {
            const newOption = new Option(d.item_text, d.item_id, true, true);
            $('#decosub_search_select').append(newOption).trigger('change');
          } else {
            $('#decosub_search_select').val(null).trigger('change');
          }

          // warehouse zone + select2 value
          $('input[name="warehouse_zone_code"]').val(d.warehouse_zone_code || '');
          if (d.warehouse_location_code) {
            const opt = new Option(d.warehouse_location_text || d.warehouse_location_code, d.warehouse_location_code, true, true);
            $('#warehouse_location_select').append(opt).trigger('change');
          } else {
            $('#warehouse_location_select').val(null).trigger('change');
          }

          // lot code
          if (d.lot_code) {
            const optl = new Option(d.lot_code, d.lot_code, true, true);
            $('#lot_code_select').append(optl).trigger('change');
          } else {
            $('#lot_code_select').val(null).trigger('change');
          }

          // project
          if (d.project_code) {
            const optp = new Option(d.project_code, d.project_code, true, true);
            $('#project_code_select').append(optp).trigger('change');
          } else {
            $('#project_code_select').val(null).trigger('change');
          }

          // g_b
          if (d.g_b) {
            const optg = new Option(d.g_b, d.g_b, true, true);
            $('#g_b_select').append(optg).trigger('change');
          } else {
            $('#g_b_select').val(null).trigger('change');
          }

          // quantities (display only in edit mode)
          $('#primary_quantity').val(parseFloat(d.primary_qty) || 0);
          $('#secondary_quantity').val(parseFloat(d.secondary_qty) || '');

          // lock quantities when editing: user requested quantities must not be editable
          $('#primary_quantity').prop('disabled', true);
          // secondary is already readonly in markup; show a small note
          $('#secondary_factor_info').show().text('Quantities are locked while editing.');

          // update modal title to Edit mode
          $('#ModalAddElementTitle').text('Edit Element');
          // hide quantity section during edit
          $('#QuantitySection').hide();

          // show modal
          $('#ModalAddElement').modal('show');
        },
        error: function(xhr, status, err) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi error server: ' + err
          });
        }
      });
    });

    // Open printable sticker view in new window
    $(document).on('click', '.btn-info.btn-info', function(e) {
      e.preventDefault();
      var elementId = $(this).data('element-id') || '';
      if (!elementId) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian',
          text: 'Element ID tidak tersedia'
        });
        return;
      }
      var url = 'pages/print_sticker_pdf.php?element_id=' + encodeURIComponent(elementId);
      window.open(url, '_blank', 'toolbar=0,location=0,menubar=0');
    });

    // Expired Waste action -> set qty to 0 and record waste
    $(document).on('click', '.btn-expired-waste', function(e) {
      e.preventDefault();
      const elementId = $(this).data('element-id') || null;
      const elementCode = $(this).data('element') || '';

      if (!elementId) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian',
          text: 'Element ID tidak tersedia'
        });
        return;
      }

      Swal.fire({
        title: 'Tandai sebagai expired waste?',
        html: `Set quantity of <strong>${elementCode}</strong> to <strong>0</strong> and record the difference as waste.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, tandai sebagai waste',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (!result.isConfirmed) return;

        $.ajax({
          url: 'pages/ajax/process_return_element.php',
          type: 'POST',
          data: {
            element_id: elementId,
            qty_return: 0
          },
          dataType: 'json',
          success: function(res) {
            if (res && res.success === true) {
              // refresh table and show success
              $('#table-balance').DataTable().ajax.reload(null, false);
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: res.message || 'Element ditandai sebagai waste dan qty di-set ke 0.'
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: res.message || 'Gagal memproses expired waste.'
              });
            }
          },
          error: function(xhr, status, err) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Terjadi error server: ' + (err || status)
            });
          }
        });
      });
    });

    // HANDLE FORM SUBMIT ADD / UPDATE ELEMENT
    $("#ModalAddElementForm").on("submit", function(e) {
      e.preventDefault();

      // decide URL depending on mode (create vs edit)
      const $form = $(this);
      const url = isEditing ? 'pages/ajax/update_balance.php' : 'pages/ajax/insert_new_balance.php';
      const data = $form.serializeArray();
      if (isEditing && editingElementId) {
        data.push({
          name: 'element_id',
          value: editingElementId
        });
      }

      $.ajax({
        url: url,
        type: 'POST',
        data: $.param(data),
        dataType: 'json',
        success: function(res) {
          if (res && (res.status === 'success' || res.success === true)) {
            // Close modal
            $('#ModalAddElement').modal('hide');

            // Reset form and editing state
            $('#ModalAddElementForm')[0].reset();
            isEditing = false;
            editingElementId = null;

            // clear select2 values
            try {
              $('#decosub_search_select').val(null).trigger('change');
              $('#warehouse_location_select').val(null).trigger('change');
              $('#lot_code_select').val(null).trigger('change');
              $('#project_code_select').val(null).trigger('change');
              $('#g_b_select').val(null).trigger('change');
            } catch (e) {}

            // Reload DataTables
            $('#table-balance').DataTable().ajax.reload(null, false);

            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: res.message || 'Element berhasil disimpan!'
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: res.message || 'Terjadi kesalahan'
            });
          }
        },
        error: function(xhr) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi error server!'
          });
        }
      });
    });

    // HANDLE FORM SUBMIT RETURN ELEMENT
    $("#ModalReturnElementForm").on("submit", function(e) {
      e.preventDefault();

      const qtyReturn = $("#ModalReturnElementQty").val();
      // const selectedResep = $("#ModalReturnElementSelectResep").val();
      const elementId = $(this).data('element_id');

      if (!qtyReturn || parseFloat(qtyReturn) < 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian',
          text: 'Qty return tidak boleh -'
        });
        return;
      }

      // if (!selectedResep) {
      //   Swal.fire({
      //     icon: 'warning',
      //     title: 'Perhatian',
      //     text: 'Pilih No. Resep yang akan direturn!'
      //   });
      //   return;
      // }

      $.ajax({
        url: "pages/ajax/process_return_element.php",
        type: "POST",
        data: {
          element_id: elementId,
          qty_return: qtyReturn / 1000,
          // no_resep: selectedResep
        },
        dataType: "json",
        success: function(res) {
          if (res.success === true) {
            // Close modal
            $("#ModalReturnElement").modal("hide");

            // Reset form
            $("#ModalReturnElementForm")[0].reset();

            // Reload DataTables
            $("#table-balance").DataTable().ajax.reload(null, false);

            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Element berhasil di-return!'
            });

          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: res.message || 'Unknown error'
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi error server: ' + error
          });
        }
      });
    });

    var transTable = null;
    $(document).on('click', '.btn-detail', function(e) {
      e.preventDefault();
      var elementId = $(this).attr('data-element-id') || '';
      var elementCode = $(this).attr('data-element') || '';

      if (!elementId) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian',
          text: 'Element ID tidak tersedia'
        });
        return;
      }

      $('#ModalDetailTransactionsLabel').text('Element Transactions - ' + elementCode);

      // initialize or reload datatable
      if (transTable) {
        transTable.ajax.url('pages/ajax/get_balance_transactions.php?element_id=' + encodeURIComponent(elementId)).load();
      } else {
        transTable = $('#table-transactions').DataTable({
          processing: true,
          serverSide: false,
          searching: false,
          paging: true,
          ajax: {
            url: 'pages/ajax/get_balance_transactions.php?element_id=' + encodeURIComponent(elementId),
            type: 'GET',
            dataSrc: 'data'
          },
          columns: [{
              data: 'no_resep',
              render: function(d, type, row) {
                if (!d || d.trim() === '') {
                  return '-';
                }
                return d;
              }
            },
            {
              data: 'total_qty',
              render: function(d) {
                var v = parseFloat(d) || 0;
                return (v / 1000);
              }
            },
            {
              data: 'total_qty',
              render: function(d) {
                var v = parseFloat(d) || 0;
                return (v);
              }
            },
            {
              data: 'last_date'
            }
          ],
          columnDefs: [{
            targets: [1, 2, 3],
            width: '100px',
            className: 'text-right'
          }]
        });
      }

      $('#ModalDetailTransactions').modal('show');
    });

    var wasteTable = null;
    $(document).on('click', '.btn-waste', function(e) {
      e.preventDefault();
      var elementId = $(this).attr('data-element-id') || '';
      var elementCode = $(this).attr('data-element') || '';

      if (!elementId) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian',
          text: 'Element ID tidak tersedia'
        });
        return;
      }

      $('#ModalDetailWasteLabel').text('Element Waste - ' + elementCode);

      // initialize or reload datatable
      if (wasteTable) {
        wasteTable.ajax.url('pages/ajax/get_balance_transactions_waste.php?element_id=' + encodeURIComponent(elementId)).load();
      } else {
        wasteTable = $('#table-waste').DataTable({
          processing: true,
          serverSide: false,
          searching: false,
          paging: true,
          ajax: {
            url: 'pages/ajax/get_balance_transactions_waste.php?element_id=' + encodeURIComponent(elementId),
            type: 'GET',
            dataSrc: 'data'
          },
          columns: [{
              data: 'qty',
              render: function(d, type, row) {
                var v = parseFloat(d) || 0;
                return (v / 1000);
              }
            },
            {
              data: 'qty',
              render: function(d, type, row) {
                var v = parseFloat(d) || 0;
                return (v);
              }
            },
            {
              data: 'created_at'
            }
          ],
          columnDefs: [{
            targets: [0, 1, 2],
            width: '100px',
            className: 'text-right'
          }]
        });
      }

      $('#ModalDetailWaste').modal('show');
    });
  })
</script>