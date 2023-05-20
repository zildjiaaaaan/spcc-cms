<?php 
	include '../config/connection.php';

    $equipmentId = $_GET['equipment_id'];
    $equipmentStatus = $_GET['equipment_status'];
    $dateAcquired = $_GET['date_acquired'];

    $query = "SELECT count(*) AS `count`
                FROM `equipment_details`
                WHERE `equipment_id` = '$equipmentId'
                    AND `status` = '$equipmentStatus'
                    AND `date_acquired` = '$dateAcquired'
                    AND `is_del` = '0';";

    if (isset($_GET['update_id'])) {
        $id = $_GET['update_id'];
        $query = "SELECT count(*) AS `count`
                  FROM `equipment_details` 
                  WHERE `equipment_id` = '$equipmentId'
                    AND `status` = '$equipmentStatus'
                    AND `date_acquired` = '$dateAcquired'
                    AND `is_del` = '0'
                    AND `id` <> '$id';";
    }

  $stmt = $con->prepare($query);
  $stmt->execute();

$r = $stmt->fetch(PDO::FETCH_ASSOC);
  $count = $r['count'];
  
  echo $count;

?>