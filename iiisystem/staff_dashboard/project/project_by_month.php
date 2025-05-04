<?php
session_start();

// include '../../asset/includes/auth_managing_director.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require("../../asset/database/db.php");
define('ALLOW_ACCESS', true);

// Get year and month from URL
if (!isset($_GET['year']) || !isset($_GET['month'])) {
    echo "Invalid year or month.";
    exit();
}

$year = $_GET['year'];
$month_num = $_GET['month'];

// Get projects for the selected year and month
$sql = "SELECT p.*, c.company, MONTH(p.date_requested) AS month_requested
        FROM project p
        JOIN client c ON p.client_id = c.client_id 
        WHERE p.status = 'Completed' AND YEAR(p.date_requested) = ? AND MONTH(p.date_requested) = ?
        ORDER BY p.date_requested DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $year, $month_num);
$stmt->execute();
$result = $stmt->get_result();

// Group by company and count projects
$projects_by_company = [];

while ($row = $result->fetch_assoc()) {
    $company = $row['company'];

    if (!isset($projects_by_company[$company])) {
        $projects_by_company[$company] = [
            'project_count' => 0
        ];
    }

    $projects_by_company[$company]['project_count']++;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Projects for <?= date('F', mktime(0, 0, 0, $month_num, 10)) ?> <?= $year ?></title>
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
                    <h2>Companies for <?= date('F', mktime(0, 0, 0, $month_num, 10)) ?> <?= $year ?></h2>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content mb-3">
                        <a href="project_by_year.php?year=<?= $year ?>" 
                           class="navigation-arrows d-flex align-items-center" style="margin-right: 1rem;">
                            <i class="bi bi-arrow-left-circle" style="font-size: 1.5rem; color: black;"></i>
                        </a>
                    </div>

                    <!-- Search bar -->
                    <div class="mb-3">
                        <label for="search">Search: </label>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by Company" style="width: 300px;">
                    </div>

                    <?php if (!empty($projects_by_company)): ?>
                        <table class="table table-bordered table-hover">
                            <thead style="background-color: #0e2238;">
                            <tr>
                                <th style="color: white;">Company</th>
                                <th style="color: white;">Number of Projects</th>
                            </tr>
                            </thead>
                            <tbody id="companyTable">
                            <?php foreach ($projects_by_company as $company => $data): ?>
                                <tr class="company-row" onclick="window.location.href='project_by_company.php?year=<?= $year ?>&month=<?= $month_num ?>&company=<?= urlencode($company) ?>'">
                                    <td><?= $company ?></td>
                                    <td><?= $data['project_count'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info text-center">No completed projects found for this month.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // JavaScript for search functionality
    document.getElementById('searchInput').addEventListener('input', function () {
        var filter = this.value.toLowerCase();
        var rows = document.querySelectorAll('#companyTable tr');

        rows.forEach(function (row) {
            var company = row.cells[0].textContent.toLowerCase();
            if (company.indexOf(filter) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
</body>

</html>
