<?php
include './config/connection.php';

 $message = '';
if(isset($_POST['save_medicine'])) {
    $id = $_POST['hidden_id'];
    $medicineName = trim($_POST['medicine_name']);
    $medicineName = ucwords(strtolower($medicineName));
    $medicineBrand = trim($_POST['medicine_brand']);
    $medicineBrand = ucwords(strtolower($medicineBrand));

    // Check if the item already exists
    $insert = true;
    $query = "SELECT COUNT(*) AS `duplicate` FROM `medicines`
    WHERE `medicine_name` = '$medicineName'
      AND `medicine_brand` = '$medicineBrand'
      AND `id` <> '$id'
    ;";

    $stmtMedicine = $con->prepare($query);
    $stmtMedicine->execute();
    $row = $stmtMedicine->fetch(PDO::FETCH_ASSOC);

    if ($row['duplicate'] > 0) {
      $insert = false;
    }
      
    if($medicineName !== '' && $insert) {
      
        $query = "UPDATE `medicines` 
        set `medicine_name` ='$medicineName', `medicine_brand` ='$medicineBrand'
        where `id`= $id";
    try{
    	$con->beginTransaction();

    	$stmtMedicine = $con->prepare($query);
	    $stmtMedicine->execute();
	   
	   $con->commit();
       
	   $message = "Record updated sucessfully.";

    }catch(PDOException $ex){
    	$con->rollback();
	    echo $ex->getMessage();
	    echo $ex->getTraceAsString();
        exit;
    }

}

if (!$insert) {
  $message = 'This brand already exists. Check the list below or the Trash.';
}

header("Location:congratulation.php?goto_page=medicines.php&message=$message");
exit;
}

try {

 $id = $_GET['id'];
	$query = "SELECT `id`, `medicine_name`, `medicine_brand` from `medicines`
	          where `id` = $id";
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
 <title>Update Medicine Brand - SPCC Caloocan Clinic</title>

 <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
 <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
            <h1>Medicine Brands</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Update Medicine Brand</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>
        <div class="card-body">
          <form method="post">
          	<div class="row">
              <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $id;?>" />

          		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Medicine Name</label>
          			<input type="text" id="medicine_name" name="medicine_name" required="required"
          			class="form-control form-control-sm rounded-0" value="<?php echo $row['medicine_name'];?>" />
          		</div>

              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Brand</label>
          			<input type="text" id="medicine_brand" name="medicine_brand" required="required"
          			class="form-control form-control-sm rounded-0" value="<?php echo $row['medicine_brand'];?>" />
          		</div>

          		<div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
                <label>&nbsp;</label>
                <button type="button" id="saveModal" class="btn btn-primary btn-sm btn-flat btn-block" data-toggle="modal" data-target="#exampleModal">
                  Update
                </button>
          			<!-- <button type="submit" id="save_medicine" name="save_medicine" class="btn btn-primary btn-sm btn-flat btn-block">Update</button> -->
          		</div>
          	</div>

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Warning!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <i>Please be informed that this will affect the records of the ff:</i> <br>
                    1. Patients who have this brand in their <a href="patient_history.php">medication</a>. <br>
                    2. Records with this brand in <a href="medicine_details.php">Medicine Inventory</a>. <br><br>
                    <h5>Do you want to proceed?</h5>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="save_medicine" name="save_medicine" class="btn btn-primary">Update</button>
                    <!-- <a href="#" class="btn btn-danger">Delete</a> -->
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>

        
    
        <!-- /.card-footer-->
      </div>
    </section>	
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
<script>

showMenuSelected("#mnu_medicines", "#mi_medicines");

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

$("#medicine_brand").blur(function() {
  var medicineBrand = $(this).val().trim();
  var medicineName = $("#medicine_name").val().trim();
  $(this).val(medicineBrand);
  $("#medicine_name").val(medicineName);

  if(medicineBrand !== '') {
    $.ajax({
      url: "ajax/check_medicine_name.php",
      type: 'GET', 
      data: {
        'medicine_name': medicineName,
        'medicine_brand': medicineBrand,
        'update_id': <?php echo $id;?>,
      },
      cache:false,
      async:false,
      success: function (count, status, xhr) {
        if(count > 0) {
          showCustomMessage("This medicine name has already been stored. Please check inventory or the Trash.");
          $("#saveModal").attr("disabled", "disabled");
        } else {
          $("#saveModal").removeAttr("disabled");
        }
      },
      error: function (jqXhr, textStatus, errorMessage) {
        showCustomMessage(errorMessage);
      }
    });
  }

});

$("#medicine_name").blur(function() {
  var medicineName = $(this).val().trim();
  var medicineBrand = $("#medicine_brand").val().trim();
  $(this).val(medicineName);
  $("#medicine_brand").val(medicineBrand);

  if(medicineName !== '') {
    $.ajax({
      url: "ajax/check_medicine_name.php",
      type: 'GET', 
      data: {
        'medicine_name': medicineName,
        'medicine_brand': medicineBrand,
        'update_id': <?php echo $id;?>,
      },
      cache:false,
      async:false,
      success: function (count, status, xhr) {
        if(count > 0) {
          showCustomMessage("This medicine name has already been stored. Please check inventory or the Trash.");
          $("#saveModal").attr("disabled", "disabled");
        } else {
          $("#saveModal").removeAttr("disabled");
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

