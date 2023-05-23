<?php
include './config/connection.php';
include './common_service/common_functions.php';


$message = '';
if (isset($_POST['save_Patient'])) {

    $patientName = strtoupper(trim($_POST['patient_name']));
    $patientMName = strtoupper(trim($_POST['patient_mname']));
    $patientSName = strtoupper(trim($_POST['patient_sname']));

    $patientFullName = $patientSName.", ".$patientName.", ".$patientMName;

    $address = trim($_POST['address']);
    $cnic = trim($_POST['cnic']);
    
    $dateBirth = trim($_POST['date_of_birth']);
    $dateArr = explode("/", $dateBirth);
    
    $dateBirth = $dateArr[2].'-'.$dateArr[0].'-'.$dateArr[1];

    $phoneNumber = trim($_POST['phone_number']);

    $contactPerson = ucwords(strtolower(trim($_POST['contact_person'])));
    $relationship = ucwords(strtolower(trim($_POST['relationship'])));
    $contactPersonNo = trim($_POST['contact_person_no']);
    $address = ucwords(strtolower($address));

    $gender = $_POST['gender'];
    
  if ($patientFullName != '' && $address != '' && $cnic != '' && $dateBirth != '' && $phoneNumber != '' && $gender != '') {
      $query = "INSERT INTO `patients`(`patient_name`, `address`, `cnic`, `date_of_birth`, `phone_number`, `gender`, `contact_person`, `relationship`, `contact_person_no`, `is_del`)
                VALUES('$patientFullName', '$address', '$cnic', '$dateBirth', '$phoneNumber', '$gender', '$contactPerson', '$relationship', '$contactPersonNo', '0');";
    try {

      $con->beginTransaction();

      $stmtPatient = $con->prepare($query);
      $stmtPatient->execute();

      $con->commit();

      $message = 'Patient Added Successfully.';

    } catch(PDOException $ex) {
      $con->rollback();

      echo $ex->getMessage();
      echo $ex->getTraceAsString();
      exit;
    }
  }
  header("Location:congratulation.php?goto_page=patients.php&message=$message");
  exit;
}



