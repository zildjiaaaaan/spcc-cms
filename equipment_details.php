<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';

if(isset($_POST['submit'])) {

  $equipmentIds = $_POST['equipmentIds'];
  $statuses = $_POST['statuses'];
  $states = $_POST['states'];
  $quantities = $_POST['quantities'];
  $remarks = $_POST['remarks'];
  $borrowerIds = $_POST['borrowerIds'];
  $unavailableSinces = $_POST['unavailableSinces'];
  $unavailableUntils = $_POST['unavailableUntils'];

  // var_dump($equipmentIds);
  // echo "<br />";
  // var_dump($statuses);
  // echo "<br />";
  // var_dump($states);
  // echo "<br />";
  // var_dump($quantities);
  // echo "<br />";
  // var_dump($remarks);
  // echo "<br />";
  // var_dump($borrowerIds);
  // echo "<br />";
  // var_dump($unavailableSinces);
  // echo "<br />";
  // var_dump($unavailableUntils);
  // echo "<br />";

  $size = sizeof($equipmentIds);

  //iterate insert query $size times to insert all the equipment details
  $isSuccess = false;
  for ($i=0; $i < $size; $i++) { 

    $equipmentId = $equipmentIds[$i];
    $status = $statuses[$i];
    $state = $states[$i];
    $quantity = $quantities[$i];
    $remark = $remarks[$i];
    $borrowerId = $borrowerIds[$i];
    $unavailableSince = !empty($unavailableSinces[$i]) ? "'" . $unavailableSinces[$i] . "'" : "NULL";
    $unavailableUntil = !empty($unavailableUntils[$i]) ? "'" . $unavailableUntils[$i] . "'" : "NULL";

    $q_equipment_details = "INSERT INTO `equipment_details` (
        `equipment_id`, `status`, `state`, `unavailable_since`,
        `unavailable_until`, `quantity`, `remarks`, `img_name`, `is_del`
      ) VALUES (
        '$equipmentId', '$status', '$state',
        $unavailableSince, $unavailableUntil, '$quantity',
        '$remark', 'none.jpeg', '0'
      );";

    $q_select_equipment = "SELECT * FROM `equipments` WHERE `id` = '$equipmentId';";

    try {
      $con->beginTransaction();

      $stmt_equipment_details = $con->prepare($q_equipment_details);
      $stmt_equipment_details->execute();
      $lastInsertId = $con->lastInsertId();

      $stmt_select_equipment = $con->prepare($q_select_equipment);
      $stmt_select_equipment->execute();
      $rowEquipment = $stmt_select_equipment->fetch(PDO::FETCH_ASSOC);
      $total_qty = (is_null($rowEquipment['total_qty'])) ? $quantity : $rowEquipment['total_qty'] + $quantity;

      $q_update_totalqty = "UPDATE `equipments` SET `total_qty` = '$total_qty' WHERE `id` = '$equipmentId';";
      $stmt_update_totalqty = $con->prepare($q_update_totalqty);
      $stmt_update_totalqty->execute();

      if ($borrowerId != '') {
        $q_borrowed = "INSERT INTO `borrowed` (`borrower_id`, `equipment_details_id`) VALUES ('$borrowerId', '$lastInsertId');";
        
        $stmt_borrowed = $con->prepare($q_borrowed);
        $stmt_borrowed->execute();
      }

      $con->commit();
      $isSuccess = true;

    } catch (PDOException $ex) {
      $con->rollback();
      echo $ex->getTraceAsString();
      echo $ex->getMessage();
      exit;
    }
  }

  $message = ($isSuccess) ? "Equipment Unit Successfully Added." : "Failed to Add Equipment Details.";

  header("Location: equipment_details.php?message=$message");
  exit;

}

$equipments = getUniqueEquipments($con);
$borrowers = getUniqueBorrowers($con);

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php' ?>

 <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
 <title>Equipment Units - SPCC Caloocan Clinic</title>

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
              <h1>Clinic Equipment Units</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Add Equipment Unit</h3>

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
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 select-select2">
                  <label>Equipment Types</label>
                  <select id="equipment" name="equipment" class="form-control form-control-sm rounded-0 select2">
                    <?php echo $equipments;?>
                  </select>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                  <label>Status</label>
                  <select id="status" name="status" class="form-control form-control-sm rounded-0">
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
                  <label>Quantity</label>
                  <input type="number" id="quantity" name="quantity" class="form-control form-control-sm rounded-0" min="1" placeholder="Minimum of 1">
                </div>

                <div class="clearfix unavailable">&nbsp;</div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-10 unavailable">
                  <div class="form-group">
                    <label>Unavailable Since</label>
                    <div class="input-group date" id="unavailable_since" data-target-input="nearest">
                        <input type="text" value="<?php echo date("m/d/Y"); ?>" id="unavailableSince" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#unavailable_since" name="unavailable_since" data-toggle="datetimepicker" autocomplete="off"/>
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

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 unavailable borrower select-select2">
                  <label>Borrower</label>
                  <select id="borrower" name="borrower" class="form-control form-control-sm rounded-0 select2">
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
            <th>Equipment Type</th>
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

<?php include './config/site_js_links.php';?>

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
  
</script>
<script src="dist/js/equipment_details.js"></script>
</body>
</html>