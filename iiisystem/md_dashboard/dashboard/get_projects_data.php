<?php
include('../../asset/database/db.php');

// Get current month and year
$currentMonth = (int)date('n');
$currentYear = (int)date('Y');

// Start from May of last year if current month is Jan–Apr
$startYear = $currentMonth >= 5 ? $currentYear : $currentYear - 1;

$projectCountData = array_fill(0, 12, 0); // Project count
$salesData = array_fill(0, 12, 0); // Sales total

$months = [];
for ($i = 0; $i < 12; $i++) {
    $monthNum = ($i + 5); // Start from May
    $year = $startYear;
    if ($monthNum > 12) {
        $monthNum -= 12;
        $year += 1;
    }
    $months[] = sprintf('%04d-%02d', $year, $monthNum);
}

$placeholders = implode(',', array_fill(0, count($months), '?'));

$sql = "
    SELECT 
        DATE_FORMAT(date_requested, '%Y-%m') AS month,
        COUNT(*) AS project_count,
        SUM(total) AS total_sales
    FROM project
    WHERE DATE_FORMAT(date_requested, '%Y-%m') IN ($placeholders)
    GROUP BY month
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat("s", count($months)), ...$months);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $index = array_search($row['month'], $months);
    if ($index !== false) {
        $projectCountData[$index] = (int)$row['project_count'];
        $salesData[$index] = (float)$row['total_sales'] ?? 0;
    }
}

echo json_encode([
    'projects' => $projectCountData,
    'sales' => $salesData
]);
?>
