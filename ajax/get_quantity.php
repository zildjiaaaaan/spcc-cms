<?php 
	include '../config/connection.php';

  	if (isset($_GET['medicineDetailsId'])) {
		$medicineDetailsId = $_GET['medicineDetailsId'];

		$query = "SELECT `quantity`
					FROM `medicine_details`
					WHERE `id` = '$medicineDetailsId';
		";
	}

	if (isset($_GET['equipmentDetailsId'])) {
		$equipmentDetailsId = $_GET['equipmentDetailsId'];

		$query = "SELECT `e`.`total_qty` - COALESCE(SUM(`ed`.`quantity`), 0) AS `quantity`
				FROM `equipments` AS `e`
				LEFT JOIN `equipment_details` AS `ed` ON `e`.`id` = `ed`.`equipment_id`
				WHERE `e`.`id` = '$equipmentDetailsId';";
	}

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