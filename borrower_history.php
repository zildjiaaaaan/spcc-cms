<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$borrowers = getActiveBorrowers($con);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './config/site_css_links.php';?>  
    <title>Borrower History - SPCC Caloocan Clinic</title>

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
            <h1>Borrower History</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Search Borrower History</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
           
          </div>
        </div>
        <div class="card-body">
          <div class="row">

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <select id="borrower" class="form-control form-control-sm rounded-0">
                <?php echo $borrowers;?>
              </select>
            </div>

            <div class="col-lg-1 col-md-2 col-sm-4 col-xs-12">
              <button type="button" id="search" class="btn btn-primary btn-sm btn-flat btn-block">Search</button>
            </div>
          </div>

            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>

            <div class="row">
              <div class="col-md-12 table-responsive">
                <table id="borrower_history" class="table table-striped table-bordered">
                  <colgroup>
                    <col width="2%">
                    <col width="25%">
                    <col width="3%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="40%">
                    <col width="10%">
                  </colgroup>
                  <thead>
                    <tr class="bg-gradient-primary text-light">
                        <th class="p-1 text-center">#</th>
                        <th class="p-1 text-center">Equipment</th>
                        <th class="p-1 text-center">Qty</th>
                        <th class="p-1 text-center">Borrowed Date</th>
                        <th class="p-1 text-center">Return Date</th>
                        <th class="p-1 text-center">Contact</th>
                        <th class="p-1 text-center">Remarks</th>
                        <th class="p-1 text-center">Status</th>
                    </tr>
                  </thead>

                  <tbody id="history_data">
                    
                  </tbody>
                </table>
              </div>
            </div>
        </div>
        <!-- /.card-body -->
        
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php 
include './config/footer.php';
?>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php include './config/site_js_links.php' ?>

<script>
  showMenuSelected("#mnu_borrowers", "#mi_borrower_history");

  $(function() {
    $("#borrower").select2({
      width: 'resolve',
      placeholder: "Enter borrower name"
    });

    const url = new URL(window.location.href);
    var search = url.searchParams.get("search");
    var tag = url.searchParams.get("tag");

    if (search === "is_recent") {
      search = (tag !== '' && tag !== null) ? tag : '';
      $("#borrower").val(search);

      if (search !== '') {
        getBorrowerHistory(search);
      }
    }

    $("#borrower").on("change", function() {
      var borrowerId = $(this).val();
      getBorrowerHistory(borrowerId);
    });

    function getBorrowerHistory(borrowerId) {
      if (borrowerId !== '') {
        $.ajax({
          url: "ajax/get_borrower_history.php",
          type: 'GET',
          data: {
            'borrower_id': borrowerId
          },
          cache: false,
          success: function(data, status, xhr) {
            $("#history_data").html(data);
          },
          error: function(jqXhr, textStatus, errorMessage) {
            showCustomMessage(errorMessage);
          }
        });
      }
    }
  });

</script>

</body>
</html>