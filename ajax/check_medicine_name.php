<?php 
	include '../config/connection.php';

  $medicineName = $_GET['medicine_name'];
  $medicineBrand = $_GET['medicine_brand'];
  $query = "select count(*) as `count` from `medicines` 
	where `medicine_name` = '$medicineName' and `medicine_brand` = '$medicineBrand' and `is_del` = '0';";

  if (isset($_GET['update_id'])) {
    $id = $_GET['update_id'];
    $query = "select count(*) as `count` from `medicines` 
	where `medicine_name` = '$medicineName' and `medicine_brand` = '$medicineBrand' and `id` <> '$id' and `is_del` = '0' ";
  }

  $stmt = $con->prepare($query);
  $stmt->execute();

$r = $stmt->fetch(PDO::FETCH_ASSOC);
  $count = $r['count'];
  
  echo $count;

?>