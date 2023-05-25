<?php 
	include '../config/connection.php';
    

    $equipmentId = $_GET['equipmentId'];
    $status = $_GET['status'];
    $state = $_GET['state'];
    $remarks = $_GET['remarks'];

    $unavailableSince = $_GET['unavailableSince'];
    $unavailableUntil = $_GET['unavailableUntil'];
    
    $borrower = $_GET['borrower'];

    $q_join = '';
    $q_unavailable = '';

    if ($unavailableSince != '') {

      // convert $unavailableSince to date format from mm/dd/yyyy to yyyy-mm-dd
      $unavailableSince = date("Y-m-d", strtotime($unavailableSince));

      $q_unavailable = " AND `unavailable_since` = '$unavailableSince'";

      if ($unavailableUntil != '') {
        $unavailableUntil = date("Y-m-d", strtotime($unavailableUntil));
        $q_unavailable .= " AND `unavailable_until` = '$unavailableUntil'";
      }

      if ($state == 'Borrowed') {
        $q_unavailable .= " AND `borrower_id` = '$borrower'";
        $q_join = " JOIN `borrowed` ON `equipment_details`.`id` = `equipment_details_id`";
      }                             
    }

    $query = "SELECT count(*) AS `count`
              FROM `equipment_details`".$q_join."
              WHERE `equipment_id` = '$equipmentId'
                AND `status` = '$status'
                AND `state` = '$state'
                AND `remarks` = '$remarks'".$q_unavailable.";";

    

    if (isset($_GET['update_id'])) {
        $id = $_GET['update_id'];
        $query = "SELECT count(*) AS `count`
                  FROM `equipment_details` 
                  WHERE `equipment_id` = '$equipmentId'
                    AND `status` = '$equipmentStatus'
                    AND `date_acquired` = '$dateAcquired'
                    AND `id` <> '$id';";
    }

  $stmt = $con->prepare($query);
  $stmt->execute();

$r = $stmt->fetch(PDO::FETCH_ASSOC);
  $count = $r['count'];
  
  echo $count;

?>