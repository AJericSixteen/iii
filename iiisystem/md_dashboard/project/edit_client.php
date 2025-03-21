<?php
session_start();
require("../../asset/database/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

define('ALLOW_ACCESS', true);

// Validate client_id
if (!isset($_GET['client_id']) || !is_numeric($_GET['client_id'])) {
    die("Error: Invalid request. Client ID is missing or invalid.");
}

$client_id = intval($_GET['client_id']);

// Fetch client details
$query = "SELECT * FROM client WHERE client_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

if (!$client) {
    die("Error: Client not found.");
}

// Fetch client projects
$queryProjects = "SELECT * FROM project WHERE client_id = ?";
$stmtProjects = $conn->prepare($queryProjects);
$stmtProjects->bind_param("i", $client_id);
$stmtProjects->execute();
$resultProjects = $stmtProjects->get_result();
$projects = $resultProjects->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = trim($_POST['name']);
    $company = trim($_POST['company']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    // Update client details
    $updateClientQuery = "UPDATE client SET name=?, company=?, address=?, phone=?, email=? WHERE client_id=?";
    $updateClientStmt = $conn->prepare($updateClientQuery);
    $updateClientStmt->bind_param("sssssi", $name, $company, $address, $phone, $email, $client_id);

    if (!$updateClientStmt->execute()) {
        die("Error updating client: " . $updateClientStmt->error);
    }

    // Update projects if data is provided
    if (!empty($_POST['projects'])) {
        foreach ($_POST['projects'] as $project_id => $project_data) {
            $queryExisting = "SELECT * FROM project WHERE project_id = ? AND client_id = ?";
            $stmtExisting = $conn->prepare($queryExisting);
            $stmtExisting->bind_param("ii", $project_id, $client_id);
            $stmtExisting->execute();
            $existingProject = $stmtExisting->get_result()->fetch_assoc();
            
            if (!$existingProject) {
                continue;
            }
            
            // Ensure proper data handling
            $service = trim($project_data['services'] ?? $existingProject['services']);
            $quantity = (int) ($project_data['quantity'] ?? $existingProject['quantity']);
            $price = (float) ($project_data['price'] ?? $existingProject['price']);
            $total = $quantity * $price;
            
            $date_requested = !empty($project_data['date_requested']) ? date('Y-m-d', strtotime($project_data['date_requested'])) : $existingProject['date_requested'];
            $date_needed = !empty($project_data['date_needed']) ? date('Y-m-d', strtotime($project_data['date_needed'])) : $existingProject['date_needed'];
            
            // Debugging: Log received data
            error_log("Updating project ID $project_id: date_needed = $date_needed");

            $updateProjectQuery = "UPDATE project SET services=?, quantity=?, price=?, total=?, date_requested=?, date_needed=? WHERE project_id=? AND client_id=?";
            $updateProjectStmt = $conn->prepare($updateProjectQuery);
            if (!$updateProjectStmt) {
                die("Prepare failed: " . $conn->error);
            }

            $updateProjectStmt->bind_param("siidssii", $service, $quantity, $price, $total, $date_requested, $date_needed, $project_id, $client_id);

            if (!$updateProjectStmt->execute()) {
                die("Error updating project: " . $updateProjectStmt->error);
            }
        }
    }

    $_SESSION['success'] = "Client and projects updated successfully!";
    header("Location: project.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>III | PROJECT</title>

    <link rel="icon" href="../../asset/img/logo.png">
    <link rel="stylesheet" href="../../asset/css/Project/project.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="wrapper">
        <?php require '../../asset/includes/sidebar.php'; ?>
        <div class="main p-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h4>Edit Client and Project</h4>
                </div>
                <div class="card-body">
                    <form action="edit_client.php?client_id=<?= htmlspecialchars($client['client_id']) ?>"
                        method="POST">
                        <div class="row">
                            <h4>Contact Information</h4>
                            <div class="p-2 col-6">
                                <label for="name" class="form-label">Name:</label> <span class="red"> * </span>
                                <input type="text" name="name" class="form-control" id="name"
                                    value="<?= htmlspecialchars($client['name']) ?>" required>
                            </div>
                            <div class="p-2 col-6">
                                <label for="company" class="form-label">Company:</label> <span class="red"> * </span>
                                <input type="text" name="company" class="form-control" id="company"
                                    value="<?= htmlspecialchars($client['company']) ?>" required>
                            </div>
                            <div class="p-2 col-6">
                                <label for="address" class="form-label">Address:</label> <span class="red"> * </span>
                                <input type="text" name="address" class="form-control" id="address"
                                    value="<?= htmlspecialchars($client['address']) ?>" required>
                            </div>
                            <div class="p-2 col-6">
                                <label for="phone" class="form-label">Phone:</label> <span class="red"> * </span>
                                <input type="text" name="phone" class="form-control" id="phone"
                                    value="<?= htmlspecialchars($client['phone']) ?>" required>
                            </div>
                            <div class="p-2 col">
                                <label for="email" class="form-label">Email:</label> <span class="red"> * </span>
                                <input type="email" name="email" class="form-control" id="email"
                                    value="<?= htmlspecialchars($client['email']) ?>" required>
                            </div>
                        </div>
                        <hr>
                        <fieldset>
                            <legend class="text-info"><span style="color: black">Project</span></legend>
                            <div class="container mt-3">
                                <div id="projectContainer">
                                    <?php foreach ($projects as $project): ?>
                                        <div class="row project-row">
                                            <div class="p-2 col-md-2">
                                                <label class="form-label">Product and Services:</label> <span class="red">
                                                    *</span>
                                                <select name="projects[<?= $project['project_id'] ?>][services]"
                                                    class="form-control" disabled>
                                                    <option value="" disabled>Select a service</option>
                                                    <?php
                                                    // Define available services
                                                    $services = [
                                                        "Banner",
                                                        "Sign",
                                                        "Lettering",
                                                        "Vehicles Signs",
                                                        "Decals",
                                                        "Displays",
                                                        "Event Management",
                                                        "Marketing Assessment"
                                                    ];

                                                    // Check if project['services'] exists
                                                    $selectedService = isset($project['services']) ? $project['services'] : '';

                                                    // Generate service dropdown options
                                                    foreach ($services as $service):
                                                        ?>
                                                        <option value="<?= $service ?>" <?= ($service == $selectedService) ? 'selected' : '' ?>>
                                                            <?= $service ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>

                                                <!-- Debugging: Check if 'services' exists -->
                                                <?php if (!isset($project['services'])): ?>
                                                    <p class="text-danger">Error: 'services' value not found in project data.
                                                    </p>
                                                <?php else: ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="p-2 col-md-2">
                                                <label class="form-label">Date Needed:</label> <span class="red"> *
                                                </span>
                                                <input type="date"
                                                    name="projects[<?= $project['project_id'] ?>][date_needed]"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($project['date_needed']) ?>" required>
                                            </div>
                                            <div class="p-2 col-md-2">
                                                <label class="form-label">Quantity:</label> <span class="red"> * </span>
                                                <input type="number"
                                                    name="projects[<?= $project['project_id'] ?>][quantity]"
                                                    class="form-control" value="<?= $project['quantity'] ?>" required>
                                            </div>
                                            <div class="p-2 col-md-2">
                                                <label class="form-label">Price:</label> <span class="red"> * </span>
                                                <input type="number" name="projects[<?= $project['project_id'] ?>][price]"
                                                    class="form-control" value="<?= $project['price'] ?>" required>
                                            </div>
                                            <div class="p-2 col-md-2">
                                                <label class="form-label">Total:</label> <span class="red"> * </span>
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="text" name="projects[<?= $project['project_id'] ?>][total]"
                                                        class="form-control total-price"
                                                        value="<?= number_format($project['total'], 2) ?>" disabled>
                                                </div>
                                            </div>


                                        </div>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                        </fieldset>
                        <hr>
                        <button type="submit" class="btn btn-primary">Update Client & Projects</button>
                        <a href="./project.php" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".project-row").forEach(row => {
            let quantityInput = row.querySelector("input[name^='projects'][name$='[quantity]']");
            let priceInput = row.querySelector("input[name^='projects'][name$='[price]']");
            let totalInput = row.querySelector("input[name^='projects'][name$='[total]']");

            function updateTotal() {
                let quantity = parseFloat(quantityInput.value) || 0;
                let price = parseFloat(priceInput.value) || 0;
                let newTotal = quantity * price;
                totalInput.value = newTotal.toFixed(2);
            }

            // Attach event listeners to both quantity and price inputs
            quantityInput.addEventListener("input", updateTotal);
            priceInput.addEventListener("input", updateTotal);
        });
    });
</script>


</html>