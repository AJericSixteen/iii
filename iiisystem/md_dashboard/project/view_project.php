<?php
session_start();
require("../../asset/database/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

define('ALLOW_ACCESS', true);

// Get client_id from GET request, or fallback to session
if (isset($_GET['client_id'])) {
    $_SESSION['client_id'] = $_GET['client_id'];
}
if (!isset($_SESSION['client_id'])) {
    die("Invalid request.");
}

$client_id = $_SESSION['client_id'];

// Fetch client details
$client_query = "SELECT client_id, name, company, address, phone, email FROM client WHERE client_id = ?";
$client_stmt = $conn->prepare($client_query);
$client_stmt->bind_param("i", $client_id);
$client_stmt->execute();
$client_result = $client_stmt->get_result();
$client = $client_result->fetch_assoc();

if (!$client) {
    die("Client not found.");
}

// Fetch projects related to the client
$project_query = "SELECT * FROM project WHERE client_id = ?";
$project_stmt = $conn->prepare($project_query);
$project_stmt->bind_param("i", $client_id);
$project_stmt->execute();
$project_result = $project_stmt->get_result();
$projects = $project_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>III | <?= htmlspecialchars($client['company']) ?> View Project Details</title>
    <link rel="icon" href="../../asset/img/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../asset/css/sidebar.css">
    <link rel="stylesheet" href="../../asset/css/project/project.css">
</head>

<body>

    <div class="wrapper">
        <?php require '../../asset/includes/sidebar.php'; ?>
        <div class="main p-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h4>View Projects for <span><?= htmlspecialchars($client['company']) ?></span></h4>
                </div>
                <div class="card-body">
                    <div class="container">
                        <h4>Contact Person</h4>
                        <div class="row">
                            <div class="p-2 col-6">
                                <label class="form-label">Name:</label>
                                <input class="form-control" value="<?= htmlspecialchars($client['name']) ?>" readonly>
                            </div>
                            <div class="p-2 col-6">
                                <label class="form-label">Phone:</label>
                                <input class="form-control" value="<?= htmlspecialchars($client['phone']) ?>" readonly>
                            </div>
                            <div class="p-2 col-6">
                                <label class="form-label">Email:</label>
                                <input class="form-control" value="<?= htmlspecialchars($client['email']) ?>" readonly>
                            </div>
                            <div class="p-2 col-6">
                                <label class="form-label">Address:</label>
                                <input class="form-control" value="<?= htmlspecialchars($client['address']) ?>"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Project ID</th>
                                <th>Date Requested</th>
                                <th>Date Needed</th>
                                <th>Age (Days Delayed)</th>
                                <th>Services</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($projects) > 0): ?>
                                <?php foreach ($projects as $project): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($project['project_id']) ?></td>
                                        <td><?= htmlspecialchars($project['date_requested']) ?></td>
                                        <td><?= htmlspecialchars($project['date_needed']) ?></td>
                                        <td>
                                            <?php
                                            $date_needed = new DateTime($project['date_needed']);
                                            $today = new DateTime();

                                            if ($today > $date_needed) {
                                                $interval = $date_needed->diff($today);
                                                echo '<span class="text-danger fw-bold">' . $interval->days . ' days delayed</span>';
                                            } else {
                                                echo '<span class="text-success">0</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($project['services']) ?></td>
                                        <td><?= htmlspecialchars($project['quantity']) ?></td>

                                        <!-- Status Column with Warning Color -->
                                        <td>
                                            <?php
                                            $status_colors = [
                                                'Pending' => 'text-danger',
                                                'On Production' => 'text-warning',
                                                'For Delivery' => 'text-primary',
                                                'Delivered' => 'text-success'
                                            ];
                                            $status_class = $status_colors[$project['status']] ?? 'text-secondary';
                                            ?>
                                            <span
                                                class="<?= $status_class ?> fw-bold"><?= htmlspecialchars($project['status']) ?></span>
                                        </td>

                                        <!-- Action Column -->
                                        <td>
                                            <form method="POST" action="update_status.php"
                                                onsubmit="return confirmUpdate(this);">
                                                <input type="hidden" name="client_id" value="<?= $client['client_id'] ?>">
                                                <input type="hidden" name="project_id" value="<?= $project['project_id'] ?>">

                                                <select name="status" class="form-select mt-2">
                                                    <?php
                                                    $statuses = ['Pending', 'On Production', 'For Delivery', 'Delivered'];
                                                    foreach ($statuses as $status) {
                                                        $selected = ($project['status'] == $status) ? 'selected' : '';
                                                        echo "<option value='$status' $selected>$status</option>";
                                                    }
                                                    ?>
                                                </select>

                                                <button type="submit" class="btn btn-primary mt-2">Update</button>
                                            </form>
                                            <script>
                                                function confirmUpdate(form) {
                                                    let status = form.status.value;
                                                    return confirm("Are you sure you want to update the project status to '" + status + "'?");
                                                }
                                            </script>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No projects found for this client.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-end">
                    <a href="project.php" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    function checkStatus(selectElement, projectId) {
        let fileUploadDiv = document.getElementById('file-upload-' + projectId);

        if (selectElement.value === 'Delivered') {
            fileUploadDiv.style.display = 'block';
        } else {
            fileUploadDiv.style.display = 'none';
        }
    }
</script>

</html>