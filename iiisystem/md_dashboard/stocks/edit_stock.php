<?php
session_start();
require('../../asset/database/db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

// Fetch stock details
if (isset($_GET['id'])) {
    $stock_id = $_GET['id'];
    $sql = "SELECT * FROM stocks WHERE stock_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stock_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stock = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: stocks.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stock_id = $_POST['stock_id'];
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $min_stock = $_POST['min_stock'];
    $max_stock = $_POST['max_stock'];

    // Update query
    $sql = "UPDATE stocks SET item_name=?, category=?, quantity=?, min_stocks=?, max_stocks=? WHERE stock_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiii", $item_name, $category, $quantity, $min_stock, $max_stock, $stock_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Stock updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating stock.";
    }
    $stmt->close();

    header("Location: stocks.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stock</title>
    <link rel="stylesheet" href="../../asset/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Stock</h2>
        <?php if(isset($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <form method="POST" action="edit_stock.php">
            <input type="hidden" name="stock_id" value="<?= htmlspecialchars($stock['stock_id']); ?>">
            
            <label>Item Name:</label>
            <input type="text" name="item_name" value="<?= htmlspecialchars($stock['item_name']); ?>" required>
            
            <label>Category:</label>
            <select name="category" required>
                <option value="Printing Materials" <?= ($stock['category'] == 'Printing Materials') ? 'selected' : ''; ?>>Printing Materials</option>
                <option value="Hardware & Fasteners" <?= ($stock['category'] == 'Hardware & Fasteners') ? 'selected' : ''; ?>>Hardware & Fasteners</option>
                <option value="Tapes & Adhesives" <?= ($stock['category'] == 'Tapes & Adhesives') ? 'selected' : ''; ?>>Tapes & Adhesives</option>
                <option value="Tools & Accessories" <?= ($stock['category'] == 'Tools & Accessories') ? 'selected' : ''; ?>>Tools & Accessories</option>
            </select>

            <label>Quantity:</label>
            <input type="number" name="quantity" value="<?= htmlspecialchars($stock['quantity']); ?>" required>

            <label>Min-Stock:</label>
            <input type="number" name="min_stock" value="<?= htmlspecialchars($stock['min_stocks']); ?>" required>

            <label>Max-Stock:</label>
            <input type="number" name="max_stock" value="<?= htmlspecialchars($stock['max_stocks']); ?>" required>

            <button type="submit">Update Stock</button>
        </form>
        <a href="stocks.php">Back to Stocks</a>
    </div>
</body>
</html>
