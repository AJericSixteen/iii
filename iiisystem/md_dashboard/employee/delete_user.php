<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

define('ALLOW_ACCESS', true);
include '../../asset/database/db.php';

// Check if account_id is provided via GET
if (!isset($_GET['account_id'])) {
    die("No account id provided.");
}
$account_id = intval($_GET['account_id']);

// Delete the user from both user_info and account tables using a multi-table DELETE
$sql = "DELETE u, a 
        FROM user_info u 
        INNER JOIN account a ON u.user_id = a.user_id 
        WHERE a.account_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}
$stmt->bind_param("i", $account_id);

if ($stmt->execute()) {
    echo "<script>alert('User deleted successfully!'); window.location.href = 'user.php';</script>";
} else {
    echo "<script>alert('Error deleting user: " . $stmt->error . "'); window.location.href = 'user.php';</script>";
}

$stmt->close();
$conn->close();
?>
