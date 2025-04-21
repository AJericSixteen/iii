<?php
require('../../asset/database/db.php');
session_start();

// Redirect to login if user not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

define('ALLOW_ACCESS', true);

// Initialize scanned items session
if (!isset($_SESSION['scanned_items'])) {
    $_SESSION['scanned_items'] = [];
}

// Cancel pending item
if (isset($_GET['cancel_pending'])) {
    unset($_SESSION['pending_item']);
}

// Handle quantity input submission (Place this BEFORE the HTML)
if (isset($_POST['confirm_barcode']) && isset($_POST['quantity_input'])) {
    $barcode = $_POST['confirm_barcode'];
    $quantity = (int) $_POST['quantity_input'];

    if ($quantity <= 0) {
        $error_message = "Insufficient quantity. Cannot deduct 0 or negative items.";
    } else {
        // Fetch stock item using barcode again
        $stmt = $conn->prepare("SELECT * FROM stocks WHERE barcode = ?");
        $stmt->bind_param("s", $barcode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $item = $result->fetch_assoc();

            if ($item['quantity'] < $quantity) {
                $error_message = "Insufficient stock. Only {$item['quantity']} items available.";
            } else {
                $item['quantity'] = $quantity;

                $stock_id = $item['stock_id'];
                $found = false;

                foreach ($_SESSION['scanned_items'] as $key => $existingItem) {
                    if ($existingItem['stock_id'] == $stock_id) {
                        $_SESSION['scanned_items'][$key]['quantity'] -= $quantity; // Deduct the quantity
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $item['quantity'] = -$quantity; // Set as negative for deduction
                    $_SESSION['scanned_items'][] = $item;
                }
            }
        }

        $stmt->close();
        unset($_SESSION['pending_item']); // clear pending item after use
    }
}

// Handle barcode scan submission
if (isset($_POST['barcode'])) {
    $barcode = trim($_POST['barcode']);

    $stmt = $conn->prepare("SELECT * FROM stocks WHERE barcode = ?");
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        $_SESSION['pending_item'] = $item; // temporarily store item waiting for quantity
    } else {
        $error_message = "Item not found for barcode: $barcode";
    }

    $stmt->close();
}

// Remove item from session
if (isset($_GET['remove_item'])) {
    $stock_id = $_GET['remove_item'];
    foreach ($_SESSION['scanned_items'] as $key => $item) {
        if ($item['stock_id'] == $stock_id) {
            unset($_SESSION['scanned_items'][$key]);
            break;
        }
    }
    $_SESSION['scanned_items'] = array_values($_SESSION['scanned_items']);
}

