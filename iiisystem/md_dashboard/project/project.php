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
    <title>III | Project</title>
    
    <!-- icon -->
    <link rel="icon" href="../../asset/img/logo.png">
</head>
<body>
    <div class="wrapper">
    <?php require '../../asset/includes/sidebar.php'; ?>
    <div class="main p-3">

    </div>
    </div>
</body>
</html>