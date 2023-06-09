<?php
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';
if (isset($_POST['save_borrower'])) {

    $borrowerName = ucwords(strtolower(trim($_POST['borrower_name'])));
    $borrowerMName = ucwords(strtolower(trim($_POST['borrower_mname'])));
    $borrowerSName = ucwords(strtolower(trim($_POST['borrower_sname'])));
    $position = ucwords(strtolower(trim($_POST['position'])));
    $borrower_id = trim($_POST['borrower_id']);
    $contact_no = trim($_POST['contact_no']);

    // Check if the item already exists
    $insert = true;
    $query = "SELECT COUNT(*) AS `duplicate` FROM `borrowers`
      WHERE `borrower_id` = '$borrower_id'
        OR (
          `fname` = '$borrowerName'
          AND `mname` = '$borrowerMName'
          AND `lname` = '$borrowerSName'
        )
    ;";

    $stmtBorrowers = $con->prepare($query);
    $stmtBorrowers->execute();
    $row = $stmtBorrowers->fetch(PDO::FETCH_ASSOC);

    if ($row['duplicate'] > 0) {
      $insert = false;
    }
    
  if ($borrowerName != '' && $borrowerSName != '' && $position != '' && $borrower_id != '' && $contact_no != '' && $insert) {
      $query = "INSERT INTO `borrowers`(`fname`, `mname`, `lname`, `position`, `borrower_id`, `contact_no`, `is_del`)
                VALUES('$borrowerName', '$borrowerMName', '$borrowerSName', '$position', '$borrower_id', '$contact_no', '0');";
    try {

      $con->beginTransaction();

      $stmtBorrower = $con->prepare($query);
      $stmtBorrower->execute();

      $con->commit();

      $message = 'Borrower Added Successfully.';

    } catch(PDOException $ex) {
      $con->rollback();

      echo $ex->getMessage();
      echo $ex->getTraceAsString();
      exit;
    }
  }

  if (!$insert) {
    $message = 'This borrower is already existing! Please check records or the Trash.';
  }

  header("Location:congratulation.php?goto_page=borrowers.php&message=$message");
  exit;
}

try {

$query = "SELECT *
          FROM `borrowers`
          WHERE `is_del` = '0'
          ORDER BY `lname` ASC;";
          

  $stmt = $con->prepare($query);
  $stmt->execute();

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
  <title>Borrowers  - SPCC Caloocan Clinic</title>
  <style>
    #a-Borrowed {
      cursor: not-allowed;
    }
  </style>
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
            <h1>Borrowers</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
     <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Add Borrower</h3>
          
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
                <input type="text" id="borrower_name" name="borrower_name" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Middle Name</label>
                <input type="text" id="borrower_mname" name="borrower_mname" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Surname</label>
                <input type="text" id="borrower_sname" name="borrower_sname" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Position</label>
                <input type="text" id="position" name="position" required="required" class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Student ID / Employee ID</label>
                <input type="text" id="borrower_id" name="borrower_id" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Contact Number</label>
                <input type="text" id="contact_no" name="contact_no" required="required" class="form-control form-control-sm rounded-0"/>
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
                    <th>Borrower</th>
                    <th>Position</th>
                    <th>Student ID / Employee ID</th>
                    <th>Contact</th>
                    <th>Action</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 
                  $count = 0;
                  while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
                    $id = $row['id'];
                    $count++;
                    $q_check = "SELECT COUNT(*) AS `borrowed` FROM `borrowed` 
                        WHERE `borrower_id` = '$id' AND `is_returned` = '0'
                      ;";
                    $stmt_check = $con->prepare($q_check);
                    $stmt_check->execute();
                    $row_check = $stmt_check->fetch(PDO::FETCH_ASSOC);
                    $isBorrowed = $row_check['borrowed'] > 0;

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
                      <a href="update_borrower.php?id=<?php echo $row['id'];?>" class = "btn btn-primary btn-sm btn-flat">
                        <i class="fa fa-edit"></i>
                      </a>

                      <a <?php echo ($isBorrowed) ? 'id="a-Borrowed" ': ''; ?> <?php echo ($isBorrowed) ? 'style="opacity: 50% !important;"': ''; ?>
                      href="<?php echo (!$isBorrowed) ? "del_borrower.php?id=".$row['id'] : "#";?>"
                      class="btn btn-danger btn-sm btn-flat"
                      <?php echo ($isBorrowed) ? "Title='Borrowers with unreturned items cannot be&#10;deleted until returned.'": ''; ?>>
                        <i class="fa fa-trash"></i>
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

        if ((borrowerName !== '' && !/^[a-zA-Z\s]+$/.test(borrowerName)) || (borrowerMName !== '' && !/^[a-zA-Z\s]+$/.test(borrowerMName)) || (borrowerSName !== '' && !/^[a-zA-Z\s]+$/.test(borrowerSName))) {
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
        
        // Check Borrower ID duplicate
        if (!borrowerID_disabled) {
            $.ajax({
            url: "ajax/check_borrower.php",
            type: 'GET',
            data: {
                'borrower_id': borrowerID
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

      // Check Borrower Name duplicate
      if(!name_disabled) {
        $.ajax({
          url: "ajax/check_borrower.php",
          type: 'GET',
          data: {
            'borrower_name': borrowerName,
            'borrower_mname': borrowerMName,
            'borrower_sname': borrowerSName
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