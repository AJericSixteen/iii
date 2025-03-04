<?php
// Restrict direct access
if (!defined('ALLOW_ACCESS')) {
    header("Location: ../404page/404.php");
    exit();
}
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>III | Dashboard</title>

    <!-- Icon -->
    <link rel="icon" href="../asset/img/logo.png">

    <!-- Lineicons -->
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <!-- Main Dashboard CSS -->
    <link rel="stylesheet" href="../asset/css/md_dashboard.css">
</head>


<aside id="sidebar">
    <div class="d-flex">
        <button class="toggle-btn" type="button">
            <i class="lni lni-grid-alt"></i>
        </button>
        <div class="sidebar-logo">
            <a href="#" style="text-decoration: none;">III ADS SERVICES</a>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="../../md_dashboard/dashboard/dashboard.php" class="sidebar-link" style="text-decoration: none;">
                <i class="fa-solid fa-gauge"></i>
                <span>Dasboard</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;">
                <i class="fa-solid fa-bars"></i>
                <span>Purchase Order</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Receiving</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
                <span>Back Order</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;">
                <i class="fa-solid fa-arrow-rotate-right"></i>
                <span>Return List</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;">
                <i class="fa-solid fa-warehouse"></i>
                <span>Stocks</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;">
                <i class="lni lni-cog"></i>
                <span>Sale List</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;">
                <i class="fa-solid fa-truck-ramp-box"></i>
                <span>Supplier List</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Item List</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="../employee/user.php" class="sidebar-link" style="text-decoration: none;">
                <i class="fa-solid fa-users"></i>
                <span>User List</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link" style="text-decoration: none;">
                <i class="lni lni-cog"></i>
                <span>Settings</span>
            </a>
        </li>
    </ul>
    <div class="sidebar-footer">
        <a href="../../logout.php" class="sidebar-link" style="text-decoration: none;">
            <i class="lni lni-exit"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>