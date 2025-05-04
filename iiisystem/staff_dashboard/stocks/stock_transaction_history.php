    <?php
    require '../../asset/database/db.php';
    define('ALLOW_ACCESS', true);

    // Fetch all transactions grouped by year and month
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
        ORDER BY st.date DESC
    ";

    $result = mysqli_query($conn, $query);

    $transactions_by_year = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $year = date('Y', strtotime($row['date']));
        $month = date('m', strtotime($row['date']));
        $month_name = date('F', strtotime($row['date']));

        if (!isset($transactions_by_year[$year])) {
            $transactions_by_year[$year] = [];
        }

        if (!isset($transactions_by_year[$year][$month])) {
            $transactions_by_year[$year][$month] = [
                'name' => $month_name,
                'items' => []
            ];
        }

        $transactions_by_year[$year][$month]['items'][] = [
            'transaction_id' => $row['transaction_id'],
            'transaction_type' => $row['transaction_type'],
            'item_name' => $row['item_name'],
            'quantity' => $row['quantity'],
            'user_name' => $row['firstname'] . ' ' . $row['lastname'],
            'date' => $row['date']
        ];
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Stock Transaction History</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="icon" href="../../asset/img/logo.png" type="image/x-icon">
    </head>

    <body>
    <div class="wrapper d-flex">
        <?php require '../../asset/includes/staff_sidebar.php'; ?>
        <div class="main p-3 flex-grow-1">
            <div class="card">
                <div class="card-header">
                    <h4>Stock Transaction History</h4>
                </div>
                <div class="card-body">

                    <?php if (!empty($transactions_by_year)) : ?>
                        <div class="accordion" id="transactionAccordion">
                            <?php foreach ($transactions_by_year as $year => $months): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?= $year ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse<?= $year ?>" aria-expanded="false"
                                                aria-controls="collapse<?= $year ?>">
                                            <?= $year ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $year ?>" class="accordion-collapse collapse"
                                        aria-labelledby="heading<?= $year ?>" data-bs-parent="#transactionAccordion">
                                        <div class="accordion-body">
                                            <div class="accordion" id="monthAccordion<?= $year ?>">
                                                <?php foreach ($months as $month => $month_data): ?>
                                                    <div class="accordion-item">
                                                    <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading<?= $year . $month ?>">
    <button class="accordion-button collapsed" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse<?= $year . $month ?>"
            aria-expanded="false"
            aria-controls="collapse<?= $year . $month ?>">
        <?= $month_data['name'] ?>
    </button>
    <div class="btn-group ms-2">
        <a href="download_month_report.php?year=<?= $year ?>&month=<?= $month ?>" class="btn btn-sm btn-success">Download</a>
    </div>
</h2>

                                                        <div id="collapse<?= $year . $month ?>"
                                                            class="accordion-collapse collapse"
                                                            aria-labelledby="heading<?= $year . $month ?>"
                                                            data-bs-parent="#monthAccordion<?= $year ?>">
                                                            <div class="accordion-body">
                                                                <table class="table table-striped table-hover transactionTable"
                                                                    style="width:100%">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>User</th>
                                                                        <th>Type</th>
                                                                        <th>Item</th>
                                                                        <th>Qty</th>
                                                                        <th>Date</th>
                                                                        <th>Time</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php foreach ($month_data['items'] as $trans): ?>
                                                                        <tr>
                                                                            <td><?= $trans['transaction_id'] ?></td>
                                                                            <td><?= $trans['user_name'] ?></td>
                                                                            <td><?= $trans['transaction_type'] ?></td>
                                                                            <td><?= $trans['item_name'] ?></td>
                                                                            <td><?= $trans['quantity'] ?></td>
                                                                            <td><?= date('F-d-Y', strtotime($trans['date'])) ?></td>
                                                                            <td><?= date('h:i A', strtotime($trans['date'])) ?></td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">No stock transactions found.</div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- JS scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTables when an accordion body is shown
            $('.accordion-collapse').on('shown.bs.collapse', function () {
                $(this).find('.transactionTable').each(function () {
                    if (!$.fn.DataTable.isDataTable(this)) {
                        $(this).DataTable({
                            "order": [[5, 'desc']]
                        });
                    }
                });
            });
        });
    </script>

    </body>
    </html>
