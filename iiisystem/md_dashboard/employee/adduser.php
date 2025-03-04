<?php
require("../../asset/database/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // ✅ Ensure file was uploaded
        if (!isset($_FILES["profile"]) || $_FILES["profile"]["error"] !== 0) {
            throw new Exception("❌ Error: No file uploaded or file upload failed. Code: " . ($_FILES["profile"]["error"] ?? 'unknown'));
        }

        // ✅ Validate file type
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        $fileName = basename($_FILES["profile"]["name"]);
        $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($imageFileType, $allowedTypes)) {
            throw new Exception("❌ Error: Invalid file type. Only JPG, JPEG, PNG & GIF are allowed.");
        }

        // ✅ Move file to the uploads folder
        $targetDir = "../../asset/uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $newFileName = time() . "_" . $fileName; // Prevent duplicate filenames
        $targetFilePath = $targetDir . $newFileName;

        if (!move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFilePath)) {
            throw new Exception("❌ Error: Failed to move uploaded file.");
        }

        // ✅ Insert user info into database
        $stmt1 = $conn->prepare("INSERT INTO user_info (firstname, lastname, phone, email, avatar) VALUES (?, ?, ?, ?, ?)");
        $stmt1->bind_param("sssss", $firstname, $lastname, $phone, $email, $targetFilePath);

        $firstname = $_POST['firstname'];
        $lastname  = $_POST['lastname'];
        $phone     = $_POST['phone'];
        $email     = $_POST['email'];

        $stmt1->execute();
        $user_id = $conn->insert_id;

        // ✅ Insert account info with hashed password
        $stmt2 = $conn->prepare("INSERT INTO account (user_id, username, password, role) VALUES (?, ?, ?, ?)");
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role     = $_POST['role'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt2->bind_param("isss", $user_id, $username, $hashed_password, $role);
        $stmt2->execute();

        $stmt1->close();
        $stmt2->close();
        $conn->close();

        echo "<script>alert('✅ User and account created successfully!')</script>";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>
