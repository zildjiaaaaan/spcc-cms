<?php 
	include '../config/connection.php';

    if ($_GET['state'] == "Borrowed") {
        echo '<option value="">Select Brand</option>';
        exit;
    }

  	$state = $_GET['state'];

  	$query = "SELECT `id`, `medicine_brand`
                FROM `medicines`
                WHERE `medicine_name` = (
                    SELECT `medicine_name` FROM `medicines` WHERE `id` = '$medicineId'
                );   
    ";

  	$brands = '<option value="">Select Brand</option>';

  	try {
  		$stmt = $con->prepare($query);
  		$stmt->execute();

  		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  			 $brands = $brands.'<option value="'.$row['id'].'">'.$row['medicine_brand'].'</option>';
  		}

  	} catch(PDOException $ex) {
  		echo $ex->getTraceAsString();
  		exit;
  	}

  	echo $brands;
?>