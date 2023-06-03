<?php

include './config/connection.php';

$query = '';
$location = '';
$recover = true;

if (isset($_GET['patient_id'])) {
    $id = $_GET['patient_id'];
    $query = "UPDATE `patients` SET `is_del` = '0' WHERE `id` = '$id';";
    $location = "patient";
} else if (isset($_GET['meddetails_id']) && isset($_GET['med_id'])) {
    $id = $_GET['meddetails_id'];
    $med_id = $_GET['med_id'];

    $checkMed = "SELECT * FROM `medicines` WHERE `id` = '$med_id' AND `is_del` = '0';";

    $stmtcheckMed = $con->prepare($checkMed);
    $stmtcheckMed->execute();
    if (empty($stmtcheckMed->fetch(PDO::FETCH_ASSOC))) {
        $recover = false;
    }

    $message = 'The `Medicine Brand` of this item was deleted. Please recover it first.';
    $query = "UPDATE `medicine_details` set `is_del` = '0' where `id`= $id";
    $location = "medicine_details";

} else if (isset($_GET['med_id'])) {
    $id = $_GET['med_id'];
    $query = "UPDATE `medicines` set `is_del` = '0' where `id`= $id";
    $location = "medicine";
}  else if (isset($_GET['equipment_id'])) {
    $id = $_GET['equipment_id'];
    $query = "UPDATE `equipments` set `is_del` = '0' where `id`= $id";
    $location = "equipments";
} else if (isset($_GET['equipmentdetails_id'])) {
    $id = $_GET['equipmentdetails_id'];
    $query = "UPDATE `equipment_details` set `is_del` = '0' where `id`= $id";
    $location = "equipment_details";
} else if (isset($_GET['borrower_id'])) {
    $id = $_GET['borrower_id'];
    $query = "UPDATE `borrowers` set `is_del` = '0' where `id`= $id";
    $location = "borrower";
}

if ($recover) {
    try {

        $con->beginTransaction();
    
        $stmtRecover = $con->prepare($query);
        $stmtRecover->execute();
    
        $con->commit();
    
        $message = 'Item Restored Successfully.';
    
    } catch(PDOException $ex) {
        $con->rollback();
    
        echo $ex->getMessage();
        echo $ex->getTraceAsString();
        exit;
    }
}

header("Location:congratulation.php?goto_page=trash.php&recover=$location&message=$message");
exit;

?>