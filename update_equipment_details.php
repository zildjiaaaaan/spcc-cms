<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';

if(isset($_POST['submit'])) {

  $equipmentId = $_POST['equipment'];
  $equipmentDetailId = $_POST['hidden_id'];
  $status = $_POST['status'];
  $quantity = $_POST['quantity'];

  $expDateArr = explode("/", $_POST['expiry']);
  $expDate = $expDateArr[2].'-'.$expDateArr[0].'-'.$expDateArr[1];

  $query = "UPDATE `medicine_details` 
            SET `medicine_id` = $medicineId, `packing` = '$packing', `exp_date` = '$expDate', `quantity` = '$quantity'
            WHERE `id` = '$medicineDetailId';
            ";

  try {

    $con->beginTransaction();

    $stmtUpdate = $con->prepare($query);
    $stmtUpdate->execute();

    $con->commit();

    $message = 'Medicine Details Updated Successfully.';

  }  catch(PDOException $ex) {
    $con->rollback();

    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;
  }
  header("location:congratulation.php?goto_page=medicine_details.php&message=$message");
  exit;
}

$equipmentId = $_GET['equipment_id'];
$equipmentDetailId = $_GET['equipment_detail_id'];

$equipments = getUniqueEquipments($con, $equipmentId);

try {
  $query = "SELECT `status`, `quantity`, date_format(`date_acquired`, '%m/%d/%Y') AS `date_acquired`, `remarks`
            FROM `equipment_details` where `id` = '$equipmentDetailId';";
  
    $stmt = $con->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    $quantity = $row['quantity'];
    $date_acquired = $row['date_acquired'];

  } catch(PDOException $ex) {
  
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php';?>
 <?php include './config/data_tables_css.php';?>
 <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
 <title>Update Medicine Details - SPCC Caloocan Clinic</title>

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
              <h1>Medicine Details</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Update Medicine Details</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
              
            </div>
          </div>
          <div class="card-body">
            <form method="post">

              <input type="hidden" name="hidden_id" 
              value="<?php echo $equipmentDetailId;?>" />

              <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                  <label>Select Equipment</label>
                  <select id="equipment" name="equipment" class="form-control form-control-sm rounded-0" required="required">
                    <?php echo $equipments;?>
                  </select>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                  <label>Status</label>
                  <select id="status" name="status" class="form-control form-control-sm rounded-0" required="required">
                    <option
                    <?php
                    if ($row['status'] == "Available") {
                        echo 'selected="selected"';
                    } ?> value="Available">Available</option>
                    <option
                    <?php
                    if ($row['status'] == "Defective") {
                        echo 'selected="selected"';
                    } ?>
                    value="Defective">Defective</option>
                    <option
                    <?php
                    if ($row['status'] == "Lost") {
                        echo 'selected="selected"';
                    } ?>
                    value="Lost">Lost</option>
                  </select>
                  <!-- <input id="status" name="status" class="form-control form-control-sm rounded-0"  required="required" placeholder="e.g. Available, Defective, Lost, etc."/> -->
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-10">
                  <div class="form-group">
                    <label>Date Acquired</label>
                    <div class="input-group date" id="date_acquired" 
                        data-target-input="nearest">
                        <input type="text" value="<?php echo $date_acquired; ?>" id="acquired" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#date_acquired" name="date_acquired" required="required" data-toggle="datetimepicker" autocomplete="off"/>
                        <div class="input-group-append" 
                        data-target="#date_acquired" 
                        data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                  <label>Quantity</label>
                  <input type="number" value="<?php echo $quantity;?>" min="1" id="quantity" name="quantity" class="form-control form-control-sm rounded-0"  required="required"/>
                </div>

                <div class="col-lg-11 col-md-12 col-sm-12 col-xs-12">
                    <label>Remarks</label>
                    <textarea id="remarks" name="remarks" class="form-control form-control-sm rounded-0" placeholder="Please note something if necessary"><?php echo $row['remarks']; ?></textarea>
                </div>

                <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12">
                  <label>&nbsp;</label>
                  <button id="save_equipment" type="submit" id="submit" name="submit" 
                  class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
                </div>
              </div>
            </form>
          </div>
          <!-- /.card-body -->
          
        </div>
        <!-- /.card -->

      </section>



      <!-- /.content-wrapper -->
    </div>

    <?php include './config/footer.php';

    $message = '';
    if(isset($_GET['message'])) {
      $message = $_GET['message'];
    }
    ?>  
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <?php include './config/site_js_links.php'; ?>
  <?php include './config/data_tables_js.php'; ?>

  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/daterangepicker/daterangepicker.js"></script>
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

  <script>
    showMenuSelected("#mnu_medicines", "#mi_medicine_details");

    var message = '<?php echo $message;?>';

    if(message !== '') {
      showCustomMessage(message);
    }

    $(document).ready(function() {
        
        $('#date_acquired').datetimepicker({
          format: 'L'
        });
        
        $("form :input").blur(function() {
            var equipmentId = $("#equipment").val();
            var equipmentStatus = $("#status").val().trim();
            var dateAcquired = $("#acquired").val().trim();
        
            var parts = dateAcquired.split("/");
            var formattedDate = parts[2] + "-" + parts[0].padStart(2, "0") + "-" + parts[1].padStart(2, "0");

            $("#equipment").val(equipmentId);
            $("#status").val(equipmentStatus);
            //$("#acquired").val(formattedDate);
            
            if(equipmentStatus !== '') {
                $.ajax({
                url: "ajax/check_equipment_status.php",
                type: 'GET', 
                data: {
                    'equipment_id': equipmentId,
                    'equipment_status': equipmentStatus,
                    'date_acquired': formattedDate,
                    'update_id': <?php echo $equipmentDetailId; ?>
                },
                cache:false,
                async:false,
                success: function (count, status, xhr) {
                    if(count > 0) {
                    showCustomMessage("This equipment has already been stored. Please check inventory or the Trash.");
                    $("#save_equipment").attr("disabled", "disabled");
                    } else {
                    $("#save_equipment").removeAttr("disabled");
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    showCustomMessage(errorMessage);
                }
                });
            }

            });

    });


  </script>
</body>
</html>