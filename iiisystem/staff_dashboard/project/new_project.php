<?php
session_start();

// include '../../asset/includes/auth_managing_director.php';

define('ALLOW_ACCESS', true);
require('../../asset/database/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}

$currentDate = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>III | PROJECT</title>
    <link rel="icon" href="../../asset/img/logo.png">

    <!-- Bootstrap & DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <link rel="icon" href="../../asset/img/logo.png">
    <link rel="stylesheet" href="../../asset/css/Project/project.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php require '../../asset/includes/sidebar.php'; ?>
        <div class="main p-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h4>Create New Project</h4>
                </div>
                <div class="card-body">
                    <form id="projectForm" action="add_client.php" method="POST">
                        <div class="row">
                            <h4>Contact Information</h4>
                            <div class="p-2 col-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Enter your name" required>
                            </div>
                            <div class="p-2 col-6">
                                <label for="company" class="form-label">Company</label>
                                <input type="text" name="company" id="company" class="form-control"
                                    placeholder="Enter company name" required>
                            </div>
                            <div class="p-2 col-6">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" name="address" id="address" class="form-control"
                                    placeholder="Enter address" required>
                            </div>
                            <div class="p-2 col-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" name="phone" id="phone" class="form-control"
                                    placeholder="Enter phone number" required>
                            </div>
                            <div class="p-2 col">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Enter email" required>
                            </div>
                        </div>
                        <hr>
                        <fieldset>
                            <legend class="text-info"><span style="color: black">Project</span></legend>
                            <div class="container mt-3">
                                <div class="row">
                                    <div class="p-2 col-md-6">
                                        <label for="date_requested" class="form-label">Date Requested</label>
                                        <input type="date" name="date_requested" id="date_requested"
                                            class="form-control" value="<?php echo $currentDate; ?>" required readonly>
                                    </div>
                                    <div class="p-2 col-md-6">
                                        <label for="date_needed" class="form-label">Date Needed</label>
                                        <input type="date" name="date_needed" id="date_needed" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <hr>
                                <!-- HTML -->

                                <!-- Default Row -->
                                <div id="productContainer"></div>


                                <button type="button" class="btn btn-success addRow">Add Row</button>
                                <button type="button" class="btn btn-primary addList">Add List</button>
                                <table id="productTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th>Tarp Type</th>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody> <!-- This is crucial -->
                                </table>
                                <hr>
                                <div class="d-flix justify-content-center">
                                    <button type="submit" class="btn btn-primary">Add New Client</button>
                                    <a  href="./project.php" type="button" class="btn btn-danger">Cancel</a>
                                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../asset/js/new_project.js"></script>
</body>

</html>