<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';

$equipments = getUniqueEquipments($con);
$borrowers = getUniqueBorrowers($con);

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php' ?>

 <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
 <title>Equipment Details - SPCC Caloocan Clinic</title>

</head>
<body class="hold-transition sidebar-mini dark-mode layout-fixed layout-navbar-fixed">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->

    <?php include './config/header.php';
include './config/sidebar.php';?>  
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Equipment Details</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Add Equipment Details</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <!-- best practices-->
            <form method="post">
              <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                  <label>Select Equipment</label>
                  <select id="equipment" name="equipment" class="form-control form-control-sm rounded-0" required="required">
                    <?php echo $equipments;?>
                  </select>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                  <label>Status</label>
                  <select id="status" name="status" class="form-control form-control-sm rounded-0" required="required">
                    <option value="">Select Status</option>
                    <option value="Available">Available</option>
                    <option value="Unavailable">Unavailable</option>
                  </select>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                  <label>State</label>
                  <select id="state" name="state" class="form-control form-control-sm rounded-0">
                    <option value="">Select State</option>
                  </select>
                </div>

                <div class="clearfix">&nbsp;</div>
                
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                  <label>Remarks</label>
                  <textarea id="remarks" name="remarks" class="form-control form-control-sm rounded-0" placeholder='e.g. "Currently in repair shop located at 11th Ave."'></textarea>
                </div>
                
                <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
                  <label>Quantity Available</label>
                  <input type="number" id="quantity" name="quantity" class="form-control form-control-sm rounded-0" required="required" min="1"/>
                </div>

                <div class="clearfix unavailable">&nbsp;</div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-10 unavailable">
                  <div class="form-group">
                    <label>Unavailable Since</label>
                    <div class="input-group date" id="unavailable_since" 
                        data-target-input="nearest">
                        <input type="text" value="<?php echo date("m/d/Y"); ?>" id="unavailableSince" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#unavailable_since" name="unavailable_since" required="required" data-toggle="datetimepicker" autocomplete="off"/>
                        <div class="input-group-append" 
                        data-target="#unavailable_since" 
                        data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-10 unavailable">
                  <div class="form-group">
                    <label>Unavailable Until</label>
                    <div class="input-group date" id="unavailable_until" 
                        data-target-input="nearest">
                        <input type="text" value="" id="unavailableUntil" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#unavailable_until" name="unavailable_until" data-toggle="datetimepicker" autocomplete="off"/>
                        <div class="input-group-append" 
                        data-target="#unavailable_until" 
                        data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 unavailable borrower">
                  <label>Borrower</label>
                  <select id="borrower" name="borrower" class="form-control form-control-sm rounded-0">
                    <?php echo $borrowers;?>
                  </select>
                </div>

              </div>

              <div class="clearfix">&nbsp;</div>
              <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-10">&nbsp;</div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                  <button type="button" id="add_to_list" class="btn btn-primary btn-sm btn-flat btn-block">
                    <i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>

    <div class="col-md-12"><hr /></div>
    <div class="clearfix">&nbsp;</div>

    <div class="clearfix">&nbsp;</div>
    <div class="row table-responsive">
      <table id="equipment_list" class="table table-striped table-bordered">
        <colgroup>
          <col width="2%">
          <col width="20%">
          <col width="10%">
          <col width="10%">
          <col width="5%">
          <col width="40%">
          <col width="3%">
        </colgroup>
        <thead class="bg-primary">
          <tr>
            <th>#</th>
            <th>Equipment</th>
            <th>Status</th>
            <th>State</th>
            <th>Qty</th>
            <th>Remarks</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody id="current_equipment_list">

        </tbody>
      </table>
    </div>

    <div class="clearfix">&nbsp;</div>
    <div class="row">
      <div class="col-md-10">&nbsp;</div>
      <div class="col-md-2">
        <button type="submit" id="submit" name="submit" 
        class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
      </div>
    </div>
  </form>

