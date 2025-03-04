<?php
session_start();
require("../../asset/database/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Check if the update form is submitted and the edit_user button is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
    // Retrieve and sanitize input values
    $account_id = intval($_POST['account_id']);
    $firstname  = trim($_POST['firstname']);
    $lastname   = trim($_POST['lastname']);
    $username   = trim($_POST['username']);
    $role       = trim($_POST['role']);

    // Prepare the update query that joins user_info and account tables
    $query = "UPDATE user_info u 
              JOIN account a ON u.user_id = a.user_id 
              SET u.firstname = ?, u.lastname = ?, a.username = ?, a.role = ? 
              WHERE a.account_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }
    
    $stmt->bind_param("ssssi", $firstname, $lastname, $username, $role, $account_id);
    
    // Execute and check for errors
    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully!'); window.location.href='user.php';</script>";
    } else {
        echo "<script>alert('Error updating user: " . $stmt->error . "');</script>";
    }
    
    $stmt->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href='user.php';</script>";
}

$conn->close();
?>
