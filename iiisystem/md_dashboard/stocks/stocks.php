<?php

require('../../asset/database/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

define('ALLOW_ACCESS', true);

// Fetch stocks data
$sql = "SELECT stock_id, item_name, category, quantity, min_stocks, max_stocks, barcode FROM stocks";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>III | STOCKS</title>
    <link rel="icon" href="../../asset/img/logo.png">

    <!-- Bootstrap & DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php require '../../asset/includes/sidebar.php'; ?>

        <div class="main p-3">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="card-header bg-light text-black d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Stock Inventory</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">+ Add Item</button>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="stockTable" class="table table-striped table-bordered" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Qr Code</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0): ?>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['stock_id']); ?></td>
                                                <td><?= htmlspecialchars($row['item_name']); ?></td>
                                                <td><?= htmlspecialchars($row['category']); ?></td>
                                                <td><?= htmlspecialchars($row['quantity']); ?></td>
                                                <td>
                                                    <?php
                                                    $quantity = $row['quantity'];
                                                    $min_stock = $row['min_stocks'];
                                                    $max_stock = $row['max_stocks'];

                                                    if ($quantity == 0) {
                                                        // Critical: Out of Stock
                                                        echo '<span class="badge bg-dark">Out of Stock</span>';
                                                    } elseif ($quantity < $min_stock * 0.25) {
                                                        // Critical: Below Critical Stock Level
                                                        echo '<span class="badge bg-danger">Below Critical Stock Level</span>';
                                                    } elseif ($quantity < $min_stock) {
                                                        // Warning: Under Minimum Stock
                                                        echo '<span class="badge bg-danger">Under Minimum Stock</span>';
                                                    } elseif ($quantity == $min_stock) {
                                                        // Caution: Minimum Stock Level
                                                        echo '<span class="badge bg-warning">Minimum Stock Level</span>';
                                                    } elseif ($quantity < $max_stock) {
                                                        // Normal: Stock Below Maximum Level
                                                        echo '<span class="badge bg-info">Stock Below Maximum Level</span>';
                                                    } elseif ($quantity == $max_stock) {
                                                        // Optimal: Maximum Stock Level
                                                        echo '<span class="badge bg-success">Maximum Stock Level</span>';
                                                    } else {
                                                        // Excess: Over Stock
                                                        echo '<span class="badge bg-success">Over Stock</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td id="qr_code_<?= $row['stock_id']; ?>"></td> <!-- QR Code initially hidden -->
                                                <td>
                                                    <button class="btn btn-success btn-sm" onclick="changeQRToAdd('<?= $row['stock_id']; ?>')">Add</button>
                                                    <button class="btn btn-danger btn-sm" onclick="changeQRToDeduct('<?= $row['stock_id']; ?>')">Deduct</button>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                                            ACTION
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                                                            <li><a href="edit_stock.php?id=<?= $row['stock_id']; ?>" class="text-primary dropdown-item">EDIT</a></li>
                                                            <li><a href="delete_stock.php" class="text-danger dropdown-item">DELETE</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No data found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Add Item Modal -->
                    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="addItemModalLabel">Add New Item</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="add_item.php" method="POST">
                                        <div class="mb-3">
                                            <label for="item_name" class="form-label">Item Name:</label>
                                            <input type="text" name="item_name" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Category:</label>
                                            <select id="category" name="category" class="form-control" required>
                                                <option value="Printing Materials">Printing Materials</option>
                                                <option value="Hardware & Fasteners">Hardware & Fasteners</option>
                                                <option value="Tapes & Adhesives">Tapes & Adhesives</option>
                                                <option value="Tools & Accessories">Tools & Accessories</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Quantity:</label>
                                            <input type="number" name="quantity" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="min_stock" class="form-label">Min-Stock:</label>
                                            <input type="number" name="min_stock" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="max_stock" class="form-label">Max-Stock:</label>
                                            <input type="number" name="max_stock" class="form-control" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Add Item</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- Card End -->
            </div> <!-- Container End -->
        </div> <!-- Main End -->
    </div> <!-- Wrapper End -->

    <script src="../../asset/js/stocks.js"></script>
    <script>
function changeQRToAdd(stockId) {
    let qrCodeCell = document.getElementById("qr_code_" + stockId);
    // Check if the QR code is already displayed
    if (qrCodeCell.innerHTML.includes("Add Stock")) {
        // If QR code is displayed, hide it
        qrCodeCell.innerHTML = '';
    } else {
        // Otherwise, display the "Add" QR code
        qrCodeCell.innerHTML = `
            <div>
                <img src="qr_code.php?code=add_${stockId}" alt="Add Stock QR" width="100px">
                <span class="badge bg-success ms-2">Add Stock</span>
            </div>`;
    }
}

function changeQRToDeduct(stockId) {
    let qrCodeCell = document.getElementById("qr_code_" + stockId);
    // Check if the QR code is already displayed
    if (qrCodeCell.innerHTML.includes("Deduct Stock")) {
        // If QR code is displayed, hide it
        qrCodeCell.innerHTML = '';
    } else {
        // Otherwise, display the "Deduct" QR code
        qrCodeCell.innerHTML = `
            <div>
                <img src="qr_code.php?code=deduct_${stockId}" alt="Deduct Stock QR" width="100px">
                <span class="badge bg-danger ms-2">Deduct Stock</span>
            </div>`;
    }
}

    </script>
</body>

</html>
