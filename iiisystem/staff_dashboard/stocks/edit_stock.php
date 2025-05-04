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

define('ALLOW_ACCESS', true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../asset/css/style.css">
</head>

<body>
    <div class="wrapper">
        <?php require '../../asset/includes/sidebar.php'; ?>

        <div class="main p-3">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="card-header bg-light text-black">
                        <h5>Edit Stock</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['error'];
                                unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="edit_stock.php">
                            <input type="hidden" name="stock_id" value="<?= htmlspecialchars($stock['stock_id']); ?>">

                            <div class="mb-3">
                                <label for="itemName" class="form-label">Item Name</label>
                                <input type="text" id="itemName" name="item_name" class="form-control"
                                    value="<?= htmlspecialchars($stock['item_name']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select id="category" name="category" class="form-select" required>
                                    <option value="Printing Materials" <?= ($stock['category'] == 'Printing Materials') ? 'selected' : ''; ?>>Printing Materials</option>
                                    <option value="Hardware & Fasteners" <?= ($stock['category'] == 'Hardware & Fasteners') ? 'selected' : ''; ?>>Hardware & Fasteners</option>
                                    <option value="Tapes & Adhesives" <?= ($stock['category'] == 'Tapes & Adhesives') ? 'selected' : ''; ?>>Tapes & Adhesives</option>
                                    <option value="Tools & Accessories" <?= ($stock['category'] == 'Tools & Accessories') ? 'selected' : ''; ?>>Tools & Accessories</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" id="quantity" name="quantity" class="form-control"
                                    value="<?= htmlspecialchars($stock['quantity']); ?>" required readonly>
                            </div>

                            <div class="mb-3">
                                <label for="minStock" class="form-label">Min-Stock</label>
                                <input type="number" id="minStock" name="min_stock" class="form-control"
                                    value="<?= htmlspecialchars($stock['min_stocks']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="maxStock" class="form-label">Max-Stock</label>
                                <input type="number" id="maxStock" name="max_stock" class="form-control"
                                    value="<?= htmlspecialchars($stock['max_stocks']); ?>" required>
                            </div>

                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary me-2"
                                    onclick="return confirm('Are you sure you\'re done editing?')">Submit</button>
                                <a href="stocks.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>