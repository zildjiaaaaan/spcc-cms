<?php 
	include '../config/connection.php';

  	$medicineId = $_GET['medicine_id'];

  	$query = "SELECT `id`, `packing` , `exp_date` from `medicine_details`
  	where `medicine_id` = $medicineId and `quantity` > '0';";

  	$packings = '<option value="">Select Unit</option>';

  	try {
  		$stmt = $con->prepare($query);
  		$stmt->execute();

  		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  			 $packings = $packings.'<option value="'.$row['id'].'">'.$row['packing'].' —— Exp. Date: '.$row['exp_date'].'</option>';
  		}

  	} catch(PDOException $ex) {
  		echo $ex->getTraceAsString();
  		exit;
  	}

  	echo $packings;
?>