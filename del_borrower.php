<?php

include './config/connection.php';
include './common_service/common_functions.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "UPDATE `borrowers` SET `is_del` = '1' WHERE `id` = '$id';";

    try {

        $con->beginTransaction();
    
        $stmtBorrower = $con->prepare($query);
        $stmtBorrower->execute();
    
        $con->commit();
    
        $message = 'Borrower Info Deleted Successfully.';
    
    } catch(PDOException $ex) {
        $con->rollback();
    
        echo $ex->getMessage();
        echo $ex->getTraceAsString();
        exit;
    }

    header("Location:congratulation.php?goto_page=borrowers.php&message=$message");
    exit;
}

?>