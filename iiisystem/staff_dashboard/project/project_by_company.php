<?php
session_start();

// include '../../asset/includes/auth_managing_director.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require("../../asset/database/db.php");
define('ALLOW_ACCESS', true);

// Get year, month, and company from URL
if (!isset($_GET['year']) || !isset($_GET['month']) || !isset($_GET['company'])) {
    echo "Invalid year, month, or company.";
    exit();
}

$year = $_GET['year'];
$month_num = $_GET['month'];
$company = $_GET['company'];

// Get projects for the selected year, month, and company
$sql = "SELECT p.*, c.company, c.name AS contact_name
        FROM project p
        JOIN client c ON p.client_id = c.client_id 
        WHERE p.status = 'Completed' AND YEAR(p.date_requested) = ? AND MONTH(p.date_requested) = ? AND c.company = ?
        ORDER BY p.date_requested DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $year, $month_num, $company);
$stmt->execute();
$result = $stmt->get_result();

// Group by contact person
$projects_by_contact = [];

while ($row = $result->fetch_assoc()) {
    $contact_name = $row['contact_name'];

    if (!isset($projects_by_contact[$contact_name])) {
        $projects_by_contact[$contact_name] = [
            'projects' => []
        ];
    }

    // Add project under respective contact person
    $projects_by_contact[$contact_name]['projects'][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Projects for <?= $company ?> - Contact Person</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .contact-row:hover {
            cursor: pointer;
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="wrapper d-flex">
        <?php require '../../asset/includes/sidebar.php'; ?>

        <div class="main p-4 flex-grow-1">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Contact Persons for <?= $company ?> (<?= date('F', mktime(0, 0, 0, $month_num, 10)) ?>
                            <?= $year ?>)</h2>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content mb-3">
                            <a href="project_by_month.php?year=<?= $year ?>&month=<?= $month_num ?>"
                                class="navigation-arrows d-flex align-items-center" style="margin-right: 1rem;">
                                <i class="bi bi-arrow-left-circle" style="font-size: 1.5rem; color: black;"></i>
                            </a>
                        </div>
                        <?php if (!empty($projects_by_contact)): ?>
                            <table class="table table-bordered table-hover">
                                <thead style="background-color: #0e2238;">
                                    <tr>
                                        <th style="color: white;">Contact Person</th>
                                        <th style="color: white;">Projects</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($projects_by_contact as $contact_name => $data): ?>
                                        <tr class="contact-row"
                                            onclick="window.location.href='project_by_contact.php?year=<?= $year ?>&month=<?= $month_num ?>&company=<?= urlencode($company) ?>&contact=<?= urlencode($contact_name) ?>'">
                                            <td><?= $contact_name ?></td>
                                            <td><?= count($data['projects']) ?> Projects</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-info text-center">No completed projects found for this company.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>