<?php
session_start();

// include '../../asset/includes/auth_managing_director.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require("../../asset/database/db.php");
define('ALLOW_ACCESS', true);

// Check if 'year' is provided in the URL
if (!isset($_GET['year'])) {
    echo "No year selected.";
    exit();
}

$year = $_GET['year'];

// Get projects for the selected year based on date_requested
$sql = "SELECT p.*, c.company, c.name AS contact_name, YEAR(p.date_requested) AS year_requested
        FROM project p
        JOIN client c ON p.client_id = c.client_id 
        WHERE p.status = 'Completed' AND YEAR(p.date_requested) = ?
        ORDER BY p.date_requested DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();

// Debugging: Check if query returned results
if ($result->num_rows == 0) {
    echo "No completed projects found for the selected year.";
    exit();
}

// Group by month, company, and client
$projects_by_month = [];

while ($row = $result->fetch_assoc()) {
    $date_requested = $row['date_requested'];
    $month_num = date('m', strtotime($date_requested));
    $month_name = date('F', strtotime($date_requested));

    if (!isset($projects_by_month[$month_num])) {
        $projects_by_month[$month_num] = [
            'name' => $month_name,
            'companies' => []
        ];
    }

    if (!isset($projects_by_month[$month_num]['companies'][$row['company']])) {
        $projects_by_month[$month_num]['companies'][$row['company']] = [
            'clients' => []
        ];
    }

    if (!isset($projects_by_month[$month_num]['companies'][$row['company']]['clients'][$row['client_id']])) {
        $projects_by_month[$month_num]['companies'][$row['company']]['clients'][$row['client_id']] = [
            'client_name' => $row['contact_name'],
            'projects' => []
        ];
    }

    $projects_by_month[$month_num]['companies'][$row['company']]['clients'][$row['client_id']]['projects'][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Projects for Year <?= $year ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="../../asset/img/logo.png">
</head>

<body>
<div class="wrapper d-flex">
    <?php require '../../asset/includes/sidebar.php'; ?>

    <div class="main p-4 flex-grow-1">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header text-center">
                    <h2>Projects for Year <?= $year ?></h2>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content mb-3">
                        <a href="project_history.php"
                           class="navigation-arrows d-flex align-items-center" style="margin-right: 1rem;">
                            <i class="bi bi-arrow-left-circle" style="font-size: 1.5rem; color: black;"></i>
                        </a>
                    </div>
                    <?php if (!empty($projects_by_month)): ?>
                        <table class="table table-bordered table-hover">
                            <thead style="background-color: #0e2238;">
                            <tr>
                                <th style="color: white;">Month</th>
                                <th style="color: white;">Number of Companies</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($projects_by_month as $month_num => $data): ?>
                                <tr class="month-row"
                                    onclick="window.location.href='project_by_month.php?year=<?= $year ?>&month=<?= $month_num ?>'">
                                    <td><?= $data['name'] ?></td>
                                    <td><?= count($data['companies']) ?> Companies</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info text-center">No completed projects found for this year.</div>
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
