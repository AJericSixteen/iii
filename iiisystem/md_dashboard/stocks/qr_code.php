<?php
require_once 'vendor/autoload.php';  // Path to the Picqer Barcode library

use Picqer\Barcode\BarcodeGeneratorPNG;

// Get the barcode code from URL parameters (e.g., 'add_001' or 'deduct_001')
$code = $_GET['code'];

// Get current date in MMDDYY format (last two digits of year)
$date = date('mdy'); // Format: MMDDYY (e.g., 042025 for April 20, 2025)

// Extract stock ID from the code (assuming 'add_001' format, so we strip 'add_' or 'deduct_')
$stockId = str_replace(['add_', 'deduct_'], '', $code);

// Ensure that stock ID is padded to 8 digits with leading zeros
$stockId = str_pad($stockId, 8, '0', STR_PAD_LEFT); // E.g., '00019234'

// Combine the date and stock ID (e.g., 04202500019234 for a stock ID of '001')
$barcodeText = $date . $stockId; // This will result in 04202500019234 for stock ID '001'

// Initialize the barcode generator (you can choose between HTML or PNG)
$generator = new BarcodeGeneratorPNG();

// Generate the barcode PNG image
$barcodeImage = $generator->getBarcode($barcodeText, $generator::TYPE_CODE_128); // Use CODE_128 for 1D barcode

// Output the barcode image
header('Content-Type: image/png');
echo $barcodeImage;
?>
