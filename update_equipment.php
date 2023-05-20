<?php
include './config/connection.php';

 $message = '';
if(isset($_POST['save_equipment'])) {
    $equipmentName = trim($_POST['equipment_name']);
    $equipmentName = ucwords(strtolower($equipmentName));
    $equipmentBrand = trim($_POST['equipment_brand']);
    $equipmentBrand = ucwords(strtolower($equipmentBrand));
   
   $id = $_POST['hidden_id'];
    if($equipmentName !== '') {
      
        $query = "UPDATE `equipments` 
        set `equipment` ='$equipmentName', `brand` ='$equipmentBrand'
        where `id`= $id";
    try{
    	$con->beginTransaction();

    	$stmtEquipment = $con->prepare($query);
	    $stmtEquipment->execute();
	   
	   $con->commit();
       
	   $message = "Record updated sucessfully.";

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
	$query = "SELECT `id`, `equipment`, `brand` from `equipments`
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
              <input type="hidden" name="hidden_id" 
              id="hidden_id" value="<?php echo $id;?>" />

          		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Equipment Name</label>
          			<input type="text" id="equipment_name" name="equipment_name" required="required"
          			class="form-control form-control-sm rounded-0" value="<?php echo $row['equipment'];?>" />
          		</div>

              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Brand</label>
          			<input type="text" id="equipment_brand" name="equipment_brand" required="required"
          			class="form-control form-control-sm rounded-0" value="<?php echo $row['brand'];?>" />
          		</div>

          		<div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
                <label>&nbsp;</label>
          			<button type="submit" id="save_equipment" 
          			name="save_equipment" class="btn btn-primary btn-sm btn-flat btn-block">Update</button>
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
$(document).ready(function() {

$("#equipment_brand").blur(function() {
  var equipmentBrand = $(this).val().trim();
  var equipmentName = $("#equipment_name").val().trim();
  $(this).val(equipmentBrand);
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
          showCustomMessage("This equipment has already been stored. Please choose another brand.");
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

$("#equipment_name").blur(function() {
  var equipmentName = $(this).val().trim();
  var equipmentBrand = $("#equipment_brand").val().trim();
  $(this).val(equipmentName);
  $("#equipment_brand").val(equipmentBrand);

  if(equipmentName !== '') {
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
          showCustomMessage("This equipment has already been stored. Please choose another name.");
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

