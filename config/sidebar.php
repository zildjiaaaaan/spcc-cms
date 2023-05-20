<?php 
if(!(isset($_SESSION['user_id']))) {
  header("location:index.php");
  exit;
}
?>
<aside class="main-sidebar sidebar-dark-primary bg-black elevation-4">
    <a href="#" class="brand-link logo-switch bg-black">
      <!-- <h4 class="brand-image-xl logo-xs mb-0 text-center"><b>SPCC</b></h4> -->
      <span><img style="padding-right: 0px;" src="dist/img/logo1.png" alt="Systems Plus Computer College" class="brand-image-xl logo-s"></span>
      <h4 class="brand-image-xl logo-xl mb-0 text-center" style="margin: 5px 0 0 15px;"><b>SPCC Caloocan</b></h4>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image" style="margin-top: 10px;">
          <img src="user_images/<?php echo $_SESSION['profile_picture'];?>" class="img-circle elevation-2" alt="User Image" />
        </div>
        <div class="info" style="margin-top: 10px;">
          <a href="#" class="d-block"><?php echo $_SESSION['display_name'];?></a>
        </div>
      </div>

      
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item" id="mnu_dashboard">
            <a href="dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          
          <li class="nav-item" id="mnu_patients">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-injured"></i>
              <p>
                Patients
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="new_prescription.php" class="nav-link" 
                id="mi_new_prescription">
                  <i class="far fa-circle nav-icon"></i>
                  <p>New Prescription</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="patients.php" class="nav-link" 
                id="mi_patients">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Patients</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="patient_history.php" class="nav-link" 
                id="mi_patient_history">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Patient History</p>
                </a>
              </li>
              
            </ul>
          </li>



          <li class="nav-item" id="mnu_medicines">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-pills"></i>
              <p>
                Medicines
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="medicines.php" class="nav-link" 
                id="mi_medicines">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Medicine</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="medicine_details.php" class="nav-link" 
                id="mi_medicine_details">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Medicine Details</p>
                </a>
              </li>
                            
            </ul>
          </li>

          <li class="nav-item" id="mnu_equipments">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tools"></i>
              <p>
                Clinic Equipments
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="equipments.php" class="nav-link" 
                id="mi_equipments">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Equipments</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="equipment_details.php" class="nav-link" 
                id="mi_equipment_details">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Equipment Details</p>
                </a>
              </li>
                            
            </ul>
          </li>

          <li class="nav-item" id="mnu_reports">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="reports.php" class="nav-link" 
                id="mi_reports">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reports</p>
                </a>
              </li>
              
            </ul>
          </li>

          <li class="nav-item" id="mnu_trash">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-trash"></i>
              <p>
                Trash
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="trash.php?recover=patient" class="nav-link" 
                id="mi_trash_patient">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Patient Info</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="trash.php?recover=medicine" class="nav-link" 
                id="mi_trash_med">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Medicine Item</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="trash.php?recover=medicine_details" class="nav-link" 
                id="mi_trash_meddetails">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Medicine Details</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="trash.php?recover=equipment" class="nav-link" 
                id="mi_trash_equipment">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Equipment</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="trash.php?recover=equipment_details" class="nav-link" 
                id="mi_trash_equipmentdetails">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Equipment</p>
                </a>
              </li>
              
            </ul>
          </li>

          <li class="nav-item" id="mnu_users">
            <a href="users.php" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>
  
          <li class="nav-item">
            <a href="logout.php" class="nav-link">
              <i class="nav-icon fa fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>