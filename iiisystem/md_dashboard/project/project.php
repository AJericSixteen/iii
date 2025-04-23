<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}

require("../../asset/database/db.php"); // Ensure database connection is included

define('ALLOW_ACCESS', true);
require("../../asset/database/db.php"); // Ensure database connection is included

// Get unique clients and count their projects
$query = "
    SELECT c.client_id, c.name, c.company, c.address, c.phone, c.email, 
           COUNT(p.project_id) AS project_count
    FROM client c
    LEFT JOIN project p ON c.client_id = p.client_id AND p.status != 'Completed' -- Exclude completed projects
    GROUP BY c.client_id
    ORDER BY c.client_id ASC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>III | PROJECT</title>

    <link rel="stylesheet" href="../../asset/css/sidebar.css">
    <link rel="stylesheet" href="../../asset/css/project/project.css">
    <link rel="icon" href="../../asset/img/logo.png">
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
                        <h4 class="mb-0">List of Clients</h4>
                        <a href="./new_project.php" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Create New Project
                        </a>
                    </div>

                    <table id="userTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Client ID</th>
                                <th>Client Name</th>
                                <th>Company</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Projects</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <?php if ($row['project_count'] == 0)
                                    continue; ?>
                                <!-- Skip clients with no active projects -->
                                <tr>
                                    <td><?= $row['client_id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['company']) ?></td>
                                    <td><?= htmlspecialchars($row['address']) ?></td>
                                    <td><?= htmlspecialchars($row['phone']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= $row['project_count'] ?> Project(s)</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="view_project.php?client_id=<?= $row['client_id'] ?>">View</a>
                                                </li>
                                                <li><a class="dropdown-item"
                                                        href="edit_client.php?client_id=<?= $row['client_id'] ?>">Edit</a>
                                                </li>
                                                <li><a class="dropdown-item text-danger"
                                                        href="delete_client.php?client_id=<?= $row['client_id'] ?>"
                                                        onclick="return confirm('Are you sure?');">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
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