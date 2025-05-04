<?php
require '../../asset/database/db.php';
require '../../../vendor/autoload.php'; // Ensure this path points to the PhpSpreadsheet autoload.php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Get year and month from URL
$year = isset($_GET['year']) ? $_GET['year'] : '';
$month = isset($_GET['month']) ? $_GET['month'] : '';

// Validate inputs
if (empty($year) || empty($month)) {
    die('Invalid year or month!');
}

// Query for Added transactions
$query_add = "
    SELECT 
        st.transaction_id,
        s.item_name,
        st.quantity,
        u.firstname,
        u.lastname,
        st.date
    FROM stock_transaction st
    JOIN stocks s ON st.stock_id = s.stock_id
    JOIN user_info u ON st.user_id = u.user_id
    WHERE YEAR(st.date) = '$year' AND MONTH(st.date) = '$month' AND st.transaction_type = 'add'
    ORDER BY st.date DESC
";

// Query for Deducted transactions
$query_deduct = "
    SELECT 
        st.transaction_id,
        s.item_name,
        st.quantity,
        u.firstname,
        u.lastname,
        st.date
    FROM stock_transaction st
    JOIN stocks s ON st.stock_id = s.stock_id
    JOIN user_info u ON st.user_id = u.user_id
    WHERE YEAR(st.date) = '$year' AND MONTH(st.date) = '$month' AND st.transaction_type = 'deduct'
    ORDER BY st.date DESC
";

// Execute queries
$result_add = mysqli_query($conn, $query_add);
$result_deduct = mysqli_query($conn, $query_deduct);

// Create new Spreadsheet
$spreadsheet = new Spreadsheet();

// Remove default sheet created automatically by PhpSpreadsheet
$spreadsheet->removeSheetByIndex(0);

// Add Added Transactions Sheet
$sheet_add = $spreadsheet->createSheet();
$sheet_add->setTitle('Added Transactions');

// Set Header for Added Transactions
$sheet_add->setCellValue('A1', 'Transaction ID')
          ->setCellValue('B1', 'User')
          ->setCellValue('C1', 'Item')
          ->setCellValue('D1', 'Quantity')
          ->setCellValue('E1', 'Date')
          ->setCellValue('F1', 'Time');

// Write data to Added Sheet
$row_num = 2;
while ($row = mysqli_fetch_assoc($result_add)) {
    $sheet_add->setCellValue('A' . $row_num, $row['transaction_id']);
    $sheet_add->setCellValue('B' . $row_num, $row['firstname'] . ' ' . $row['lastname']);
    $sheet_add->setCellValue('C' . $row_num, $row['item_name']);
    $sheet_add->setCellValue('D' . $row_num, $row['quantity']);
    $sheet_add->setCellValue('E' . $row_num, date('F-d-Y', strtotime($row['date'])));
    $sheet_add->setCellValue('F' . $row_num, date('h:i A', strtotime($row['date'])));
    $row_num++;
}

// Add Deducted Transactions Sheet
$sheet_deduct = $spreadsheet->createSheet();
$sheet_deduct->setTitle('Deducted Transactions');

// Set Header for Deducted Transactions
$sheet_deduct->setCellValue('A1', 'Transaction ID')
             ->setCellValue('B1', 'User')
             ->setCellValue('C1', 'Item')
             ->setCellValue('D1', 'Quantity')
             ->setCellValue('E1', 'Date')
             ->setCellValue('F1', 'Time');

// Write data to Deducted Sheet
$row_num = 2;
while ($row = mysqli_fetch_assoc($result_deduct)) {
    $sheet_deduct->setCellValue('A' . $row_num, $row['transaction_id']);
    $sheet_deduct->setCellValue('B' . $row_num, $row['firstname'] . ' ' . $row['lastname']);
    $sheet_deduct->setCellValue('C' . $row_num, $row['item_name']);
    $sheet_deduct->setCellValue('D' . $row_num, $row['quantity']);
    $sheet_deduct->setCellValue('E' . $row_num, date('F-d-Y', strtotime($row['date'])));
    $sheet_deduct->setCellValue('F' . $row_num, date('h:i A', strtotime($row['date'])));
    $row_num++;
}

// Set active sheet to Added Transactions for first display
$spreadsheet->setActiveSheetIndex(0);

// Generate Excel file and download
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="stock_transaction_report_' . $year . '-' . $month . '.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>
