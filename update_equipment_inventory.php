<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';


$equipment_id = $_GET['equipment_id'];
$id = $_GET['equipment_detail_id'];
$equipments = getUniqueEquipments($con, $equipment_id);
$borrowers = getUniqueBorrowers($con, $_GET['b_id']);

try {
    $query = "SELECT *, DATE_FORMAT(`unavailable_since`, '%m/%d/%Y') AS `unavailable_since`, DATE_FORMAT(`unavailable_until`, '%m/%d/%Y') AS `unavailable_until`
                FROM `equipment_details`
                WHERE `id` = '$id'
                AND `equipment_id` = '$equipment_id';";

    $stmt = $con->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $unavailable_since = $row['unavailable_since'];
    $unavailable_until = $row['unavailable_until'];

} catch(PDOException $ex) {

    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php' ?>

 <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
 <title>Update Equipment Details - SPCC Caloocan Clinic</title>

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
              <h1>Equipment Details</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Update Equipment Details</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <!-- best practices-->
            <form method="post">
              <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                  <label>Select Equipment</label>
                  <select id="equipment" name="equipment" class="form-control form-control-sm rounded-0">
                    <?php echo $equipments;?>
                  </select>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                  <label>Status</label>
                  <select id="status" name="status" class="form-control form-control-sm rounded-0">
                    <option value="">Select Status</option>
                    <option <?php echo ($row['status'] == "Available") ? "selected='selected'" : ""; ?> value="Available">Available</option>
                    <option <?php echo ($row['status'] == "Unavailable") ? "selected='selected'" : ""; ?> value="Unavailable">Unavailable</option>
                  </select>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                  <label>State</label>
                  <select id="state" name="state" class="form-control form-control-sm rounded-0">
                    <?php echo getState($row['state']);?>
                  </select>
                </div>

                <div class="clearfix">&nbsp;</div>
                
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                  <label>Remarks</label>
                  <textarea id="remarks" name="remarks" class="form-control form-control-sm rounded-0"><?php echo $row['remarks']; ?>
                  </textarea>
                </div>
                
                <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
                  <label>Quantity Available</label>
                  <input type="number" value="<?php echo $row['quantity']; ?>" id="quantity" name="quantity" class="form-control form-control-sm rounded-0" min="1"/>
                </div>

                <div class="clearfix unavailable">&nbsp;</div>

                <?php 

                
                ?>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-10 unavailable">
                  <div class="form-group">
                    <label>Unavailable Since</label>
                    <div class="input-group date" id="unavailable_since" 
                        data-target-input="nearest">
                        <input type="text" value="<?php echo (!is_null($unavailable_since)) ? $unavailable_since : "" ; ?>" id="unavailableSince" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#unavailable_since" name="unavailable_since" data-toggle="datetimepicker" autocomplete="off"/>
                        <div class="input-group-append" 
                        data-target="#unavailable_since" 
                        data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-10 unavailable">
                  <div class="form-group">
                    <label>Unavailable Until</label>
                    <div class="input-group date" id="unavailable_until" 
                        data-target-input="nearest">
                        <input type="text" value="<?php echo (!is_null($unavailable_until)) ? $unavailable_until : "" ; ?>" id="unavailableUntil" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#unavailable_until" name="unavailable_until" data-toggle="datetimepicker" autocomplete="off"/>
                        <div class="input-group-append" 
                        data-target="#unavailable_until" 
                        data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 unavailable borrower">
                  <label>Borrower</label>
                  <select id="borrower" name="borrower" class="form-control form-control-sm rounded-0">
                    <?php echo $borrowers;?>
                  </select>
                </div>

            </div>

            <div class="clearfix">&nbsp;</div>

            <div class="row">
                <div class="col-md-10">&nbsp;</div>
                <div class="col-md-2">
                    <button type="submit" id="submit" name="submit" 
                    class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
                </div>
            </div>
        </form>
    </div>

</div>
<!-- /.card -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include './config/footer.php';
$message = '';
if(isset($_GET['message'])) {
  $message = $_GET['message'];
}
?>  
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php include './config/site_js_links.php';
?>

<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
    $(function () {

        

        $("#status").change(function() {
            var status = $("#status option:selected").text();
            var html = '';

            if (status === 'Available') {
                html = '<option value="Active">Active</option>';
                html += '<option value="Non-Borrowable">Non-Borrowable</option>';
            } else if (status === 'Unavailable') {
                html = '<option value="Used">Used</option>';
                html += '<option value="Missing">Missing</option>';
                html += '<option value="Defective">Defective</option>';
                html += '<option value="In Repair">In Repair</option>';
                html += '<option value="Borrowed">Borrowed</option>';
                html += '<option value="Transferred">Transferred</option>';
            }

            $("#state").html(html);
        });
    });
</script>
</body>
</html>