<?php
session_start(); // Start session
require("../../asset/database/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = $_POST['client_id'];
    $project_id = $_POST['project_id'];
    $new_status = $_POST['status'];

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update query - changing status for only one project
    $stmt = $conn->prepare("UPDATE project SET status = ? WHERE client_id = ? AND project_id = ?");
    $stmt->bind_param("sii", $new_status, $client_id, $project_id);

    if ($stmt->execute()) {
        echo "Status updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Store client_id and project_id in session instead of passing them in the URL
    $_SESSION['client_id'] = $client_id;
    $_SESSION['project_id'] = $project_id;

    // Redirect without exposing IDs in the URL
    header("Location: view_project.php");
    exit();
}
?>
