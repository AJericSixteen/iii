<?php
require('../../asset/database/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $min_stock = $_POST['min_stock']; // Match input name from form
    $max_stock = $_POST['max_stock']; // Match input name from form

    // Insert item into database
    $sql = "INSERT INTO stocks (item_name, category, min_stocks, max_stocks) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $item_name, $category, $min_stock, $max_stock);

    if ($stmt->execute()) {
        // Get last inserted stock_id
        $stock_id = $conn->insert_id;

        // Generate unique barcode (III + 6-digit stock ID)
        $barcode = "III" . str_pad($stock_id, 6, "0", STR_PAD_LEFT);

        // Update the stock record with the barcode
        $update_sql = "UPDATE stocks SET barcode = ? WHERE stock_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $barcode, $stock_id);
        $update_stmt->execute();

        echo '<script>alert("Successfully added new item with Barcode: ' . $barcode . '"); window.location.href="stocks.php";</script>';
    } else {
        echo '<script>alert("Error adding item!"); window.location.href="stocks.php";</script>';
    }

    // Close connections
    $stmt->close();
    $update_stmt->close();
    $conn->close();
}
?>
