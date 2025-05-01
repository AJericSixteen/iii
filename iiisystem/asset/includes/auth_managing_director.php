<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Managing Director') {
    // If staff, send to staff dashboard
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
        header("Location: ../../staff_dashboard/dashboard/dashboard.php");
    } else {
        header("Location: ../../index.php"); // Redirect to login page if not logged in
    }
    exit();
}
?>