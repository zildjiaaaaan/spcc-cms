<?php 
	include './config/connection.php';

	$presId = $_GET['pres_id'];
    $gotoPage = $_GET['goto_page'];
    $message = '';

    $query = "SELECT `pres_remarks` FROM `patient_visits` WHERE `id` = '$presId';";


    try {      
        $stmtRemarks = $con->prepare($query);
        $stmtRemarks->execute();

        $r = $stmtRemarks->fetch(PDO::FETCH_ASSOC);

        $message = $r['pres_remarks'];
      
      } catch(PDOException $ex) {
        $con->rollback();
      
        echo $ex->getMessage();
        echo $ex->getTraceAsString();
        exit;
      }
     	
  	header("Location:".$gotoPage."?message=$message");

?>
