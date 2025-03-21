<?php
session_start();
require("../../asset/database/db.php"); // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and sanitize client details
    $name = trim($_POST['name'] ?? '');
    $company = trim($_POST['client_company'] ?? '');
    $address = trim($_POST['client_address'] ?? '');
    $phone = trim($_POST['client_phone'] ?? '');
    $email = trim($_POST['client_email'] ?? '');
    $date_needed = !empty($_POST['date_needed']) ? $_POST['date_needed'] : NULL;

    // Validate required fields
    if (empty($name) || empty($company) || empty($address) || empty($phone) || empty($email)) {
        die("Error: Missing required fields.");
    }

    // Insert client details
    $query = "INSERT INTO client (name, company, address, phone, email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $name, $company, $address, $phone, $email);
    if (!$stmt->execute()) {
        die("Error inserting client: " . $stmt->error);
    }
    $client_id = $stmt->insert_id; // Get the inserted client ID
    $stmt->close();

    // Set default status
    $status = "Pending";

    // Ensure services, quantities, and prices exist
    if (!empty($_POST['services']) && !empty($_POST['quantity']) && !empty($_POST['price'])) {
        $query = "INSERT INTO project (client_id, date_requested, date_needed, status, age, services, quantity, price, total) 
                  VALUES (?, NOW(), ?, ?, DATEDIFF(?, NOW()), ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        foreach ($_POST['services'] as $index => $service) {
            if (!isset($_POST['quantity'][$index]) || !isset($_POST['price'][$index])) {
                continue; // Skip if quantity or price is missing
            }

            $service = trim($service);
            $quantity = (float) $_POST['quantity'][$index];
            $price = (float) $_POST['price'][$index];
            $total = $quantity * $price;

            $stmt->bind_param("issssidd", $client_id, $date_needed, $status, $date_needed, $service, $quantity, $price, $total);
            if (!$stmt->execute()) {
                die("Error inserting project: " . $stmt->error);
            }
        }
        $stmt->close();
    }

    // Redirect after successful insertion
    header("Location: project.php?success=Project added successfully");
    exit();
} else {
    header("Location: new_project.php");
    exit();
}
?>
