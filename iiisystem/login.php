<?php
session_start();
include './asset/database/db.php'; // Database connection

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form data is received
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        die("Error: Username or Password not received. Check form names.");
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        die("Error: Username or Password cannot be empty.");
    }

    // Fetch user details and account details
    $sql = "SELECT account.user_id, account.username, account.password, account.role, 
                   user_info.firstname, user_info.lastname 
            FROM account 
            JOIN user_info ON account.user_id = user_info.user_id
            WHERE account.username = ? LIMIT 1";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Ensure 'role' exists in the database result
        if (!isset($row['role']) || empty($row['role'])) {
            die("Error: Role is missing in the database.");
        }

        // Verify the password using password_verify() against the hashed password
        if (password_verify($password, $row["password"])) {
            // Set session variables
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['lastname'] = $row['lastname'];
            $_SESSION['role'] = trim($row['role']);

            // Redirect based on role
            if ($_SESSION['role'] === "managing director") {
                header("Location: ./md_dashboard/dashboard/dashboard.php");
            } else {
                // Stuff Dashboard
                header("Location: dashboard.php");
            }
            exit();
            
        } else {
            echo "<script> alert('Invalid Password!'); window.location.href='./index.php';</script>";
            
        }
    } else {
        echo "<script>alert('Invalid Username and Password!'); window.location.href='./index.php';</script>";
    }

    $stmt->close();
}
?>