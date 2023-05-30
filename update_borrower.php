<?php
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';
if (isset($_POST['save_borrower'])) {
  
    $hiddenId = $_POST['hidden_id'];

    $borrowerName = ucwords(strtolower(trim($_POST['borrower_name'])));
    $borrowerMName = ucwords(strtolower(trim($_POST['borrower_mname'])));
    $borrowerSName = ucwords(strtolower(trim($_POST['borrower_sname'])));
    $position = ucwords(strtolower(trim($_POST['position'])));
    $borrower_id = trim($_POST['borrower_id']);
    $contact_no = trim($_POST['contact_no']);

    if ($borrowerName != '' && $borrowerSName != '' && $position != '' && $borrower_id != '' && $contact_no != '') {
        $query = "UPDATE `borrowers` 
                SET `fname` = '$borrowerName',
                    `mname` = '$borrowerMName', 
                    `lname` = '$borrowerSName',
                    `position` = '$position',
                    `borrower_id` = '$borrower_id',
                    `contact_no` = '$contact_no'
                WHERE `id` = $hiddenId;";
  try {

    $con->beginTransaction();

    $stmtBorrower = $con->prepare($query);
    $stmtBorrower->execute();

    $con->commit();

    $message = 'Borrower Info Updated Successfully.';

  } catch(PDOException $ex) {
    $con->rollback();

    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;
  }
}
  header("Location:congratulation.php?goto_page=borrowers.php&message=$message");
  exit;
}

try {
    $id = $_GET['id'];
    $query = "SELECT * FROM `borrowers` where `id` = $id;";

    $stmt = $con->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

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
  <title>Update Borrower Information - SPCC Caloocan Clinic</title>

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
            <h1>Borrower</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
     <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Update Borrower</h3>
          
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>
        <div class="card-body">
          <form method="post">
            <div class="row">
            <input type="hidden" name="hidden_id" value="<?php echo $row['id'];?>">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>First Name</label>
                <input type="text" value="<?php echo $row['fname'];?>" id="borrower_name" name="borrower_name" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Middle Name</label>
                <input type="text" value="<?php echo $row['mname'];?>" id="borrower_mname" name="borrower_mname" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Surname</label>
                <input type="text" value="<?php echo $row['lname'];?>" id="borrower_sname" name="borrower_sname" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Position</label>
                <input type="text" value="<?php echo $row['position'];?>" id="position" name="position" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Student ID / Employee ID</label>
                <input type="text" value="<?php echo $row['borrower_id'];?>" id="borrower_id" name="borrower_id" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Contact Number</label>
                <input type="text" value="<?php echo $row['contact_no'];?>" id="contact_no" name="contact_no" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
            </div>
              
              <div class="clearfix">&nbsp;</div>

              <div class="row">
                <div class="col-lg-11 col-md-10 col-sm-10 xs-hidden">&nbsp;</div>

              <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                <button type="submit" id="save_borrower" 
                name="save_borrower" class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
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

    showMenuSelected("#mnu_borrowers", "#mi_borrowers");

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
        
      $("form :input").blur(function() {
          var borrowerID_disabled = false;
          var name_disabled = false;
  
          var borrowerName = $("#borrower_name").val().trim();
          var borrowerMName = $("#borrower_mname").val().trim();
          var borrowerSName = $("#borrower_sname").val().trim();
          var borrowerID = $("#borrower_id").val().trim();
          var borrowerContact = $("#contact_no").val().trim();

          $("#borrower_name").val(borrowerName);
          $("#borrower_mname").val(borrowerMName);
          $("#borrower_sname").val(borrowerSName);
          $("#borrower_id").val(borrowerID);
          $("#contact_no").val(borrowerContact);
  
          if ((borrowerName !== '' && !/^[a-zA-Z]+$/.test(borrowerName)) || (borrowerMName !== '' && !/^[a-zA-Z]+$/.test(borrowerMName)) || (borrowerSName !== '' && !/^[a-zA-Z]+$/.test(borrowerSName))) {
              showCustomMessage("Invalid characters in Name fields.");
              $("#save_borrower").attr("disabled", "disabled");
              borrowerID_disabled = true;
              name_disabled = true;
          }
          
          if (borrowerID !== '' && !/^[a-zA-Z0-9]+$/.test(borrowerID)) {
              showCustomMessage("Invalid characters in Student ID / Employee ID field.");
              $("#save_borrower").attr("disabled", "disabled");
              borrowerID_disabled = true;
              name_disabled = true;
          }
          
          if (borrowerContact !== '' && /\D/.test(borrowerContact)) {
              showCustomMessage("Invalid characters in Contact Number field.");
              $("#save_borrower").attr("disabled", "disabled");
          } 
          
          if (!borrowerID_disabled) {
              $.ajax({
              url: "ajax/check_borrower.php",
              type: 'GET',
              data: {
                  'borrower_id': borrowerID,
                  'update_id': <?php echo $row['id']; ?>
              },
              cache:false,
              async:false,
              success: function (count, status, xhr) {
                  if(count > 0) {
                      showCustomMessage("This ID is already existing! Please check records or the Trash.");
                  $("#save_borrower").attr("disabled", "disabled");
                  } else {
                      $("#save_borrower").removeAttr("disabled");
                  }
              },
              error: function (jqXhr, textStatus, errorMessage) {
                  showCustomMessage(errorMessage);
              }
              });
          }
  
        if(!name_disabled) {
          $.ajax({
            url: "ajax/check_borrower.php",
            type: 'GET',
            data: {
              'borrower_name': borrowerName,
              'borrower_mname': borrowerMName,
              'borrower_sname': borrowerSName,
              'update_id': <?php echo $row['id']; ?>
            },
            cache:false,
            async:false,
            success: function (count, status, xhr) {
              if(count > 0) {
                showCustomMessage("This borrower is already existing! Please check records or the Trash.");
                $("#save_borrower").attr("disabled", "disabled");
              } else {
                $("#save_borrower").removeAttr("disabled");
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