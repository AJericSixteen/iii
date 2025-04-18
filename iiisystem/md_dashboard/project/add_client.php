<?php
require '../../asset/database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Insert into client table
    $sql = "INSERT INTO client (name, company, address, phone, email) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $name, $company, $address, $phone, $email);

    if (mysqli_stmt_execute($stmt)) {
        $client_id = mysqli_insert_id($conn);

        // Handle product rows (if there are multiple products)
        if (isset($_POST['services'])) {
            $services = $_POST['services'];
            $tarp_types = $_POST['tarp_type'];
            $descriptions = $_POST['description'];
            $heights = $_POST['height'];
            $widths = $_POST['width'];
            $quantities = $_POST['quantity'];
            $prices = $_POST['price'];

            $sql2 = "INSERT INTO project (
                client_id, services, tarp_type, date_requested, date_needed, description, height, width, quantity, price, total
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt2 = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "issssssiiid", $client_id, $services[0], $tarp_types[0], $_POST['date_requested'], $_POST['date_needed'], $descriptions[0], $heights[0], $widths[0], $quantities[0], $prices[0], $total);

            // Loop through all product rows
            foreach ($services as $index => $service) {
                $total = $quantities[$index] * $prices[$index]; // calculate total for this row

                // Execute prepared statement for each product row
                mysqli_stmt_bind_param($stmt2, "issssssiiid", $client_id, $services[$index], $tarp_types[$index], $_POST['date_requested'], $_POST['date_needed'], $descriptions[$index], $heights[$index], $widths[$index], $quantities[$index], $prices[$index], $total);

                if (!mysqli_stmt_execute($stmt2)) {
                    echo "Error inserting product: " . mysqli_stmt_error($stmt2);
                }
            }
            header("Location: ./project.php");
            exit();
        } else {
            echo "No products added.";
        }
    } else {
        echo "Error inserting into client: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt2);
    mysqli_close($conn);
}
?>