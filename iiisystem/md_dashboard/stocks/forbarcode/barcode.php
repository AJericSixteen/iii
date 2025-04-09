<?php
require('../../asset/database/db.php');

$barcode = $_GET['code'] ?? '';
use Picqer\Barcode\BarcodeGeneratorPNG;
if (!empty($barcode)) {
    require('vendor/autoload.php'); // Ensure you have Picqer\Barcode installed

    header('Content-Type: image/png');
    $generator = new BarcodeGeneratorPNG();
    
    // Generate barcode only for the code, without a URL path
    echo $generator->getBarcode($barcode, $generator::TYPE_CODE_128);
}
?>
