<?php 
include './config/connection.php';

$message = '';
if(isset($_POST['save_equipment'])) {
  $message = '';
  $equipmentName = trim($_POST['equipment_name']);
  $equipmentBrand = trim($_POST['equipment_brand']);
  $equipmentName = ucwords(strtolower($equipmentName));
  $equipmentBrand = ucwords(strtolower($equipmentBrand));

  $equipmentBrand = ($equipmentBrand == '') ? "Generic Brand" : "$equipmentBrand";

  $acquiredDateArr = explode("/", $_POST['date_acquired']);
  $acquiredDate = $acquiredDateArr[2].'-'.$acquiredDateArr[0].'-'.$acquiredDateArr[1];

  $total_qty = "NULL";

  // Check if the item already exists
  $insert = true;
  $query = "SELECT COUNT(*) AS `duplicate` FROM `equipments`
    WHERE `equipment` = '$equipmentName'
      AND `brand` = '$equipmentBrand'
  ;";

  $stmtEquipment = $con->prepare($query);
  $stmtEquipment->execute();
  $row = $stmtEquipment->fetch(PDO::FETCH_ASSOC);

  if ($row['duplicate'] > 0) {
    $insert = false;
  }

  if ($equipmentName != '' && $equipmentBrand != '' && $acquiredDate != '' && $insert) {

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

  if (!$insert) {
    $message = 'This equipment type has already been stored. Please check inventory or the Trash.';
  }

  header("Location:congratulation.php?goto_page=equipments.php&message=$message");
  exit;
}

try {
  $query = "SELECT `id`, `equipment`, `brand`, `date_acquired`, `total_qty`
      FROM `equipments` WHERE `is_del` = '0' ORDER BY `equipment` ASC
  ;";

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
 <title>Equipment Types - SPCC Caloocan Clinic</title>
 <style>
    .cell-link {
      color: white;
      /* text-decoration: none; */
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
              <h1>Clinic Equipment Types</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- Main content -->
      <section class="content">
        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Add Equipment Type</h3>
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
                  <input id="equipment_brand" name="equipment_brand" class="form-control form-control-sm rounded-0" placeholder="Leave it blank for generic brand" />
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
          <h3 class="card-title">Available Equipment Types</h3>

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
              <col width="0%">
            </colgroup>

            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Equipment</th>
                <th>Equipment Brand</th>
                <th>Date Acquired</th>
                <th>Total Quantity</th>
                <th class="text-center">Action</th>
                <th>Tags</th>
              </tr>
            </thead>

            <tbody>
              <?php 
                $serial = 0;
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $serial++;
                  $id = $row['id'];
                  $searchName = "unit:".str_replace(" ", "", $row['equipment']."—".strtoupper($row['brand']));
                  $q_check = "SELECT COUNT(*) AS 'borrowed' FROM `equipment_details`
                      WHERE `state` = 'Borrowed' AND `equipment_id` = '$id'
                  ;";

                  $stmt_check = $con->prepare($q_check);
                  $stmt_check->execute();
                  $row_check = $stmt_check->fetch(PDO::FETCH_ASSOC);
                  $borrowed = $row_check['borrowed'];
              ?>
              <tr>
                <td class="text-center"><?php echo $serial;?></td>
                <td><?php echo $row['equipment'];?></td>
                <td><?php echo (!empty($row['brand'])) ? $row['brand'] : "Generic Brand";?></td>
                <td><?php echo $row['date_acquired'];?></td>
                <td><?php echo (!is_null($row['total_qty'])) ? "<a href='equipment_inventory.php?search=Stock&tag=".$searchName."' target='_blank' class='cell-link'>".$row['total_qty']."</a>" : "<i>Not Set</i>" ;?></td>
                <td class="text-center">
                  <a href="update_equipment.php?id=<?php echo $id;?>" class="btn btn-primary btn-sm btn-flat">
                    <i class="fa fa-edit"></i>
                  </a>
                  <span>&nbsp;</span>
                  <span <?php 
                    $title = '';
                    if ($borrowed > 0) {
                      if ($borrowed > 1) {
                        $title = 'Cannot be deleted! There are '.$borrowed.' items borrowed with this equipment type.';
                      } else {
                        $title = 'Cannot be deleted! There is '.$borrowed.' item borrowed with this equipment type.';
                      }
                      echo 'class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="left" title="'.$title.'"';
                    }                   
                  ?>>
                    <button type="button" class="btn btn-danger btn-sm btn-flat" data-toggle="modal" data-target="#exampleModal-<?php echo $id;?>"
                    <?php echo ($borrowed > 0) ? 'style="cursor: not-allowed;" disabled' : ''; ?>>
                      <i class="fa fa-trash"></i>
                    </button>
                  </span>
                </td>
                <td>
                  <?php
                    echo ($row['total_qty'] < 1 && !is_null($row['total_qty'])) ? "is_torestock:true": "is_torestock:false";
                  ?>
                </td>
              </tr>

              <div class="modal fade" id="exampleModal-<?php echo $id;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        echo '<h5>Are you sure you want to delete this equipment type "'.$row["equipment"].' — '.strtoupper($row["brand"]).'"?</h5>';
                        $q_checkEquipDetails = "SELECT * FROM `equipment_details`
                            WHERE `equipment_id` = '$id' AND `state` <> 'Borrowed'
                            AND `is_del` = '0'
                        ;";

                        $stmt_checkEquipDetails = $con->prepare($q_checkEquipDetails);
                        $stmt_checkEquipDetails->execute();
                        $rowCount = $stmt_checkEquipDetails->rowCount();
                        $message = '';

                        if ($rowCount > 0) {
                          $message .= "The following Equipment Unit/s will be deleted too: <br>";
                          $i = 1;                          
                          while ($r = $stmt_checkEquipDetails->fetch(PDO::FETCH_ASSOC)) {
                            $digit_id = $r['id'];
                            $digit_id_Length = strlen($digit_id);
                            $f_id = ($digit_id_Length < 5) ? str_pad($digit_id, 5, '0', STR_PAD_LEFT) : (string)$digit_id;
  
                            $digit_eid = $r['equipment_id'];
                            $digit_eid_Length = strlen($digit_eid);
                            $f_eid = ($digit_eid_Length < 5) ? str_pad($digit_eid, 5, '0', STR_PAD_LEFT) : (string)$digit_eid;


                            $link = "equipment_inventory.php?search=Item&tag="."EquipDetId:".$f_id."-".$f_eid;;
                            $message .= $i.".) Status: ";
                            $message .= "<a href='".$link."' target='_blank'>".$r['status']."</a>&nbsp;&nbsp;&nbsp; — &nbsp;&nbsp;&nbsp;State: ".$r['state'];
                            $message .= "&nbsp;&nbsp;&nbsp; — &nbsp;&nbsp;&nbsp;Qty: ".$r['quantity']."<br>";
                            $i++;
                          }
                        }
                        echo "<p style='margin-top:20px;'>".$message."</p>";                      
                      ?>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                      <a href="del_equipment.php?id=<?php echo $row['id']."&qty=".$row['total_qty'];?>" class="btn btn-danger">Delete</a>
                    </div>
                  </div>
                </div>
              </div>

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

    $('[data-toggle="tooltip"]').tooltip();

    const url = new URL(window.location.href);
    var search = url.searchParams.get("search");
    var tag = url.searchParams.get("tag");

    const dataTableOptions = {
      order: [[0, 'asc']],
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      'columnDefs': [{
        'targets': 6,
        'visible': false
      }]
      // buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
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

    if (search === "is_recent") {
      search = (tag != '' && tag != null) ? tag : '';
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
              showCustomMessage("This equipment type has already been stored. Please check inventory or the Trash.");
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