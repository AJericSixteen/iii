<?php
require('../../asset/database/db.php');

if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];
    
    // Query to find the item by barcode
    $stmt = $pdo->prepare("SELECT * FROM stocks WHERE barcode = ?");
    $stmt->execute([$barcode]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        // Return item data as JSON
        echo json_encode($item);
    } else {
        // Return an empty response or error
        echo json_encode(null);
    }
}
?>
