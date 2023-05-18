<?php

include './config/connection.php';

$query = '';
$location = '';

if (isset($_GET['patient_id'])) {
    $id = $_GET['patient_id'];
    $query = "UPDATE `patients` SET `is_del` = '0' WHERE `id` = '$id';";
    $location = "patient";
} else if (isset($_GET['med_id'])) {
    $id = $_GET['med_id'];
    $query = "UPDATE `medicines` set `is_del` = '0' where `id`= $id";
    $location = "medicine";
}

try {

    $con->beginTransaction();

    $stmtPatient = $con->prepare($query);
    $stmtPatient->execute();

    $con->commit();

    $message = 'Item Restored Successfully.';

} catch(PDOException $ex) {
    $con->rollback();

    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;
}

header("Location:congratulation.php?goto_page=trash.php&recover=$location&message=$message");
exit;

?>