<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';

if(isset($_POST['submit'])) {
  $medicineId = $_POST['medicine'];
  $packing = ucwords(strtolower(trim($_POST['packing'])));
  $quantity = $_POST['quantity'];

  $expDateArr = explode("/", $_POST['expiry']);
  $expDate = $expDateArr[2].'-'.$expDateArr[0].'-'.$expDateArr[1];

  $status = true;
  $targetFile = "none.jpeg";

  if (!empty($_FILES["img_medicine"]["name"])) {
    $allowedExtensions = array('png', 'jpg', 'jpeg');
    $baseName = basename($_FILES["img_medicine"]["name"]);
    $fileExtension = strtolower(pathinfo($baseName, PATHINFO_EXTENSION));

    // Check if the uploaded file has a valid extension
    if (in_array($fileExtension, $allowedExtensions)) {
        $targetFile = time() . $baseName;
        $status = move_uploaded_file($_FILES["img_medicine"]["tmp_name"], 'user_images/meds/' . $targetFile);
    } else {
        // Invalid file format, handle the error as needed
        $message = "Invalid file format. Only PNG, JPG, or JPEG files are allowed.";
        $status = false;
    }
  }

  // Check if the item already exists
  $insert = true;
  $query = "SELECT COUNT(*) AS `duplicate` FROM `medicine_details`
    WHERE `medicine_id` = '$medicineId'
      AND `packing` = '$packing'
      AND `exp_date` = '$expDate'
  ;";

  $stmtMedicineDetails = $con->prepare($query);
  $stmtMedicineDetails->execute();
  $row = $stmtMedicineDetails->fetch(PDO::FETCH_ASSOC);

  if ($row['duplicate'] > 0) {
    $insert = false;
  }

  if ($status && $insert) {
    try {

      $query = "INSERT INTO `medicine_details`
          (`medicine_id`, `packing`, `exp_date`, `quantity`, `img_name`)
          VALUES ('$medicineId', '$packing', '$expDate', '$quantity', '$targetFile')
      ;";
  
      $con->beginTransaction();
      
      $stmtDetails = $con->prepare($query);
      $stmtDetails->execute();
  
      $con->commit();
  
      $message = 'Medicine Item Saved Successfully.';
  
    } catch(PDOException $ex) {
  
     $con->rollback();
  
     echo $ex->getMessage();
     echo $ex->getTraceAsString();
     exit;
    }
  }

  if (!$insert) {
    $message = 'This medicine item already exists. Check the list below or the Trash.';
  }

  header("location:congratulation.php?goto_page=medicine_details.php&message=$message");
  exit;
}


$medicines = getUniqueMedicines($con);

