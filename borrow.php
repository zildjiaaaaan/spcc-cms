<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';

$equipments = getActiveEquipments($con);
$borrowers = getUniqueBorrowers($con);

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php' ?>

 <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
 <title>Borrow Equipment - SPCC Caloocan Clinic</title>

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
              <h1>Borrow Equipment</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Borrow Equipment</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <!-- best practices-->
            <form method="post">
              <div class="row d-flex justify-content-center">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 select-select2">
                  <label>Select Borrower</label>
                  <select id="borrower" name="borrower" class="form-control form-control-sm rounded-0 select2">
                    <?php echo $borrowers;?>
                  </select>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 select-select2">
                  <label>Select Equipment</label>
                  <select id="equipment" name="equipment" class="form-control form-control-sm rounded-0 select2">
                    <?php echo $equipments;?>
                  </select>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-10">
                  <div class="form-group">
                    <label>Return Date</label>
                    <div class="input-group date" id="unavailable_until" data-target-input="nearest">
                        <input type="text" id="unavailableUntil" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#unavailable_until" name="unavailable_until" data-toggle="datetimepicker" autocomplete="off"/>
                        <div class="input-group-append" 
                        data-target="#unavailable_until" 
                        data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                  <label>Quantity</label>
                  <input type="number" id="quantity" name="quantity" class="form-control form-control-sm rounded-0" min="1" >
                </div>
              
              </div>

              <div class="row d-flex justify-content-center">
                
                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                  <label><i class="fa fa-info-circle"></i> Current Remarks</label>
                  <textarea id="current_remarks" name="current_remarks" class="form-control form-control-sm rounded-0 remarks" placeholder="Some remarks" disabled style="cursor: not-allowed"></textarea>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <label>Add New Remarks</label>
                  <textarea id="new_remarks" name="new_remarks" class="form-control form-control-sm rounded-0 remarks" placeholder='e.g. "Currently borrowed to use for a healthcare event"'></textarea>
                </div>

                <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12">
                  <label>&nbsp;</label>
                  <button type="button" id="add_to_list" class="btn btn-primary btn-sm btn-flat btn-block">
                    <i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>

              <div class="clearfix">&nbsp;</div>

    <div class="col-md-12"><hr /></div>
    <div class="clearfix">&nbsp;</div>

    <div class="clearfix">&nbsp;</div>
    <div class="row table-responsive">
      <table id="equipment_list" class="table table-striped table-bordered">
        <colgroup>
          <col width="2%">
          <col width="25%">
          <col width="5%">
          <col width="10%">
          <col width="20%">
          <col width="35%">
          <col width="3%">
        </colgroup>
        <thead class="bg-primary">
          <tr>
            <th>#</th>
            <th>Equipment</th>
            <th>Qty</th>
            <th>Return Date</th>
            <th>Borrower</th>
            <th>New Remarks</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody id="current_equipment_list">

        </tbody>
      </table>
    </div>

    <div class="clearfix">&nbsp;</div>
    <div class="row">
      <div class="col-md-10">&nbsp;</div>
      <div class="col-md-2">
        <button type="button" id="submit" name="submit" 
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

<?php include './config/site_js_links.php';?>

<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script>  

  var serial = 1;
  showMenuSelected("", "#mi_borrow");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }
  
</script>
<script src="dist/js/borrow.js"></script>
</body>
</html>