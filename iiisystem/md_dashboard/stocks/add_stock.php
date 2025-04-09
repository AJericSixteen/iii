<?php
session_start();
require '../../asset/database/db.php'; // Database connection
define('ALLOW_ACCESS', true);
// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to add stock.'); window.location.href='../login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stock_id = trim($_POST['stock_id']);
    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user_id'];

    if (empty($stock_id) || $quantity <= 0) {
        echo "<script>alert('Invalid input.'); window.location.href='add_stock.php';</script>";
        exit();
    }

    // Fetch stock item details
    $query = $conn->prepare("SELECT item_name FROM stocks WHERE stock_id = ?");
    $query->bind_param("s", $stock_id);
    $query->execute();
    $result = $query->get_result();
    $stock = $result->fetch_assoc();

    if (!$stock) {
        echo "<script>alert('Stock item not found.'); window.location.href='add_stock.php';</script>";
        exit();
    }

    // Update stock quantity
    $updateStock = $conn->prepare("UPDATE stocks SET quantity = quantity + ? WHERE stock_id = ?");
    $updateStock->bind_param("is", $quantity, $stock_id);

    if ($updateStock->execute()) {
        // Log transaction
        $logTransaction = $conn->prepare("INSERT INTO stock_transaction (stock_id, user_id, transaction_type, quantity, remarks) VALUES (?, ?, 'add', ?, ?)");
        $remarks = "Stock added via QR";
        $logTransaction->bind_param("iiis", $stock_id, $user_id, $quantity, $remarks);
        $logTransaction->execute();

        echo "<script>alert('Stock added successfully.'); window.location.href='../md_dashboard/stocks.php';</script>";
    } else {
        echo "<script>alert('Error adding stock.'); window.location.href='add_stock.php';</script>";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock via QR</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Scan QR Code to Add Stock</h2>
        <video id="preview" style="width:100%; max-width: 500px;"></video>

        <form method="POST" action="add_stock.php">
            <div class="mb-3">
                <label class="form-label">Stock ID (Scanned)</label>
                <input type="text" id="stock_id" name="stock_id" class="form-control" readonly required>
            </div>
            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Stock</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/instascan/1.0.0/instascan.min.js"></script>
    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        scanner.addListener('scan', function (content) {
            document.getElementById("stock_id").value = content;
        });
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                alert('No cameras found.');
            }
        }).catch(function (e) {
            console.error(e);
        });
    </script>
</body>
</html>
