<?php

include './config/connection.php';
include './common_service/common_functions.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "UPDATE `patients` SET `is_del` = '1' WHERE `id` = '$id';";

    try {

        $con->beginTransaction();
    
        $stmtPatient = $con->prepare($query);
        $stmtPatient->execute();
    
        $con->commit();
    
        $message = 'Patient info deleted successfully.';
    
    } catch(PDOException $ex) {
        $con->rollback();
    
        echo $ex->getMessage();
        echo $ex->getTraceAsString();
        exit;
    }

    header("Location:congratulation.php?goto_page=patients.php&message=$message");
    exit;
}

?>