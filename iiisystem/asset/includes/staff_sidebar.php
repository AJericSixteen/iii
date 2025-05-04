<?php
// Restrict direct access
if (!defined('ALLOW_ACCESS')) {
    header("Location: ../404page/404.php");
    exit();
}

$project_current_page = basename($_SERVER['SCRIPT_NAME']);
$project_pages = ['project.php', 'project_history.php'];
$projects_active = in_array($project_current_page, $project_pages) ? 'show' : '';

$stock_current_page = basename($_SERVER['PHP_SELF']);
$stocks_pages = [
    'stocks.php',
    'transactions.php',
    'add_stocks.php',
    'deduct_stocks.php',
    'stock_transaction_history.php'
];
$stocks_active = in_array($stock_current_page, $stocks_pages) ? 'show' : '';


?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>III | Dashboard</title>

    <!-- sidebar css -->
    <link rel="stylesheet" href="../../asset/css/sidebar.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../asset/css/fontawesome/all.min.css">
    <link rel="stylesheet" href="../../asset/css/fontawesome/fontawesome.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../asset/bootstrap/bootstrap.css">

    <!-- Main Dashboard CSS -->
    <link rel="stylesheet" href="../css/sidebar.css">
</head>


<aside id="sidebar" class="expand">
    <div class="d-flex">
        <button class="toggle-btn" type="button">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="sidebar-logo">
            <a href="#" style="text-decoration: none;">III ADS SERVICES</a>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="../../staff_dashboard/dashboard/dashboard.php" class="sidebar-link" style="text-decoration: none;"
                title="Dashboard">
                <i class="fa-solid fa-gauge"></i>
                <span>Dasboard</span>
            </a>
        </li>
        <!-- <li class="sidebar-item">
            <a href="../../md_dashboard/project/project.php" class="sidebar-link" style="text-decoration: none;"
                title="Purchase Order">
                <i class="fa-solid fa-diagram-project"></i>
                <span>Project</span>
            </a>
        </li> -->
        <!-- Sidebar Projects Dropdown -->

        <li class="sidebar-item">
            <a href="../../staff_dashboard/project/project.php" class="sidebar-link" style="text-decoration: none;"
                title="Projects">
                <i class="fa-solid fa-diagram-project"></i>
                <span>Project</span>
            </a>
        </li>

        <!-- <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;" title="Receiving">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Receiving</span>
            </a>
        </li> -->
        <!-- <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;" title="Back Order">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
                <span>Back Order</span>
            </a>
        </li> -->
        <!-- <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;" title="Return List">
                <i class="fa-solid fa-arrow-rotate-right"></i>
                <span>Return List</span>
            </a>
        </li> -->
        <!-- Sidebar Stocks Dropdown -->
        <div class="sidebar-item">
            <a href="#" class="sidebar-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#stocksDropdown"
                style="text-decoration: none;" title="Stocks">
                <i class="fa-solid fa-warehouse"></i>
                <span>Stocks</span>
            </a>

            <div id="stocksDropdown" class="collapse sidebar-dropdown <?= $stocks_active ?>">
                <ul>
                    <li>
                        <a href="../../staff_dashboard/stocks/add_stocks.php"
                            class="sidebar-link <?= $stock_current_page == 'add_stocks.php' ? 'active' : '' ?>"
                            style="text-decoration: none;">Add Stocks</a>
                    </li>
                    <li>
                        <a href="../../staff_dashboard/stocks/deduct_stocks.php"
                            class="sidebar-link <?= $stock_current_page == 'deduct_stocks.php' ? 'active' : '' ?>"
                            style="text-decoration: none;">Deduct Stocks</a>
                    </li>
                    <li>
                        <a href="../../staff_dashboard/stocks/stocks.php"
                            class="sidebar-link <?= $stock_current_page == 'stocks.php' ? 'active' : '' ?>"
                            style="text-decoration: none;">Stock Inventory</a>
                    </li>
                    <!-- <li>
                        <a href="../../staff_dashboard/stocks/transactions.php"
                            class="sidebar-link <?= $stock_current_page == 'transactions.php' ? 'active' : '' ?>"
                            style="text-decoration: none;">Stock Transactions</a>
                    </li> -->
                    <!-- <li>
                        <a href="../../staff_dashboard/stocks/stock_transaction_history.php"
                            class="sidebar-link <?= $stock_current_page == 'stock_transaction_history.php' ? 'active' : '' ?>"
                            style="text-decoration: none;">Stock Transaction History</a>
                    </li> -->
                </ul>
            </div>
        </div>
        <!-- <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;" title="Sale List">
                <i class="fa-solid fa-tag"></i>
                <span>Sale List</span>
            </a>
        </li> -->
        <!-- <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;" title="Supplier List">
                <i class="fa-solid fa-truck-ramp-box"></i>
                <span>Supplier List</span>
            </a>
        </li> -->
        <!-- <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;" title="Item List">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Item List</span>
            </a>
        </li> -->
        <!-- <li class="sidebar-item">
            <a href="../employee/user.php" class="sidebar-link" style="text-decoration: none;" title="User List">
                <i class="fa-solid fa-users"></i>
                <span>User List</span>
            </a>
        </li> -->
        <!-- <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;" title="Settings">
                <i class="fa-solid fa-cog"></i>
                <span>Settings</span>
            </a>
        </li> -->
    </ul>
    <div class="sidebar-footer">
        <a href="../../logout.php" class="sidebar-link" style="text-decoration: none;" title="Logout">
            <i class="fa-solid fa-sign-out"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
<script src="../../asset/js/sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>