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
} else if (isset($_GET['id']) && isset($_GET['qty'])) {
    $id = $_GET['id'];
    $qty = $_GET['qty'];

    $q_checkEquipDetails = "SELECT `id` FROM `equipment_details`
        WHERE `equipment_id` = '$id' AND `state` <> 'Borrowed'
        AND `is_del` = '0' AND `quantity` > '0'
    ;";

    $q_totalDecrease = "SELECT SUM(`quantity`) AS `total_dec` FROM `equipment_details`
        WHERE `equipment_id` = '$id' AND `state` <> 'Borrowed'
        AND `is_del` = '0' AND `quantity` > '0'
    ;";

    $stmt_checkEquipDetails = $con->prepare($q_checkEquipDetails);
    $stmt_checkEquipDetails->execute();
    $r = $stmt_checkEquipDetails->fetch(PDO::FETCH_ASSOC);

    $stmt_totalDecrease = $con->prepare($q_totalDecrease);
    $stmt_totalDecrease->execute();
    $new = $stmt_totalDecrease->fetch(PDO::FETCH_ASSOC);
    $diff = $qty - $new['total_dec'];

    $query = "UPDATE `equipments` SET `total_qty` = '$diff', `is_del` = '1' where `id`= $id";   

    try {

        $con->beginTransaction();
    
        $stmtEquipment = $con->prepare($query);
        $stmtEquipment->execute();

        foreach ($r as $row) {
            $id = $row['id'];
            $q_delEquipDetails = "UPDATE `equipment_details` SET `is_del` = '1' WHERE `id`= '$id';";
            $stmt_delEquipDetails = $con->prepare($q_delEquipDetails);
            $stmt_delEquipDetails->execute();
        }
    
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