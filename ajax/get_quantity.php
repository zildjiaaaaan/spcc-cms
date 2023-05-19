<?php 
	include '../config/connection.php';

  	$medicineDetailsId = $_GET['medicineDetailsId'];

  	$query = "SELECT `quantity`
                FROM `medicine_details`
                WHERE `id` = '$medicineDetailsId';
    ";

  	$quantity = 0;

  	try {
  		$stmt = $con->prepare($query);
  		$stmt->execute();

  		$r = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($r['quantity'] > 0) {
            $quantity = $r['quantity'];
        }
        

  	} catch(PDOException $ex) {
  		echo $ex->getTraceAsString();
  		exit;
  	}

  	echo $quantity;
?>