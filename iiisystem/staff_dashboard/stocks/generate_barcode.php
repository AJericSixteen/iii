<?php
// Include the Composer autoload file to use the barcode library
require 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

// Create an instance of the barcode generator
$generator = new BarcodeGeneratorPNG();

// Get the barcode data from the URL parameter (e.g., barcode=04202025-00019234)
$barcodeData = isset($_GET['barcode']) ? $_GET['barcode'] : '1234567890';

// Generate the barcode image
$barcodeImage = $generator->getBarcode($barcodeData, $generator::TYPE_CODE_128);

// Set the headers to indicate the content type is an image and to prompt download
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="barcode.png"');

// Output the generated barcode image
echo $barcodeImage;
exit();
?>