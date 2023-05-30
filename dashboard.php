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

  // $queryYear = "SELECT count(*) as `year` 
  // from `patient_visits` 
  // where YEAR(`visit_date`) = YEAR('$date');";

  $queryYear = "SELECT `patients`.*
                FROM `patient_visits`
                JOIN `patients` ON `patient_visits`.`patient_id` = `patients`.`id`
                WHERE YEAR(`visit_date`) = YEAR('$date')
                ORDER BY visit_date DESC
                LIMIT 1;";

  $queryMonth = "SELECT count(*) as `month` 
  from `patient_visits` 
  where YEAR(`visit_date`) = $year and 
  MONTH(`visit_date`) = $month;";

  $todaysCount = 0;
  $currentWeekCount = 0;
  $currentMonthCount = 0;
  $currentYearCount = "";

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
    $rPatient = $stmtYear->fetch(PDO::FETCH_ASSOC);
    $currentYearCount = $rPatient['patient_name'];

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
  .responsive-h3 {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  @media (max-width: 768px) {
    .responsive-h3 {
      font-size: 14px;
      /* line-height: 16px; */
    }
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
            <h1 id="live-time">
              <?php
                // date_default_timezone_set("Asia/Singapore");
                // echo "Today is ".date("M d, Y")." — ".date("h:ia");
              ?>
            </h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <!--------------------------------------------- PATIENTS -------------------------------------->

        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white" id="box_todayspatient">
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
            <div class="small-box bg-white" id="box_weekspatient">
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
            <div class="small-box bg-white text-reset" id="box_monthspatient">
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
            <div class="small-box bg-white text-reset" id="box_recentpatient">
              <div class="inner">
                <h3 class="responsive-h3"><?php 
                  $names = explode(", ", $currentYearCount);
                  //echo ucwords(strtolower($names[1]))." ".$names[0][0].".";
                  echo $names[1][0].". ".ucwords(strtolower($names[0]));
                ?></h3>

                <p>Recent Patient</p>
              </div>
              <div class="icon">
                <i class="fa fa-user-injured"></i>
              </div>
            </div>
          </div>
        </div>

        <!--------------------------------------------- MEDICINE -------------------------------------->

        <?php

          try {

            $query = "SELECT count(*) AS `totalmed`
                      FROM `medicines`
                      WHERE `is_del` = '0';";

            $currentDate = date('Y-m-d');
            $nextMonthEndDate = date('Y-m-d', strtotime('+30 days'));
            $queryExpiry = "SELECT COUNT(*) AS `exp_date`
                            FROM `medicine_details`
                            JOIN `medicines` ON `medicine_details`.`medicine_id` = `medicines`.`id`
                            WHERE `exp_date` >= '$currentDate'
                              AND `exp_date` <= '$nextMonthEndDate'
                              AND `medicines`.`is_del` = '0'
                              AND `medicine_details`.`is_del` = '0';";

            $queryQty = "SELECT COUNT(*) AS `quantity`
                        FROM `medicine_details`
                        JOIN `medicines` ON `medicine_details`.`medicine_id` = `medicines`.`id`
                        WHERE `quantity` = '0'
                          AND `medicines`.`is_del` = '0'
                          AND `medicine_details`.`is_del` = '0';";

            $queryExpired = "SELECT COALESCE(SUM(quantity), 0) AS `total_expired`
                        FROM `medicine_details`
                        JOIN `medicines` ON `medicine_details`.`medicine_id` = `medicines`.`id`
                        WHERE `exp_date` <= CURDATE()
                          AND `quantity` > '0'
                          AND `medicines`.`is_del` = '0'
                          AND `medicine_details`.`is_del` = '0';";
                        
              $stmtMed = $con->prepare($query);
              $stmtMed->execute();
              $r = $stmtMed->fetch(PDO::FETCH_ASSOC);

              $stmtExpiry = $con->prepare($queryExpiry);
              $stmtExpiry->execute();
              $rExpiry = $stmtExpiry->fetch(PDO::FETCH_ASSOC);

              $stmtQty = $con->prepare($queryQty);
              $stmtQty->execute();
              $rQty = $stmtQty->fetch(PDO::FETCH_ASSOC);

              $stmtExpired = $con->prepare($queryExpired);
              $stmtExpired->execute();
              $rExpired = $stmtExpired->fetch(PDO::FETCH_ASSOC);
            
            } catch(PDOException $ex) {
              echo $ex->getMessage();
              echo $ex->getTraceAsString();
              exit;
            }
        ?>

        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-yellow" id="box_totalmedicine">
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
            <div class="small-box bg-yellow" id="box_tobeexpired">
              <div class="inner">
                <h3><?php
                  if (!empty($rExpiry['exp_date'])) {
                    echo $rExpiry['exp_date'];
                  } else {
                    echo 0;
                  }
                ?></h3>

                <p>To Be Expired (in 1 month)</p>
              </div>
              <div class="icon">
                <i class="fa fa-pills"></i>
              </div>             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-yellow text-reset" id="box_torestock">
              <div class="inner">
                <h3><?php echo $rQty['quantity'];?></h3>

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
            <div class="small-box bg-yellow text-reset" id="box_expired">
              <div class="inner">
                <h3><?php echo $rExpired['total_expired'];?></h3>

                <p>Expired Medicines</p>
              </div>
              <div class="icon">
                <i class="fa fa-pills"></i>
              </div>
            </div>
          </div>
        </div>

        <!--------------------------------------------- EQUIPMENT -------------------------------------->

        <?php

        try {
          $queryEquipments = "SELECT COUNT(*) AS `total_equipments`
          FROM `equipments`
          WHERE `is_del` = '0';";

          $queryBorrowed = "SELECT COUNT(*) AS `total_borrowed`
          FROM `equipment_details`
          WHERE `state` = 'Borrowed'
            AND `is_del` = '0';";

          $queryRecent = "SELECT *, `id` AS `equipment_id`
          FROM `equipments`
          WHERE `is_del` = '0'
          ORDER BY `id` DESC
          LIMIT 1;";

          $queryDefective = "SELECT SUM(quantity) AS `defective_qty`
          FROM `equipment_details`
          WHERE `state` = 'Defective'
          AND `is_del` = '0';";

          $stmtEquipments = $con->prepare($queryEquipments);
          $stmtEquipments->execute();
          $rEquipments = $stmtEquipments->fetch(PDO::FETCH_ASSOC);

          $stmtBorrowed = $con->prepare($queryBorrowed);
          $stmtBorrowed->execute();
          $rBorrowed = $stmtBorrowed->fetch(PDO::FETCH_ASSOC);

          $stmtRecent = $con->prepare($queryRecent);
          $stmtRecent->execute();
          $rRecent = $stmtRecent->fetch(PDO::FETCH_ASSOC);

          $stmtDefective = $con->prepare($queryDefective);
          $stmtDefective->execute();
          $rDefective = $stmtDefective->fetch(PDO::FETCH_ASSOC);

        } catch(PDOException $ex) {
          echo $ex->getMessage();
          echo $ex->getTraceAsString();
          exit;
        }
        ?>

        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info" id="box_totalequipment">
              <div class="inner">
                <h3><?php echo $rEquipments['total_equipments'];?></h3>

                <p>Total Equipment</p>
              </div>
              <div class="icon">
                <i class="fa fa-tools"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info" id="box_borrowed">
              <div class="inner">
                <h3><?php echo $rBorrowed['total_borrowed'];?></h3>

                <p>Borrowed Equipment</p>
              </div>
              <div class="icon">
                <i class="fa fa-tools"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info text-reset" id="box_defective">
              <div class="inner">
                <h3><?php echo (!empty($rDefective['defective_qty']) || $rDefective['defective_qty'] != 0) ? $rDefective['defective_qty'] : "0";?></h3>
                <p>Defective Equipment</p>
              </div>
              <div class="icon">
                <i class="fa fa-tools"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info text-reset" id="box_recent">
              <div class="inner">
                  <h3 class="responsive-h3"><?php echo (!empty($rRecent['equipment'])) ? $rRecent['equipment'] : "None";?></h3>

                  <p>Recently Added Equipment</p>
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

            $queryBorrower = "SELECT `borrowed`.*, `borrowers`.*, `borrowers`.`id` AS `person_id`
                              FROM `borrowed`
                              JOIN `borrowers` ON `borrowed`.`borrower_id` = `borrowers`.`id`
                              ORDER BY `borrowed`.`id` DESC LIMIT 1;";

            $queryUser = "SELECT COUNT(*) AS `attendant` FROM `users`";

            $queryVisit = "SELECT `next_visit_date` AS `upcoming` FROM `patient_visits` WHERE `next_visit_date` > CURDATE() ORDER BY `next_visit_date` ASC LIMIT 1;";

            $queryBrands = "SELECT COUNT(DISTINCT(`medicine_brand`)) AS `medicine_brand` FROM `medicines` WHERE `is_del` = '0';";

            $currentDate = date('Y-m-d');
            $nextMonthEndDate = date('Y-m-t', strtotime('+1 month'));
            $queryExpiry = "SELECT COUNT(*) AS `exp_date` FROM `medicine_details` WHERE `exp_date` >= '$currentDate' AND `exp_date` <= '$nextMonthEndDate'";
            
            
              $stmtBorrower = $con->prepare($queryBorrower);
              $stmtBorrower->execute();
              $rBorrower = $stmtBorrower->fetch(PDO::FETCH_ASSOC);

              $stmtUser = $con->prepare($queryUser);
              $stmtUser->execute();
              $rUser = $stmtUser->fetch(PDO::FETCH_ASSOC);

              $stmtVisit = $con->prepare($queryVisit);
              $stmtVisit->execute();
              $rVisit = $stmtVisit->fetch(PDO::FETCH_ASSOC);

              $stmtBrand = $con->prepare($queryBrands);
              $stmtBrand->execute();
              $rBrand = $stmtBrand->fetch(PDO::FETCH_ASSOC);
            
            } catch(PDOException $ex) {
              echo $ex->getMessage();
              echo $ex->getTraceAsString();
              exit;
            }
        ?>

        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-navy" id="box_totalattendants">
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
            <div class="small-box bg-navy" id="box_totalmedbrand">
              <div class="inner">
                <h3><?php echo $rBrand['medicine_brand'];?></h3>

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
            <div class="small-box bg-navy text-reset" id="box_recentborrower">
              <div class="inner">
                <h3 class="responsive-h3"><?php
                  echo $rBorrower['fname'][0].". ".$rBorrower['lname'];
                ?></h3>
                <p>Recent Borrower</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-tag"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-navy text-reset">
              <div class="inner">
                <h3 class="responsive-h3"><?php
                      if (!empty($rVisit['upcoming'])) {
                        $date = DateTime::createFromFormat('Y-m-d', $rVisit['upcoming']);
                        $formattedDate = $date->format('M. d, Y');
                        echo $formattedDate;
                      } else {
                        echo "None";
                      }
                      
                    ?></h3>

                <p>Upcoming Visit</p>
              </div>
              <div class="icon">
                <i class="fa fa-hospital"></i>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <div class="clearfix">&nbsp;</div>
      <!-- Main content -->
      <section class="content">
        <!-- Default box -->
        <?php 

          $labels = array(
              array('label' => 'Patients', 'icon' => 'fas fa-user-injured', 'recover' => 'patient'),
              array('label' => 'Medicine Brands', 'icon' => 'fa fa-medkit', 'recover' => 'medicine'),
              array('label' => 'Medicine Items', 'icon' => 'fa fa-pills', 'recover' => 'medicine_details'),
              array('label' => 'Equipment Brands', 'icon' => 'fa fa-tools', 'recover' => 'equipments'),
              array('label' => 'Equipment Units', 'icon' => 'fa fa-stethoscope', 'recover' => 'equipment_inventory'),
              array('label' => 'Borrowers', 'icon' => 'fas fa-user-tag', 'recover' => 'borrower')
          );
          try {

            $queryDel = "SELECT COUNT(*) AS `deleted` FROM `patients` WHERE `is_del` = 1 UNION ALL
                SELECT COUNT(*) FROM `medicines` WHERE `is_del` = 1 UNION ALL
                SELECT COUNT(*) FROM `medicine_details` WHERE `is_del` = 1 UNION ALL
                SELECT COUNT(*) FROM `equipments` WHERE `is_del` = 1 UNION ALL
                SELECT COUNT(*) FROM `equipment_details` WHERE `is_del` = 1 UNION ALL
                SELECT COUNT(*) FROM `borrowers` WHERE `is_del` = 1;
                ";            
            
              $stmtDel = $con->prepare($queryDel);
              $stmtDel->execute();
              //$row = $stmtDel->fetch(PDO::FETCH_ASSOC);
            
            } catch(PDOException $ex) {
              echo $ex->getMessage();
              echo $ex->getTraceAsString();
              exit;
            }
        ?>
        <div class="container-fluid">
        <div class="row">
          <?php 
            $i = 0;
            while ($row = $stmtDel->fetch(PDO::FETCH_ASSOC)){ 
          ?>
          <div class="col-lg-4 col-6">
            <div class="info-box bg-<?php echo ($row['deleted'] < 1) ? "grey" : "danger"; ?>" id="box_trash<?php echo str_replace(' ', '', $labels[$i]['label']);?>">
              <span class="info-box-icon"><i class="<?php echo $labels[$i]['icon']; ?>"></i></i></span>
              <div class="info-box-content responsive-h3">
                <span class="info-box-text"><?php echo $labels[$i]['label']; ?></span>
                <span class="info-box-number"><?php echo $row['deleted']; ?></span>
              </div>
              <div class="icon" style="opacity: 20%;">
                <i class="fa fa-trash"></i>
              </div>
            </div>
          </div>
          <?php $i++; } ?>
        </div>
        </div>
      <!-- /.card -->
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

  $(document).ready(function(){

    // PATIENTS

    $("#box_todayspatient, #box_weekspatient, #box_monthspatient").on("mouseenter", function() {
      $(this).css("cursor", "pointer");
    }).on("click", function() {
      window.open("patient_history.php", "_blank");
    });

    $("#box_recentpatient").on("mouseenter", function() {
      $(this).css("cursor", "pointer");
    }).on("click", function() {
      window.open("patient_history.php?search=is_recent&tag=<?php echo $rPatient['id']; ?>", "_blank");
    });

    // MEDICINES

    $("#box_totalmedicine, #box_totalmedbrand").on("mouseenter", function() {
      $(this).css("cursor", "pointer");
    }).on("click", function() {
      window.open("medicines.php", "_blank");
    });

    $("#box_tobeexpired").on("mouseenter", function() {
      $(this).css("cursor", "pointer");
    }).on("click", function() {
      window.open("medicine_details.php?search=is_expiredinmonth:true", "_blank");
    });

    $("#box_torestock").on("mouseenter", function() {
      $(this).css("cursor", "pointer");
    }).on("click", function() {
      window.open("medicine_details.php?search=is_torestock:true", "_blank");
    });

    $("#box_expired").on("mouseenter", function() {
      $(this).css("cursor", "pointer");
    }).on("click", function() {
      window.open("medicine_details.php?search=is_expired:true", "_blank");
    });

    // EQUIPMENT

    $("#box_totalequipment").on("mouseenter", function() {
      $(this).css("cursor", "pointer");
    }).on("click", function() {
      window.open("equipments.php", "_blank");
    });

    $("#box_borrowed").on("mouseenter", function() {
      $(this).css("cursor", "pointer");
    }).on("click", function() {
      window.open("equipment_inventory.php?search=Borrowed", "_blank");
    });

    $("#box_recent").on("mouseenter", function() {
      $(this).css("cursor", "pointer");
    }).on("click", function() {
      window.open("equipments.php?search=is_recent&tag=<?php echo $rRecent['equipment']." ".$rRecent['brand']; ?>", "_blank");
    });

    $("#box_defective").on("mouseenter", function() {
      $(this).css("cursor", "pointer");
    }).on("click", function() {
      window.open("equipment_inventory.php?search=Defective", "_blank");
    });  

    // MAINTENANCE

    $("#box_totalattendants").on("mouseenter", function() {
      $(this).css("cursor", "pointer");
    }).on("click", function() {
      window.open("users.php", "_blank");
    });

    $("#box_recentborrower").on("mouseenter", function() {
      $(this).css("cursor", "pointer");
    }).on("click", function() {
      window.open("borrower_history.php?search=is_recent&tag=<?php echo $rBorrower['person_id']; ?>", "_blank");
    });

    // TRASH
    <?php
      foreach ($labels as $label) {

        $box_id = "#box_trash".str_replace(' ', '', $label['label']);
        $rec = $label['recover'];

        echo '$("'.$box_id.'").on("mouseenter", function() {
                $(this).css("cursor", "pointer");
              }).on("click", function() {
                window.open("trash.php?recover='.$rec.'", "_blank");
              });';
      }
    
    ?>

  });
</script>

</body>
</html>