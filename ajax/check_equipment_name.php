<?php 
	include '../config/connection.php';

  $equipmentName = $_GET['equipment_name'];
  $equipmentBrand = $_GET['equipment_brand'];
  $query = "select count(*) as `count` from `equipments` 
	where `equipment` = '$equipmentName' and `brand` = '$equipmentBrand' and `is_del` = '0';";

  if (isset($_GET['update_id'])) {
    $id = $_GET['update_id'];
    $query = "select count(*) as `count` from `equipments` 
	where `equipment` = '$equipmentName' and `brand` = '$equipmentBrand' and `id` <> '$id' and `is_del` = '0' ";
  }

  $stmt = $con->prepare($query);
  $stmt->execute();

$r = $stmt->fetch(PDO::FETCH_ASSOC);
  $count = $r['count'];
  
  echo $count;

?>