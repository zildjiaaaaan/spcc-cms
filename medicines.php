<?php 
include './config/connection.php';

$message = '';
if(isset($_POST['save_medicine'])) {
  $message = '';
  $medicineName = trim($_POST['medicine_name']);
  $medicineBrand = trim($_POST['medicine_brand']);
  $medicineName = ucwords(strtolower($medicineName));
  $medicineBrand = ucwords(strtolower($medicineBrand));

  $insert = true;

  // Check if the item already exists
  try {

    $query = "SELECT COUNT(*) AS `duplicate` FROM `medicines` WHERE `medicine_name` = '$medicineName' AND `medicine_brand` = '$medicineBrand';";

    $stmtMedicine = $con->prepare($query);
    $stmtMedicine->execute();
    $row = $stmtMedicine->fetch(PDO::FETCH_ASSOC);

    if ($row['duplicate'] > 0) {
      $insert = false;
    }
    
  } catch (PDOException $ex) {

    $con->rollback();

    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;

  }

  // Insert the item
  if($medicineName != '' && $medicineBrand != '' && $insert) {

    $query = "INSERT INTO `medicines`(`medicine_name`, `medicine_brand`)
    VALUES('$medicineName', '$medicineBrand');";
    
    try {

      $con->beginTransaction();

      $stmtMedicine = $con->prepare($query);
      $stmtMedicine->execute();

      $con->commit();

      $message = 'Medicine Brand Added Successfully.';
    }catch(PDOException $ex) {
    $con->rollback();

    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;
  }

} else {
  if (!$insert) {
    $message = 'This brand already exists. Check the list below or the Trash.';
  } else {
    $message = 'Empty form can not be submitted.';
  }
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
 <title>Medicine Brands - SPCC Caloocan Clinic</title>
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
            <h3 class="card-title">Add Medicine Brand</h3>
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
          <h3 class="card-title">Available Medicine Brands</h3>

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
                  <button type="button" class="btn btn-danger btn-sm btn-flat" data-toggle="modal" data-target="#exampleModal-<?php echo $row['id'];?>">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>

              <div class="modal fade" id="exampleModal-<?php echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Warning!</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <?php
                        echo '<h5>Are you sure you want to delete this medicine brand "'.strtoupper($row["medicine_name"]).' — '.$row["medicine_brand"].'"?</h5>';
                        $id = $row['id'];
                        $q_checkMedDetails = "SELECT * FROM `medicine_details`
                            WHERE `medicine_id` = '$id' AND `is_del` = '0'
                        ;";

                        $stmt_checkMedDetails = $con->prepare($q_checkMedDetails);
                        $stmt_checkMedDetails->execute();
                        $rowCount = $stmt_checkMedDetails->rowCount();
                        $message = '';

                        if ($rowCount > 0) {
                          $message .= "The following Medicine Item/s will be deleted too: <br>";
                          $i = 1;                          
                          while ($r = $stmt_checkMedDetails->fetch(PDO::FETCH_ASSOC)) { 
                            $message .= $i.".) ";
                            $message .= "".$r['packing']."&nbsp;&nbsp;&nbsp; — &nbsp;&nbsp;&nbsp;Exp. Date: ".$r['exp_date'];
                            $message .= "&nbsp;&nbsp;&nbsp; — &nbsp;&nbsp;&nbsp;Qty: ".$r['quantity']."<br>";
                            $i++;
                          }
                        }
                        echo "<p style='margin-top:20px;'>".$message."</p>";                      
                      ?>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                      <a href="del_medicine.php?id=<?php echo $row['id'];?>" class="btn btn-danger">Delete</a>
                    </div>
                  </div>
                </div>
              </div>

              <?php } ?>
            </tbody>
          </table>
          </div>
        </div>

        <!-- Modal -->
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
    
    var dataTableOptions = {
      "responsive": true, "lengthChange": false, "autoWidth": false
      // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    };

    var exportColumns = [0, 1, 2, 3];

    dataTableOptions.buttons = [
      {
        extend: 'copyHtml5',
        exportOptions: {
          columns: exportColumns
        }
      },
      {
        extend: 'csvHtml5',
        exportOptions: {
          columns: exportColumns
        }
      },
      {
        extend: 'excelHtml5',
        exportOptions: {
          columns: exportColumns
        }
      },
      {
        extend: 'pdfHtml5',
        download: 'open',
        exportOptions: {
          columns: exportColumns
        }
      },
      {
        extend: 'print',
        exportOptions: {
          columns: exportColumns
        }
      },
      "colvis"
    ];
    
    $("#all_medicines").DataTable(dataTableOptions).buttons().container().appendTo('#all_medicines_wrapper .col-md-6:eq(0)');

    // $("#medicine_brand").on("keyup blur", function(event) {
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