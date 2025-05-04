<?php
require '../../asset/database/db.php';

if (!isset($_GET['code'])) {
    die("Invalid request. No stock ID provided.");
}

$stock_id = $_GET['code'];

// Fetch current stock quantity
$query = $conn->prepare("SELECT quantity FROM stocks WHERE stock_id = ?");
$query->bind_param("i", $stock_id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Stock item not found.");
}

$current_quantity = $row['quantity'];

if ($current_quantity > 0) {
    // Deduct stock
    $new_quantity = $current_quantity - 1;
    $update = $conn->prepare("UPDATE stocks SET quantity = ? WHERE stock_id = ?");
    $update->bind_param("ii", $new_quantity, $stock_id);
    $update->execute();

    echo "<script>alert('Stock deducted successfully! New quantity: $new_quantity'); window.location.href='inventory.php';</script>";
} else {
    echo "<script>alert('Stock is already empty!'); window.location.href='stocks.php';</script>";
}

$conn->close();
?>