$query = "SELECT `m`.`medicine_name`, `m`.`medicine_brand`, `md`.`id`, `md`.`packing`, 
            `md`.`medicine_id`, `md`.`exp_date`, `md`.`quantity`, `md`.`img_name`
          FROM `medicines` as `m`, `medicine_details` as `md` 
          WHERE `m`.`id` = `md`.`medicine_id`
            AND `m`.`is_del` = '0'
            AND `md`.`is_del` = '0'
          ORDER BY `m`.`id` ASC, `md`.`id` ASC;";

 try {
  
    $stmtDetails = $con->prepare($query);
    $stmtDetails->execute();

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
 <title>Medicine Inventory - SPCC Caloocan Clinic</title>

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
              <h1>Medicine Items Inventory</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Add Medicine Item</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
              
            </div>
          </div>
          <div class="card-body">
            <form method="post" enctype="multipart/form-data">
              <div class="row">

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 select-select2">
                  <label>Medicine Brand</label>
                  <select id="medicine" name="medicine" class="form-control form-control-sm rounded-0 select2" required="required">
                    <?php echo $medicines;?>
                  </select>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                  <label>Unit Type</label>
                  <input id="packing" name="packing" class="form-control form-control-sm rounded-0"  required="required" placeholder="e.g. Tablet, Capsule, Syrup, etc."/>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-10">
                  <div class="form-group">
                    <label>Expiration Date</label>
                    <div class="input-group date" id="expiry" 
                        data-target-input="nearest">
                        <input type="text" placeholder="Enter Expiration date" id="exp_date" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#expiry" name="expiry" required="required" data-toggle="datetimepicker" autocomplete="off"/>
                        <div class="input-group-append" 
                        data-target="#expiry" 
                        data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                  <label>Quantity</label>
                  <input type="number" placeholder="Enter Quantity" min="1" id="quantity" name="quantity" class="form-control form-control-sm rounded-0"  required="required"/>
                </div>

                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-10">
                  <label>Picture (Optional)</label>
                  <input type="file" id="img_medicine" name="img_medicine" class="form-control form-control-sm rounded-0" />
                </div>

                <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12">
                  <label>&nbsp;</label>
                  <button id="save_medicine" type="submit" id="submit" name="submit" 
                  class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
                </div>
              </div>
            </form>
          </div>
          <!-- /.card-body -->
          
        </div>
        <!-- /.card -->

      </section>

      <div class="clearfix">&nbsp;</div>
      <div class="clearfix">&nbsp;</div>
      
  <section class="content">
      <!-- Default box -->
      <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Availalble Medicine Items</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>

        <div class="card-body">
            <div class="row table-responsive">
              <table id="medicine_details" class="table table-striped dataTable table-bordered dtr-inline" role="grid" aria-describedby="medicine_details_info">
                <colgroup>
                  <col width="2%">
                  <col width="30%">
                  <col width="10%">
                  <col width="5%">
                  <col width="20%">
                  <col width="5%">
                  <col width="0%">
                </colgroup>
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th>MEDICINE NAME — Brand</th>
                    <th>Unit Type</th>
                    <th>Quantity</th>
                    <th>Expiration Date</th>
                    <th>Action</th>
                    <th>Tags</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 
                  $serial = 0;
                  while($row =$stmtDetails->fetch(PDO::FETCH_ASSOC)){
                    $serial++;
                  ?>
                  <tr>
                    <td class="text-center"><?php echo $serial; ?></td>
                    <td><?php echo strtoupper($row['medicine_name'])." — ".$row['medicine_brand'];?></td>
                    <td><?php echo $row['packing'];?></td>
                    <td><?php echo $row['quantity'];?></td>
                    <td>
                      <?php echo $row['exp_date'];?>
                    </td>
                    
                    <td class="text-center">
                      <a href="update_medicine_details.php?medicine_id=<?php echo $row['medicine_id'];?>&medicine_detail_id=<?php echo $row['id'];?>&packing=<?php echo $row['packing'];?>" 
                      class = "btn btn-primary btn-sm btn-flat">
                      <i class="fa fa-edit"></i>
                      </a>
                      <a href="del_medicine.php?delId=<?php echo $row['id'];?>" class="btn btn-danger btn-sm btn-flat">
                        <i class="fa fa-trash"></i>
                      </a>
                    </td>
                    <td>
                      <p>
                        <?php
                          echo (strtotime($row['exp_date']) > strtotime(date('Y-m-d'))) ? "is_expired:false" : "is_expired:true";

                          $date1 = new DateTime($row['exp_date']);
                          $date2 = new DateTime(date('Y-m-d'));
                          $interval = $date1->diff($date2);
                          $days = $interval->days;
  
                          $days = (!$interval->invert) ? -$days : $days;
  
                          echo ($days > 30 || $days < 0) ? ", is_expiredinmonth:false" : ", is_expiredinmonth:true";
                          echo ($row['quantity'] > 0) ? ", is_torestock:false" : ", is_torestock:true";
                        ?>
                      </p>
                    </td>
                  </tr>
                <?php
                }
                ?>
                </tbody>
              </table>
            </div>
        </div>
      </div>

      
    </section>
  <!-- /.content-wrapper -->
 </div>

  <?php include './config/footer.php';

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
  showMenuSelected("#mnu_medicines", "#mi_medicine_details");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }

  $(document).ready(function() {

    $("#medicine").select2({
      width: 'resolve',
      placeholder: "Select Medicine"
    });
    
    const url = new URL(window.location.href);
    const search = url.searchParams.get("search");

    var tbl = $('#medicine_details');

    const dataTableOptions = {
      'order': [[4, 'asc']],
      'responsive': true,
      'lengthChange': false,
      'autoWidth': false,
      'columnDefs': [{
        'targets': 6,
        'visible': false
      }],
      // 'buttons': ["copy", "csv", "excel", "pdf", "print", "colvis"]
    };
    
    var exportColumns = [0, 1, 2, 3, 4];

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

    if (search === "is_expired:true" || search === "is_expiredinmonth:true" || search === "is_torestock:true") {
      dataTableOptions.search = {
        search: search
      };
      dataTableOptions.order = (search === "is_torestock:true") ? [[3, 'asc']] : [[4, 'asc']];
    } else {
      dataTableOptions.order = [[0, 'asc']];
    }

    $("#medicine_details").DataTable(dataTableOptions).buttons().container().appendTo('#medicine_details_wrapper .col-md-6:eq(0)');
        
    $('#expiry').datetimepicker({
      format: 'L',
      minDate:new Date()
    });

    $("form :input").blur(handleBlurEvent);

  });

  function handleBlurEvent() {

    var medicineId = $("#medicine").val();
    var medicineUnit = $("#packing").val().trim();
    var expiry = $("#exp_date").val().trim();
    var formattedDate = '';
    if (expiry !== '') {
      var parts = expiry.split("/");
      formattedDate = parts[2] + "-" + parts[0].padStart(2, "0") + "-" + parts[1].padStart(2, "0");
    }

    $("#medicine").val(medicineId);
    $("#packing").val(medicineUnit);

    if (medicineUnit !== '' && formattedDate !== '' && medicineId !== '') {
      $.ajax({
        url: "ajax/check_medicine_unit.php",
        type: 'GET',
        data: {
          'medicine_id': medicineId,
          'medicine_unit': medicineUnit,
          'exp_date': formattedDate
        },
        cache: false,
        async: false,
        success: function(count, status, xhr) {
          if (count > 0) {
            showCustomMessage("This medicine item has already been stored. Please check inventory or the <a href='trash.php?recover=medicine_details' target='_blank'>Trash</a>.");
            $("#save_medicine").attr("disabled", "disabled");
          } else {
            $("#save_medicine").removeAttr("disabled");
          }
        },
        error: function(jqXhr, textStatus, errorMessage) {
          showCustomMessage(errorMessage);
        }
      });
    }
  }

</script>
</body>
</html>