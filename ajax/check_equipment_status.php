<?php 
	include '../config/connection.php';
    

if (!isset($_GET['update_id']) && !isset($_GET['page'])) {

  $equipmentId = $_GET['equipmentId'];
  $status = $_GET['status'];
  $state = $_GET['state'];
  $remarks = $_GET['remarks'];

  $unavailableSince = $_GET['f_unavailableSince'];
  $unavailableUntil = $_GET['f_unavailableUntil'];

  $borrower = $_GET['borrowerId'];

  $q_join = '';
  $q_unavailable = '';

  if ($unavailableSince != '') {

    // convert $unavailableSince to date format from mm/dd/yyyy to yyyy-mm-dd
    // $unavailableSince = date("Y-m-d", strtotime($unavailableSince));

    $q_unavailable = " AND `unavailable_since` = '$unavailableSince'";

    if ($unavailableUntil != '') {
      // $unavailableUntil = date("Y-m-d", strtotime($unavailableUntil));
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

} else if (isset($_GET['update_id'])) {
  $id = $_GET['update_id'];

  if ($unavailableSince != '') {

    $q_unavailable = " AND `unavailable_since` = '$unavailableSince'";

    if ($unavailableUntil != '') {
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
      AND `equipment_details`.`id` <> '$id'
      AND `status` = '$status'
      AND `state` = '$state'
      AND `remarks` = '$remarks'".$q_unavailable.";";

} else if (isset($_GET['page'])) {

  //$borrowerId = $_GET['borrowerId'];
  $unavailableSince = date('Y-m-d');
  $unavailableUntil = $_GET['f_unavailableUntil'];
  $remarks = $_GET['remarks'];
  // $quantity = $_GET['quantity'];
  // `equipment_details`.`id` = '$equipmentDetailsId'
  $query = "SELECT COUNT(*) AS `count`
      FROM `equipment_details`
      JOIN `borrowed` ON `equipment_details`.`id` = `borrowed`.`equipment_details_id`
      WHERE `status` = 'Unavailable'
        AND `state` = 'Borrowed'
        AND `unavailable_since` = '$unavailableSince'
        AND `unavailable_until` = '$unavailableUntil'
        AND `remarks` = '$remarks'
        AND `is_returned` = '0'
    ;";
  }

$stmt = $con->prepare($query);
$stmt->execute();

$r = $stmt->fetch(PDO::FETCH_ASSOC);
$count = $r['count'];

echo $count;

?>