try {

$query = "SELECT `id`, `patient_name`, `address`, `cnic`, date_format(`date_of_birth`, '%d %b %Y')
          AS `date_of_birth`, `phone_number`, `gender`
          FROM `patients`
          WHERE `is_del` = '0'
          ORDER BY `patient_name` ASC;";
          

  $stmtPatient1 = $con->prepare($query);
  $stmtPatient1->execute();

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
  <title>Patients  - SPCC Caloocan Clinic</title>

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
            <h1>Patients</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
     <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Add Patients</h3>
          
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>
        <div class="card-body">
          <form method="post">
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>First Name</label>
              <input type="text" id="patient_name" name="patient_name" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>Middle Name</label>
              <input type="text" id="patient_mname" name="patient_mname" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>Surname</label>
              <input type="text" id="patient_sname" name="patient_sname" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <br>
              <br>
              <br>
              <div class="col-lg-8 col-md-4 col-sm-4 col-xs-10">
                <label>Address</label> 
                <input type="text" id="address" name="address" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Student ID / Employee ID</label>
                <input type="text" id="cnic" name="cnic" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <div class="form-group">
                  <label>Date of Birth</label>
                    <div class="input-group date" 
                    id="date_of_birth" 
                    data-target-input="nearest">
                        <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#date_of_birth" name="date_of_birth" 
                        data-toggle="datetimepicker" autocomplete="off" />
                        <div class="input-group-append" 
                        data-target="#date_of_birth" 
                        data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>Gender</label>
                <select class="form-control form-control-sm rounded-0" id="gender" 
                name="gender">
                  <?php echo getGender();?>
                </select>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Contact Person</label>
                <input type="text" id="contact_person" name="contact_person" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Relationship</label>
                <input type="text" id="relationship" name="relationship" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Contact Person Phone Number</label>
                <input type="text" id="contact_person_no" name="contact_person_no" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
            </div>
              
              <div class="clearfix">&nbsp;</div>

              <div class="row">
                <div class="col-lg-11 col-md-10 col-sm-10 xs-hidden">&nbsp;</div>

              <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                <button type="submit" id="save_Patient" 
                name="save_Patient" class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
              </div>
            </div>
          </form>
        </div>
        
      </div>
      
    </section>

     <br/>
     <br/>
     <br/>

 <section class="content">
      <!-- Default box -->
      <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Total Patients</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
              <table id="all_patients" 
              class="table table-striped dataTable table-bordered dtr-inline" 
               role="grid" aria-describedby="all_patients_info">
              
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Address</th>
                    <th>Student ID / Employee ID</th>
                    <th>Birthdate</th>
                    <th>Contact</th>
                    <th>Gender</th>
                    <th>Action</th>
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
                    <td><?php
                      $string = $row['patient_name'];
                      $explodedArray = explode(', ', $string);
                      $resultArray = array_map('trim', $explodedArray);

                      echo $resultArray[0].", ".ucwords(strtolower($resultArray[1])).", ".ucwords(strtolower($resultArray[2]));
                    ?></td>
                    <td><?php echo $row['address'];?></td>
                    <td><?php echo $row['cnic'];?></td>
                    <td><?php echo $row['date_of_birth'];?></td>
                    <td><?php echo $row['phone_number'];?></td>
                    <td><?php echo $row['gender'];?></td>
                    <td class="text-center">
                      <a href="update_patient.php?id=<?php echo $row['id'];?>" class = "btn btn-primary btn-sm btn-flat">
                      <i class="fa fa-edit"></i>
                      </a>
                    </td>
                   
                  </tr>
                <?php
                }
                ?>
                </tbody>
              </table>
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
  showMenuSelected("#mnu_patients", "#mi_patients");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }

  $(document).ready(function() {
        
    $('#date_of_birth').datetimepicker({
        format: 'L',
        maxDate:new Date()
    });
        
    $("form :input").blur(function() {
      var patientName = $("#patient_name").val().trim();
      var patientMName = $("#patient_mname").val().trim();
      var patientSName = $("#patient_sname").val().trim();
      var patientContact = $("#phone_number").val().trim();
      var emergencyContact = $("#contact_person_no").val().trim();
      var studentID = $("#cnic").val().trim();

      $("#patient_name").val(patientName);
      $("#patient_mname").val(patientMName);
      $("#patient_sname").val(patientSName);
      $("#phone_number").val(patientContact);
      $("#contact_person_no").val(emergencyContact);
      $("#cnic").val(studentID);
           
      if ((studentID !== '' && !/^[a-zA-Z0-9]+$/.test(studentID))) {
        showCustomMessage("Invalid characters in Student ID / Employee ID field.");
        $("#save_Patient").attr("disabled", "disabled");
      } if (patientContact !== '' && /\D/.test(patientContact)) {
        showCustomMessage("Invalid characters in Phone Number field.");
        $("#save_Patient").attr("disabled", "disabled");
      } else if (emergencyContact !== '' && /\D/.test(emergencyContact)) {
        showCustomMessage("Invalid characters in Contact Person Phone Number field.");
        $("#save_Patient").attr("disabled", "disabled");
      } else {
        $.ajax({
          url: "ajax/check_patient.php",
          type: 'GET',
          data: {
            'cnic': studentID
          },
          cache:false,
          async:false,
          success: function (count, status, xhr) {
            if(count > 0) {
              showCustomMessage("This student ID is already existing! Please check records or the Trash.");
              $("#save_Patient").attr("disabled", "disabled");
            } else {
              $("#save_Patient").removeAttr("disabled");
            }
          },
          error: function (jqXhr, textStatus, errorMessage) {
            showCustomMessage(errorMessage);
          }
        });
      }

      if(patientName !== '' && patientMName !== '' && patientSName !== '') {
        $.ajax({
          url: "ajax/check_patient.php",
          type: 'GET',
          data: {
            'patient_name': patientName,
            'patient_mname': patientMName,
            'patient_sname': patientSName
          },
          cache:false,
          async:false,
          success: function (count, status, xhr) {
            if(count > 0) {
              showCustomMessage("This patient is already existing! Please check records or the Trash.");
              $("#save_Patient").attr("disabled", "disabled");
            } else {
              $("#save_Patient").removeAttr("disabled");
            }
          },
          error: function (jqXhr, textStatus, errorMessage) {
            showCustomMessage(errorMessage);
          }
        });
      }
    });
  });      
    
   $(function () {
    $("#all_patients").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#all_patients_wrapper .col-md-6:eq(0)');
    
  });

   
</script>
</body>
</html>