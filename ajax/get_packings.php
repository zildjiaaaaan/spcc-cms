<?php 
	include '../config/connection.php';

  	$medicineId = $_GET['medicine_id'];
	$currentDate = date('Y-m-d');

  	$query = "SELECT `id`, `packing`, `exp_date`
			FROM `medicine_details`
			WHERE `medicine_id` = $medicineId
				AND `quantity` > '0'
				AND `exp_date` > '$currentDate'
				AND `is_del` = '0';";

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