<?php
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

    $sql = "SELECT p.project_id, p.date_requested, p.services, p.total
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
            <td>₱" . number_format($row['total'], 2) . "</td>
        </tr>";

        $projectListPlain .= "Project ID: {$row['project_id']}\n";
        $projectListPlain .= "Date Requested: {$row['date_requested']}\n";
        $projectListPlain .= "Service: {$row['services']}\n";
        $projectListPlain .= "Total: ₱" . number_format($row['total'], 2) . "\n\n";
    }

    if (empty($projectListHTML)) {
        $projectListHTML = "<tr><td colspan='4'>No completed projects found for this contact person.</td></tr>";
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
            <h2 style='color: #222;'>Completed Projects Summary</h2>
            <p>Dear $contact_name,</p>
            <p>Please find below the list of completed projects for <strong>$company</strong>:</p>
            <table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%;'>
                <thead>
                    <tr style='background-color: #f2f2f2;'>
                        <th>Project ID</th>
                        <th>Date Requested</th>
                        <th>Service</th>
                        <th>Total (₱)</th>
                    </tr>
                </thead>
                <tbody>
                    $projectListHTML
                </tbody>
            </table>
            <br>
            <p>If you have any inquiries or require further assistance, please do not hesitate to contact us.</p>
            <p>Best regards,</p>
            <p><strong>III Advertising Services</strong></p>
        </div>";

        // Plain-text alternative
        $mail->AltBody = "Dear $contact_name,\n\n"
            . "Here is the summary of completed projects for $company:\n\n"
            . $projectListPlain
            . "\nIf you have any inquiries, feel free to reach out.\n\n"
            . "Best regards,\n"
            . "III Advertising Services";

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
