<?php 
	include '../config/connection.php';

  $patientName = $_GET['patient_name'];
  $studentID = $_GET['cnic'];

  $query = "SELECT COUNT(*) AS `count` FROM `patients`
            WHERE `patient_name` = '$patientName'
            AND `cnic` = '$studentID';";

  if (isset($_GET['update_id'])) {
    $id = $_GET['update_id'];
    $query = "SELECT COUNT(*) AS `count`
            FROM `patients`
            WHERE `patient_name` = '$patientName'
                AND `cnic` = '$studentID'
                AND `id` <> '$id';";
  }

  $stmt = $con->prepare($query);
  $stmt->execute();

$r = $stmt->fetch(PDO::FETCH_ASSOC);
  $count = $r['count'];
  
  echo $count;

?>