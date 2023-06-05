<?php 
	include '../config/connection.php';

  $query = '';

  if (isset($_GET['patient_name']) && isset($_GET['patient_mname']) && isset($_GET['patient_sname']) && isset($_GET['cnic'])) {
    $patientName = $_GET['patient_name'];
    $patientMName = $_GET['patient_mname'];
    $patientSName = $_GET['patient_sname'];
    $studentID = $_GET['cnic'];

    $patientFullName = strtoupper($patientSName.", ".$patientName.", ".$patientMName);

    $query = "SELECT COUNT(*) AS `count` FROM `patients`
              WHERE `patient_name` = '$patientFullName'
              AND `cnic` = '$studentID'
    ;";
  }

  if (isset($_GET['cnic'])) {
    $studentID = $_GET['cnic'];
    $query = "SELECT COUNT(*) AS `count` FROM `patients`
            WHERE `cnic` = '$studentID';";
  }  

  if (isset($_GET['update_id'])) {
    $id = $_GET['update_id'];
    if (isset($_GET['patient_name']) && isset($_GET['patient_mname']) && isset($_GET['patient_sname']) && isset($_GET['cnic'])) {
      $patientName = $_GET['patient_name'];
      $patientMName = $_GET['patient_mname'];
      $patientSName = $_GET['patient_sname'];
      $studentID = $_GET['cnic'];
  
      $patientFullName = strtoupper($patientSName.", ".$patientName.", ".$patientMName);
  
      $query = "SELECT COUNT(*) AS `count` FROM `patients`
                WHERE `patient_name` = '$patientFullName'
                AND `cnic` = '$studentID'
                AND `id` <> '$id';";
    }
  
    if (isset($_GET['cnic'])) {
      $studentID = $_GET['cnic'];
      $query = "SELECT COUNT(*) AS `count` FROM `patients`
              WHERE `cnic` = '$studentID' AND `id` <> '$id';";
    }  
  }

  $stmt = $con->prepare($query);
  $stmt->execute();

$r = $stmt->fetch(PDO::FETCH_ASSOC);
  $count = $r['count'];
  
  echo $count;

?>