// Confirm and update all stock quantities
// Confirm and update all stock quantities
if (isset($_POST['confirm_all'])) {
    $debug_log = [];
    $transaction_type = 'deduct';

    foreach ($_SESSION['scanned_items'] as $item) {
        $debug_log[] = "Attempting to deduct barcode {$item['barcode']} with quantity {$item['quantity']}";

        $conn->begin_transaction();

        try {
            $stmtCheck = $conn->prepare("SELECT quantity FROM stocks WHERE barcode = ?");
            $stmtCheck->bind_param("s", $item['barcode']);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();

            if ($resultCheck->num_rows > 0) {
                $stock = $resultCheck->fetch_assoc();
                $currentQty = (int)$stock['quantity'];

                if ($currentQty >= abs($item['quantity'])) {
                    $quantityChange = $item['quantity']; // already negative

                    $stmt = $conn->prepare("UPDATE stocks SET quantity = quantity + ? WHERE barcode = ?");
                    $stmt->bind_param("is", $quantityChange, $item['barcode']);

                    if ($stmt->execute()) {
                        $user_id = $_SESSION['user_id'];

                        $abs_quantity = abs($item['quantity']);
                        $stmt2 = $conn->prepare("INSERT INTO stock_transaction (stock_id, user_id, transaction_type, quantity, date) VALUES (?, ?, ?, ?, NOW())");
                        $stmt2->bind_param("iisi", $item['stock_id'], $user_id, $transaction_type, $abs_quantity);
                        
                        $stmt2->execute();

                        $conn->commit();
                        $debug_log[] = "✅ Deducted successfully and transaction logged.";
                    } else {
                        $debug_log[] = "❌ Failed to deduct: " . $stmt->error;
                        $conn->rollback();
                    }

                    $stmt->close();
                    $stmt2->close();
                } else {
                    $debug_log[] = "❌ Insufficient stock for barcode {$item['barcode']}. Requested: " . abs($item['quantity']) . ", Available: $currentQty";
                    $conn->rollback();
                }
            }

            $stmtCheck->close();
        } catch (Exception $e) {
            $conn->rollback();
            $debug_log[] = "❌ Error: " . $e->getMessage();
        }
    }

    $success_message = "Stock deduction results:<br><pre>" . implode("\n", $debug_log) . "</pre>";
    $_SESSION['scanned_items'] = [];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>III | Deduct Stock</title>
    <link rel="icon" href="../../asset/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .scanned-items-table {
            border: 2px solid #333;
            border-collapse: collapse;
        }

        .scanned-items-table th,
        .scanned-items-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php require '../../asset/includes/sidebar.php'; ?>

        <div class="main p-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Deduct Stock</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Left: Table -->
                        <div class="col-md-6">
                            <form action="" method="POST">
                                <table class="table table-striped scanned-items-table">
                                    <thead>
                                        <tr>
                                            <th>Barcode</th>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($_SESSION['scanned_items']) > 0): ?>
                                            <?php foreach ($_SESSION['scanned_items'] as $item): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($item['barcode']) ?></td>
                                                    <td><?= htmlspecialchars($item['item_name']) ?></td>
                                                    <td><?= $item['quantity'] ?></td>
                                                    <td><a href="?remove_item=<?= $item['stock_id'] ?>"
                                                            onclick="return confirm('Are you sure to remove <?= addslashes($item['item_name']) ?>? ');"
                                                            class="btn btn-danger btn-sm">Remove</a></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3">No items scanned yet.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <?php if (count($_SESSION['scanned_items']) > 0): ?>
                                    <button type="submit" name="confirm_all" class="btn btn-danger">Confirm All (Deduct)</button>
                                <?php endif; ?>
                            </form>
                        </div>

                        <!-- Right: Barcode Scanner -->
                        <div class="col-md-6">
                            <!-- Barcode Scanning Form -->
                            <?php if (!isset($_SESSION['pending_item'])): ?>
                                <form action="" method="POST" id="barcode-form">
                                    <input type="hidden" name="quantity" id="quantity">
                                    <div class="mb-3">
                                        <label for="barcode" class="form-label">Scan Barcode</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control" autofocus
                                            autocomplete="off">
                                    </div>
                                </form>

                                <?php if (isset($error_message)): ?>
                                    <div class="alert alert-danger mt-3"><?= $error_message ?></div>
                                <?php endif; ?>

                                <?php if (isset($success_message)): ?>
                                    <div class="alert alert-success mt-3"><?= $success_message ?></div>
                                <?php endif; ?>

                                <script>
                                    let timeout = null;

                                    document.getElementById('barcode').addEventListener('input', function () {
                                        clearTimeout(timeout);
                                        timeout = setTimeout(function () {
                                            const barcodeInput = document.getElementById('barcode');
                                            if (barcodeInput.value.trim() !== "") {
                                                document.getElementById('barcode-form').submit();
                                            }
                                        }, 300);
                                    });

                                    window.onload = function () {
                                        document.getElementById('barcode').focus();
                                    };
                                </script>

                            <?php else: ?>
                                <!-- Quantity Prompt Form -->
                                <form action="" method="POST">
                                    <input type="hidden" name="confirm_barcode"
                                        value="<?= $_SESSION['pending_item']['barcode'] ?>">
                                    <div class="mb-3">
                                        <label for="quantity_input" class="form-label">
                                            Enter Quantity to Deduct for
                                            <strong><?= htmlspecialchars($_SESSION['pending_item']['item_name']) ?></strong>:
                                        </label>
                                        <input type="number" name="quantity_input" id="quantity_input" class="form-control"
                                            min="1" required>
                                    </div>
                                    <button type="submit" class="btn btn-danger">Deduct Item</button>
                                    <a href="?cancel_pending=1" class="btn btn-secondary">Cancel</a>
                                </form>

                                <script>
                                    document.getElementById('quantity_input').focus();
                                </script>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
