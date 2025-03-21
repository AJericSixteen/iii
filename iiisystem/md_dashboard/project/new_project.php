<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}

define('ALLOW_ACCESS', true);
require("../../asset/database/db.php"); // Ensure database connection is included

date_default_timezone_set('Asia/Manila');
$currentDate = date('M-d-Y');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>III | PROJECT</title>

    <link rel="icon" href="../../asset/img/logo.png">
    <link rel="stylesheet" href="../../asset/css/Project/project.css">

    <!-- Bootstrap (Optional) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
                    <form action="add_client.php" method="POST">
                        <div class="row">
                            <h4>Contact Information</h4>
                            <div class="p-2 col-6">
                                <label for="name" class="form-label">Name:</label> <span class="red"> * </span>
                                <input type="text" name="name" class="form-control" id="name" required>
                            </div>
                            <div class="p-2 col-6">
                                <label for="client_company" class="form-label">Company:</label> <span class="red"> *
                                </span>
                                <input type="text" name="client_company" class="form-control" id="client_company"
                                    required>
                            </div>
                            <div class="p-2 col-6">
                                <label for="client_address" class="form-label">Address:</label> <span class="red"> *
                                </span>
                                <input type="text" name="client_address" class="form-control" id="client_address"
                                    required>
                            </div>
                            <div class="p-2 col-6">
                                <label for="client_phone" class="form-label">Phone:</label> <span class="red"> * </span>
                                <input type="text" name="client_phone" class="form-control" id="client_phone" required>
                            </div>
                            <div class="p-2 col">
                                <label for="client_email" class="form-label">Email:</label> <span class="red"> * </span>
                                <input type="email" name="client_email" class="form-control" id="client_email" required>
                            </div>
                        </div>
                        <hr>
                        <fieldset>
                            <legend class="text-info"><span style="color: black">Project</span></legend>
                            <div class="container mt-3">
                                <div class="row">
                                    <div class="p-2 col-md-6">
                                        <label class="form-label">Date Requested:</label>
                                        <input type="text" name="date_requested" class="form-control"
                                            value="<?php echo $currentDate; ?>" disabled>
                                    </div>
                                    <div class="p-2 col-md-6">
                                        <label class="form-label">Date Needed:</label> <span class="red"> * </span>
                                        <input type="date" name="date_needed" class="form-control" required>
                                    </div>
                                </div>

                                <div id="productContainer">
                                    <!-- Default Row -->
                                    <div class="row product-row">
                                        <div class="p-2 col-md-3">
                                            <label class="form-label">Product and Services:</label> <span class="red"> *
                                            </span>
                                            <select name="services[]" class="form-control" required>
                                                <option value="Banner">Banner</option>
                                                <option value="Sign">Sign</option>
                                                <option value="Lettering">Lettering</option>
                                                <option value="Vehicles Signs">Vehicles Signs</option>
                                                <option value="Decals">Decals</option>
                                                <option value="Displays">Displays</option>
                                                <option value="Event Management">Event Management</option>
                                                <option value="Marketing Assessment">Marketing Assessment</option>
                                            </select>

                                        </div>

                                        <div class="p-2 col-md-2">
                                            <label class="form-label">Quantity</label> <span class="red"> * </span>
                                            <input type="number" name="quantity[]" class="form-control quantity" min="1"
                                                required>
                                        </div>
                                        <div class="p-2 col-md-2">
                                            <label class="form-label">Price Per Piece</label> <span class="red"> *
                                            </span>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input type="number" name="price[]" class="form-control price" min="1"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="p-2 col-md-2">
                                            <label class="form-label">Total</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input type="number" class="form-control total" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-success btn-sm addRow mt-2">+</button>
                                <button type="button" class="btn btn-primary btn-sm mt-2 addList">Add List</button>
                            </div>
                        </fieldset>
                        <hr>
                        <table class="table table-striped" id="productTable" border="1">
                            <thead>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </thead>
                            <tbody></tbody>

                        </table>
                        <hr>
                        <div class="d-flix justify-content-center">
                            <button type="submit" class="btn btn-primary">Add New Client</button>
                            <a href="./project.php" class="btn btn-danger">Cancel</a>
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