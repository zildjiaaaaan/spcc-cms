<?php 
	include '../config/connection.php';

  $query = '';

  if (isset($_GET['borrower_name']) && isset($_GET['borrower_mname']) && isset($_GET['borrower_sname'])) {
    $borrowerName = ucwords(strtolower($_GET['borrower_name']));
    $borrowerMName = ucwords(strtolower($_GET['borrower_mname']));
    $borrowerSName = ucwords(strtolower($_GET['borrower_sname']));

    $query = "SELECT COUNT(*) AS `count` FROM `borrowers`
              WHERE `fname` = '$borrowerName'
                AND `mname` = '$borrowerMName'
                AND `lname` = '$borrowerSName';";
  }

  if (isset($_GET['borrower_id'])) {
    $borrowerID = $_GET['borrower_id'];
    $query = "SELECT COUNT(*) AS `count` FROM `borrowers`
            WHERE `borrower_id` = '$borrowerID';";
  }

  if (isset($_GET['update_id'])) {
    $id = $_GET['update_id'];
    if (isset($_GET['borrower_name']) && isset($_GET['borrower_mname']) && isset($_GET['borrower_sname'])) {
      $borrowerName = ucwords(strtolower($_GET['borrower_name']));
      $borrowerMName = ucwords(strtolower($_GET['borrower_mname']));
      $borrowerSName = ucwords(strtolower($_GET['borrower_sname']));
  
      $query = "SELECT COUNT(*) AS `count` FROM `borrowers`
                WHERE `fname` = '$borrowerName'
                  AND `mname` = '$borrowerMName'
                  AND `lname` = '$borrowerSName'
                  AND `id` <> '$id';";
    }
  
    if (isset($_GET['borrower_id'])) {
      $borrowerID = $_GET['borrower_id'];
      $query = "SELECT COUNT(*) AS `count` FROM `borrowers`
              WHERE `borrower_id` = '$borrowerID' AND `id` <> '$id';";
    }  
  }

  $stmt = $con->prepare($query);
  $stmt->execute();

$r = $stmt->fetch(PDO::FETCH_ASSOC);
  $count = $r['count'];
  
  echo $count;

?>