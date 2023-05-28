<?php
include './connection.php';

if(isset($_POST['isLightMode'])){
    $isLightMode = filter_var($_POST['isLightMode'], FILTER_VALIDATE_BOOLEAN);
    if ($isLightMode) {
        $_SESSION['dark_mode'] = "0";
        echo "Light Mode";
    } else {
        $_SESSION['dark_mode'] = "1";
        echo "Dark Mode";
    }    
}


?>