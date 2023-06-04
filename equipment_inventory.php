<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';

$query = "SELECT `equipment_details`.*, `equipment`, `brand`, `date_acquired`
          FROM `equipment_details`
          JOIN `equipments` ON `equipment_details`.`equipment_id` = `equipments`.id
          WHERE `equipments`.`id` = `equipment_details`.`equipment_id`
            AND `equipments`.`is_del` = '0'
            AND `equipment_details`.`is_del` = '0'
            AND `quantity` > '0'
          ORDER BY `equipment_details`.`id` ASC
          ;";

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
 <title>Equipment Inventory - SPCC Caloocan Clinic</title>
 <style>
    .cell-link {
      color: white;
      /* text-decoration: none; */
    }
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
              <h1>Equipment Units Inventory</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      
  <section class="content">
      <!-- Default box -->
      <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">All Equipment Units</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>

        <div class="card-body">
            <div class="row table-responsive">
              <table id="equipment_inventory" class="table table-striped dataTable table-bordered dtr-inline" role="grid" aria-describedby="equipment_inventory_info">
                <colgroup>
                  <col width="2%">
                  <col width="20%">
                  <col width="10%">
                  <col width="10%">
                  <col width="5%">
                  <col width="40%">
                  <col width="5%">
                  <col width="0%">
                </colgroup>
                <thead class="bg-primary">
                  <tr>
                    <th class="text-center">#</th>
                    <th>Equipment</th>
                    <th>Status</th>
                    <th>State</th>
                    <th>Qty</th>
                    <th>Remarks</th>
                    <th>Action</th>
                    <th>Tags</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 
                    $serial = 0;
                    while($row =$stmtDetails->fetch(PDO::FETCH_ASSOC)){
                      $serial++;

                      // convert date format to YYYY/MM/DD
                      if ($row['unavailable_since'] != null) {
                        $row['unavailable_since'] = date('Y/m/d', strtotime($row['unavailable_since']));
                      }
                      if ($row['unavailable_until'] != null) {
                        $row['unavailable_until'] = date('Y/m/d', strtotime($row['unavailable_until']));
                      }
                      
                      $rowBorrowed = array();
                      $b_id = "";

                      if ($row['state'] == "Borrowed") {
                        $query = "SELECT `borrowed`.`id` AS `main_id`, `borrowed`.`borrower_id` AS `b_id`, `borrowers`.*
                                  FROM `borrowed`
                                  JOIN `borrowers` ON `borrowed`.`borrower_id` = `borrowers`.`id`
                                  WHERE `borrowed`.`equipment_details_id` = '".$row['id']."';
                        ";
                        try {
                          $stmtBorrowed = $con->prepare($query);
                          $stmtBorrowed->execute();
                          $rowBorrowed = $stmtBorrowed->fetch(PDO::FETCH_ASSOC);

                          $b_id = $rowBorrowed['b_id'];
                          
                        } catch(PDOException $ex) {
                          echo $ex->getMessage();
                          echo $ex->getTraceAsString();
                          exit;
                        }
                      }                  

                  ?>
                  <tr>
                    <td class="text-center"><?php echo $serial; ?></td>
                    <td><a class="cell-link" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo "Date Acquired: ".$row['date_acquired'];?>"><?php echo $row['equipment']." — ".strtoupper($row['brand']);?></td>
                    <td>
                      <a class="cell-link" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $row['unavailable_since']." - ".$row['unavailable_until'];?>">
                      <?php echo $row['status']; ?>
                      </a></td>
                    <td>
                      <a class="cell-link" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo (!empty($rowBorrowed)) ? "Borrowed by: ".$rowBorrowed['borrower_id']." - ".strtoupper($rowBorrowed['lname']) : "";?>">
                        <?php echo $row['state'];?>
                      </a>
                    </td>
                    <td><?php echo $row['quantity'];?></td>
                    <td><?php echo (!empty($row['remarks'])) ? $row['remarks'] : "<i>No Remarks</i>" ;?></td>
                    
                    <td class="text-center">
                      <a href="update_equipment_inventory.php?equipment_id=<?php echo $row['equipment_id'];?>&equipment_detail_id=<?php echo $row['id'];?>&b_id=<?php echo $b_id;?>" 
                      class = "btn btn-primary btn-sm btn-flat">
                      <i class="fa fa-edit"></i>
                      </a>
                      <a id="a-<?php echo $row['state']; ?>"
                      href="<?php echo ($row['state'] != "Borrowed") ? "del_equipment.php?delId=".$row['id']."&qty=".$row['quantity']."&id=".$row['equipment_id'] : "#";?>"
                      class="btn btn-danger btn-sm btn-flat">
                        <i class="fa fa-trash"></i>
                      </a>
                    </td>
                    <td>
                      <?php
                          echo (!empty($rowBorrowed)) ? "Borrowed by: ".$rowBorrowed['borrower_id']." - ".strtoupper($rowBorrowed['lname']) : "";
                          echo " ";
                          echo ($row['status'] == "Available") ? "isAvailable" : "";
                          echo " ";
                          echo $row['unavailable_since']." - ".$row['unavailable_until'];
                          echo " ";
                          echo "Date Acquired: ".$row['date_acquired'];
                      ?>
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
  showMenuSelected("#mnu_equipments", "#mi_equipment_inventory");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }

  $(document).ready(function() {
    
    // Search filters
    const url = new URL(window.location.href);
    var search = url.searchParams.get("search");
    var tag = url.searchParams.get("tag");

    const dataTableOptions = {
      'order': [[0, 'asc']],
      'responsive': true,
      'lengthChange': false,
      'autoWidth': false,
      'columnDefs': [{
        'targets': 7,
        'visible': false
      }]
      // buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    };

    var exportColumns = [0, 1, 2, 3, 4, 5];

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

    if (search === "Borrowed" || search === "Defective" || search === "Stock") {
      search = (tag != '') ? tag : search;
      dataTableOptions.search = {
        search: search
      };
    }

    $("#equipment_inventory").DataTable(dataTableOptions).buttons().container().appendTo('#equipment_inventory_wrapper .col-md-6:eq(0)');
        
    // Initialize datetimepicker
    $('#expiry').datetimepicker({
      format: 'L',
      minDate:new Date()
    });

    // Input validation
    $("form :input").blur(function() {
      
      var medicineId = $("#medicine").val();
      var medicineUnit = $("#packing").val().trim();

      $("#medicine").val(medicineId);
      $("#packing").val(medicineUnit);
      
      if(medicineUnit !== '') {
        $.ajax({
          url: "ajax/check_medicine_unit.php",
          type: 'GET', 
          data: {
            'medicine_id': medicineId,
            'medicine_unit': medicineUnit
          },
          cache:false,
          async:false,
          success: function (count, status, xhr) {
            if(count > 0) {
              showCustomMessage("This medicine unit has already been stored. Please check inventory or the Trash.");
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