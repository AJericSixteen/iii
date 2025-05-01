<?php
session_start();
require("../../asset/database/db.php");

// include '../../asset/includes/auth_managing_director.php';

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

// Check if all projects are delivered
$all_delivered = true;
foreach ($projects as $project) {
    if ($project['status'] != 'Delivered') {
        $all_delivered = false;
        break;
    }
}

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

                    <!-- Master Dropdown for Updating All Projects -->
                    <form method="POST" action="update_multiple_status.php" onsubmit="return confirmUpdate();">
                        <input type="hidden" name="client_id" value="<?= $client_id ?>"> <!-- Pass the client_id -->
                        <div class="mb-3">
                            <label for="master_status" class="form-label">Select Status to Apply to All Projects</label>
                            <select name="master_status" class="form-select" required>
                                <option value="">Select Status for all Projects</option>
                                <option value="Pending">Pending</option>
                                <option value="On Production">On Production</option>
                                <option value="For Delivery">For Delivery</option>
                                <option value="Delivered">Delivered</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update All Projects</button>
                    </form>

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

                                            if ($project['status'] == 'Completed') {
                                                echo '<span class="badge bg-success">Completed</span>';
                                            } elseif ($project['status'] != 'Delivered') {
                                                if ($today > $date_needed) {
                                                    $interval = $date_needed->diff($today);
                                                    echo '<span class="badge bg-danger fw-bold">' . $interval->days . ' days delayed</span>';
                                                } else {
                                                    echo '<span class="text-success">0</span>';
                                                }
                                            } else {
                                                echo '<span class="badge bg-success">Delivered</span>';
                                            }
                                            ?>
                                        </td>

                                        <td><?= htmlspecialchars($project['services']) ?></td>
                                        <td><?= htmlspecialchars($project['quantity']) ?></td>

                                        <!-- Status Column with Warning Color -->
                                        <td>
                                            <?php
                                            $status_colors = [
                                                'Pending' => 'badge bg-secondary',
                                                'On Production' => 'badge bg-warning',
                                                'For Delivery' => 'badge bg-primary',
                                                'Delivered' => 'badge bg-success',
                                                'Completed' => 'badge bg-success',
                                            ];
                                            $status_class = $status_colors[$project['status']] ?? 'text-secondary';
                                            ?>
                                            <span
                                                class="<?= $status_class ?> fw-bold"><?= htmlspecialchars($project['status']) ?></span>
                                        </td>

                                        <!-- Action Column -->
                                        <td>
    <form method="POST" action="update_status.php" onsubmit="return confirmUpdate(this);">
        <input type="hidden" name="client_id" value="<?= $client['client_id'] ?>">
        <input type="hidden" name="project_id" value="<?= $project['project_id'] ?>">

        <select name="status" class="form-select mt-2" 
            <?php if ($project['status'] == 'Delivered') echo 'disabled'; ?>>
            <?php
            $statuses = ['Pending', 'On Production', 'For Delivery', 'Delivered'];
            foreach ($statuses as $status) {
                $selected = ($project['status'] == $status) ? 'selected' : '';
                echo "<option value='$status' $selected>$status</option>";
            }
            ?>
        </select>

        <?php if ($project['status'] != 'Delivered'): ?>
            <button type="submit" class="btn btn-primary mt-2">Update</button>
        <?php endif; ?>
    </form>
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

                    <!-- Show Close Project Button if All Projects are Delivered -->
                    <?php if ($all_delivered): ?>
                        <div class="mt-4">
                            <h4>All Projects Delivered</h4>
                            <form method="POST" action="complete_project.php" enctype="multipart/form-data">
                                <input type="hidden" name="client_id" value="<?= $client_id ?>"> <!-- Add this line -->
                                <div class="mb-3">
                                    <label for="delivery_receipt" class="form-label">Upload Delivery Receipt</label>
                                    <input type="file" class="form-control" id="delivery_receipt" name="delivery_receipt"
                                        required>
                                </div>
                                <button type="submit" class="btn btn-success mt-2">Upload Delivery Receipt</button>
                            </form>
                        </div>
                    <?php endif; ?>

                </div>
                <div class="card-footer text-end">
                    <a href="project.php" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>

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
    <script>
    function confirmUpdate() {
        return confirm("Are you sure you want to update all the project statuses?");
    }
</script>

</body>

</html>