<?php

include './config/connection.php';

if (isset($_GET['id'])) {
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