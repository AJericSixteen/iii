<?php
include('../../asset/database/db.php');

// Get the current year
$currentYear = date('Y');

// Generate the list of months for the current year
$months = [];
for ($i = 1; $i <= 12; $i++) {
    $months[] = sprintf('%04d-%02d', $currentYear, $i); // Format as YYYY-MM
}

// Query to fetch the count of projects per month for the current year
$query = "
    SELECT 
        DATE_FORMAT(date_requested, '%Y-%m') AS month, 
        COUNT(project_id) AS project_count
    FROM 
        project
    WHERE
        YEAR(date_requested) = '$currentYear'  -- Filter by the current year
    GROUP BY 
        month
    ORDER BY month;
";

// Execute the query using mysqli
$result = $conn->query($query);

// Check for query errors
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Prepare the data for the response
$projectData = array_fill(0, 12, 0);  // Initialize with 0 values for each month
$monthsData = [];  // Store the month as keys for easy access

// Fetch the results and map the project counts to the respective months
while ($row = $result->fetch_assoc()) {
    $monthIndex = (int)substr($row['month'], 5, 2) - 1;  // Get the month index (0-based)
    $projectData[$monthIndex] = (int)$row['project_count']; // Set the project count for the correct month
}

// Close the database connection
$conn->close();

// Return the data as JSON
echo json_encode($projectData);
?>
