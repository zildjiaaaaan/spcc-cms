<?php 
	include '../config/connection.php';

  $medicineId = $_GET['medicine_id'];
  $medicineUnit = $_GET['medicine_unit'];
  $query = "select count(*) as `count` from `medicine_details` 
	where `medicine_id` = '$medicineId' and `packing` = '$medicineUnit';";

  $stmt = $con->prepare($query);
  $stmt->execute();

$r = $stmt->fetch(PDO::FETCH_ASSOC);
  $count = $r['count'];
  
  echo $count;

?>