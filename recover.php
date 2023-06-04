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

    $q_check = "SELECT * FROM `medicines` WHERE `id` = '$med_id' AND `is_del` = '0';";

    $stmtcheck = $con->prepare($q_check);
    $stmtcheck->execute();
    if (empty($stmtcheck->fetch(PDO::FETCH_ASSOC))) {
        $recover = false;
    }

    $message = 'The `Medicine Brand` of this item was deleted. Please recover it first.';
    $query = "UPDATE `medicine_details` set `is_del` = '0' where `id`= $id";
    $location = "medicine_details";

} else if (isset($_GET['med_id'])) {
    $id = $_GET['med_id'];
    $query = "UPDATE `medicines` set `is_del` = '0' where `id`= $id";
    $location = "medicine";
} else if (isset($_GET['equipmentdetails_id']) && isset($_GET['equipment_id'])) {
    $id = $_GET['equipmentdetails_id'];
    $e_id = $_GET['equipment_id'];
    $qty = $_GET['qty'];

    $q_check = "SELECT * FROM `equipments` WHERE `id` = '$e_id' AND `is_del` = '0';";
    $stmtcheck = $con->prepare($q_check);
    $stmtcheck->execute();
    if (empty($stmtcheck->fetch(PDO::FETCH_ASSOC))) {
        $recover = false;
    }

    $message = 'The `Equipment Type` of this item was deleted. Please recover it first.';

    $q_update_qty = "UPDATE `equipments`
        SET `total_qty` = `total_qty` + $qty
        WHERE `id` = '$e_id'
    ;";

    $query = "UPDATE `equipment_details` set `is_del` = '0' where `id`= $id";
    $location = "equipment_inventory";

} else if (isset($_GET['equipment_id'])) {
    $id = $_GET['equipment_id'];
    $query = "UPDATE `equipments` set `is_del` = '0' where `id`= $id";
    $location = "equipments";
}  else if (isset($_GET['borrower_id'])) {
    $id = $_GET['borrower_id'];
    $query = "UPDATE `borrowers` set `is_del` = '0' where `id`= $id";
    $location = "borrower";
}

if ($recover) {
    try {

        $con->beginTransaction();
    
        $stmtRecover = $con->prepare($query);
        $stmtRecover->execute();

        $stmt_update_qty = $con->prepare($q_update_qty);
        $stmt_update_qty->execute();
    
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