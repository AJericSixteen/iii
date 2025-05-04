<?php
require_once './vendor/autoload.php'; // Adjust path as needed
require_once '../../asset/database/db.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

function getStockDataById($stockId) {
    global $conn; // bring $conn into the function
    $sql = "SELECT item_name, barcode FROM stocks WHERE stock_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $stockId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['barcode_select'])) {
    $selectedStockIds = $_POST['barcode_select'];
    $generator = new BarcodeGeneratorPNG();

    header('Content-Type: text/html; charset=utf-8');
    echo '<center>';
    echo '<html><head><title>Barcodes</title></head><body>';
    echo '<h3>Selected Barcodes</h3>';

    foreach ($selectedStockIds as $stockId) {
        $stockData = getStockDataById($stockId);
        if (!$stockData) continue; // skip if not found

        $itemName = htmlspecialchars($stockData['item_name']);
        $barcode = htmlspecialchars($stockData['barcode']);
        $barcodeImage = $generator->getBarcode($barcode, BarcodeGeneratorPNG::TYPE_CODE_128);

        echo '<div style="margin-bottom: 20px;">';
        
        echo '<img src="data:image/png;base64,' . base64_encode($barcodeImage) . '" alt="' . $itemName . ' barcode">';
        echo '<div style="margin-top: 5px; font-family: monospace; font-size: 14px;">' . $barcode . '</div>';
        echo '<h4>' . $itemName . '</h4>';
        echo '<center>';
        echo '</div>';
    }

    echo '</body></html>';
} else {
    echo 'No barcodes selected.';
}
?>
