<?php

// include '../../asset/includes/auth_managing_director.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';
require("../../asset/config/config.php");
require("../../asset/database/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $contact_name = $_POST['contact_name'];
    $company = $_POST['company'];
    $year = $_POST['year'];
    $month_num = $_POST['month_num'];

    $sql = "SELECT p.project_id, p.date_requested, p.services, p.description, p.total
            FROM project p
            JOIN client c ON p.client_id = c.client_id
            WHERE p.status = 'Completed'
            AND LOWER(c.company) = LOWER(?) 
            AND LOWER(c.name) = LOWER(?)";

    $params = [$company, $contact_name];
    $types = "ss";

    if (!empty($year)) {
        $sql .= " AND YEAR(p.date_requested) = ?";
        $params[] = $year;
        $types .= "i";
    }
    if (!empty($month_num)) {
        $sql .= " AND MONTH(p.date_requested) = ?";
        $params[] = $month_num;
        $types .= "i";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $projectListHTML = "";
    $projectListPlain = "";
    while ($row = $result->fetch_assoc()) {
        $projectListHTML .= "<tr>
            <td>{$row['project_id']}</td>
            <td>{$row['date_requested']}</td>
            <td>{$row['services']}</td>
            <td>{$row['description']}</td>
            <td>₱" . number_format($row['total'], 2) . "</td>
        </tr>";

        $projectListPlain .= "Project ID: {$row['project_id']}\n";
        $projectListPlain .= "Date Requested: {$row['date_requested']}\n";
        $projectListPlain .= "Service: {$row['services']}\n";
        $projectListPlain .= "Description: {$row['description']}\n";
        $projectListPlain .= "Total: ₱" . number_format($row['total'], 2) . "\n\n";
    }

    if (empty($projectListHTML)) {
        $projectListHTML = "<tr><td colspan='5'>No completed projects found for this contact person.</td></tr>";
        $projectListPlain = "No completed projects found for this contact person.";
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'iiiadssystem@gmail.com';
        $mail->Password = 'pnbg hslo iblb enjl';  // Replace with app password for production
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('iiiadssystem@gmail.com', 'III Advertising Services');
        $mail->addAddress($email, $contact_name);
        $mail->addReplyTo('iiiadssystem@gmail.com', 'III Advertising Services');

        $mail->isHTML(true);
        $mail->Subject = "Summary of Completed Projects - $company ($contact_name)";

        // HTML body
        $mail->Body = "
<div style='font-family: Arial, sans-serif; color: #333;'>
    <h2 style='color: #222;'>Summary of Completed Projects</h2>
    <p>Dear $contact_name,</p>
    <p>We hope this message finds you well. Please find below the summary of the completed projects for <strong>$company</strong>. We value our partnership with you and are pleased to share the details of the projects we've worked on together.</p>
    <table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%;'>
        <thead>
            <tr style='background-color: #f2f2f2;'>
                <th style='text-align: left;'>Project ID</th>
                <th style='text-align: left;'>Date Requested</th>
                <th style='text-align: left;'>Service</th>
                <th style='text-align: left;'>Description</th>
                <th style='text-align: left;'>Total (₱)</th>
            </tr>
        </thead>
        <tbody>
            $projectListHTML
        </tbody>
    </table>
    <br>
    <p>We trust that these completed projects have met your expectations. Should you have any further questions or need assistance with any of the services provided, please do not hesitate to reach out to us. We are committed to continuing our successful collaboration with you.</p>
    <p>You can contact us via email at the following addresses:</p>
    <p>Email: iii_ads@yahoo.com.ph<br>
    iiiadvertisingservices@gmail.com</p>
    <p>We look forward to working with you on future projects.</p>
    <br>
    <p>Best regards,</p>
    <p><strong>III Advertising Services</strong><br>
    Your trusted partner for advertising solutions</p>
</div>";

        // Plain-text alternative
        $mail->AltBody = "Dear $contact_name,\n\n"
    . "I hope this email finds you well. Please find below the summary of completed projects for $company.\n\n"
    . $projectListPlain
    . "\n\nWe trust that these projects have met your expectations. If you have any questions or need further assistance, please feel free to reach out to us at the following email addresses:\n\n"
    . "iii_ads@yahoo.com.ph\n"
    . "iiiadvertisingservices@gmail.com\n\n"
    . "We look forward to our continued partnership.\n\n"
    . "Best regards,\n"
    . "III Advertising Services\n"
    . "Your trusted partner for advertising solutions";

        $mail->send();
        echo '<script>alert("Message sent successfully!"); window.location.href = "project_by_contact.php?year=' . $year . '&month=' . $month_num . '&company=' . urlencode($company) . '&contact=' . urlencode($contact_name) . '";</script>';

    } catch (Exception $e) {
        // Check if the error message contains "User not found" or similar
        if (strpos($mail->ErrorInfo, 'User not found') !== false) {
            echo "<script>alert('The email address does not exist.'); window.location.href = 'project_by_contact.php?year=' . $year . '&month=' . $month_num . '&company=' . urlencode($company) . '&contact=' . urlencode($contact_name) . '</script>";
        } else {
            echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.location.href = 'project_by_contact.php?year=' . $year . '&month=' . $month_num . '&company=' . urlencode($company) . '&contact=' . urlencode($contact_name) . '</script>";
        }
    }

} else {
    echo "<script>alert('Invalid Request.');window.location.href = 'project_by_contact.php?year=' . $year . '&month=' . $month_num . '&company=' . urlencode($company) . '&contact=' . urlencode($contact_name) . '</script>";
}
?>