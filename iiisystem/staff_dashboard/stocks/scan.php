<?php
// scan.php
require('../../asset/database/db.php');  // Adjust path to your DB connection
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to be logged in to update stock.'); window.location.href = '../../index.php';</script>";
    exit();
}

// ✅ Case 1: Prompt for Quantity
if (isset($_GET['code']) && !isset($_GET['quantity'])) {

    $code = $_GET['code'];
    list($action, $stockId) = explode('_', $code);

    // Get item name from database
    $stmt = $conn->prepare("SELECT item_name FROM stocks WHERE stock_id = ?");
    $stmt->bind_param("i", $stockId);
    $stmt->execute();
    $stmt->bind_result($item_name);
    $stmt->fetch();
    $stmt->close();

    if (!$item_name) {
        echo "<script>alert('Item not found!'); window.location.href='./stocks.php';</script>";
        exit();
    }

    echo "<h2>Stock Action: $action</h2>";
    echo "
        <script>
            var quantity = prompt('Item: {$item_name}\\nEnter the quantity to {$action}:');
            if (quantity !== null && quantity > 0) {
                window.location.href = 'scan.php?code=" . urlencode($code) . "&quantity=' + quantity;
            } else {
                alert('Invalid quantity. Please enter a positive number.');
                window.location.href = 'scan.php?code=" . urlencode($code) . "';
            }
        </script>
    ";

// ✅ Case 2: Process Quantity
} elseif (isset($_GET['code']) && isset($_GET['quantity'])) {

    $quantity = (int) $_GET['quantity'];
    $code = $_GET['code'];
    list($action, $stockId) = explode('_', $code);

    // Validate
    if ($quantity <= 0) {
        echo "<script>alert('Invalid quantity. Quantity must be a positive number!');</script>";
        exit();
    }

    // Get current item info
    $stmt = $conn->prepare("SELECT item_name, quantity FROM stocks WHERE stock_id = ?");
    $stmt->bind_param("i", $stockId);
    $stmt->execute();
    $stmt->bind_result($item_name, $current_qty);
    $stmt->fetch();
    $stmt->close();

    if (!$item_name) {
        echo "<script>alert('Item not found!'); window.location.href='./stocks.php';</script>";
        exit();
    }

    // Prepare stock update query
    if ($action === 'add') {
        $sql = "UPDATE stocks SET quantity = quantity + ? WHERE stock_id = ?";
    } elseif ($action === 'deduct') {
        $sql = "UPDATE stocks SET quantity = quantity - ? WHERE stock_id = ?";
    } else {
        echo "<script>alert('Invalid action!');</script>";
        exit();
    }

    // Log transaction
    $remarks = ($action == 'deduct') ? "Deducted $quantity item(s)" : "Added $quantity item(s)";
    $logTransaction = $conn->prepare("INSERT INTO stock_transaction (stock_id, user_id, transaction_type, quantity, remarks, date) VALUES (?, ?, ?, ?, ?, NOW())");
    $logTransaction->bind_param("sisss", $stockId, $_SESSION['user_id'], $action, $quantity, $remarks);
    $logTransaction->execute();
    $logTransaction->close();

    // Update stock
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantity, $stockId);
    $stmt->execute();
    $stmt->close();

    // Get new quantity
    $stmt = $conn->prepare("SELECT quantity FROM stocks WHERE stock_id = ?");
    $stmt->bind_param("i", $stockId);
    $stmt->execute();
    $stmt->bind_result($new_quantity);
    $stmt->fetch();
    $stmt->close();

    echo "<script>
            alert('✅ Stock updated!\\nItem: {$item_name}\\nQuantity now: {$new_quantity}');
            window.location.href = './stocks.php';
          </script>";

// ✅ Case 3: No code
} else {
    echo "<script>alert('No code or quantity provided!');</script>";
}
?>
