<?php
include './config/connection.php';

 $message = '';
if(isset($_POST['save_equipment'])) {
    $equipmentName = trim($_POST['equipment_name']);
    $equipmentName = ucwords(strtolower($equipmentName));
    $equipmentBrand = trim($_POST['equipment_brand']);
    $equipmentBrand = ucwords(strtolower($equipmentBrand));

    $acquiredDateArr = explode("/", $_POST['date_acquired']);
    $acquiredDate = $acquiredDateArr[2].'-'.$acquiredDateArr[0].'-'.$acquiredDateArr[1];
   
   $id = $_POST['hidden_id'];
    if($equipmentName !== '' && $equipmentBrand !== '' && $acquiredDate !== '') {
      
      $query = "UPDATE `equipments` 
                SET `equipment` ='$equipmentName',
                  `brand` ='$equipmentBrand',
                  `date_acquired` ='$acquiredDate'
                WHERE `id`= $id";
    try{
    	$con->beginTransaction();

    	$stmtEquipment = $con->prepare($query);
	    $stmtEquipment->execute();
	   
      $con->commit();
        
      $message = "Record Updated Sucessfully.";

    }catch(PDOException $ex){
    	$con->rollback();
	    echo $ex->getMessage();
	    echo $ex->getTraceAsString();
      exit;
    }

}
header("Location:congratulation.php?goto_page=equipments.php&message=$message");
exit;
}

try {

 $id = $_GET['id'];
	$query = "SELECT `id`, `equipment`, `brand`, date_format(`date_acquired`, '%m/%d/%Y') AS `date_acquired`
            FROM `equipments`
	          WHERE `id` = $id";
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
 <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
 <title>Update Equipment - SPCC Caloocan Clinic</title>

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
            <h1>Equipments</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Update Equipment</h3>

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

              <div class="col-lg-4 col-md-6 col-sm-6 col-xs-10">
                <label>Equipment Name</label>
                <input type="text" value="<?php echo $row['equipment'];?>" id="equipment_name" name="equipment_name" required="required" placeholder="e.g. Disposable Syringe"
                class="form-control form-control-sm rounded-0" autofocus/>
              </div>

              <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                  <label>Brand</label>
                  <input id="equipment_brand" value="<?php echo $row['brand'];?>" name="equipment_brand" class="form-control form-control-sm rounded-0" placeholder="Leave it blank for generic brand" required="required" />
              </div>

              <div class="col-lg-3 col-md-6 col-sm-6 col-xs-10">
                <div class="form-group">
                  <label>Date Acquired</label>
                  <div class="input-group date" id="date_acquired" 
                      data-target-input="nearest">
                      <input type="text" value="<?php echo $row['date_acquired']; ?>" id="acquired" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#date_acquired" name="date_acquired" required="required" data-toggle="datetimepicker" autocomplete="off"/>
                      <div class="input-group-append" 
                      data-target="#date_acquired" 
                      data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-1 col-md-12 col-sm-12 col-xs-2">
                <label>&nbsp;</label>
                <button type="submit" id="save_equipment" 
                name="save_equipment" class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
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

<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
$(document).ready(function() {

  $("#customSwitch1").on("change", function(){
      if($(this).prop("checked") == true){
          $("body").removeClass("dark-mode");
      } else {
          $("body").addClass("dark-mode");
      }
  });

  $('#date_acquired').datetimepicker({
    format: 'L'
  });

  $("form :input").blur(function() {
    var equipmentBrand = $("#equipment_brand").val().trim();
    var equipmentName = $("#equipment_name").val().trim();
    $("#equipment_brand").val(equipmentBrand);
    $("#equipment_name").val(equipmentName);

    if(equipmentBrand !== '') {
      $.ajax({
        url: "ajax/check_equipment_name.php",
        type: 'GET', 
        data: {
          'equipment_name': equipmentName,
          'equipment_brand': equipmentBrand,
          'update_id': <?php echo $id;?>,
        },
        cache:false,
        async:false,
        success: function (count, status, xhr) {
          if(count > 0) {
            showCustomMessage("This equipment has already been stored. Please check inventory or the Trash.");
            $("#save_equipment").attr("disabled", "disabled");
          } else {
            $("#save_equipment").removeAttr("disabled");
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

