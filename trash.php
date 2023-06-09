<?php
include './config/connection.php';
include './common_service/common_functions.php';

$menuSelected = '';
$rec = '';

if (isset($_GET['recover'])) {
  if ($_GET['recover'] == "patient") {
    $rec = "Patients";
    $menuSelected = "#mi_trash_patient";

    try {

      $query = "SELECT `id`, `patient_name`, `address`, `cnic`, date_format(`date_of_birth`, '%d %b %Y')
                AS `date_of_birth`, `phone_number`, `gender`
                FROM `patients`
                WHERE `is_del` = '1'
                ORDER BY `patient_name` ASC;";
                
      
        $stmt = $con->prepare($query);
        $stmt->execute();
      
      } catch(PDOException $ex) {
        echo $ex->getMessage();
        echo $ex->getTraceAsString();
        exit;
      }
    
  } else if ($_GET['recover'] == "medicine") {
    $rec = "Medicine Brands";
    $menuSelected = "#mi_trash_med";

    try {
      $query = "select `id`, `medicine_name`, `medicine_brand` from `medicines` where `is_del` = '1' order by `medicine_name` asc;";
      $stmt = $con->prepare($query);
      $stmt->execute();
    
    } catch(PDOException $ex) {
      echo $ex->getMessage();
      echo $e->getTraceAsString();
      exit;  
    }
  } else if ($_GET['recover'] == "medicine_details") {
    $rec = "Medicine Items";
    $menuSelected = "#mi_trash_meddetails";

    try {
      $query = "SELECT `m`.`medicine_name`, `m`.`medicine_brand`, `md`.`id`, `md`.`packing`,  `md`.`medicine_id`, `md`.`exp_date`, `md`.`quantity`
                FROM `medicines` as `m`, `medicine_details` as `md` 
                WHERE `m`.`id` = `md`.`medicine_id`
                  AND `md`.`is_del` = '1'
                ORDER BY `m`.`id` ASC, `md`.`id` ASC;";

      $stmt = $con->prepare($query);
      $stmt->execute();
    
    } catch(PDOException $ex) {
      echo $ex->getMessage();
      echo $e->getTraceAsString();
      exit;  
    }
  } else if ($_GET['recover'] == "equipments") {
    $rec = "Equipment Types";
    $menuSelected = "#mi_trash_equipments";

    try {
      $query = "select * from `equipments` where `is_del` = '1' order by `equipment` asc;";
      $stmt = $con->prepare($query);
      $stmt->execute();
    
    } catch(PDOException $ex) {
      echo $ex->getMessage();
      echo $e->getTraceAsString();
      exit;  
    }
  } else if ($_GET['recover'] == "equipment_inventory") {
    $rec = "Equipment Unit Inventory";
    $menuSelected = "#mi_trash_equipmentinventory";

    try {
      $query = "SELECT `ed`.*, `e`.`equipment`, `e`.`brand`
      FROM `equipment_details` AS `ed`, `equipments` AS `e`
      WHERE `ed`.`equipment_id` = `e`.`id`
        AND `ed`.`is_del` = '1';";

      $stmt = $con->prepare($query);
      $stmt->execute();
    
    } catch(PDOException $ex) {
      echo $ex->getMessage();
      echo $e->getTraceAsString();
      exit;  
    }
  } else if ($_GET['recover'] == "borrower") {
    $rec = "Borrowers Information";
    $menuSelected = "#mi_trash_borrower";

    try {
      $query = "SELECT * FROM `borrowers` WHERE `is_del` = '1' ORDER BY `lname` ASC;";

      $stmt = $con->prepare($query);
      $stmt->execute();
    
    } catch(PDOException $ex) {
      echo $ex->getMessage();
      echo $e->getTraceAsString();
      exit;  
    }
  }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php';?>

 <?php include './config/data_tables_css.php';?>

  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <title>Recover <?php echo $rec;?> - SPCC Caloocan Clinic</title>

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
            <h1>Trash</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
 <section class="content">
      <!-- Default box -->
      <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Recover <?php echo $rec;?></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
              <?php
                if ($rec == "Patients") {
              ?>
<!------------------------------------------------------------------ PATIENTS ---------------------------------------------------------------->
              <table id="all_patients" class="table table-striped dataTable table-bordered dtr-inline" role="grid" aria-describedby="all_patients_info">
                <colgroup>
                    <col width="1%">
                    <col width="20%">
                    <col width="8%">
                    <col width="35%">
                    <col width="8%">
                    <col width="8%">
                    <col width="5%">
                  </colgroup>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Student ID</th>
                    <th>Address</th>
                    <th>Birthdate</th>
                    <th>Phone Number</th>
                    <th>Recover</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 
                  $count = 0;
                  while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
                    $count++;
                    $patient_id = $row['id'];

                    $q_medCount = "SELECT COUNT(*) AS `med_count` FROM `patient_medication_history`
                      WHERE `patient_visit_id` IN (
                        SELECT `id` FROM `patient_visits` WHERE `patient_id` = '$patient_id'
                      )                  
                    ;";
                    $stmt_medCount = $con->prepare($q_medCount);
                    $stmt_medCount->execute();
                    $r = $stmt_medCount->fetch(PDO::FETCH_ASSOC);
                    if ($r['med_count'] > 1) {
                      $medCount = "This patient have ".$r['med_count']." medication histories.";
                    } else {
                      $medCount = "This patient has ".$r['med_count']." medication history.";
                    }

                    $popover = "patient_history.php?search=is_deleted&tag=".$patient_id;

                  ?>
                  <tr>
                    <td><?php echo $count; ?></td>
                    <td><a id="myPopover" data-toggle="popover" data-content="<a href='<?php echo $popover ?>' target='_blank'><?php echo $medCount; ?></a>" data-placement="top" style="cursor: pointer;">
                      <?php echo $row['patient_name'];?>
                    </a></td>
                    <td><?php echo $row['cnic'];?></td>
                    <td><?php echo $row['address'];?></td>
                    <td><?php echo $row['date_of_birth'];?></td>
                    <td><?php echo $row['phone_number'];?></td>
                    <td class="text-center">
                      <a href="recover.php?patient_id=<?php echo $row['id'];?>" class = "btn btn-success btn-sm btn-flat">
                      <i class="fa fa-recycle"></i>
                      </a>
                    </td>
                   
                  </tr>
                <?php
                }
                ?>
                </tbody>
              </table>
<!------------------------------------------------------------------ MEDICINE ---------------------------------------------------------------->
                <?php
                  } else if ($rec == "Medicine Brands") {
                ?>
                <table id="all_medicines" class="table table-striped dataTable table-bordered dtr-inline" role="grid" aria-describedby="all_medicines_info">
                <colgroup>
                  <col width="10%">
                  <col width="80%">
                  <col width="10%">
                </colgroup>

                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th>Medicine Brand</th>
                    <th class="text-center">Recover</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 
                    $serial = 0;
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $serial++;
                  ?>
                  <tr>
                    <td class="text-center"><?php echo $serial;?></td>
                    <td><?php echo strtoupper($row['medicine_name'])." — ".$row['medicine_brand'];?></td>
                    <td class="text-center">
                      <a href="recover.php?med_id=<?php echo $row['id'];?>" class = "btn btn-success btn-sm btn-flat">
                        <i class="fa fa-recycle"></i>
                      </a>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
<!------------------------------------------------------------------ MEDICINE DETAILS ---------------------------------------------------------------->
              <?php
                  } else if ($rec == "Medicine Items") {
              ?>
              <table id="all_med_details" class="table table-striped dataTable table-bordered dtr-inline" role="grid" aria-describedby="all_med_details_info">
              
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Medicine Brand</th>
                    <th>Unit Type</th>
                    <th>Expiration Date</th>
                    <th>Recover</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 
                  $count = 0;
                  while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
                    $count++;
                  ?>
                  <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo strtoupper($row['medicine_name'])." — ".$row['medicine_brand'];?></td>
                    <td><?php echo $row['packing'];?></td>
                    <td><?php echo $row['exp_date'];?></td>
                    <td class="text-center">
                      <a href="recover.php?meddetails_id=<?php echo $row['id'];?>&med_id=<?php echo $row['medicine_id']; ?>" class = "btn btn-success btn-sm btn-flat">
                      <i class="fa fa-recycle"></i>
                      </a>
                    </td>
                   
                  </tr>
                <?php
                }
                ?>
                </tbody>
              </table>
