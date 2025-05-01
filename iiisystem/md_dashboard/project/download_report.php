<?php
session_start();
require("../../asset/database/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['start_date']) || !isset($_GET['end_date'])) {
    die("Invalid date range.");
}

$start_date = $_GET['start_date'];
$end_date   = $_GET['end_date'];

// Get completed projects within the date range
$sql = "SELECT p.project_id, c.company, c.name AS contact_name, p.services, p.quantity, p.price, p.total, p.date_requested
        FROM project p
        JOIN client c ON p.client_id = c.client_id
        WHERE p.status = 'Completed' AND p.date_requested BETWEEN ? AND ?
        ORDER BY p.date_requested ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("No projects found for the selected date range.");
}

// Set CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=Completed_Projects_Report.csv');

$output = fopen('php://output', 'w');

// CSV column headers
fputcsv($output, ['Project ID', 'Company', 'Contact Person', 'Services', 'Quantity', 'Price', 'Total', 'Date Requested']);

// Data rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