</div>

</div>
<!-- /.card -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include './config/footer.php';
$message = '';
if(isset($_GET['message'])) {
  $message = $_GET['message'];
}
?>  
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php include './config/site_js_links.php';
?>

<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
  var serial = 1;
  showMenuSelected("#mnu_equipments", "#mi_equipment_details");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }

  var equipmentDetailsArr = [];

  $(document).ready(function() {

    $("#quantity").css("height", "52px");

    // in javascript, i wanna change the <options> of the #state select box depending on the #status selected
    // if #status is Available, then #state can be active or non-borrowable
    // if #status is Unavailable, then state can be used, missing, defective, in repair, borrowed, transferred

    $("#status").change(function() {
      var status = $("#status option:selected").text();
      var html = '';

      if (status === 'Available') {
        html = '<option value="Active">Active</option>';
        html += '<option value="Non-Borrowable">Non-Borrowable</option>';
      } else if (status === 'Unavailable') {
        html = '<option value="Used">Used</option>';
        html += '<option value="Missing">Missing</option>';
        html += '<option value="Defective">Defective</option>';
        html += '<option value="In Repair">In Repair</option>';
        html += '<option value="Borrowed">Borrowed</option>';
        html += '<option value="Transferred">Transferred</option>';
      }

      $("#state").html(html);

    });

    // element that has the class of unavailable will be hidden
    $(".unavailable").hide();

    // if the #status is Unavailable, then show the elements that has the class of unavailable except .borrower
    $("#status, #state").change(function() {
      var status = $("#status option:selected").text();
      var state = $("#state option:selected").text();

      if (status === 'Unavailable' && state === 'Borrowed') {
        $(".unavailable").show();
      } else if (status === 'Unavailable') {
        $(".unavailable").show();
        $(".borrower").hide();
      } else {
        $(".unavailable").hide();
      }
    });

    $("form :input").blur(function() {
      // input validation
    });
    
    
    $('#equipment_list').find('td').addClass("px-2 py-1 align-middle")
    $('#equipment_list').find('th').addClass("p-1 align-middle")

    $('#unavailable_since, #unavailable_until').datetimepicker({
      format: 'L'
    });

    $("#equipment").change(function() {

      var equipmentDetailsId = $(this).val();

      if(equipmentDetailsId !== '') {
        $.ajax({
          url: "ajax/get_quantity.php",
          type: 'GET', 
          data: {
            'equipmentDetailsId': equipmentDetailsId
          },
          cache:false,
          async:false,
          success: function (data, status, xhr) {

            if (equipmentDetailsArr.length > 0) {
              for (let i = 0; i < equipmentDetailsArr.length; i++) {
                if (equipmentDetailsArr[i].equipmentId === equipmentDetailsId) {
                  data -= equipmentDetailsArr[i].qty;
                  if (data < 0) {
                    data = 0;
                  }
                }
              }
            }

            $("#quantity").val(data);
            $("input").attr({
              "max" : data,
              "min" : 0
            });

            $("#quantity").on("input", function() {
              var value = $(this).val();
              var min = parseInt($(this).attr("min"));
              var max = parseInt($(this).attr("max"));

              if (value < min) {
                $(this).val(min);
              } else if (value > max) {
                $(this).val(max);
              }
            });
          },
          error: function (jqXhr, textStatus, errorMessage) {
            showCustomMessage(errorMessage);
          }
        });
      }
    });


    $("#add_to_list").click(function() {

      var equipmentId = $("#equipment").val();
      var equipmentName = $("#equipment option:selected").text();
      
      var status = $("#status").val();
      var state = $("#state").val();
      var remarks = $("#remarks").val().trim();

      var quantity = $("#quantity").val().trim();
      if (quantity == '0') {
        quantity = '';
      }

      // get value of #unavailable_since and #unavailable_until if the element exists
      var f_unavailableSince = '';
      var f_unavailableUntil = '';
      if ($(".unavailable").css('display') != 'none') {
        var unavailableSince = $("#unavailableSince").val().trim();
        var parts = unavailableSince.split("/");
        console.log("unavailableSince"+parts);
        var month = parts[0].length === 1 ? '0' + parts[0] : parts[0];
        var day = parts[1].length === 1 ? '0' + parts[1] : parts[1];
        f_unavailableSince = parts[2] + "-" + month + "-" + day;

        
        var unavailableUntil = $("#unavailableUntil").val().trim();
        if (unavailableUntil != '') {
          parts = unavailableUntil.split("/");
          console.log("unavailableUntil"+parts);
          month = parts[0].length === 1 ? '0' + parts[0] : parts[0];
          day = parts[1].length === 1 ? '0' + parts[1] : parts[1];
          f_unavailableUntil = parts[2] + "-" + month + "-" + day;
        }
      }

      //get #borrower value if the element exists
      var borrowerId = '';
      if ($(".borrower").css('display') != 'none') {
        borrowerId = $("#borrower").val();
      }

      var hasNoId = true;
      var addCell = true;
        
      if (equipmentDetailsArr.length > 0) {
        for (let i = 0; i < equipmentDetailsArr.length; i++) {

          var id_arr = equipmentDetailsArr[i].equipmentId;
          var status_arr = equipmentDetailsArr[i].status;
          var state_arr = equipmentDetailsArr[i].state;
          var remarks_arr = equipmentDetailsArr[i].remarks;
          var uSince_arr = equipmentDetailsArr[i].unavailableSince;
          var uUntil_arr = equipmentDetailsArr[i].unavailableUntil;
          var borId_arr = equipmentDetailsArr[i].borrowerId;

          if (id_arr === equipmentId && status_arr === status && state_arr === state && remarks_arr === remarks && uSince_arr === f_unavailableSince && uUntil_arr === f_unavailableUntil && borId_arr === borrowerId) {
            var qtyId = equipmentId+"_"+status+"_"+state+"_"+remarks+"_"+f_unavailableSince+"_"+f_unavailableUntil+"_"+borrowerId;
            addQuantity(parseInt(quantity), qtyId);
            equipmentDetailsArr[i].qty += parseInt(quantity);
            hasNoId = false;
            addCell = false;
            break;
          }
        }
      }

      var oldData = $("#current_equipment_list").html();
      var clearForm = true;

      if(equipmentName !== '' && status !== '' && state !== '' && quantity !== '' && addCell) {
        
        var qtyId = equipmentId+"_"+status+"_"+state+"_"+remarks+"_"+f_unavailableSince+"_"+f_unavailableUntil+"_"+borrowerId;
        var inputs = '';
        inputs = inputs + '<input type="hidden" name="equipmentIds[]" value="'+equipmentId+'" />';
        inputs = inputs + '<input type="hidden" name="status[]" value="'+status+'" />';
        inputs = inputs + '<input type="hidden" name="states[]" value="'+state+'" />';
        inputs = inputs + '<input type="hidden" name="quantities[]" id="inp-'+qtyId+'" value="'+quantity+'" />';
        inputs = inputs + '<input type="hidden" name="remarks[]" value="'+remarks+'" />';
        // inputs = inputs + '<input type="hidden" name="equipmentDetailsArr[]" value=\'{"equipmentId":'+equipmentId+', "qty":'+quantity+'}\'/>';
        inputs = inputs + '<input type="hidden" name="borrowerIds[]" value="'+borrowerId+'" />';
        inputs = inputs + '<input type="hidden" name="unavailableSinces[]" value="'+f_unavailableSince+'" />';
        inputs = inputs + '<input type="hidden" name="unavailableUntils[]" value="'+f_unavailableUntil+'" />';

        var tr = '<tr>';
        tr = tr + '<td class="px-2 py-1 align-middle">'+serial+'</td>';
        tr = tr + '<td class="px-2 py-1 align-middle">'+equipmentName+'</td>';
        tr = tr + '<td class="px-2 py-1 align-middle">'+status+'</td>';
        tr = tr + '<td class="px-2 py-1 align-middle">'+state+'</td>';
        tr = tr + '<td class="px-2 py-1 align-middle" id="'+qtyId+'">'+quantity+'</td>';
        tr = tr + '<td class="px-2 py-1 align-middle">'+remarks + inputs +'</td>';

        tr = tr + '<td class="px-2 py-1 align-middle text-center"><button type="button" class="btn btn-outline-danger btn-sm rounded-0" onclick="deleteCurrentRow(this);"><i class="fa fa-times"></i></button></td>';
        tr = tr + '</tr>';
        oldData = oldData + tr;
        serial++;

        $("#current_equipment_list").html(oldData);

        if (hasNoId) {
          equipmentDetailsArr.push({
            equipmentId: equipmentId,
            status: status,
            state: state,
            qty: parseInt(quantity),
            remarks: remarks,
            borrowerId: borrowerId,
            unavailableSince: f_unavailableSince,
            unavailableUntil: f_unavailableUntil            
          });
        }

      } else {
        if (!addCell) {
          showCustomMessage("Equipment \""+ equipmentName +"\" already exists. The quantity has been updated.");
        } else {
          showCustomMessage("Please fill out all the fields.");
          clearForm = false;
        }
      }

      if (clearForm) {
        // reset the form
        $("#equipment").val('');
        $("#status").val('');
        $("#state").val('');
        $("#remarks").val('');
        $("#quantity").val('');

        if ($(".unavailable").length > 0 && $(".borrower").length > 0) {
          $("#unavailable_since").val('');
          $("#unavailable_until").val('');
          $("#borrower").val('');
          $(".unavailable").hide();
        } else if ($(".unavailable").length > 0) {
          $("#unavailable_since").val('');
          $("#unavailable_until").val('');
          $(".unavailable").hide();
        }
      }
    });

  });

  function deleteCurrentRow(obj) {

    var rowIndex = obj.parentNode.parentNode.rowIndex;
    
    var row = document.getElementById("equipment_list").rows[rowIndex];
    var del_qty = row.cells[4].textContent.trim();
    var id = row.cells[4].id;

    document.getElementById("equipment_list").deleteRow(rowIndex);

    var del_arr = id.split("_");

    for (let i = 0; i < equipmentDetailsArr.length; i++) {

      var id_arr = equipmentDetailsArr[i].equipmentId;
      var status_arr = equipmentDetailsArr[i].status;
      var state_arr = equipmentDetailsArr[i].state;
      var remarks_arr = equipmentDetailsArr[i].remarks;
      var uSince_arr = equipmentDetailsArr[i].unavailableSince;
      var uUntil_arr = equipmentDetailsArr[i].unavailableUntil;
      var borId_arr = equipmentDetailsArr[i].borrowerId;

      var del_id = del_arr[0];
      var del_status = del_arr[1];
      var del_state = del_arr[2];
      var del_remarks = del_arr[3];
      var del_uSince = del_arr[4];
      var del_uUntil = del_arr[5];
      var del_borId = del_arr[6];

      if (id_arr === del_id && status_arr === del_status && state_arr === del_state && remarks_arr === del_remarks && uSince_arr === del_uSince && uUntil_arr === del_uUntil && borId_arr === del_borId) {
        equipmentDetailsArr.splice(i, 1);
        break;
      }
    }
  }

  function addQuantity(quantity, qtyId) {
    var currentQty = $("#"+qtyId).text();
    currentQty = parseInt(currentQty);
    currentQty += quantity;
    $("#"+qtyId).text(currentQty);
    $("#inp-"+qtyId).val(currentQty);
  }

</script>
</body>
</html>