<!------------------------------------------------------------------ EQUIPMENTS ---------------------------------------------------------------->
              <?php
                } else if ($rec == "Equipment Types") {          
              ?>
              <table id="all_equipments" class="table table-striped dataTable table-bordered dtr-inline" role="grid" aria-describedby="all_equipments_info">
                <colgroup>
                  <col width="5%">
                  <col width="30%">
                  <col width="15%">
                  <col width="15%">
                  <col width="10%">
                  <col width="5%">
                </colgroup>

                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th>Equipment</th>
                    <th>Equipment Brand</th>
                    <th>Date Acquired</th>
                    <th>Total Quantity</th>
                    <th class="text-center">Recover</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 
                    $serial = 0;
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $serial++;
                  ?>
                  <tr>
                    <td class="text-center"><?php echo $serial;?></td>
                    <td><?php echo $row['equipment'];?></td>
                    <td><?php echo $row['brand'];?></td>
                    <td><?php echo $row['date_acquired'];?></td>
                    <td><?php echo $row['total_qty'];?></td>
                    <td class="text-center">
                      <a href="recover.php?equipment_id=<?php echo $row['id'];?>" class = "btn btn-success btn-sm btn-flat">
                        <i class="fa fa-recycle"></i>
                      </a>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
<!------------------------------------------------------------------ EQUIPMENTS DETAILS ---------------------------------------------------------------->
              <?php
                } else if ($rec == "Equipment Unit Inventory") {
              ?>
              <table id="all_equipment_details" class="table table-striped dataTable table-bordered dtr-inline" role="grid" aria-describedby="all_equipment_details_info">

              <colgroup>
                <col width="2%">
                <col width="20%">
                <col width="10%">
                <col width="10%">
                <col width="5%">
                <col width="40%">
                <col width="5%">
              </colgroup>
              
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th>Equipment Type</th>
                  <th>Status</th>
                  <th>State</th>
                  <th>Qty</th>
                  <th>Remarks</th>
                  <th class="text-center">Recover</th>
                </tr>
              </thead>

              <tbody>
                <?php 
                $count = 0;
                while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
                  $count++;
                ?>
                <tr>
                  <td class="text-center"><?php echo $count; ?></td>
                  <td><?php echo $row['equipment']." — ".strtoupper($row['brand']);?></td>
                  <td><?php echo $row['status'];?></a></td>
                  <td><?php echo $row['state'];?></a></td>
                  <td><?php echo $row['quantity'];?></td>
                  <td><?php echo $row['remarks'];?></td>
                  
                  <td class="text-center">
                    <a href="recover.php?equipmentdetails_id=<?php echo $row['id'];?>&equipment_id=<?php echo $row['equipment_id'];?>&qty=<?php echo $row['quantity'];?>" class = "btn btn-success btn-sm btn-flat">
                    <i class="fa fa-recycle"></i>
                    </a>
                  </td>
                  
                </tr>
              <?php
              }
              ?>
              </tbody>
            </table>

