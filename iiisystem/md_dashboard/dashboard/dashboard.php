<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
define('ALLOW_ACCESS', true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>III | Dashboard</title>

  <!-- EXTERNAL CSS -->
  <link rel="stylesheet" href="../../asset/css/dashboard/dashboard_style.css">

  <!-- ICON LOGO -->
  <link rel="icon" href="../../asset/img/logo.png">

</head>

<style>
  
</style>

<body>
  <div class="wrapper">
    <?php require '../../asset/includes/sidebar.php'; ?>
    <div class="main p-3">
      <div class="container">
        <h2 class="dashboard-title text-center">
          Welcome to III Advertising Services<br> Inventory Management System
        </h2>
        <hr class="divider">
        <div class="row g-4">
          <!-- Projects Card -->
          <div class="col-md-4">
            <div class="card p-3 d-flex align-items-center" id="projectsCard">
              <div class="d-flex align-items-center">
                <span style="color: #0c95b9;">
                  <i class="fa-solid fa-diagram-project card-icon" style="font-size: 50px;"></i>
                </span>
                <div>
                  <h5>Projects</h5>
                  <p class="mb-0">2</p>
                </div>
              </div>
            </div>
            <div class="graph-container" id="projectsGraph">
              <canvas id="projectsChart"></canvas>
            </div>
          </div>
          <!-- Sales Card -->
          <div class="col-md-4">
            <div class="card p-3 d-flex align-items-center" id="salesCard">
              <div class="d-flex align-items-center">
                <span style="color: #ffbb02;">
                  <i class="fa-solid fa-tag card-icon" style="font-size: 50px;"></i>
                </span>
                <div>
                  <h5>Sales</h5>
                  <p class="mb-0">6</p>
                </div>
              </div>
            </div>
            <div class="graph-container" id="salesGraph">
              <canvas id="salesChart"></canvas>
            </div>
          </div>
          <!-- Stocks Card -->
          <div class="col-md-4">
            <div class="card p-3 d-flex align-items-center" id="stocksCard">
              <div class="d-flex align-items-center">
                <span style="color: #0077ff;">
                  <i class="fa-solid fa-warehouse card-icon" style="font-size: 50px;"></i>
                </span>
                <div>
                  <h5>Stocks</h5>
                  <p class="mb-0">4</p>
                </div>
              </div>
            </div>
            <div class="graph-container" id="stocksGraph">
              <canvas id="stocksChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JS Files -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../../asset/js/graph.js"></script>
</body>
</html>
