<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: ../../index.php");
  exit();
}
define('ALLOW_ACCESS', true);

// Database connection (Make sure this is included or requires the proper database connection)
include('../../asset/database/db.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>III | DASHBOARD</title>

  <!-- EXTERNAL CSS -->
  <link rel="stylesheet" href="../../asset/css/dashboard/dashboard_style.css">

  <!-- ICON LOGO -->
  <link rel="icon" href="../../asset/img/logo.png">
  <!-- datatable -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

  <!-- FontAwesome Icons -->
  <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script>
  <style>
    /* CSS to handle text overflow and ensure all characters fit inside the table */
    table {
      width: 100%;
      table-layout: fixed;
      /* Forces table to have fixed column width */
    }

    th,
    td {
      word-wrap: break-word;
      /* Ensures text wraps within the cell */
      overflow: hidden;
      text-overflow: ellipsis;
      /* Add ellipsis for overflow text */
      max-width: 200px;
      /* Optional: Limit width of columns to fit content */
    }

    /* Optionally add a scrollable container for the table if it exceeds the page width */
    .table-container {
      max-width: 100%;
      overflow-x: auto;
      /* Add horizontal scrollbar if needed */
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <?php require '../../asset/includes/sidebar.php'; ?>
    <div class="main p-3">
      <div class="container">
        <div class="row g-4">
          <!-- Projects, Sales, and Stocks Cards -->
          <div class="col-md-6">
            <div class="card p-3 d-flex align-items-center card-toggle" data-target="#projectsGraph">
              <div class="d-flex align-items-center">
                <span style="color: #0c95b9;">
                  <i class="fa-solid fa-diagram-project card-icon" style="font-size: 50px;"></i>
                </span>
                <div>
                  <h5>Projects</h5>
                  <?php
                  // Assuming database connection is already established
                  $query = "SELECT COUNT(project_id) AS total_projects FROM project WHERE YEAR(date_requested) = YEAR(CURDATE())";
                  $result = mysqli_query($conn, $query);

                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $totalProjects = $row['total_projects'];
                  } else {
                    // Handle query error if needed
                    $totalProjects = 0;
                  }
                  ?>
                  <p class="mb-0"><?= $totalProjects ?></p>
                </div>
              </div>
            </div>
            <div class="graph-container" id="projectsGraph">
              <canvas id="projectsChart"></canvas>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card p-3 d-flex align-items-center card-toggle" data-target="#salesGraph">
              <div class="d-flex align-items-center">
                <span style="color: #ffbb02;">
                  <i class="fa-solid fa-tag card-icon" style="font-size: 50px;"></i>
                </span>
                <div>
                  <h5>Gross Sales</h5>
                  <p class="mb-0">6</p>
                </div>
              </div>
            </div>
            <div class="graph-container" id="salesGraph">
              <canvas id="salesChart"></canvas>
            </div>
          </div>

          <!-- <div class="col-md-4">
            <div class="card p-3 d-flex align-items-center card-toggle" data-target="#stocksGraph">
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
          </div> -->
        </div>

        <!-- Real-Time Stock Levels -->
        <div class="row mt-4">
          <?php
          // Get stock data from the database where quantity is less than min_stocks
          $query = "SELECT item_name, category, quantity, min_stocks, max_stocks, last_updated FROM stocks WHERE quantity < min_stocks ORDER BY quantity ASC";
          $result = mysqli_query($conn, $query);
          ?>

          <div class="col-12">
            <div class="card p-3">
              <h5 class="text-center">Real-Time Stock Levels</h5>
              <table class="table table-striped" id="stockTable">
                <thead>
                  <tr>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Assuming $result is your query result
                  while ($row = mysqli_fetch_assoc($result)):
                    $quantity = $row['quantity'];
                    $min_stock = $row['min_stocks'];
                    $status = "";

                    // Determine the stock level status
                    if ($quantity == 0) {
                      $status = "<span class='badge bg-danger'>Out of Stock</span>";
                    } elseif ($quantity > 0 && $quantity < $min_stock) {
                      $status = "<span class='badge bg-dark'>Under Stock</span>";
                    }

                    // Only display Out of Stock or Under Stock
                    if ($status): // This ensures that only the relevant statuses are displayed
                      ?>
                      <tr>
                        <td><?= htmlspecialchars($row['item_name']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= $quantity ?></td>
                        <td><?= date("M-d-Y", strtotime($row['last_updated'])) ?></td>
                        <td><?= date("h:i A", strtotime($row['last_updated'])) ?></td>
                        <td><?= $status ?></td> <!-- Display only Out of Stock or Under Stock -->
                      </tr>
                    <?php endif; endwhile; ?>
                </tbody>


              </table>
            </div>
          </div>
          <!-- Projects to Track Status -->
          <div class="col-md-6">
            <div class="card p-3">
              <h5 class="text-center">
                Projects Status Tracker
                <select id="statusFilter" class="form-select"
                  style="width: auto; display: inline-block; margin-left: 15px;">
                  <option value="all">All</option>
                  <option value="pending">Pending</option>
                  <option value="on_production">On Production</option>
                  <option value="for_delivery">For Delivery</option>
                  <option value="delivered">Delivered</option>
                </select>
              </h5>
              <div class="table-container">
                <table class="table table-striped" id="projectsTable">
                  <thead>
                    <tr>
                      <th>Project ID</th>
                      <th>Client Name</th>
                      <th>Project Name</th>
                      <th>Status</th>
                      <th>Due Date</th>
                      <th>Age</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Query to fetch all projects with their statuses and client names
                    $query = "
                      SELECT 
                        p.project_id, 
                        c.name, 
                        p.status, 
                        p.date_needed, 
                        DATEDIFF(CURDATE(), p.date_needed) AS age, -- Calculate the delay in days
                        p.services  -- Assuming there's a field for the project/service name
                      FROM project p
                      JOIN client c ON p.client_id = c.client_id  -- Join with the clients table
                    ";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)):
                      ?>
                      <tr class="project-row" data-status="<?= strtolower($row['status']); ?>">
                        <td><?= htmlspecialchars($row['project_id']); ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td> <!-- Display client name -->
                        <td><?= htmlspecialchars($row['services']); ?></td> <!-- Display project/service name -->
                        <td><?= ucfirst(strtolower($row['status'])); ?></td>
                        <td><?= htmlspecialchars($row['date_needed']); ?></td>

                        <!-- Check if the status is "Delivered" before displaying the age -->
                        <?php if (strtolower($row['status']) != 'delivered'): ?>
                          <td><?= htmlspecialchars($row['age']); ?> days</td>
                        <?php else: ?>
                          <td>—</td> <!-- Don't show age if the project is delivered -->
                        <?php endif; ?>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>

                </table>
              </div>
            </div>
          </div>
          <!-- Frequently Used Stock Items (Based on Deduct Transactions) -->
          <div class="col-md-6">
            <div class="card p-3">
              <h5 class="text-center">Frequently Used Stock Items</h5>
              <table class="table table-striped" id="frequentItemsTable">
                <thead>
                  <tr>
                    <th>Item Name</th>
                    <th>Usage Count</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = "
            SELECT 
            s.item_name,
            COUNT(st.transaction_id) AS usage_count
             FROM stock_transaction st
            JOIN stocks s ON st.stock_id = s.stock_id
           WHERE st.transaction_type = 'deduct'
          GROUP BY st.stock_id
            ORDER BY usage_count DESC
          LIMIT 10
        ";
                  $result = mysqli_query($conn, $query);
                  while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($row['item_name']) ?></td>
                      <td><?= $row['usage_count'] ?></td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</body>

</html>

<!-- JS Files -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../../asset/js/graph.js"></script>
<script src="../../asset/js/dashboard_table.js"></script>