<!------------------------------------------------------------------ BORROWERS ---------------------------------------------------------------->
              <?php
                } else if ($rec == "Borrowers Information") {
              ?>
              <table id="all_borrowers" class="table table-striped dataTable table-bordered dtr-inline" role="grid" aria-describedby="all_borrowers_info">

              <colgroup>
                <col width="2%">
                <col width="35%">
                <col width="25%">
                <col width="15%">
                <col width="15%">
                <col width="5%">
              </colgroup>
              
              <thead>
                <tr>
                  <th>#</th>
                  <th>Borrower</th>
                  <th>Position</th>
                  <th>Student ID / Employee ID</th>
                  <th>Contact</th>
                  <th>Recover</th>
                </tr>
              </thead>

              <tbody>
                <?php 
                $count = 0;
                while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
                  $count++;
                ?>
                <tr>
                  <td><?php echo $count; ?></td>
                  <td><?php
                      $fullname = strtoupper($row['lname']).", ".ucwords(strtolower($row['fname'])).", ".ucwords(strtolower($row['mname']));
                      echo $fullname;
                  ?></td>
                  <td><?php echo $row['position'];?></td>
                  <td><?php echo $row['borrower_id'];?></td>
                  <td><?php echo $row['contact_no'];?></td>
                  <td class="text-center">
                    <a href="recover.php?borrower_id=<?php echo $row['id'];?>" class = "btn btn-success btn-sm btn-flat">
                    <i class="fa fa-recycle"></i>
                    </a>
                  </td>
                  
                </tr>
              <?php
              }
              ?>
              </tbody>
            </table>
            <?php } ?>

            </div>
        </div>
     
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

   
    </section>
  </div>
    <!-- /.content -->
  
  <!-- /.content-wrapper -->
<?php 
 include './config/footer.php';

  $message = '';
  if(isset($_GET['message'])) {
    $message = $_GET['message'];
  }
?>  
  <!-- /.control-sidebar -->


<?php include './config/site_js_links.php'; ?>
<?php include './config/data_tables_js.php'; ?>


<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
  showMenuSelected("#mnu_trash", "<?php echo $menuSelected;?>");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }
  $('#date_of_birth').datetimepicker({
    format: 'L'
  });
      
    
   $(function () {

    $("#all_patients").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      "buttons": ["colvis"]
    }).buttons().container().appendTo('#all_patients_wrapper .col-md-6:eq(0)');

    //$('#myPopover').popover();

    // popover demo
    $("[data-toggle=popover]")
    .popover({html:true})
    
  });

  $(function () {
    $("#all_medicines").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["colvis"]
    }).buttons().container().appendTo('#all_medicines_wrapper .col-md-6:eq(0)');
    
  });

  $(function () {
    $("#all_med_details").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["colvis"]
    }).buttons().container().appendTo('#all_med_details_wrapper .col-md-6:eq(0)');
    
  });

  $(function () {
    $("#all_equipments").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["colvis"]
    }).buttons().container().appendTo('#all_equipments_wrapper .col-md-6:eq(0)');
    
  });

  $(function () {
    $("#all_equipment_details").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["colvis"]
    }).buttons().container().appendTo('#all_equipment_details_wrapper .col-md-6:eq(0)');
    
  });

  $(function () {
    $("#all_borrowers").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["colvis"]
    }).buttons().container().appendTo('#all_borrowers_wrapper .col-md-6:eq(0)');
    
  });
   
</script>
</body>
</html>