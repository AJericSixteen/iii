<?php
session_start();
require("../../asset/database/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Get client ID from GET request
if (!isset($_GET['client_id'])) {
    die("Invalid request.");
}

$client_id = $_GET['client_id'];

// Delete associated projects first to avoid foreign key constraint errors
$delete_projects = "DELETE FROM project WHERE client_id=?";
$stmt1 = $conn->prepare($delete_projects);
$stmt1->bind_param("i", $client_id);
$stmt1->execute();

// Now delete the client
$delete_client = "DELETE FROM client WHERE client_id=?";
$stmt2 = $conn->prepare($delete_client);
$stmt2->bind_param("i", $client_id);

if ($stmt2->execute()) {
    $_SESSION['success'] = "Client deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete client.";
}

header("Location: project.php");
exit();
