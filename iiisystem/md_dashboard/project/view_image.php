<?php
// view_image.php

$filename = $_GET['file'];
$filePath = "../../asset/uploads/delivery_receipts/" . basename($filename);

// Check if file exists
if (file_exists($filePath)) {
    // Get file extension to set the correct MIME type
    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    switch ($fileExtension) {
        case 'jpg':
        case 'jpeg':
            header("Content-Type: image/jpeg");
            break;
        case 'png':
            header("Content-Type: image/png");
            break;
        case 'gif':
            header("Content-Type: image/gif");
            break;
        default:
            // Return a 404 if the file type is not supported
            header("HTTP/1.1 404 Not Found");
            exit();
    }

    // Output the image content
    readfile($filePath);
    exit();
} else {
    // Handle error if file does not exist
    echo "File not found.";
    exit();
}
?>
