<?php 
include './config/connection.php';

$message = '';
if(isset($_POST['save_equipment'])) {
  $message = '';
  $equipmentName = trim($_POST['equipment_name']);
  $equipmentBrand = trim($_POST['equipment_brand']);
  $equipmentName = ucwords(strtolower($equipmentName));
  $equipmentBrand = ucwords(strtolower($equipmentBrand));

  $acquiredDateArr = explode("/", $_POST['date_acquired']);
  $acquiredDate = $acquiredDateArr[2].'-'.$acquiredDateArr[0].'-'.$acquiredDateArr[1];

  $total_qty = "NULL";

  if($equipmentName != '' && $equipmentBrand != '' && $acquiredDate != '') {
   $query = "INSERT INTO `equipments`(`equipment`, `brand`, `date_acquired`, `total_qty`)
   VALUES('$equipmentName', '$equipmentBrand', '$acquiredDate', $total_qty);";
   
   try {

    $con->beginTransaction();

    $stmtEquipment = $con->prepare($query);
    $stmtEquipment->execute();

    $con->commit();

    $message = 'Equipment Added Successfully.';
  } catch(PDOException $ex) {
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
  $query = "SELECT `id`, `equipment`, `brand`, `date_acquired`, `total_qty`
            FROM `equipments`
            WHERE `is_del` = '0'
            ORDER BY `equipment` ASC;";
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
 <?php include './config/data_tables_css.php';?>
 <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
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
                class="form-control form-control-sm rounded-0" autofocus/>
              </div>

              <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                  <label>Brand</label>
                  <input id="equipment_brand" name="equipment_brand" class="form-control form-control-sm rounded-0" placeholder="Leave it blank for generic brand" required="required" />
              </div>

              <div class="col-lg-3 col-md-6 col-sm-6 col-xs-10">
                <div class="form-group">
                  <label>Date Acquired</label>
                  <div class="input-group date" id="date_acquired" 
                      data-target-input="nearest">
                      <input type="text" value="<?php echo date("m/d/Y"); ?>" id="acquired" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#date_acquired" name="date_acquired" required="required" data-toggle="datetimepicker" autocomplete="off"/>
                      <div class="input-group-append" 
                      data-target="#date_acquired" 
                      data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                <label>Quantity</label>
                <input type="number" min="1" id="quantity" name="quantity" class="form-control form-control-sm rounded-0"  required="required"/>
              </div> -->

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
              <col width="20%">
              <col width="15%">
              <col width="15%">
              <col width="10%">
              <col width="10%">
            </colgroup>

            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Equipment</th>
                <th>Equipment Brand</th>
                <th>Date Acquired</th>
                <th>Total Quantity</th>
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
                <td><?php echo $row['date_acquired'];?></td>
                <td><?php echo (!is_null($row['total_qty'])) ? $row['total_qty'] : "<i>Not Set</i>" ;?></td>
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
<div style="height:8px;"></div>
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
    const url = new URL(window.location.href);
    var search = url.searchParams.get("search");
    var tag = url.searchParams.get("tag");

    const dataTableOptions = {
      order: [[0, 'asc']],
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    };

    if (search === "is_recent") {
      search = (tag != '' || tag != null) ? tag : '';
      dataTableOptions.search = {
        search: search
      };
    }

    $("#all_equipments").DataTable(dataTableOptions).buttons().container().appendTo('#all_equipments_wrapper .col-md-6:eq(0)');
  });

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
      //maxDate: new Date()
      // "setDate": new Date(),
      // "autoclose": true
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
            'equipment_brand': equipmentBrand
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