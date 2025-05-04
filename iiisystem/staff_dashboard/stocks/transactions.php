<?php
require '../../asset/database/db.php';
define('ALLOW_ACCESS', true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Transactions</title>
    <!-- icon -->
    <link rel="icon" href="../../asset/img/logo.png" type="image/x-icon">
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="wrapper">
        <?php require '../../asset/includes/staff_sidebar.php'; ?>
        <div class="main p-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Stock Transaction</h5>
                </div>
                <div class="card-body">
                    <table id="transactionTable" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Name</th>
                                <th>Transaction Type</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $currentMonth = date('m');
                            $currentYear = date('Y');
                            
                            $query = "
                                SELECT 
                                    st.transaction_id,
                                    CASE 
                                        WHEN st.transaction_type = 'add' THEN 'Added'
                                        WHEN st.transaction_type = 'deduct' THEN 'Deducted'
                                    END AS transaction_type,
                                    s.item_name,
                                    st.quantity,
                                    u.firstname,
                                    u.lastname,
                                    st.date
                                FROM stock_transaction st
                                JOIN stocks s ON st.stock_id = s.stock_id
                                JOIN user_info u ON st.user_id = u.user_id
                                WHERE MONTH(st.date) = '$currentMonth' AND YEAR(st.date) = '$currentYear'
                                ORDER BY st.date DESC
                            ";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['transaction_id']); ?></td>
                                    <td><?= htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']); ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['transaction_type']); ?></td>
                                    <td><?= htmlspecialchars($row['item_name']); ?></td>
                                    <td><?= htmlspecialchars($row['quantity']); ?></td>
                                    <td><?= htmlspecialchars(date('M-d-Y', strtotime($row['date']))); ?></td>
                                    <td><?= date("h:i A", strtotime($row['date'])) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#transactionTable').DataTable({
                "order": [[5, 'desc']], // Sort by the Date column (index 5) in descending order
                "columnDefs": [{
                    "targets": 5, // Target the Date column
                    "type": "date" // Ensure proper date sorting
                }]
            });
        });
    </script>
</body>

</html>