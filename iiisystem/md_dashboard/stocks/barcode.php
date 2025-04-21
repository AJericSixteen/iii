<?php
require __DIR__ . '/vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    $generator = new BarcodeGeneratorPNG();
    header('Content-Type: image/png');
    echo $generator->getBarcode($code, $generator::TYPE_CODE_128);
} else {
    echo 'No code provided';
}
?>
