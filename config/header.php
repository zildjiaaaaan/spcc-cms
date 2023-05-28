<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-light fixed-top">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
  <a href="dashboard.php" class="navbar-brand">
    <span class="brand-text font-weight-light">Clinic Management System</span>
  </a>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- <li class="nav-item">
      <div class="login-user text-light font-weight-bolder right">Hello, <?= $_SESSION['display_name'] ?>!</div>
    </li> -->
    <li class="nav-item">
      <div class="custom-control custom-switch" style="padding-bottom: 10px;">
        <input type="checkbox" class="custom-control-input" id="customSwitch1" <?php echo ($_SESSION['dark_mode'] != "1") ? "checked" : ""; ?>>
        <label class="custom-control-label" for="customSwitch1">&nbsp;</label>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->