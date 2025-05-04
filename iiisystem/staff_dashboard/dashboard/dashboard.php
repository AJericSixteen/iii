<?php
session_start();
define('ALLOW_ACCESS', true);
require('../../asset/database/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch counts
$active_projects = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM project WHERE status != 'Completed'"))['total'];
$completed_projects = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM project 
    WHERE status = 'Completed' 
    AND MONTH(date_requested) = MONTH(CURRENT_DATE()) 
    AND YEAR(date_requested) = YEAR(CURRENT_DATE())
"))['total'];

// Fetch recent projects
// Fetch recent projects for current month only
$recent_projects = mysqli_query($conn, "
    SELECT p.*, c.name AS client_name
    FROM project p
    LEFT JOIN client c ON p.client_id = c.client_id
    WHERE MONTH(p.date_requested) = MONTH(CURRENT_DATE())
    AND YEAR(p.date_requested) = YEAR(CURRENT_DATE())
    ORDER BY p.date_requested DESC
");

// Fetch recent stock transactions with JOIN to get stock name
$recent_transactions = mysqli_query($conn, "
    SELECT st.*, s.item_name AS stock_name 
    FROM stock_transaction st
    JOIN stocks s ON st.stock_id = s.stock_id
    WHERE MONTH(st.date) = MONTH(CURRENT_DATE())
    AND YEAR(st.date) = YEAR(CURRENT_DATE())
    ORDER BY st.date DESC
");

// Fetch projects for delivery today
date_default_timezone_set('Asia/Manila');
$today_date = date('Y-m-d'); // Get today's date
$projects_for_delivery_today = mysqli_query($conn, "
    SELECT p.*, c.name 
    FROM project p
    LEFT JOIN client c ON p.client_id = c.client_id
    WHERE p.status = 'For Delivery' 
    AND DATE(p.date_needed) = '$today_date'  -- Handle both DATE and DATETIME format
    ORDER BY p.date_requested DESC
");

// Calculate progress bar for completed projects
$total_projects = $active_projects + $completed_projects;
$completion_rate = $total_projects > 0 ? round(($completed_projects / $total_projects) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>III | Staff Dashboard</title>
    <link rel="icon" href="../../asset/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../asset/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <div class="wrapper d-flex">
        <?php require '../../asset/includes/staff_sidebar.php'; ?>
        <div class="main p-4 w-100">
            <div class="row mb-4 justify-content-center">
                <div class="col-12 col-md-5 mb-3 mb-md-0">
                    <div class="bg-primary text-white p-4 rounded shadow">
                        <h5>Current Projects</h5>
                        <h2><?= $active_projects ?></h2>
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <div class="bg-success text-white p-4 rounded shadow">
                        <h5>Completed Projects</h5>
                        <h2><?= $completed_projects ?></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Projects for Delivery Today Table Card -->
                <div class="col-12 mb-4">
                    <div class="card mt-4">
                        <div class="card-header">
                            Projects for Delivery Today
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="deliveryTodayTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Client</th>
                                            <th>Services</th>
                                            <th>Date Requested</th>
                                            <th>Date Needed</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 1;
                                        if (mysqli_num_rows($projects_for_delivery_today) > 0):
                                            while ($proj = mysqli_fetch_assoc($projects_for_delivery_today)): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($proj['client_id']) ?></td>
                                                    <td><?= htmlspecialchars($proj['name']) ?></td>
                                                    <td><?= htmlspecialchars($proj['services']) ?></td>
                                                    <td><?= $proj['date_requested'] ?></td>
                                                    <td><?= $proj['date_needed'] ?></td>
                                                    <td>
                                                        <?php
                                                        $status = $proj['status'];
                                                        $badge_class = 'secondary';
                                                        if ($status == 'Pending')
                                                            $badge_class = 'warning';
                                                        elseif ($status == 'In Progress')
                                                            $badge_class = 'primary';
                                                        elseif ($status == 'Delivered')
                                                            $badge_class = 'info';
                                                        ?>
                                                        <span class="badge bg-<?= $badge_class ?>"><?= $status ?></span>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No projects for delivery today.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Projects Table Card -->
                <div class="col-12 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            Recent Project Requests (This Month)
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="projectsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Client</th>
                                            <th>Service</th>
                                            <th>Status</th>
                                            <th>Date Requested</th>
                                            <th>Date Needed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter = 1;
                                        while ($proj = mysqli_fetch_assoc($recent_projects)): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($proj['client_id']) ?></td>
                                                <td><?= htmlspecialchars($proj['client_name']) ?></td>
                                                <td><?= htmlspecialchars($proj['services']) ?></td>
                                                <td><?= htmlspecialchars($proj['status']) ?></td>
                                                <td><?= $proj['date_requested'] ?></td>
                                                <td><?= $proj['date_needed'] ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Stock Transactions Table Card -->
                <div class="col-12 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            Recent Stock Transactions (This Month)
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="transactionsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter = 1;
                                        while ($txn = mysqli_fetch_assoc($recent_transactions)): ?>
                                            <tr>
                                                <td><?= $counter++ ?></td>
                                                <td><?= htmlspecialchars($txn['stock_name']) ?></td>
                                                <td><?= $txn['quantity'] ?></td>
                                                <td><?= $txn['date'] ?></td>
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
    </div>

    <script src="../../asset/js/bootstrap.bundle.min.js"></script>
    <!-- DataTable Initialization -->
    <script>
        $(document).ready(function () {
            $('#projectsTable').DataTable();
            $('#transactionsTable').DataTable();
            $('#deliveryTodayTable').DataTable();
        });
    </script>
</body>

</html>