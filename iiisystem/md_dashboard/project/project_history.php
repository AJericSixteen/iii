<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require("../../asset/database/db.php");
define('ALLOW_ACCESS', true);

// Get completed projects with year
$sql = "SELECT p.*, c.company, c.name AS contact_name, YEAR(p.date_requested) AS year_requested
        FROM project p
        JOIN client c ON p.client_id = c.client_id 
        WHERE p.status = 'Completed'
        ORDER BY year_requested DESC, p.date_requested DESC";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "No completed projects found.";
    exit();
}

// Group by year, month, company, and client
$projects_by_year = [];

while ($row = $result->fetch_assoc()) {
    $year = date('Y', strtotime($row['date_requested']));

    if (!isset($projects_by_year[$year])) {
        $projects_by_year[$year] = [
            'total_projects' => 0,
            'companies_set' => []
        ];
    }

    $projects_by_year[$year]['total_projects']++;

    $projects_by_year[$year]['companies_set'][$row['company']] = true;

    $date_requested = $row['date_requested'];
    $month_num = date('m', strtotime($date_requested));
    $month_name = date('F', strtotime($date_requested));

    if (!isset($projects_by_year[$year][$month_num])) {
        $projects_by_year[$year][$month_num] = [
            'name' => $month_name,
            'companies' => []
        ];
    }

    if (!isset($projects_by_year[$year][$month_num]['companies'][$row['company']])) {
        $projects_by_year[$year][$month_num]['companies'][$row['company']] = [
            'clients' => []
        ];
    }

    if (!isset($projects_by_year[$year][$month_num]['companies'][$row['company']]['clients'][$row['client_id']])) {
        $projects_by_year[$year][$month_num]['companies'][$row['company']]['clients'][$row['client_id']] = [
            'client_name' => $row['contact_name'],
            'projects' => []
        ];
    }

    $projects_by_year[$year][$month_num]['companies'][$row['company']]['clients'][$row['client_id']]['projects'][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project History | III Advertising Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="../../asset/img/logo.png">
    <style>
        .year-row:hover {
            background-color: #f2f2f2;
            cursor: pointer;
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
                    <h2>Completed Project History</h2>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content mb-3">
                        <a class="navigation-arrows d-flex align-items-center" style="margin-right: 1rem;">
                            <i class="bi bi-arrow-left-circle" style="font-size: 1.5rem; color: black;"></i>
                        </a>
                    </div>
                    <?php if (!empty($projects_by_year)): ?>
                        <table class="table table-bordered table-hover">
                            <thead style="background-color: #0e2238;">
                                <tr>
                                    <th style="color: white;">Year</th>
                                    <th style="color: white;">Total Projects</th>
                                    <th style="color: white;">Total Companies</th>
                                    <th style="color: white;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects_by_year as $year => $data): ?>
                                    <tr class="year-row"
                                        onclick="window.location.href='project_by_year.php?year=<?= $year ?>'">
                                        <td><?= $year ?></td>
                                        <td><?= $data['total_projects'] ?></td>
                                        <td><?= count($data['companies_set']) ?></td>
                                        <td><button class="btn btn-primary btn-sm">View Projects</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info text-center">No completed projects found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
