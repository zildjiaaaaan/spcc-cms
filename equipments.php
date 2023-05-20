<?php 
include './config/connection.php';

$message = '';
if(isset($_POST['save_equipment'])) {
  $message = '';
  $equipmentName = trim($_POST['equipment_name']);
  $equipmentBrand = trim($_POST['equipment_brand']);
  $equipmentName = ucwords(strtolower($equipmentName));
  $equipmentBrand = ucwords(strtolower($equipmentBrand));
  if($equipmentName != '' && $equipmentBrand != '') {
   $query = "INSERT INTO `equipments`(`equipment`, `brand`)
   VALUES('$equipmentName', '$equipmentBrand');";
   
   try {

    $con->beginTransaction();

    $stmtEquipment = $con->prepare($query);
    $stmtEquipment->execute();

    $con->commit();

    $message = 'Equipment Added Successfully.';
  }catch(PDOException $ex) {
   $con->rollback();

   echo $ex->getMessage();
   echo $ex->getTraceAsString();
   exit;
 }

} else {
 $message = 'Empty form can not be submitted.';
}
header("Location:congratulation.php?goto_page=equipments.php&message=$message");
exit;
}

try {
  $query = "select `id`, `equipment`, `brand` from `equipments` where `is_del` = '0' order by `equipment` asc;";
  $stmt = $con->prepare($query);
  $stmt->execute();

} catch(PDOException $ex) {
  echo $ex->getMessage();
  echo $e->getTraceAsString();
  exit;  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php';?>
 <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
 
 <?php include './config/data_tables_css.php';?>
 <title>Clinic Equipments - SPCC Caloocan Clinic</title>
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
              <h1>Clinic Equipments</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- Main content -->
      <section class="content">
        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Add Equipment</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <form method="post">
             <div class="row">
              <div class="col-lg-4 col-md-6 col-sm-6 col-xs-10">
                <label>Equipment Name</label>
                <input type="text" id="equipment_name" name="equipment_name" required="required" placeholder="e.g. Disposable Syringe"
                class="form-control form-control-sm rounded-0" />
              </div>

              <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                  <label>Brand</label>
                  <input id="equipment_brand" name="equipment_brand" class="form-control form-control-sm rounded-0" placeholder="Leave it blank for generic brand" required="required" />
              </div>

              <div class="col-lg-1 col-md-12 col-sm-12 col-xs-2">
                <label>&nbsp;</label>
                <button type="submit" id="save_equipment" 
                name="save_equipment" class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
              </div>
            </div>
          </form>
        </div>

      </div>
      <!-- /.card -->
    </section>
    <section class="content">
      <!-- Default box -->
      <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">All Equipments</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>
        <div class="card-body">
          <div class="row table-responsive">

          <table id="all_equipments" class="table table-striped dataTable table-bordered dtr-inline" role="grid" aria-describedby="all_equipments_info">
            <colgroup>
              <col width="5%">
              <col width="35%">
              <col width="35%">
              <col width="10%">
            </colgroup>

            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Equipment</th>
                <th>Equipment Brand</th>
                <th class="text-center">Action</th>
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
                <td class="text-center">
                  <a href="update_equipment.php?id=<?php echo $row['id'];?>" class="btn btn-primary btn-sm btn-flat">
                    <i class="fa fa-edit"></i>
                  </a>
                  <span>&nbsp;</span>
                  <a href="del_equipment.php?id=<?php echo $row['id'];?>" class="btn btn-danger btn-sm btn-flat">
                    <i class="fa fa-trash"></i>
                  </a>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          </div>
        </div>

<!-- /.card-footer-->
</div>
<!-- /.card -->

</section>
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
  showMenuSelected("#mnu_equipments", "#mi_equipments");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }

  $(function () {
    $("#all_equipments").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#all_equipments_wrapper .col-md-6:eq(0)');
    
  });

  $('#expiry').datetimepicker({
    minDate:new Date(),
    format: 'L'
  });

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
            'equipment_brand': equipmentBrand
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
            'equipment_brand': equipmentBrand
          },
          cache:false,
          async:false,
          success: function (count, status, xhr) {
            if(count > 0) {
              showCustomMessage("This equipment name has already been stored. Please choose another name.");
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