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
                
      
        $stmtPatient1 = $con->prepare($query);
        $stmtPatient1->execute();
      
      } catch(PDOException $ex) {
        echo $ex->getMessage();
        echo $ex->getTraceAsString();
        exit;
      }
    
  } else if ($_GET['recover'] == "medicine") {
    $rec = "Medicines";
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
    $rec = "Medicine Details";
    $menuSelected = "#mi_trash_meddetails";

    try {
      $query = "SELECT `id`, `medicine_name`
                FROM `medicine_details`
                WHERE `is_del` = '1' order by `medicine_name` asc;";

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
  <title>Patients - Clinic's Patient Management System in PHP</title>

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
              <table id="all_patients" 
              class="table table-striped dataTable table-bordered dtr-inline" 
               role="grid" aria-describedby="all_patients_info">
              
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Student ID</th>
                    <th>Address</th>
                    <th>Birthdate</th>
                    <th>Phone Number</th>
                    <th>Gender</th>
                    <th>Recover</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 
                  $count = 0;
                  while($row =$stmtPatient1->fetch(PDO::FETCH_ASSOC)){
                    $count++;
                  ?>
                  <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row['patient_name'];?></td>
                    <td><?php echo $row['cnic'];?></td>
                    <td><?php echo $row['address'];?></td>
                    <td><?php echo $row['date_of_birth'];?></td>
                    <td><?php echo $row['phone_number'];?></td>
                    <td><?php echo $row['gender'];?></td>
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
                <?php
                  } else if ($rec == "Medicines") {
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
                    <th>Medicine Item</th>
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
              <?php
                }
              ?>
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
    
  });

  $(function () {
    $("#all_medicines").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      "buttons": ["colvis"]
    }).buttons().container().appendTo('#all_medicines_wrapper .col-md-6:eq(0)');
    
  });

   
</script>
</body>
</html>