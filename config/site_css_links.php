 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="dist/css/fonts.css">
  <!-- Font Awesome -->

  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
    

  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <link rel="stylesheet" href="dist/js/jquery_confirm/jquery-confirm.css">
  

  <link rel="stylesheet" href="dist/css/default.css" />

  <link href="plugins/select2/css/select2.min.css" rel="stylesheet" />

  <link rel="icon" type="image/x-icon" href="dist/img/logo1.png">

<style>
  #loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    /* background-color: rgba(0, 15, 30, 0.98);  */
    background-color: <?php echo ($_SESSION['dark_mode'] != "1") ? "rgba(255, 255, 255, 0.99)" : "rgba(0, 15, 30, 0.99)" ; ?>;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  #loader {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  .cell-link {
      color: <?php echo ($_SESSION['dark_mode'] != "1") ? "black;" : "white;" ; ?>;
      text-decoration: none;
  }
  .select2-selection {
      background-color: <?php echo ($_SESSION['dark_mode'] != "1") ? "white" : "#343a40" ; ?>;
  }

  .select2-selection__rendered {
      color: <?php echo ($_SESSION['dark_mode'] != "1") ? "black" : "white" ; ?>;
  }

  .select2 {
    width: 100% !important;
  }

</style>