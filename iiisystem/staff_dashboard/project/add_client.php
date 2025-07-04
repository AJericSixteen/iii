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
            $quantities = $_POST['quantity'];
            $prices = $_POST['price'];

            $sql2 = "INSERT INTO project (
                client_id, services, tarp_type, date_requested, date_needed, description, quantity, price, total
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt2 = mysqli_prepare($conn, $sql2);
            
            // Loop through all product rows
            foreach ($services as $index => $service) {
                // Calculate total (multiply quantity by price)
                $total = $quantities[$index] * $prices[$index]; 

                // Round price and total to 2 decimal places
                $formatted_price = round($prices[$index], 2);
                $formatted_total = round($total, 2);

                // Execute prepared statement for each product row
                mysqli_stmt_bind_param(
                    $stmt2, 
                    "isssssiid", 
                    $client_id, 
                    $services[$index], 
                    $tarp_types[$index], 
                    $_POST['date_requested'], 
                    $_POST['date_needed'], 
                    $descriptions[$index], 
                    $quantities[$index], 
                    $formatted_price, 
                    $formatted_total
                );

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

    // Close statements and connection
    mysqli_stmt_close($stmt);
    if (isset($stmt2)) {
        mysqli_stmt_close($stmt2);
    }
    mysqli_close($conn);
}
?>
