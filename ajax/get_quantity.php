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

		$query = "SELECT `quantity`, `remarks`
			FROM `equipment_details`
			WHERE `id` = '$equipmentDetailsId';";
	}

  	$quantity = 0;
	$data = [];

  	try {
  		$stmt = $con->prepare($query);
  		$stmt->execute();

  		$r = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($r['quantity'] > 0) {
            $quantity = $r['quantity'];
			if (isset($r['remarks'])) {
				$remarks = $r['remarks'];
				$data = [
					'quantity' => $quantity,
					'remarks' => $remarks
				];
			}
        }

  	} catch(PDOException $ex) {
  		echo $ex->getTraceAsString();
  		exit;
  	}

	echo (!empty($data)) ? json_encode($data) : $quantity ;  	
?>