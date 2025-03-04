<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php"); // Redirect to login if not logged in
    exit();
}
?>
<?php define('ALLOW_ACCESS', true); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>III | Dashboard</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">

    <!-- external css -->
    <link rel="stylesheet" href="../../asset/css/sidebar.css">
    <link rel="stylesheet" href="../../asset/css/dashboard/dashboard_style.css">

    <!-- Logo -->
    <link rel="icon" href="../../asset/img/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">

</head>


<body>
    <div class="wrapper">
        <?php include '../sidebar.php'; ?>
        <div class="main p-3">
        <div class="container">
    <h2 class="dashboard-title text-center">Welcome to III Advertising Services<br> Inventory Management System</h2>
    <hr class="divider">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card p-3 d-flex align-items-center">
          <div class="d-flex align-items-center">
            <!-- PO Records Icon -->
             <span style="color: #0c95b9;">
            <i class="fa-solid fa-th card-icon"></i>
            </span>
            <div>
              <h5>PO Records</h5>
              <p class="mb-0">2</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 d-flex align-items-center">
          <div class="d-flex align-items-center">
            <!-- Receiving Records Icon -->
             <span style="color: #ffbb02;">
            <i class="fa-solid fa-archive card-icon"></i>
            </span>
            <div>
              <h5>Receiving Records</h5>
              <p class="mb-0">6</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 d-flex align-items-center">
          <div class="d-flex align-items-center">
            <!-- BO Records Icon -->
             <span style="color: #0077ff;">
            <i class="fa-solid fa-arrow-right-arrow-left card-icon"></i>
            </span>
            <div>
              <h5>BO Records</h5>
              <p class="mb-0">4</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 d-flex align-items-center">
          <div class="d-flex align-items-center">
            <!-- Return Records Icon -->
             <span style="color: #e84743;">
            <i class="fa-solid fa-sync card-icon"></i>
            </span>
            <div>
              <h5>Return Records</h5>
              <p class="mb-0">1</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 d-flex align-items-center">
          <div class="d-flex align-items-center">
            <!-- Sales Records Icon -->
            <span style="color: #1c9444;">
            <i class="fa-solid fa-shopping-cart card-icon"></i>
            </span>
            <div>
              <h5>Sales Records</h5>
              <p class="mb-0">1</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 d-flex align-items-center">
          <div class="d-flex align-items-center">
            <!-- Sales Records Icon -->
             <span style="color #001d40;">
            <i class="fa-solid fa-truck-ramp-box card-icon"></i>
            </span>
            <div>
              <h5>Suppliers</h5>
              <p class="mb-0">1</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 d-flex align-items-center">
          <div class="d-flex align-items-center">
            <!-- Sales Records Icon -->
             <span style="color: #3687c2;">
            <i class="fa-solid fa-boxes-stacked card-icon"></i>
            </span>
            <div>
              <h5>Items</h5>
              <p class="mb-0">1</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 d-flex align-items-center">
          <div class="d-flex align-items-center">
            <!-- Users Icon -->
             <span style="color: #11b697;">
            <i class="fa-solid fa-users card-icon"></i>
            </span>
            <div>
              <h5>Users</h5>
              <p class="mb-0">2</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
        </div>
    </div>
    </div>

    <!-- JS Files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../asset/js/script.js"></script>
</body>

</html>