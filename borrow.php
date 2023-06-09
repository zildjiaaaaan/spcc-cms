<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';

if (isset($_POST['submit'])) {

  $equipmentDetailsIds = $_POST['equipmentDetailsIds'];
  $equipmentIds = $_POST['equipmentIds'];
  $borrowerIds = $_POST['borrowerIds'];
  $unavailableUntils = $_POST['unavailableUntils'];
  $quantities = $_POST['quantities'];
  $current_remarks = $_POST['current_remarks'];
  $remarks = $_POST['remarks'];
  $hasRecords = $_POST['hasRecords'];

  $size = sizeof($equipmentDetailsIds);

  // var_dump($equipmentDetailsIds);
  // echo "<br />";
  // var_dump($equipmentIds);
  // echo "<br />";
  // var_dump($borrowerIds);
  // echo "<br />";
  // var_dump($unavailableUntils);
  // echo "<br />";
  // var_dump($quantities);
  // echo "<br />";
  // var_dump($current_remarks);
  // echo "<br />";
  // var_dump($remarks);
  // echo "<br />";
  // var_dump($hasRecords);
  // echo "<br />";

  $text = '';
  $isSuccess = false;

  for ($i=0; $i < $size; $i++) { 
    
    $equipmentDetailsId = $equipmentDetailsIds[$i];
    $equipmentId = $equipmentIds[$i];
    $borrowerId = $borrowerIds[$i];
    $unavailableSince = date('Y-m-d');
    $unavailableUntil = $unavailableUntils[$i];
    $quantity = $quantities[$i];
    $current_remark = $current_remarks[$i];
    $remark = $remarks[$i];
    if (empty($remark)) {
      $remark = $current_remark;
    }
    $hasRecord = $hasRecords[$i];

    $q_select_qty = "SELECT `quantity` AS `max`, `img_name` FROM `equipment_details` WHERE `id` = '$equipmentDetailsId';";
    $stmt_select_qty = $con->prepare($q_select_qty);
    $stmt_select_qty->execute();
    $r = $stmt_select_qty->fetch(PDO::FETCH_ASSOC);

    $diff = $r['max'] - $quantity;
    $cur_img_name = $r['img_name'];

    $q_update_active = '';
    $q_borrowed = '';
    $q_new_borrower = '';
    $newBorrower = true;
    $newEquipment = false;
    $no_q_borrowed = false;

    if ($diff > 0) {
      $q_update_active = "UPDATE `equipment_details`
        SET `quantity` = '$diff'
        WHERE `id` = '$equipmentDetailsId'
      ;";

      if ($hasRecord == '') {
        $q_borrowed = "INSERT INTO `equipment_details`
          (`equipment_id`, `status`, `state`, `unavailable_since`,
          `unavailable_until`, `quantity`, `remarks`, `img_name`, `is_del`)
          VALUES ('$equipmentId', 'Unavailable', 'Borrowed', '$unavailableSince',
          '$unavailableUntil', '$quantity', '$remark', '$cur_img_name', '0')
        ;";
        $newEquipment = true;
      } else {
        $q_borrowed = "UPDATE `equipment_details`
          JOIN `borrowed` ON `equipment_details`.`id` = `borrowed`.`equipment_details_id`
          SET `quantity` = `quantity` + $quantity
          WHERE `borrowed`.`borrower_id` = '$borrowerId'
        ;";
        $newBorrower = false;
      }

    } else {
      if ($hasRecord == '') {
        $q_update_active = "UPDATE `equipment_details`
          SET `status` = 'Unavailable', `state` = 'Borrowed',
          `unavailable_since` = '$unavailableSince',
          `unavailable_until` = '$unavailableUntil',
          `remarks` = '$remark'
          WHERE `id` = '$equipmentDetailsId'
        ;";

        $no_q_borrowed = true;       

      } else {
        $q_update_active = "UPDATE `equipment_details`
            SET `quantity` = '0'
            WHERE `id` = '$equipmentDetailsId'
          ;";

        $q_borrowed = "UPDATE `equipment_details`
            JOIN `borrowed` ON `equipment_details`.`id` = `borrowed`.`equipment_details_id`
            SET `quantity` = `quantity` + $quantity
            WHERE `borrowed`.`borrower_id` = '$borrowerId'
          ;";
        $newBorrower = false;
      }
    }

    // $text .= $q_update_active."\n".$q_borrowed."\n".$q_new_borrower."\n----------------\n";

    try {
      $con->beginTransaction();

      $stmt_update_active = $con->prepare($q_update_active);
      $stmt_update_active->execute();

      if (!$no_q_borrowed) {
        $stmt_borrowed = $con->prepare($q_borrowed);
        $stmt_borrowed->execute();
        $equipmentDetailsId = ($newEquipment) ? $con->lastInsertId() : $equipmentDetailsId;
      }

      if ($newBorrower) {
        $q_new_borrower = "INSERT INTO `borrowed` (
          `borrower_id`, `equipment_details_id`, `is_returned`,
          `borrowed_date`, `returned_date`
          ) VALUES ('$borrowerId', '$equipmentDetailsId', '0', '', '')
        ;";

        $stmt_new_borrower = $con->prepare($q_new_borrower);
        $stmt_new_borrower->execute();
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

  if ($isSuccess) {
    if ($size > 1) {
      $message = "Equipment Units have been successfully borrowed.";
    } else {
      $message = "Equipment Unit has been successfully borrowed.";
    }
  } else {
    $message = "Failed to borrow equipment. Please try again.";
  }

  // $file = 'output.txt';
  // file_put_contents($file, $text);
  header("Location: borrow.php?message=$message");
  exit;
}

$equipments = getActiveEquipments($con);
$borrowers = getUniqueBorrowers($con);

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php' ?>

 <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
 <title>Borrow Equipment - SPCC Caloocan Clinic</title>

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
              <h1>Borrow Equipment</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Borrow Equipment</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <!-- best practices-->
            <form method="post">
              <div class="row d-flex justify-content-center">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 select-select2">
                  <label>Select Borrower</label>
                  <select id="borrower" name="borrower" class="form-control form-control-sm rounded-0 select2">
                    <?php echo $borrowers;?>
                  </select>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 select-select2">
                  <label>Select Equipment</label>
                  <select id="equipment" name="equipment" class="form-control form-control-sm rounded-0 select2">
                    <?php echo $equipments;?>
                  </select>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-10">
                  <div class="form-group">
                    <label>Return Date</label>
                    <div class="input-group date" id="unavailable_until" data-target-input="nearest">
                        <input type="text" id="unavailableUntil" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#unavailable_until" name="unavailable_until" data-toggle="datetimepicker" autocomplete="off"/>
                        <div class="input-group-append" 
                        data-target="#unavailable_until" 
                        data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                  <label>Quantity</label>
                  <input type="number" id="quantity" name="quantity" class="form-control form-control-sm rounded-0" min="1" >
                </div>
              
              </div>

              <div class="row d-flex justify-content-center">
                
                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                  <label><i class="fa fa-info-circle"></i> Current Remarks</label>
                  <textarea id="current_remarks" name="current_remarks" class="form-control form-control-sm rounded-0 remarks" placeholder="Some remarks" disabled style="cursor: not-allowed"></textarea>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <label>Add New Remarks</label>
                  <textarea id="new_remarks" name="new_remarks" class="form-control form-control-sm rounded-0 remarks" placeholder='e.g. "Currently borrowed to use for a healthcare event"'></textarea>
                </div>

                <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12">
                  <label>&nbsp;</label>
                  <button type="button" id="add_to_list" class="btn btn-primary btn-sm btn-flat btn-block">
                    <i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>

              <div class="clearfix">&nbsp;</div>

    <div class="col-md-12"><hr /></div>
    <div class="clearfix">&nbsp;</div>
    <div class="row table-responsive">
      <table id="equipment_list" class="table table-striped table-bordered">
        <colgroup>
          <col width="2%">
          <col width="25%">
          <col width="5%">
          <col width="10%">
          <col width="20%">
          <col width="35%">
          <col width="3%">
        </colgroup>
        <thead class="bg-primary">
          <tr>
            <th>#</th>
            <th>Equipment</th>
            <th>Qty</th>
            <th>Return Date</th>
            <th>Borrower</th>
            <th>New Remarks</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody id="current_equipment_list">

        </tbody>
      </table>
    </div>
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
    <p><i>Note: Equipment with <span class="text-warning">this background</span> has already been borrowed with identical details. Instead of creating a new record, the quantity will be increased. You can check it in <a href="borrower_history.php" target="_blank">Borrower History</a>.</i></p>

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
  showMenuSelected("", "#mi_borrow");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }
  
</script>
<script src="dist/js/borrow.js"></script>
</body>
</html>