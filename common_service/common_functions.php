<?php 

function getGender($gender = '') {
	$data = '<option value="">Select Gender</option>';
	
	$arr = array("Male", "Female", "Other");

	$i = 0;
	$size = sizeof($arr);

	for($i = 0; $i < $size; $i++) {
		if($gender == $arr[$i]) {
			$data = $data .'<option selected="selected" value="'.$arr[$i].'">'.$arr[$i].'</option>';
		} else {
		$data = $data .'<option value="'.$arr[$i].'">'.$arr[$i].'</option>';
		}
	}

	return $data;
}

function getState($state = '') {
	$data = '<option value="">Select State</option>';
	
	$arr = array("Active", "Non-Borrowable", "Used", "Missing", "Defective", "In Repair", "Borrowed", "Transferred");

	$i = 0;
	$size = sizeof($arr);

	for($i = 0; $i < $size; $i++) {
		if($state == $arr[$i]) {
			$data = $data .'<option selected="selected" value="'.$arr[$i].'">'.$arr[$i].'</option>';
		} else {
		$data = $data .'<option value="'.$arr[$i].'">'.$arr[$i].'</option>';
		}
	}

	return $data;
}


function getMedicines($con, $medicineId = 0) {

	$query = "select `id`, `medicine_name` from `medicines` 
	order by `medicine_name` asc;";

	$stmt = $con->prepare($query);
	try {
		$stmt->execute();

	} catch(PDOException $ex) {
		echo $ex->getTraceAsString();
		echo $ex->getMessage();
		exit;
	}

	$data = '<option value="">Select Medicine</option>';

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if($medicineId == $row['id']) {
			$data = $data.'<option selected="selected" value="'.$row['id'].'">'.$row['medicine_name'].'</option>';

		} else {
		$data = $data.'<option value="'.$row['id'].'">'.$row['medicine_name'].'</option>';
		}
	}

	return $data;
	
}

function getActiveMedicines($con, $medicineId = 0) {

	$query = "select `id`, `medicine_name` from `medicines` where `is_del` = '0' order by `medicine_name` asc;";

	$stmt = $con->prepare($query);
	try {
		$stmt->execute();

	} catch(PDOException $ex) {
		echo $ex->getTraceAsString();
		echo $ex->getMessage();
		exit;
	}

	$data = '<option value="">Select Medicine</option>';

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if($medicineId == $row['id']) {
			$data = $data.'<option selected="selected" value="'.$row['id'].'">'.$row['medicine_name'].'</option>';

		} else {
		$data = $data.'<option value="'.$row['id'].'">'.$row['medicine_name'].'</option>';
		}
	}

	return $data;
	
}

function getUniqueMedicines($con, $medicineId = 0) {

	$query = "select `id`, `medicine_name`, `medicine_brand` from `medicines` where `is_del` = '0' order by `medicine_name` asc;";

	$stmt = $con->prepare($query);
	try {
		$stmt->execute();

	} catch(PDOException $ex) {
		echo $ex->getTraceAsString();
		echo $ex->getMessage();
		exit;
	}

	$data = '<option value=""></option>';

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if($medicineId == $row['id']) {
			$data = $data.'<option selected="selected" value="'.$row['id'].'">'.strtoupper($row['medicine_name']).' — '.$row['medicine_brand'].'</option>';

		} else {
		$data = $data.'<option value="'.$row['id'].'">'.strtoupper($row['medicine_name']).' — '.$row['medicine_brand'].'</option>';
		}
	}

	return $data;
	
}

function getUniqueBorrowers($con, $borrowerID = 0) {

	$query = "select * from `borrowers` where `is_del` = '0' order by `lname` asc;";

	$stmt = $con->prepare($query);
	try {
		$stmt->execute();

	} catch(PDOException $ex) {
		echo $ex->getTraceAsString();
		echo $ex->getMessage();
		exit;
	}

	$data = '<option value="">Select Borrowers</option>';

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if($borrowerID == $row['id']) {
			$data = $data.'<option selected="selected" value="'.$row['id'].'">'.strtoupper($row['lname']).', '.$row['fname'].', '.$row['mname'].'</option>';
		} else {
			$data = $data.'<option value="'.$row['id'].'">'.strtoupper($row['lname']).', '.$row['fname'].', '.$row['mname'].' ('.$row['borrower_id'].')</option>';
		}
	}

	return $data;
	
}


function getPatients($con) {
$query = "select `id`, `patient_name`, `phone_number` 
from `patients` order by `patient_name` asc;";

	$stmt = $con->prepare($query);
	try {
		$stmt->execute();

	} catch(PDOException $ex) {
		echo $ex->getTraceAsString();
		echo $ex->getMessage();
		exit;
	}

	$data = '<option value="">Select Patient</option>';

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$data = $data.'<option value="'.$row['id'].'">'.$row['patient_name'].' ('.$row['phone_number'].')'.'</option>';
	}

	return $data;
}

function getActivePatients($con) {
	$query = "select `id`, `patient_name`, `cnic` 
	from `patients` where `is_del` = '0' order by `patient_name` asc;";
	
	$stmt = $con->prepare($query);
	try {
		$stmt->execute();

	} catch(PDOException $ex) {
		echo $ex->getTraceAsString();
		echo $ex->getMessage();
		exit;
	}

	$data = '<option value=""></option>';

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$data = $data.'<option value="'.$row['id'].'">'.$row['patient_name'].' ('.$row['cnic'].')'.'</option>';
	}

	return $data;
}

function getUniqueEquipments($con, $equipmentId = 0) {

	$query = "select `id`, `equipment`, `brand` from `equipments` where `is_del` = '0' order by `equipment` asc;";

	$stmt = $con->prepare($query);
	try {
		$stmt->execute();

	} catch(PDOException $ex) {
		echo $ex->getTraceAsString();
		echo $ex->getMessage();
		exit;
	}

	$data = '<option value=""></option>';

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if($equipmentId == $row['id']) {
			$data = $data.'<option selected="selected" value="'.$row['id'].'">'.$row['equipment'].' — '.strtoupper($row['brand']).'</option>';

		} else {
		$data = $data.'<option value="'.$row['id'].'">'.$row['equipment'].' — '.strtoupper($row['brand']).'</option>';
		}
	}

	return $data;
	
}


function getDateTextBox($label, $dateId) {

	$d = '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-10">
                <div class="form-group">
                  <label>'.$label.'</label>
                  <div class="input-group rounded-0 date" 
                  id="" 
                  data-target-input="nearest">
                  <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" data-toggle="datetimepicker" 
data-target="#'.$dateId.'" name="'.$dateId.'" id="'.$dateId.'" required="required" autocomplete="off"/>
                  <div class="input-group-append rounded-0" 
                  data-target="#'.$dateId.'" 
                  data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
              </div>
            </div>
          </div>';

          return $d;
}

function getActiveBorrowers($con) {
	$query = "select `id`, `fname`, `mname`, `lname`, `borrower_id` 
	from `borrowers` where `is_del` = '0' order by `lname` asc;";
	
	$stmt = $con->prepare($query);
	try {
		$stmt->execute();

	} catch(PDOException $ex) {
		echo $ex->getTraceAsString();
		echo $ex->getMessage();
		exit;
	}

	

	$data = '<option value=""></option>';

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$fullname = $row['lname'].', '.$row['fname'].', '.$row['mname'];
		$fullname = strtoupper($fullname);
		$data = $data.'<option value="'.$row['id'].'">'.$fullname.' ('.$row['borrower_id'].')'.'</option>';
	}

	return $data;
}
?>
