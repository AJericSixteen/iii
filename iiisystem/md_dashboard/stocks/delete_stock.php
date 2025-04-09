<?php
require('../../asset/database/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $stock_id = $_GET['id'];

    $sql = "DELETE FROM stocks WHERE stock_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stock_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Stock deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete stock.";
    }
}

header("Location: stocks.php");
exit();
?>
