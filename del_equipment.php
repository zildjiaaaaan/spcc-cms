<?php

include './config/connection.php';

if (isset($_GET['delId']) && isset($_GET['qty']) && isset($_GET['id'])) {
    $id = $_GET['delId'];
    $e_id = $_GET['id'];
    $qty = $_GET['qty'];

    $q_update_qty = "UPDATE `equipments`
        SET `total_qty` = `total_qty` - $qty
        WHERE `id` = '$e_id'
    ;";

    $query = "UPDATE `equipment_details` SET `is_del` = '1' WHERE `id`= $id;";


    try {

        $con->beginTransaction();
    
        $stmtEquipmentDetails = $con->prepare($query);
        $stmtEquipmentDetails->execute();

        $stmt_update_qty = $con->prepare($q_update_qty);
        $stmt_update_qty->execute();
    
        $con->commit();
    
        $message = 'Equipment Units Deleted Successfully.';
    
    } catch(PDOException $ex) {
        $con->rollback();
    
        echo $ex->getMessage();
        echo $ex->getTraceAsString();
        exit;
    }

    header("Location:congratulation.php?goto_page=equipment_inventory.php&message=$message");
    exit;
} else if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "UPDATE `equipments` set `is_del` = '1' where `id`= $id";   

    try {

        $con->beginTransaction();
    
        $stmtEquipment = $con->prepare($query);
        $stmtEquipment->execute();
    
        $con->commit();
    
        $message = 'Equipment Deleted Successfully.';
    
    } catch(PDOException $ex) {
        $con->rollback();
    
        echo $ex->getMessage();
        echo $ex->getTraceAsString();
        exit;
    }

    header("Location:congratulation.php?goto_page=equipments.php&message=$message");
    exit;
}

?>