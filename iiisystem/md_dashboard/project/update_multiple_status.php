<?php
session_start();
require("../../asset/database/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

define('ALLOW_ACCESS', true);

// Check if client_id and master_status are set
if (!isset($_POST['client_id']) || !isset($_POST['master_status'])) {
    die("Invalid request. client_id or master_status not set.");
}

$client_id = $_POST['client_id'];
$master_status = $_POST['master_status'];

// Check the values received
if (empty($client_id) || empty($master_status)) {
    die("Invalid request. client_id or master_status is empty.");
}

// Prepare SQL query to update all projects' statuses for the client
$update_query = "UPDATE project SET status = ? WHERE client_id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("si", $master_status, $client_id);

if ($update_stmt->execute()) {
    // Redirect without the message
    header("Location: view_project.php?client_id=$client_id");
    exit();
} else {
    // Redirect without the message
    header("Location: view_projects.php?client_id=$client_id");
    exit();
}
?>
