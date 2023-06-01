<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-light fixed-top">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
  <a href="dashboard.php" class="navbar-brand">
    <span class="brand-text font-weight-light">SPCC Clinic</span>
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

    <li class="nav-item dropdown" style="margin: 8px 15px 0 0;">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-danger navbar-badge">3</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="user_images\1684405733Darth-Vader-Dark-Minimal-iPhone-Wallpaper.png" alt="User Avatar" class="img-size-50 mr-3 img-circle">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                John Doe
                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">There's an activity 1</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="user_images\1684405733Darth-Vader-Dark-Minimal-iPhone-Wallpaper.png" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                Jane Pierce
                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">Some activity here 2</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="user_images\1684405733Darth-Vader-Dark-Minimal-iPhone-Wallpaper.png" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                Peter Parker
                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">Another activity here 3</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Activities</a>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->