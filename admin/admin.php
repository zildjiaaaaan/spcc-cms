<?php 

include '../config/connection.php';

$message = '';

if(isset($_POST['admin_login'])) {

    $id = '';

    $userName = $_POST['user_name'];
    $password = $_POST['password'];

    $encryptedPassword = md5($password);

    $query = "SELECT * FROM `users`
        WHERE `user_name` = '$userName'
            AND `password` = '$encryptedPassword'
            AND `access_lvl` = 'Admin'
        ;";

    try {
        $stmtLogin = $con->prepare($query);
        $stmtLogin->execute();

        $count = $stmtLogin->rowCount();

        if($count == 1) {
            $row = $stmtLogin->fetch(PDO::FETCH_ASSOC);

            $_SESSION['admin'] = $row['access_lvl'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['display_name'] = $row['display_name'];
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['profile_picture'] = $row['profile_picture'];
            $_SESSION['dark_mode'] = '1';

            header("location:../dashboard.php");
            exit;

        } else {
            $message = 'Incorrect username or password.';
        }

    }  catch(PDOException $ex) {
        echo $ex->getTraceAsString();
        echo $ex->getMessage();
        exit;
    }		
}

if (isset($_SESSION['user_id'])) {
    header("location:../dashboard.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPCC Clinic - Admin Panel</title>
    <style>
        /* Importing fonts from Google */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

    /* Reseting */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background: #ecf0f3;
    }

    .wrapper {
        max-width: 350px;
        min-height: 500px;
        margin: 80px auto;
        padding: 40px 30px 30px 30px;
        background-color: #ecf0f3;
        border-radius: 15px;
        box-shadow: 13px 13px 20px #cbced1, -13px -13px 20px #fff;
    }

    .logo {
        width: 80px;
        margin: auto;
    }

    .logo img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        box-shadow: 0px 0px 3px #5f5f5f,
            0px 0px 0px 5px #ecf0f3,
            8px 8px 15px #a7aaa7,
            -8px -8px 15px #fff;
    }

    .wrapper .name {
        font-weight: 600;
        font-size: 1.4rem;
        letter-spacing: 1.3px;
        color: #555;
        text-align: center;
    }

    .wrapper .form-field input {
        width: 100%;
        display: block;
        border: none;
        outline: none;
        background: none;
        font-size: 1.2rem;
        color: #666;
        padding: 10px 15px 10px 10px;
        /* border: 1px solid red; */
    }

    .wrapper .form-field {
        padding-left: 10px;
        margin-bottom: 20px;
        border-radius: 20px;
        box-shadow: inset 8px 8px 8px #cbced1, inset -8px -8px 8px #fff;
    }

    .wrapper .form-field .fas {
        color: #555;
    }

    .wrapper .btn {
        box-shadow: none;
        width: 100%;
        height: 40px;
        background-color: #03A9F4;
        color: #fff;
        border-radius: 25px;
        box-shadow: 3px 3px 3px #b1b1b1,
            -3px -3px 3px #fff;
        letter-spacing: 1.3px;
    }

    .wrapper .btn:hover {
        background-color: #039BE5;
    }

    .wrapper a {
        text-decoration: none;
        font-size: 0.8rem;
        color: #03A9F4;
    }

    .wrapper a:hover {
        color: #039BE5;
    }

    @media(max-width: 380px) {
        .wrapper {
            margin: 30px 20px;
            padding: 40px 15px 15px 15px;
        }
    }
    </style>
</head>
<body>
    <div class="clearfix" style="height:50px;">&nbsp;</div>
    <div class="wrapper">
        <div class="logo">
            <img src="../dist/img/logo1.png" alt="">
        </div>
        <div class="clearspace">&nbsp;</div>
        <div class="text-center name">
            SPCC Clinic
            <p style="font-size:12px;">Admin Panel</p>
            <br>
        </div>
        <form class="p-3 mt-3" method="POST">
            <div class="form-field d-flex align-items-center">
                <span class="far fa-user"></span>
                <input type="text" name="user_name" id="userName" placeholder="Username">
            </div>
            <div class="form-field d-flex align-items-center">
                <span class="fas fa-key"></span>
                <input type="password" name="password" id="pwd" placeholder="Password">
            </div>
            <button type="submit" class="btn mt-3" name="admin_login">Login</button>
        </form>
    </div>
</body>
</html>