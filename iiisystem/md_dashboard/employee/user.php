<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}

define('ALLOW_ACCESS', true);
require("../../asset/database/db.php"); // Ensure database connection is included
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>III | EMPLOYEES</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="../../asset/css/sidebar.css">
    <link rel="stylesheet" href="../../asset/css/employees/user_style.css">

    <!-- Favicon -->
    <link rel="icon" href="../../asset/img/logo.png">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap5.min.css">
</head>

<body>
    <div class="wrapper">
        <?php require '../../asset/includes/sidebar.php'; ?>

        <div class="main p-3">
            <div class="container">
                <div class="table-container p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">List of Employees</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fa fa-plus"></i> Create New
                        </button>
                    </div>

                    <!-- Add Employee Modal -->
                    <div class="modal fade modal-lg" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Employee</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="adduser.php" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label class="form-label">First Name:</label>
                                            <input type="text" name="firstname" class="form-control" placeholder="Firstname" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Last Name:</label>
                                            <input type="text" name="lastname" class="form-control" placeholder="Lastname" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phone:</label>
                                            <input type="text" name="phone" class="form-control" placeholder="Phone" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email:</label>
                                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Profile:</label>
                                            <input type="file" name="profile" class="form-control" accept="image/*" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Username:</label>
                                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password:</label>
                                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="role">Select Role:</label>
                                            <select name="role" id="role" class="form-control" required>
                                                <option value="administrator">Administrator</option>
                                                <option value="user">Staff</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-success">Add Employee</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Table -->
                    <table id="userTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Account ID</th>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>User Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT a.account_id, u.user_id, u.firstname, u.lastname, u.avatar, a.username, a.role 
                                      FROM user_info u 
                                      JOIN account a ON u.user_id = a.user_id";
                            $result = $conn->query($query);

                            while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['account_id']); ?></td>
                                    <td>
                                        <img src="../../asset/uploads/<?php echo !empty($row['avatar']) ? htmlspecialchars($row['avatar']) : 'default.jpg'; ?>" class="avatar" alt="User Image" width="50">
                                    </td>
                                    <td><?php echo htmlspecialchars($row['firstname'] . " " . $row['lastname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars(ucwords($row['role'])); ?></td>
                                    <td>
                                        <a href="edituser.php?account_id=<?php echo $row['account_id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="delete_user.php?account_id=<?php echo $row['account_id']; ?>" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this user?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>

                            <?php if ($result->num_rows === 0): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No users found</td>
                                </tr>
                            <?php endif; ?>

                            <?php $conn->close(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#userTable').DataTable({
                responsive: true,
                lengthMenu: [5, 10, 25, 50],
                pageLength: 5,
                order: [[0, 'asc']]
            });
        });
    </script>

</body>
</html>
