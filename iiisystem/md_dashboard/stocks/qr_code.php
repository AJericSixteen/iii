<?php
// Include the Composer autoloader for the QR Code library
require './vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Check if the 'code' parameter exists, otherwise set a default value
$code = $_GET['code'] ?? 'default';

// Set the content-type header for PNG images
header('Content-Type: image/png');

// Create the QR code by appending the URL with the 'code' parameter
$qrCode = QrCode::create("http://192.168.0.13/iii/iiisystem/md_dashboard/stocks/scan.php?code=" . urlencode($code));

// Create a PNG writer instance
$writer = new PngWriter();

// Write the QR code to a string (PNG format)
$result = $writer->write($qrCode);

// Output the QR code image directly to the browser
echo $result->getString();
?>