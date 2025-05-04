<?php
session_start();

// include '../../asset/includes/auth_managing_director.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require("../../asset/database/db.php");
define('ALLOW_ACCESS', true);

// Get and validate parameters
$year = $_GET['year'] ?? null;
$month_num = $_GET['month'] ?? null;
$company = $_GET['company'] ?? null;
$contact_name = $_GET['contact'] ?? null;

if (!$year || !$month_num || !$company || !$contact_name) {
    echo "Invalid year, month, company, or contact.";
    exit();
}

// Fetch contact information for the specific client
// Fetch contact information for the specific client
$contact_info_sql = "SELECT c.company, c.name AS contact_name, c.email, c.phone 
                     FROM client c 
                     WHERE c.company = ? AND c.name = ?";
$contact_stmt = $conn->prepare($contact_info_sql);

// Check for query preparation errors
if ($contact_stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$contact_stmt->bind_param("ss", $company, $contact_name);

// Check for parameter binding errors
if (!$contact_stmt->bind_param("ss", $company, $contact_name)) {
    die('Parameter binding error: ' . $contact_stmt->error);
}

$contact_stmt->execute();

// Check for execution errors
if ($contact_stmt->error) {
    die('MySQL execute error: ' . $contact_stmt->error);
}

$contact_result = $contact_stmt->get_result();

// Fetch the contact information if available
$contact = null;
if ($contact_result->num_rows > 0) {
    $contact = $contact_result->fetch_assoc();
} else {
    // If no contact info found, handle accordingly
    echo "No contact information found.";
}


// Fetch completed projects for the specified criteria
$sql = "SELECT p.*, c.company, c.name AS contact_name
        FROM project p
        JOIN client c ON p.client_id = c.client_id 
        WHERE p.status = 'Completed' 
            AND YEAR(p.date_requested) = ? 
            AND MONTH(p.date_requested) = ? 
            AND c.company = ? 
            AND c.name = ?
        ORDER BY p.date_requested DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $year, $month_num, $company, $contact_name);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Projects for <?= htmlspecialchars($contact_name) ?> - <?= htmlspecialchars($company) ?>
        (<?= date('F', mktime(0, 0, 0, $month_num, 10)) ?> <?= $year ?>)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 1rem;
        }

        .card-header {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .navigation-arrows {
            font-size: 1.1rem;
            color: #0d6efd;
            text-decoration: none;
        }

        .navigation-arrows:hover {
            color: #084298;
        }
    </style>
</head>

<body>
    <div class="wrapper d-flex">
        <?php require '../../asset/includes/sidebar.php'; ?>

        <div class="main p-4 flex-grow-1">
            <div class="container-fluid">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        Projects for <?= htmlspecialchars($contact_name) ?> at <?= htmlspecialchars($company) ?>
                        (<?= date('F', mktime(0, 0, 0, $month_num, 10)) ?> <?= $year ?>)
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content mb-3">
                            <a href="project_by_company.php?year=<?= $year ?>&month=<?= $month_num ?>&company=<?= urlencode($company) ?>"
                                class="navigation-arrows d-flex align-items-center" style="margin-right: 1rem;">
                                <i class="bi bi-arrow-left-circle" style="font-size: 1.5rem; color: black;"></i>
                            </a>
                        </div>

                        <?php if ($contact): ?>
                            <div class="mb-4 p-4 border rounded-3 shadow-sm bg-light">
                                <h5 class="mb-4">Contact Information</h5>
                                <div class="row">
                                    <!-- Left side: Name and Company -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Name:</label>
                                            <input type="text" class="form-control"
                                                value="<?= htmlspecialchars($contact['contact_name']) ?>" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Company:</label>
                                            <input type="text" class="form-control"
                                                value="<?= htmlspecialchars($contact['company']) ?>" readonly>
                                        </div>
                                    </div>

                                    <!-- Right side: Email and Phone (inside input boxes) -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Email:</label>
                                            <input type="text" class="form-control"
                                                value="<?= htmlspecialchars($contact['email'] ?? 'N/A') ?>" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Phone:</label>
                                            <input type="text" class="form-control"
                                                value="<?= htmlspecialchars($contact['phone'] ?? 'N/A') ?>" readonly>
                                        </div>
                                    </div>
                                    <?php if ($contact): ?>
                                        <form action="send_email.php" method="POST">
                                            <input type="hidden" name="email"
                                                value="<?= htmlspecialchars($contact['email']) ?>">
                                            <input type="hidden" name="company"
                                                value="<?= htmlspecialchars($contact['company']) ?>">
                                            <input type="hidden" name="contact_name"
                                                value="<?= htmlspecialchars($contact['contact_name']) ?>">
                                            <input type="hidden" name="year" value="<?= $year ?>">
                                            <input type="hidden" name="month_num" value="<?= $month_num ?>">
                                            <button type="submit" class="btn btn-primary mb-3">
                                                <i class="bi bi-envelope-fill"></i> Send to Email
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger text-center">No contact information found for this person.</div>
                        <?php endif; ?>


                        <?php if ($result->num_rows > 0): ?>
                            <div class="mb-3">
                                <label for="searchInput" class="form-label">Search:</label>
                                <input type="text" id="searchInput" class="form-control form-control-sm"
                                    placeholder="Search projects..." onkeyup="searchTable()" style="max-width: 300px;" />
                            </div>


                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="projectsTable">
                                    <thead class="text-center" style="background-color: #0e2238;">
                                        <tr>
                                            <th style="color: white;">Project ID</th>
                                            <th style="color: white;">Date Requested</th>
                                            <th style="color: white;">Date Needed</th>
                                            <th style="color: white;">Services</th>
                                            <th style="color: white;">Tarp Type</th>
                                            <th style="color: white;">Description</th>
                                            <th style="color: white;">Price</th>
                                            <th style="color: white;">Total</th>
                                            <th style="color: white;">Delivery Receipt</th>
                                            <th style="color: white;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center align-middle">
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= $row['project_id'] ?></td>
                                                <td><?= date('F j, Y', strtotime($row['date_requested'])) ?></td>
                                                <td><?= date('F j, Y', strtotime($row['date_needed'])) ?></td>
                                                <td><?= htmlspecialchars($row['services']) ?></td>
                                                <td><?= htmlspecialchars($row['tarp_type']) ?></td>
                                                <td><?= htmlspecialchars($row['description']) ?></td>
                                                <td>₱<?= number_format($row['price'], 2) ?></td>
                                                <td>₱<?= number_format($row['total'], 2) ?></td>
                                                <td>
                                                    <?php if ($row['delivery_receipt']): ?>
                                                        <a href="#"
                                                            class="badge bg-primary text-white p-2 rounded-3 shadow-sm text-decoration-none"
                                                            data-bs-toggle="modal" data-bs-target="#imageModal"
                                                            data-bs-image="view_image.php?file=<?= urlencode($row['delivery_receipt']) ?>">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>

                                                    <?php else: ?>
                                                        <span class="text-muted">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><span class="badge bg-success"><?= $row['status'] ?></span></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center">No completed projects found for this contact person.
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Viewing Receipt -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title">Delivery Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modal-image" src="" alt="Receipt" class="img-fluid rounded shadow-sm"
                        style="max-height: 75vh;">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function searchTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toUpperCase();
            const table = document.getElementById("projectsTable");
            const trs = table.getElementsByTagName("tr");

            // Loop through all table rows and hide those who don't match the search query
            for (let i = 1; i < trs.length; i++) {
                const tds = trs[i].getElementsByTagName("td");
                let matchFound = false;
                for (let j = 0; j < tds.length; j++) {
                    if (tds[j]) {
                        const txtValue = tds[j].textContent || tds[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            matchFound = true;
                            break;
                        }
                    }
                }
                trs[i].style.display = matchFound ? "" : "none";
            }
        }
        const imageModal = document.getElementById('imageModal');
        imageModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const imageUrl = button.getAttribute('data-bs-image');
            document.getElementById('modal-image').src = imageUrl;
        });
    </script>
</body>

</html>