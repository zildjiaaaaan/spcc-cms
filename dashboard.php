<?php 
include './config/connection.php';

  $date = date('Y-m-d');
  
  $year =  date('Y'); 
  $month =  date('m');

  $queryToday = "SELECT count(*) as `today` 
  from `patient_visits` 
  where `visit_date` = '$date';";

  $queryWeek = "SELECT count(*) as `week` 
  from `patient_visits` 
  where YEARWEEK(`visit_date`) = YEARWEEK('$date');";

$queryYear = "SELECT count(*) as `year` 
  from `patient_visits` 
  where YEAR(`visit_date`) = YEAR('$date');";

$queryMonth = "SELECT count(*) as `month` 
  from `patient_visits` 
  where YEAR(`visit_date`) = $year and 
  MONTH(`visit_date`) = $month;";

  $todaysCount = 0;
  $currentWeekCount = 0;
  $currentMonthCount = 0;
  $currentYearCount = 0;


  try {

    $stmtToday = $con->prepare($queryToday);
    $stmtToday->execute();
    $r = $stmtToday->fetch(PDO::FETCH_ASSOC);
    $todaysCount = $r['today'];

    $stmtWeek = $con->prepare($queryWeek);
    $stmtWeek->execute();
    $r = $stmtWeek->fetch(PDO::FETCH_ASSOC);
    $currentWeekCount = $r['week'];

    $stmtYear = $con->prepare($queryYear);
    $stmtYear->execute();
    $r = $stmtYear->fetch(PDO::FETCH_ASSOC);
    $currentYearCount = $r['year'];

    $stmtMonth = $con->prepare($queryMonth);
    $stmtMonth->execute();
    $r = $stmtMonth->fetch(PDO::FETCH_ASSOC);
    $currentMonthCount = $r['month'];

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
 <title>Dashboard - SPCC Caloocan Clinic</title>
<style>
  .dark-mode .bg-fuchsia, .dark-mode .bg-maroon {
    color: #fff!important;
}
</style>
</head>
<body class="hold-transition sidebar-mini dark-mode layout-fixed layout-navbar-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->

<?php 

include './config/header.php';
include './config/sidebar.php';
?>  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?php 
            date_default_timezone_set("Asia/Singapore");
            echo "Today is ".date("M d, Y")." — ".date("h:ia"); ?>
            </h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <div class="inner">
                <h3><?php echo $todaysCount;?></h3>

                <p>Today's Patients</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-day"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <div class="inner">
                <h3><?php echo $currentWeekCount;?></h3>

                <p>Current Week</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-week"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white text-reset">
              <div class="inner">
                <h3><?php echo $currentMonthCount;?></h3>

                <p>Current Month</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white text-reset">
              <div class="inner">
                <h3><?php echo $currentYearCount;?></h3>

                <p>Current Year</p>
              </div>
              <div class="icon">
                <i class="fa fa-user-injured"></i>
              </div>
             
            </div>
          </div>
        </div>

        <?php

          try {

            $query = "SELECT count(*) AS `totalmed`
                      FROM `medicines`
                      WHERE `is_del` = '0';";
            
              $stmtMed = $con->prepare($query);
              $stmtMed->execute();
              $r = $stmtMed->fetch(PDO::FETCH_ASSOC);
            
            } catch(PDOException $ex) {
              echo $ex->getMessage();
              echo $ex->getTraceAsString();
              exit;
            }
        ?>

        <!--------------------------------------------- MEDICINE -------------------------------------->

        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3><?php echo $r['totalmed'];?></h3>

                <p>Total Medicine Stocks</p>
              </div>
              <div class="icon">
                <i class="fa fa-pills"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3><?php echo "0"//$currentWeekCount;?></h3>

                <p>Medicines To Be Expired</p>
              </div>
              <div class="icon">
                <i class="fa fa-pills"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-yellow text-reset">
              <div class="inner">
                <h3><?php echo "0"//$currentMonthCount;?></h3>

                <p>Need To Restock</p>
              </div>
              <div class="icon">
                <i class="fa fa-pills"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-yellow text-reset">
              <div class="inner">
                <h3><?php echo "0"//$currentYearCount;?></h3>

                <p>Expired Medicines</p>
              </div>
              <div class="icon">
                <i class="fa fa-pills"></i>
              </div>
             
            </div>
          </div>
        </div>

        <!--------------------------------------------- EQUIPMENT -------------------------------------->

        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $todaysCount;?></h3>

                <p>Total Equipments</p>
              </div>
              <div class="icon">
                <i class="fa fa-tools"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo "0"//$currentWeekCount;?></h3>

                <p>Defective Equipments</p>
              </div>
              <div class="icon">
                <i class="fa fa-tools"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info text-reset">
              <div class="inner">
                <h3><?php echo "0"//$currentMonthCount;?></h3>

                <p>To Restock</p>
              </div>
              <div class="icon">
                <i class="fa fa-tools"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info text-reset">
              <div class="inner">
                <h3><?php echo "0"//$currentYearCount;?></h3>

                <p>Expired Medicines</p>
              </div>
              <div class="icon">
                <i class="fa fa-tools"></i>
              </div>
             
            </div>
          </div>
        </div>

        <!--------------------------------------------- MAINTENANCE -------------------------------------->

        <?php

        try {

            $queryDel = "SELECT COUNT(*) AS `deleted`
                        FROM (
                            SELECT `is_del` FROM `medicines`
                            UNION ALL
                            SELECT `is_del` FROM `patients`
                        ) AS `combined`
                        WHERE `is_del` = '1';
                        ";

            $queryUser = "SELECT COUNT(*) AS `attendant` FROM `users`";

            $queryVisit = "SELECT `next_visit_date` AS `upcoming` FROM `patient_visits` WHERE `next_visit_date` > CURDATE() ORDER BY `next_visit_date` ASC LIMIT 1;";
            
              $stmtDel = $con->prepare($queryDel);
              $stmtDel->execute();
              $rDel = $stmtDel->fetch(PDO::FETCH_ASSOC);

              $stmtUser = $con->prepare($queryUser);
              $stmtUser->execute();
              $rUser = $stmtUser->fetch(PDO::FETCH_ASSOC);

              $stmtVisit = $con->prepare($queryVisit);
              $stmtVisit->execute();
              $rVisit = $stmtVisit->fetch(PDO::FETCH_ASSOC);
            
            } catch(PDOException $ex) {
              echo $ex->getMessage();
              echo $ex->getTraceAsString();
              exit;
            }
        ?>

        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-navy">
              <div class="inner">
                <h3><?php echo $rUser['attendant'];?></h3>

                <p>Total Clinic Attendants</p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-navy">
              <div class="inner">
                <h3><?php echo "0"//$currentWeekCount;?></h3>

                <p>Total Medicine Brands</p>
              </div>
              <div class="icon">
                <i class="fa fa-tag"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-navy text-reset">
              <div class="inner">
                <h3><?php
                      $date = DateTime::createFromFormat('Y-m-d', $rVisit['upcoming']);
                      $formattedDate = $date->format('F j, Y');
                      echo $formattedDate;
                    ?></h3>

                <p>Upcoming Visit</p>
              </div>
              <div class="icon">
                <i class="fa fa-hospital"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-navy text-reset">
              <div class="inner">
                <h3><?php echo $rDel['deleted'];?></h3>

                <p>Deleted Items</p>
              </div>
              <div class="icon">
                <i class="fa fa-trash"></i>
              </div>
             
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include './config/footer.php';?>  
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php include './config/site_js_links.php';?>
<script>
  $(function(){
    showMenuSelected("#mnu_dashboard", "");
  })
</script>

</body>
</html>