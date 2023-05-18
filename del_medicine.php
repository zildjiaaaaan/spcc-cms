<?php

include './config/connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "UPDATE `medicines` set `is_del` = '1' where `id`= $id";   

    try {

        $con->beginTransaction();
    
        $stmtPatient = $con->prepare($query);
        $stmtPatient->execute();
    
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

?>