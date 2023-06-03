<?php
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';
if (isset($_POST['save_Patient'])) {
  
    $hiddenId = $_POST['hidden_id'];

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
if ($patientName != '' && $address != '' && 
  $cnic != '' && $dateBirth != '' && $phoneNumber != '' && $gender != '') {
      $query = "UPDATE `patients` 
                SET `patient_name` = '$patientFullName', 
                    `address` = '$address', 
                    `cnic` = '$cnic', 
                    `date_of_birth` = '$dateBirth', 
                    `phone_number` = '$phoneNumber', 
                    `gender` = '$gender', 
                    `contact_person` = '$contactPerson',
                    `relationship` = '$relationship',
                    `contact_person_no` = '$contactPersonNo'
                WHERE `id` = $hiddenId;";
  try {

    $con->beginTransaction();

    $stmtPatient = $con->prepare($query);
    $stmtPatient->execute();

    $con->commit();

    $message = 'Patient Updated Successfully.';

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
$id = $_GET['id'];
$query = "SELECT `id`, `patient_name`, `address`, `cnic`, date_format(`date_of_birth`, '%m/%d/%Y') as `date_of_birth`, `phone_number`, `gender`,
          `contact_person`, `relationship`, `contact_person_no`
          FROM `patients` where `id` = $id;";

  $stmtPatient1 = $con->prepare($query);
  $stmtPatient1->execute();
  $row = $stmtPatient1->fetch(PDO::FETCH_ASSOC);

  $gender = $row['gender'];
  $string = $row['patient_name'];
  $explodedArray = explode(', ', $string);
  $resultArray = array_map('trim', $explodedArray);

  $dob = $row['date_of_birth']; 
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
  <title>Update Patient Details - SPCC Caloocan Clinic</title>

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
          <h3 class="card-title">Update Patients</h3>
          
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>
        <div class="card-body">
          <form method="post">
            <input type="hidden" name="hidden_id" 
            value="<?php echo $row['id'];?>">
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>First Name</label>
              <input type="text" value="<?php echo $resultArray[1]; ?>" id="patient_name" name="patient_name" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>Middle Name</label>
              <input type="text" value="<?php echo $resultArray[2]; ?>" id="patient_mname" name="patient_mname" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>Surname</label>
              <input type="text" value="<?php echo $resultArray[0]; ?>" id="patient_sname" name="patient_sname" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <br>
              <br>
              <br>
              <div class="col-lg-8 col-md-4 col-sm-4 col-xs-10">
                <label>Address</label> 
                <input type="text" value="<?php echo $row['address']; ?>" id="address" name="address" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Student ID</label>
                <input type="text" value="<?php echo $row['cnic']; ?>" id="cnic" name="cnic" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <div class="form-group">
                  <label>Date of Birth</label>
                    <div class="input-group date" 
                    id="date_of_birth" 
                    data-target-input="nearest">
                        <input type="text" value="<?php echo $dob;?>" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#date_of_birth" name="date_of_birth" 
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
                <input type="text" value="<?php echo $row['phone_number'];?>" id="phone_number" name="phone_number" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>Gender</label>
                <!-- $gender -->

                <select class="form-control form-control-sm rounded-0" id="gender" 
                name="gender">
                 <?php echo getGender($gender);?>
                </select>
                
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Contact Person</label>
                <input type="text" value="<?php echo $row['contact_person']; ?>" id="contact_person" name="contact_person" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Relationship</label>
                <input type="text" value="<?php echo $row['relationship']; ?>" id="relationship" name="relationship" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Contact Person Phone Number</label>
                <input type="text" value="<?php echo $row['contact_person_no']; ?>" id="contact_person_no" name="contact_person_no" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
            </div>
              
            <div class="clearfix">&nbsp;</div>
            <div class="row">
              <div class="col-lg-11 col-md-10 col-sm-10">&nbsp;</div>
              <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
                <button type="submit" id="save_Patient" 
                name="save_Patient" class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
                <a href="del_patient.php?id=<?php echo $row['id'];?>" class = "btn btn-danger btn-sm btn-flat btn-block">
                  Delete
                </a>
              </div>
            </div>
          </form>
        </div>
        
      </div>
      
    </section>
     <br/>
     <br/>
     <br/>

 
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php 
 include './config/footer.php';

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
  showMenuSelected("#mnu_patients", "#mi_patients");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }
    
  $(document).ready(function() {

    $("#customSwitch1").on("change", function(){
      if($(this).prop("checked") == true){
          $("body").removeClass("dark-mode");
      } else {
          $("body").addClass("dark-mode");
      }
  });
      
    $('#date_of_birth').datetimepicker({
        format: 'L'
    });
        
    $("form :input").blur(function() {
      var studentid_disabled = false;
      var name_disabled = false;

      var patientName = $("#patient_name").val().trim();
      var patientMName = $("#patient_mname").val().trim();
      var patientSName = $("#patient_sname").val().trim();
      var contactPerson = $("#contact_person").val().trim();
      var patientContact = $("#phone_number").val().trim();
      var emergencyContact = $("#contact_person_no").val().trim();
      var studentID = $("#cnic").val().trim();

      $("#patient_name").val(patientName);
      $("#patient_mname").val(patientMName);
      $("#patient_sname").val(patientSName);
      $("#phone_number").val(patientContact);
      $("#contact_person_no").val(emergencyContact);
      $("#cnic").val(studentID);

      $patientNameValid = (patientName !== '' && !/^[a-zA-Z\s]+$/.test(patientName));
      $patientMNameValid = (patientMName !== '' && !/^[a-zA-Z\s]+$/.test(patientMName));
      $patientSnameValid = (patientSName !== '' && !/^[a-zA-Z\s]+$/.test(patientSName));
      $contactPersonValid = (contactPerson !== '' && !/^[a-zA-Z\s]+$/.test(contactPerson));

      if ($patientNameValid || $patientMNameValid || $patientSnameValid || $contactPersonValid) {
        showCustomMessage("Invalid characters in Name fields.");
        $("#save_Patient").attr("disabled", "disabled");
        studentid_disabled = true;
        name_disabled = true;
      }
           
      if ((studentID !== '' && !/^[a-zA-Z0-9]+$/.test(studentID))) {
        showCustomMessage("Invalid characters in Student ID / Employee ID field.");
        $("#save_Patient").attr("disabled", "disabled");
        studentid_disabled = true;
        name_disabled = true;
      }
      
      if (patientContact !== '' && /\D/.test(patientContact)) {
        showCustomMessage("Invalid characters in Phone Number field.");
        $("#save_Patient").attr("disabled", "disabled");
        studentid_disabled = true;
        name_disabled = true;
      }
      
      if (emergencyContact !== '' && /\D/.test(emergencyContact)) {
        showCustomMessage("Invalid characters in Contact Person Phone Number field.");
        $("#save_Patient").attr("disabled", "disabled");
        studentid_disabled = true;
        name_disabled = true;
      }
      
      if(!studentid_disabled) {
        $.ajax({
          url: "ajax/check_patient.php",
          type: 'GET',
          data: {
            'cnic': studentID,
            'update_id': <?php echo $row['id']; ?>
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
      if(!name_disabled) {
        $.ajax({
          url: "ajax/check_patient.php",
          type: 'GET',
          data: {
            'patient_name': patientName,
            'patient_mname': patientMName,
            'patient_sname': patientSName,
            'update_id': <?php echo $row['id']; ?>
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