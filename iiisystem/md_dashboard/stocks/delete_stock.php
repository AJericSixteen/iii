<?php
session_start();
require('../../asset/database/db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

// Check if the 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    $stock_id = $_GET['id'];

    // Delete the stock from the database
    $sql = "DELETE FROM stocks WHERE stock_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stock_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Stock deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting stock.";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "No stock selected for deletion.";
}

header("Location: stocks.php");
exit();
?>
