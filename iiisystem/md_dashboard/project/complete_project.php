<?php
require '../../asset/database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'];

    // Retrieve client name based on client_id
    $stmt_client = $conn->prepare("SELECT name FROM client WHERE client_id = ?");
    $stmt_client->bind_param("i", $client_id);
    $stmt_client->execute();
    $stmt_client->bind_result($client_name);
    $stmt_client->fetch();
    $stmt_client->close();

    // FILE UPLOAD SECTION
    $target_dir = "../../asset/uploads/delivery_receipts/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create the directory if not exists
    }

    $file_name = basename($_FILES["delivery_receipt"]["name"]);
    $target_file = $target_dir . time() . '_' . $file_name; // unique filename
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image is valid
    $check = getimagesize($_FILES["delivery_receipt"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists (not needed with time prefix, but optional)
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["delivery_receipt"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Check allowed formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Final upload
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["delivery_receipt"]["tmp_name"], $target_file)) {
            // Database update
            $stmt = $conn->prepare("UPDATE project SET status = 'Completed', delivery_receipt = ? WHERE client_id = ? AND status = 'Delivered'");
            $stmt->bind_param("si", $target_file, $client_id);

            if ($stmt->execute()) {
                // Show success message with client name
                echo "<script>
                        alert('All delivered projects for " . htmlspecialchars($client_name) . " have been marked as completed and receipt uploaded.');
                        window.location.href = 'project.php';
                      </script>";
            } else {
                // Show error with client name
                echo "<script>
                        alert('Database error for client " . htmlspecialchars($client_name) . ": " . addslashes($stmt->error) . "');
                        window.location.href = 'project.php';
                      </script>";
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Your file was not uploaded due to previous errors.";
    }

    $conn->close();
}
?>
