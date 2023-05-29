<?php 
include './config/connection.php';

$message = '';
if(isset($_POST['save_medicine'])) {
  $message = '';
  $medicineName = trim($_POST['medicine_name']);
  $medicineBrand = trim($_POST['medicine_brand']);
  $medicineName = ucwords(strtolower($medicineName));
  $medicineBrand = ucwords(strtolower($medicineBrand));
  if($medicineName != '' && $medicineBrand != '') {

    $query = "INSERT INTO `medicines`(`medicine_name`, `medicine_brand`)
    VALUES('$medicineName', '$medicineBrand');";
    
    try {

      $con->beginTransaction();

      $stmtMedicine = $con->prepare($query);
      $stmtMedicine->execute();

      $con->commit();

      $message = 'Medicine Added Successfully.';
    }catch(PDOException $ex) {
    $con->rollback();

    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;
  }

} else {
 $message = 'Empty form can not be submitted.';
}
header("Location:congratulation.php?goto_page=medicines.php&message=$message");
exit;
}

try {
  $query = "select `id`, `medicine_name`, `medicine_brand` from `medicines` where `is_del` = '0' order by `medicine_name` asc;";
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
 <title>Medicines - SPCC Caloocan Clinic</title>
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
              <h1>Medicines</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- Main content -->
      <section class="content">
        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Add Medicine</h3>
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
                <label>Medicine Name</label>
                <input type="text" id="medicine_name" name="medicine_name" required="required" placeholder="e.g. Paracetamol"
                class="form-control form-control-sm rounded-0" />
              </div>

              <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                  <label>Brand</label>
                  <input id="medicine_brand" name="medicine_brand" class="form-control form-control-sm rounded-0" placeholder="e.g. Biogesic" required="required" />
              </div>

              <div class="col-lg-1 col-md-12 col-sm-12 col-xs-2">
                <label>&nbsp;</label>
                <button type="submit" id="save_medicine" 
                name="save_medicine" class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
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
          <h3 class="card-title">All Medicines</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>
        <div class="card-body">
          <div class="row table-responsive">

          <table id="all_medicines" class="table table-striped dataTable table-bordered dtr-inline" role="grid" aria-describedby="all_medicines_info">
            <colgroup>
              <col width="5%">
              <col width="35%">
              <col width="35%">
              <col width="10%">
              <col width="10%">
            </colgroup>

            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Medicine Name</th>
                <th>Medicine Brand</th>
                <th>Total Quantity</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>

            <tbody>
              <?php 
                $serial = 0;
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $serial++;
                  $id = $row['id'];
                  $total_qty = 0;
                  
                  try {
                    $q_total_qty = "SELECT SUM(`quantity`) AS `total_qty` FROM `medicine_details` WHERE `medicine_id` = '$id';";
                    $stmt_total_qty = $con->prepare($q_total_qty);
                    $stmt_total_qty->execute();
                    $row_medicine = $stmt_total_qty->fetch(PDO::FETCH_ASSOC);
                    $total_qty = (!empty($row_medicine['total_qty'])) ? $row_medicine['total_qty'] : 0;
                  
                  } catch(PDOException $ex) {
                    echo $ex->getMessage();
                    echo $e->getTraceAsString();
                    exit;  
                  }

              ?>
              <tr>
                <td class="text-center"><?php echo $serial;?></td>
                <td><?php echo $row['medicine_name'];?></td>
                <td><?php echo $row['medicine_brand'];?></td>
                <td><?php echo $total_qty;?></td>
                <td class="text-center">
                  <a href="update_medicine.php?id=<?php echo $row['id'];?>" class="btn btn-primary btn-sm btn-flat">
                    <i class="fa fa-edit"></i>
                  </a>
                  <span>&nbsp;</span>
                  <a href="del_medicine.php?id=<?php echo $row['id'];?>" class="btn btn-danger btn-sm btn-flat">
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
  showMenuSelected("#mnu_medicines", "#mi_medicines");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }

  $('#expiry').datetimepicker({
    minDate:new Date(),
    format: 'L'
  });

  $(document).ready(function() {

    $("#customSwitch1").on("change", function(){
        if($(this).prop("checked") == true){
            $("body").removeClass("dark-mode");
        } else {
            $("body").addClass("dark-mode");
        }
    });
    
    $("#all_medicines").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#all_medicines_wrapper .col-md-6:eq(0)');

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
            'medicine_brand': medicineBrand
          },
          cache:false,
          async:false,
          success: function (count, status, xhr) {
            if(count > 0) {
              showCustomMessage("This medicine has already been stored. Please check inventory or the Trash.");
              $("#save_medicine").attr("disabled", "disabled");
            } else {
              $("#save_medicine").removeAttr("disabled");
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
            'medicine_brand': medicineBrand
          },
          cache:false,
          async:false,
          success: function (count, status, xhr) {
            if(count > 0) {
              showCustomMessage("This medicine has already been stored. Please check inventory or the Trash.");
              $("#save_medicine").attr("disabled", "disabled");
            } else {
              $("#save_medicine").removeAttr("disabled");
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