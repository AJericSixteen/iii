<?php
require '../../asset/database/db.php';

// Check if dates are set
if (!isset($_GET['start_date']) || !isset($_GET['end_date'])) {
    die("Start and End dates are required.");
}

$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

// Validate date format (basic check)
if (!strtotime($start_date) || !strtotime($end_date)) {
    die("Invalid date format.");
}

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="stock_transaction_report_' . $start_date . '_to_' . $end_date . '.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Add CSV header row
fputcsv($output, ['Transaction ID', 'Name', 'Transaction Type', 'Item Name', 'Quantity', 'Date', 'Time']);

// Query the transactions within date range
$query = "
    SELECT 
        st.transaction_id,
        CONCAT(u.firstname, ' ', u.lastname) AS user_name,
        CASE 
            WHEN st.transaction_type = 'add' THEN 'Added'
            WHEN st.transaction_type = 'deduct' THEN 'Deducted'
        END AS transaction_type,
        s.item_name,
        st.quantity,
        st.date
    FROM stock_transaction st
    JOIN stocks s ON st.stock_id = s.stock_id
    JOIN user_info u ON st.user_id = u.user_id
    WHERE DATE(st.date) BETWEEN '$start_date' AND '$end_date'
    ORDER BY st.date DESC
";

$result = mysqli_query($conn, $query);

// Check if query ran successfully
if (!$result) {
    die("Database query failed.");
}

// Output data rows
while ($row = mysqli_fetch_assoc($result)) {
    $date = date('M-d-Y', strtotime($row['date']));
    $time = date('h:i A', strtotime($row['date']));
    fputcsv($output, [
        $row['transaction_id'],
        $row['user_name'],
        $row['transaction_type'],
        $row['item_name'],
        $row['quantity'],
        $date,
        $time
    ]);
}

// Close output stream
fclose($output);
exit();
?>
