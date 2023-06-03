<?php

include './config/connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $q_checkMedDetails = "SELECT `id` FROM `medicine_details`
        WHERE `medicine_id` = '$id' AND `is_del` = '0'
    ;";
    
    $query = "UPDATE `medicines` set `is_del` = '1' where `id`= $id";

    $stmt_checkMedDetails = $con->prepare($q_checkMedDetails);
    $stmt_checkMedDetails->execute();
    $r = $stmt_checkMedDetails->fetch(PDO::FETCH_ASSOC);

   

    try {

        $con->beginTransaction();
    
        $stmtMedicine = $con->prepare($query);
        $stmtMedicine->execute();

        foreach ($r as $row) {
            $id = $row['id'];
            $medDetailsId = $r['id'];
            $q_delMedDetails = "UPDATE `medicine_details` SET `is_del` = '1' WHERE `id`= '$id';";
            $stmt_delMedDetails = $con->prepare($q_delMedDetails);
            $stmt_delMedDetails->execute();
        }
    
        $con->commit();
    
        $message = 'Medicine Brand Deleted Successfully.';
    
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