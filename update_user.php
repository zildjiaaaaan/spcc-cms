<?php
include './config/connection.php';
include './common_service/common_functions.php';
$message = '';
$user_id = $_GET['user_id'];

$query = "SELECT * from `users` where `id` = '$user_id';";

try {
  $stmtUpdateUser = $con->prepare($query);
  $stmtUpdateUser->execute();
  $row = $stmtUpdateUser->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
  echo $ex->getTraceAsString();
  echo $ex->getMessage();
  exit;
}

if (isset($_POST['save_user'])) {
  // current_display
  // current_user
  // current_lvl
  // current_img
  $displayName = ($_POST['display_name'] != '') ? trim($_POST['display_name']) : $_POST['current_display'];
  $userName = ($_POST['username'] != '') ? trim($_POST['username']) : $_POST['current_user'];
  $accessLvl = (isset($_POST['access_lvl'])) ? $_POST['access_lvl'] : $_POST['current_lvl'];
  $password = ($_POST['password'] != '') ? md5($_POST['password']) : '';
  $hiddenId = $_POST['hidden_id'];
  $targetFile = $_POST['current_img'];

  $status = true;
  $oldFile = '';

  if (!empty($_FILES["profile_picture"]["name"])) {
      $allowedExtensions = array('png', 'jpg', 'jpeg');
      $baseName = basename($_FILES["profile_picture"]["name"]);
      $fileExtension = strtolower(pathinfo($baseName, PATHINFO_EXTENSION));

      // Check if the uploaded file has a valid extension
      if (in_array($fileExtension, $allowedExtensions)) {
          $oldFile = 'user_images/'.$targetFile;
          $targetFile = time() . $baseName;
          $status = move_uploaded_file($_FILES["profile_picture"]["tmp_name"], 'user_images/' . $targetFile);

      } else {
          // Invalid file format, handle the error as needed
          $message = "Invalid file format. Only PNG, JPG, or JPEG files are allowed.";
          $status = false;
      }
  }

  if ($status) {
    try {
      $updateUserQuery = "UPDATE `users`
          SET `display_name` = '$displayName',
          `user_name` = '$userName',
          ";

      if ($password != '') {
          $updateUserQuery .= "`password` = '$password', ";
      }

      $updateUserQuery .= "`profile_picture` = '$targetFile',
          `access_lvl` = '$accessLvl'
          WHERE `id` = '$hiddenId';";

      $con->beginTransaction();
      $stmtUpdateUser = $con->prepare($updateUserQuery);
      $stmtUpdateUser->execute();

      $con->commit();

      if (file_exists($oldFile) && $oldFile != 'user_images/admin.png' && $oldFile != 'user_images/clinic-staff.png') {
        unlink($oldFile);        
      }

      $message = "User Info Updated Successfully";

      $_SESSION['profile_picture'] = $targetFile;
  
    } catch(PDOException $ex) {
      $con->rollback();
      echo $ex->getTraceAsString();
      echo $ex->getMessage();
      exit;
    }
  }

  

  header("Location:congratulation.php?goto_page=users.php&message=$message");
  exit;
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php';?>

 <title>Update Users - SPCC Caloocan Clinic</title>

</head>
<body class="hold-transition sidebar-mini dark-mode layout-fixed layout-navbar-fixed">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <?php include './config/header.php';
include './config/sidebar.php';?>  
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Users</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Update User</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>

            </div>
          </div>
          <div class="card-body">
            <form method="post" enctype="multipart/form-data">
              <input type="hidden" name="hidden_id" value="<?php echo $user_id;?>">
              <input type="hidden" name="current_display" id="current_display" value="<?php echo $row['display_name'];?>" />
              <input type="hidden" name="current_user" id="current_user" value="<?php echo $row['user_name'];?>" />
              <input type="hidden" name="current_lvl" id="current_lvl" value="<?php echo $row['access_lvl'];?>" />
              <input type="hidden" name="current_img" id="current_img" value="<?php echo $row['profile_picture'];?>" />

              <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                  <label>Display Name</label>
                  <input type="text" id="display_name" name="display_name" required="required"
                  class="form-control form-control-sm rounded-0" value="<?php echo $row['display_name'];?>" />
                </div>
                <br>
                <br>
                <br>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                  <label>Username</label> 
                  <input type="text" id="username" name="username" required="required"
                  class="form-control form-control-sm rounded-0" value="<?php echo $row['user_name'];?>" />
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                  <label>Password</label> 
                  <input type="password" id="password" name="password" class="form-control form-control-sm rounded-0"/>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                  <label>Profile picture</label>
                  <input type="file" id="profile_picture" name="profile_picture" class="form-control form-control-sm rounded-0" />
                </div>

                <?php
                  if (isset($_SESSION['admin'])) {
                ?>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                  <label>Access</label>
                  <select id="access_lvl" name="access_lvl" class="form-control form-control-sm rounded-0" required>
                    <option value="">Select Role</option>
                    <option <?php echo ($row['access_lvl'] == "Admin") ? "selected": ""; ?> value="Admin">Admin</option>
                    <option <?php echo ($row['access_lvl'] == "Staff") ? "selected": ""; ?> value="Staff">Staff</option>
                  </select>
                </div>
                <?php } ?>

              </div>
              <div class="clearfix">&nbsp;</div>
              <div class="row">
                <div class="col-lg-11 col-md-10 col-sm-10">&nbsp;</div>
                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
                  <button type="submit" id="save_user" 
                  name="save_user" class="btn btn-primary btn-sm btn-flat btn-block">Update</button>
                </div>
              </div>
              
            </div>

            
          </form>
        </div>
        
      </div>
      
    </section>


    <?php 
    include './config/footer.php';

    $message = '';
    if(isset($_GET['message'])) {
      $message = $_GET['message'];
    }
    ?>  

    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <?php include './config/site_js_links.php'; ?>


  <script>

    var message = '<?php echo $message;?>';

    if(message !== '') {
      showCustomMessage(message);
    }
    


  </script>
</body>
</html>