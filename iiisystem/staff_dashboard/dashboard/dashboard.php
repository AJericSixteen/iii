<?php
session_start();
define('ALLOW_ACCESS', true);
require('../../asset/database/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>III | Staff Dashboard</title>
</head>

<body>
    <div class="wrapper">
        <?php require '../../asset/includes/staff_sidebar.php'; ?>
        <div class="main p-3">
        </div>
    </div>
</body>

</html>