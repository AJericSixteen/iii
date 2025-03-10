<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

define('ALLOW_ACCESS', true);

include '../../asset/database/db.php';

// Get the account_id from GET parameter
if (!isset($_GET['account_id'])) {
    die("No account id provided.");
}
$account_id = intval($_GET['account_id']);

// Fetch user details from user_info joined with account based on account_id
$sql = "SELECT u.*, a.account_id, a.role, a.username, a.password 
        FROM user_info u 
        JOIN account a ON u.user_id = a.user_id 
        WHERE a.account_id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $account_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// If no user found, handle the error
if (!$user) {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>III | Dashboard - Edit User</title>

  <!-- Logo -->
  <link rel="icon" href="../../asset/img/logo.png">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">
  
  <!-- Custom Styles for Modern, Elegant Look -->
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .main {
      margin-top: 30px;
    }
    .edit-card {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 30px;
      max-width: 600px;
      margin: 0 auto;
    }
    .edit-card h3 {
      margin-bottom: 20px;
      text-align: center;
      font-weight: 600;
      color: #333;
    }
    .btn-back {
      margin-bottom: 20px;
    }
    .form-label {
      font-weight: 500;
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
      padding: 10px 20px;
      font-weight: 500;
    }
  </style>
</head>
<body>
  <div class="wrapper">
  <?php require '../../asset/includes/sidebar.php'; ?>
    <div class="main p-3">
      <div class="edit-card">
        <!-- Back Button -->
        <a href="user.php" class="btn btn-secondary btn-back">
          <i class="fa fa-arrow-left"></i> Back
        </a>
        <h3>Edit User</h3>
        <form action="updateuser.php" method="POST">
          <!-- Hidden input to pass the account ID -->
          <input type="hidden" name="account_id" value="<?php echo htmlspecialchars($user['account_id']); ?>">

          <div class="mb-3">
            <label for="firstname" class="form-label">Firstname</label>
            <input type="text" name="firstname" class="form-control"
                   value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="lastname" class="form-label">Lastname</label>
            <input type="text" name="lastname" class="form-control"
                   value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control"
                   value="<?php echo htmlspecialchars($user['phone']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control"
                   value="<?php echo htmlspecialchars($user['username']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">User Type</label>
            <input type="text" name="role" class="form-control"
                   value="<?php echo htmlspecialchars($user['role']); ?>" readonly>
          </div>
          <button type="submit" name="edit_user" class="btn btn-primary w-100">Update User</button>
        </form>
      </div>
    </div>
  </div>

  <!-- JS Files -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../asset/js/script.js"></script>
</body>
</html>
