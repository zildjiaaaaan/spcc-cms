<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';

if(isset($_POST['submit'])) {

  $medicineId = $_POST['medicine'];
  $medicineDetailId = $_POST['hidden_id'];
  $packing = $_POST['packing'];
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

$medicineId = $_GET['medicine_id'];
$medicineDetailId = $_GET['medicine_detail_id'];
$packing = $_GET['packing'];

$medicines = getUniqueMedicines($con, $medicineId);

try {
  $query = "SELECT date_format(`exp_date`, '%m/%d/%Y') AS `exp_date`, `quantity`
            FROM `medicine_details` where `id` = '$medicineDetailId';";
  
    $stmtMedDetails = $con->prepare($query);
    $stmtMedDetails->execute();
    $row = $stmtMedDetails->fetch(PDO::FETCH_ASSOC);
  
    $quantity = $row['quantity'];
    $exp_date = $row['exp_date'];

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
              value="<?php echo $medicineDetailId;?>" />

              <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                  <label>Select Medicine</label>
                  <select id="medicine" name="medicine" class="form-control form-control-sm rounded-0" required="required">
                    <?php echo $medicines;?>
                  </select>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                  <label>Unit</label>
                  <input id="packing" name="packing" class="form-control form-control-sm rounded-0" required="required" value="<?php echo $packing; ?>"/>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-10">
                  <div class="form-group">
                    <label>Expiration Date</label>
                    <?php
                        
                    ?>
                    <div class="input-group date" id="expiry" 
                        data-target-input="nearest">
                        <input type="text" id="exp_date" value="<?php echo $exp_date;?>" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#expiry" name="expiry" required="required" data-toggle="datetimepicker"/>
                        <div class="input-group-append" 
                        data-target="#expiry" 
                        data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                  <label>Quantity</label>
                  <input type="number" min="1" value="<?php echo $quantity?>" id="quantity" name="quantity" class="form-control form-control-sm rounded-0"  required="required"/>
                </div>

                <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12">
                  <label>&nbsp;</label>
                  <button id="save_medicine" type="submit" id="submit" name="submit" 
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
        
        $('#expiry').datetimepicker({
          format: 'L',
          minDate:new Date()
        });
        
        $("form :input").blur(function() {
          var medicineId = $("#medicine").val();
          var expiry = $("#exp_date").val().trim();
          var medicineUnit = $("#packing").val().trim();

          var parts = expiry.split("/");
          var formattedDate = parts[2] + "-" + parts[0].padStart(2, "0") + "-" + parts[1].padStart(2, "0");

          $("#medicine").val(medicineId);
          // $("#expiry").val(formattedDate);
          $("#packing").val(medicineUnit);
          
          if(medicineUnit !== '') {
            $.ajax({
              url: "ajax/check_medicine_unit.php",
              type: 'GET', 
              data: {
                'medicine_id': medicineId,
                'medicine_unit': medicineUnit,
                'exp_date': formattedDate,
                'update_id': <?php echo $medicineDetailId; ?>
              },
              cache:false,
              async:false,
              success: function (count, status, xhr) {
                if(count > 0) {
                  showCustomMessage("Duplicate entry! This item is already existing.");
                  $("#save_medicine").attr("disabled", "disabled");
                } else {
                  $("#save_medicine").removeAttr("disabled");
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