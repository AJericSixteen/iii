<?php
require('../../asset/database/db.php');
session_start();

// Redirect to login if user not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

define('ALLOW_ACCESS', true);

// Initialize scanned items session if not set
if (!isset($_SESSION['scanned_items'])) {
    $_SESSION['scanned_items'] = [];
}

// Handle barcode scan submission
if (isset($_POST['barcode'])) {
    $barcode = trim($_POST['barcode']);

    // Fetch item from database using barcode
    $stmt = $conn->prepare("SELECT * FROM stocks WHERE barcode = ?");
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();

        // Check if item already scanned
        $found = false;
        foreach ($_SESSION['scanned_items'] as &$existingItem) {
            if ($existingItem['stock_id'] == $item['stock_id']) {
                // Keep quantity empty as per your request
                $found = true;
                break;
            }
        }

        // If not found, add new item to session with empty quantity
        if (!$found) {
            $item['quantity'] = ''; // Keep quantity empty
            $_SESSION['scanned_items'][] = $item;
        }
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

// Handle confirm all submission
if (isset($_POST['confirm_all'])) {
    $debug_log = [];
    $transaction_type = 'deduct'; // Ensure it's for deducting stock

    foreach ($_SESSION['scanned_items'] as $key => $item) {
        $conn->begin_transaction();

        // Get the quantity from the form submission
        $quantity = isset($_POST['quantity'][$key]) ? $_POST['quantity'][$key] : '';

        // Validate quantity
        if (empty($quantity) || !is_numeric($quantity) || $quantity <= 0) {
            // If quantity is empty, invalid or zero, mark item as invalid
            $debug_log[] = "❌ Invalid quantity for item: " . $item['item_name'];
            continue;
        }

        // Update item quantity and process transaction
        try {
            // Fetch current quantity
            $stmtCheck = $conn->prepare("SELECT quantity FROM stocks WHERE barcode = ?");
            $stmtCheck->bind_param("s", $item['barcode']);
            $stmtCheck->execute();
            $stmtCheck->bind_result($current_quantity);
            $stmtCheck->fetch();
            $stmtCheck->close();
        
            // Check if sufficient stock is available
            if ($quantity > $current_quantity) {
                $debug_log[] = "❌ Insufficient stock for item: " . $item['item_name'] . "<br>" .
                               "You only have " . $current_quantity . " in stock, but tried to deduct " . $quantity . ".<br>";
                $conn->rollback();
                continue;
            }
        
            // Proceed with deduction
            $quantityChange = -$quantity;
        
            $stmt = $conn->prepare("UPDATE stocks SET quantity = quantity + ? WHERE barcode = ?");
            $stmt->bind_param("is", $quantityChange, $item['barcode']);
        
            if ($stmt->execute()) {
                // Fetch the updated quantity after deduction
                $stmt2 = $conn->prepare("SELECT quantity FROM stocks WHERE barcode = ?");
                $stmt2->bind_param("s", $item['barcode']);
                $stmt2->execute();
                $stmt2->bind_result($updated_quantity);
                $stmt2->fetch();
                $stmt2->close();
        
                // Log the transaction
                $user_id = $_SESSION['user_id'];
        
                $stmt3 = $conn->prepare("INSERT INTO stock_transaction (stock_id, user_id, transaction_type, quantity, date) VALUES (?, ?, ?, ?, NOW())");
                $stmt3->bind_param("iisi", $item['stock_id'], $user_id, $transaction_type, $quantity);
                $stmt3->execute();
        
                $conn->commit();
        
                // Log success
                $debug_log[] = "✅ Deducted successfully for item: " . $item['item_name'] . "<br>" .
                               "Quantity deducted: " . $quantity . "<br>" .
                               "New quantity: " . $updated_quantity . "<br>";
            } else {
                $debug_log[] = "❌ Failed to update item: " . $stmt->error;
                $conn->rollback();
            }
        
            $stmt->close();
            $stmt3->close();
        
        } catch (Exception $e) {
            $conn->rollback();
            $debug_log[] = "❌ Error: " . $e->getMessage();
        }
        
    }

    $success_message = "Stock update results:<br><pre>" . implode("\n", $debug_log) . "</pre>";
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
    <script>
        // Automatically move to next input field when "Tab" is pressed
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.quantity-input');
            
            inputs.forEach((input, index) => {
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Tab') {
                        // Prevent default tab behavior
                        e.preventDefault();
                        // Focus the next input field
                        let nextInput = inputs[index + 1] || inputs[0]; // Wrap around to first input if last input
                        nextInput.focus();
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="wrapper">
        <?php require '../../asset/includes/staff_sidebar.php'; ?>

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
                                            <th>Current Quantity</th>
                                            <th>Quantity to Deduct</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($_SESSION['scanned_items']) > 0): ?>
                                            <?php foreach ($_SESSION['scanned_items'] as $key => $item): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($item['barcode']) ?></td>
                                                    <td><?= htmlspecialchars($item['item_name']) ?></td>
                                                    <td>
                                                        <?php
                                                            // Fetch current quantity from database
                                                            $stmt = $conn->prepare("SELECT quantity FROM stocks WHERE stock_id = ?");
                                                            $stmt->bind_param("i", $item['stock_id']);
                                                            $stmt->execute();
                                                            $stmt->bind_result($current_quantity);
                                                            $stmt->fetch();
                                                            $stmt->close();
                                                            echo $current_quantity; // Display current quantity from the database
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <!-- Quantity Input -->
                                                        <input type="number" name="quantity[<?= $key ?>]" value="<?= $item['quantity'] ?>" class="form-control quantity-input" min="1">
                                                    </td>
                                                    <td>
                                                        <a href="?remove_item=<?= $item['stock_id'] ?>"
                                                            onclick="return confirm('Are you sure to remove <?= addslashes($item['item_name']) ?>? ');"
                                                            class="btn btn-danger btn-sm">Remove</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5">No items scanned yet.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <?php if (count($_SESSION['scanned_items']) > 0): ?>
                                    <button type="submit" name="confirm_all" class="btn btn-danger">Confirm All to deduct</button>
                                <?php endif; ?>
                            </form>
                        </div>

                        <!-- Right: Barcode Scanner & Manual Barcode -->
                        <div class="col-md-6">
                            <!-- Barcode Scanning Form -->
                            <form action="" method="POST" id="barcode-form">
                                <div class="mb-3">
                                    <label for="barcode" class="form-label">Scan or Manually Enter Barcode</label>
                                    <input type="text" name="barcode" id="barcode" class="form-control" autofocus autocomplete="off" placeholder="Scan or type barcode">
                                </div>
                            </form>

                            <?php if (isset($error_message)): ?>
                                <div class="alert alert-danger mt-3"><?= $error_message ?></div>
                            <?php endif; ?>

                            <?php if (isset($success_message)): ?>
                                <div class="alert alert-success mt-3"><?= $success_message ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
