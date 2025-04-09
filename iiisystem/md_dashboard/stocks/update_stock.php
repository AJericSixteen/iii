<?php
session_start();
require '../../asset/database/db.php'; // Database connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to modify stock.'); window.location.href='../login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];  // Get user ID from session

// Retrieve data from GET request
$code = $_GET['code'] ?? '';
$action = $_GET['action'] ?? '';
$quantity = $_GET['qty'] ?? null;

// Debug: Check values
echo "Code: $code, Action: $action, Quantity: $quantity";

// Validate action type
if (empty($code) || !in_array($action, ['add', 'deduct'])) {
    die('Invalid request.');
}

// Fetch current stock details
$query = $conn->prepare("SELECT * FROM stocks WHERE stock_id = ?");
$query->bind_param("s", $code);
$query->execute();
$result = $query->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die('Stock item not found.');
}

// Prompt for quantity only if it's missing (for deduct action)
if ($quantity === null) {
    if ($action === 'deduct') {
        echo "<script>
            var qty = prompt('Enter quantity to deduct:', 1);
            if (qty === null || qty === '' || isNaN(qty) || qty <= 0) {
                alert('Invalid quantity.');
                window.location.href='./stocks.php';
            } else {
                window.location.href='update_stock.php?code=$code&action=$action&qty=' + qty;
            }
        </script>";
        exit();
    } else {
        $quantity = 1; // Default quantity for adding stock
    }
}

// Convert quantity to integer and validate
$quantity = (int)$quantity;
if ($quantity <= 0) {
    echo "<script>alert('Invalid quantity!'); window.location.href='./stocks.php';</script>";
    exit();
}

// Check if enough stock is available when deducting
if ($action == 'deduct' && $quantity > $item['quantity']) {
    echo "<script>alert('Error: Not enough stock to deduct!'); window.location.href='./stocks.php';</script>";
    exit();
}

// Calculate new quantity
$new_quantity = ($action == 'deduct') ? ($item['quantity'] - $quantity) : ($item['quantity'] + $quantity);
$remarks = ($action == 'deduct') ? "Deducted $quantity item(s)" : "Added $quantity item(s)";

// Update stock quantity
$updateStock = $conn->prepare("UPDATE stocks SET quantity = ? WHERE stock_id = ?");
$updateStock->bind_param("is", $new_quantity, $code);
if (!$updateStock->execute()) {
    die('Error updating stock: ' . $conn->error);
}

var_dump($code, $user_id, $action, $quantity, $remarks);


// Log transaction with transaction date
$logTransaction = $conn->prepare("INSERT INTO stock_transaction (stock_id, user_id, transaction_type, quantity, remarks, date) VALUES (?, ?, ?, ?, ?, NOW())");
$logTransaction->bind_param("sisss", $code, $user_id, $action, $quantity, $remarks);

// Check if the transaction query executed successfully
if (!$logTransaction->execute()) {
    // Output error for debugging
    echo "Error logging transaction: " . $logTransaction->error;
    die();
}

// Redirect back with success message
echo "<script>alert('Stock $action successful.'); window.location.href='./stocks.php';</script>";
exit();
?>