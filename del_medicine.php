<?php

include './config/connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "UPDATE `medicines` set `is_del` = '1' where `id`= $id";   

    try {

        $con->beginTransaction();
    
        $stmtMedicine = $con->prepare($query);
        $stmtMedicine->execute();
    
        $con->commit();
    
        $message = 'Medicine deleted successfully.';
    
    } catch(PDOException $ex) {
        $con->rollback();
    
        echo $ex->getMessage();
        echo $ex->getTraceAsString();
        exit;
    }

    header("Location:congratulation.php?goto_page=medicines.php&message=$message");
    exit;
}

if (isset($_GET['delId'])) {
    $id = $_GET['delId'];
    $query = "UPDATE `medicine_details` set `is_del` = '1' where `id`= $id";   

    try {

        $con->beginTransaction();
    
        $stmtMedicineDetails = $con->prepare($query);
        $stmtMedicineDetails->execute();
    
        $con->commit();
    
        $message = 'Medicine Unit Deleted Successfully.';
    
    } catch(PDOException $ex) {
        $con->rollback();
    
        echo $ex->getMessage();
        echo $ex->getTraceAsString();
        exit;
    }

    header("Location:congratulation.php?goto_page=medicine_details.php&message=$message");
    exit;
}

?>