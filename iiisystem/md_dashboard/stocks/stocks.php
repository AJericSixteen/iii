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
                        <div>
                            <!-- Print Barcodes Button -->
                            <button id="printBarcodesButton" class="btn btn-warning btn-sm">Print Barcodes</button>

                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addItemModal">+ Add Item</button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="stockTable" class="table table-striped table-bordered" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Stock ID</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
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
                                                        echo '<span class="badge bg-danger">Out of Stock</span>';
                                                    } elseif ($quantity > 0 && $quantity < $min_stock) {
                                                        echo '<span class="badge bg-dark">Under Minimum Stock</span>';
                                                    } elseif ($quantity == $min_stock) {
                                                        echo '<span class="badge bg-warning">Minimum Stock</span>';
                                                    } elseif ($quantity > $min_stock && $quantity < $max_stock) {
                                                        echo '<span class="badge bg-info">In Stock</span>';
                                                    } elseif ($quantity == $max_stock) {
                                                        echo '<span class="badge bg-primary">Maximum Stock</span>';
                                                    } elseif ($quantity > $max_stock) {
                                                        echo '<span class="badge bg-success">Over Stock</span>';
                                                    }
                                                    ?>
                                                </td>




                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            ACTION
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                                                            <li><a href="edit_stock.php?id=<?= $row['stock_id']; ?>"
                                                                    class="text-primary dropdown-item">EDIT</a></li>
                                                            <li>
                                                                <a href="delete_stock.php?id=<?= $row['stock_id']; ?>"
                                                                    class="text-danger dropdown-item"
                                                                    onclick="return confirm('Are you sure you want to delete the item: <?= addslashes($row['item_name']); ?>?')">
                                                                    DELETE
                                                                </a>
                                                            </li>

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
                    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="add_item.php" method="POST">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="addItemModalLabel">Add New Item</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Item Name -->
                                        <div class="mb-3">
                                            <label for="itemName" class="form-label">Item Name</label>
                                            <input type="text" class="form-control" id="itemName" name="item_name"
                                                required>
                                        </div>

                                        <!-- Category -->
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Category:</label>
                                            <select id="category" name="category" class="form-control" required>
                                                <option value="Printing Materials">Printing Materials</option>
                                                <option value="Hardware & Fasteners">Hardware & Fasteners</option>
                                                <option value="Tapes & Adhesives">Tapes & Adhesives</option>
                                                <option value="Tools & Accessories">Tools & Accessories</option>
                                            </select>
                                        </div>

                                        <!-- Minimum Stock -->
                                        <div class="mb-3">
                                            <label for="minStock" class="form-label">Minimum Stock</label>
                                            <input type="number" class="form-control" id="minStock" name="min_stock"
                                                required min="0">
                                        </div>

                                        <!-- Maximum Stock -->
                                        <div class="mb-3">
                                            <label for="maxStock" class="form-label">Maximum Stock</label>
                                            <input type="number" class="form-control" id="maxStock" name="max_stock"
                                                required min="0">
                                        </div>


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Add Item</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Print Barcodes Modal -->
                    <div class="modal fade" id="printBarcodeModal" tabindex="-1"
                        aria-labelledby="printBarcodeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="print_barcodes.php" method="POST" target="_blank">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="printBarcodeModalLabel">Print Barcode</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                                            <label class="form-check-label" for="selectAllCheckbox">
                                                Select All
                                            </label>
                                        </div>

                                        <div class="row">
                                            <?php
                                            $result->data_seek(0); // reset pointer if already iterated
                                            while ($row = $result->fetch_assoc()):
                                                $stockId = htmlspecialchars($row['stock_id']);
                                                $itemName = htmlspecialchars($row['item_name']);
                                                $barcode = htmlspecialchars($row['barcode']);
                                                ?>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input barcode-checkbox" type="checkbox"
                                                            name="barcode_select[]" value="<?= $stockId ?>"
                                                            id="barcode_<?= $stockId ?>">
                                                        <label class="form-check-label" for="barcode_<?= $stockId ?>">
                                                            <?= $itemName ?> - Barcode: <?= $barcode ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Print Barcode</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div> <!-- Card End -->
            </div> <!-- Container End -->
        </div> <!-- Main End -->
    </div> <!-- Wrapper End -->

    <script src="../../asset/js/stocks.js"></script>

    <script>
        // Open the modal when button clicked
        document.getElementById('printBarcodesButton').addEventListener('click', function () {
            new bootstrap.Modal(document.getElementById('printBarcodeModal')).show();
        });

        // Handle "Select All" checkbox logic
        document.getElementById('selectAllCheckbox').addEventListener('change', function () {
            const allCheckboxes = document.querySelectorAll('.barcode-checkbox');
            allCheckboxes.forEach(checkbox => checkbox.checked = this.checked);

        });
    </script>


</body>

</html>