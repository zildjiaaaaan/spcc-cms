<?php 
	include '../config/connection.php';

    $medicineId = $_GET['medicine_id'];
    $medicineUnit = $_GET['medicine_unit'];
    $query = "select count(*) as `count` from `medicine_details` 
	where `medicine_id` = '$medicineId' and `packing` = '$medicineUnit';";

    if (isset($_GET['update_id'])) {
        $id = $_GET['update_id'];
        $exp_date = $_GET['exp_date'];
        $query = "SELECT count(*) AS `count`
                  FROM `medicine_details` 
                  WHERE `medicine_id` = '$medicineId'
                    AND `packing` = '$medicineUnit'
                    AND `exp_date` = '$exp_date'
                    AND `id` <> '$id';";
    }

  $stmt = $con->prepare($query);
  $stmt->execute();

$r = $stmt->fetch(PDO::FETCH_ASSOC);
  $count = $r['count'];
  
  echo $count;

?>