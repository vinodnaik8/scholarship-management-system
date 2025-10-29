<?php
session_start();
require 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit();
}

function sendStatusEmail($email, $scholarship_name, $status, $application_data) {
    // Generate the student's application form as a PDF
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    // Add content to the PDF (this is where you format the application data into the PDF)
    $pdf->Cell(0, 10, "Scholarship Application Form", 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->Cell(40, 10, "Scholarship: " . $application_data['scholarship_name']);
    $pdf->Ln();
    $pdf->Cell(40, 10, "Aadhaar: " . $application_data['aadhaar']);
    $pdf->Ln();
    $pdf->Cell(40, 10, "Education Level: " . $application_data['education_level']);
    $pdf->Ln();
    $pdf->Cell(40, 10, "Percentage: " . $application_data['percentage'] . "%");
    $pdf->Ln();
    $pdf->Cell(40, 10, "Course: " . $application_data['course']);
    $pdf->Ln();
    $pdf->Cell(40, 10, "Institution: " . $application_data['institution']);
    $pdf->Ln();
    $pdf->Cell(40, 10, "Status: " . ucfirst($status));
    $pdf->Ln();

    // Output the PDF to a string (instead of saving to file system)
    $pdf_output = $pdf->Output('application_form.pdf', 'S'); // 'S' means output as a string

    // Send the email with the PDF as attachment
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'vinodnaik00089@gmail.com';
        $mail->Password = 'yptkgnqksfclukut';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('vinodnaik00089@gmail.com', 'Scholarship System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Scholarship Application Status - $scholarship_name";
        $mail->Body    = "Dear Student,<br><br>Your application for the scholarship '$scholarship_name' has been $status.<br><br>Best regards,<br>Scholarship Management System";

        // Attach the generated PDF form
        $mail->addStringAttachment($pdf_output, 'application_form.pdf');

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['application_id'])) {
    $application_id = intval($_POST['application_id']);
    $action = $_POST['action'];

    if (in_array($action, ['approved', 'rejected'])) {
        $stmt = $conn->prepare("SELECT * FROM applications WHERE id = ?");
        $stmt->bind_param("i", $application_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $app = $result->fetch_assoc();

        $status = ucfirst($action);

        // Pass the application data to sendStatusEmail for generating and sending the PDF
        sendStatusEmail($app['email'], $app['scholarship_name'], $status, $app);

        // Update the status of the application
        $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $action, $application_id);
        $stmt->execute();
    }
}


$applications = [];
$sql = "SELECT * FROM applications ORDER BY applied_on DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}

$documentApplications = [];
$sqlDocuments = "SELECT * FROM applications WHERE status = 'Pending'";
$resultDocuments = $conn->query($sqlDocuments);
if ($resultDocuments) {
    while ($row = $resultDocuments->fetch_assoc()) {
        $documentApplications[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Applications and Verify Documents</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-image: url('sms1.jpg');
            background-size:cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 40px;
        }

        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; background: rgba(245, 245, 245, 0.64); margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #007BFF; color: white; }
        .status-pending { color: orange; }
        .status-approved { color: green; }
        .status-rejected { color: red; }
        button { padding: 5px 10px; margin: 2px; border: none; border-radius: 5px; cursor: pointer; }
        .approve { background-color: #28a745; color: white; }
        .reject { background-color: #dc3545; color: white; }
        .view { background-color: #17a2b8; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; }
        .btn-back { display: inline-block; margin: 20px 0; padding: 10px 15px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>

<a href="admin_dashboard.php" class="btn-back">← Back to Dashboard</a>

<h2>Scholarship Applications</h2>

<table>
    <thead>
        <tr>
            <th>Aadhaar</th>
            <th>Scholarship</th>
            <th>Education Level</th>
            <th>Percentage</th>
            <th>Course</th>
            <th>Institution</th>
            <th>Applied On</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($applications) > 0): ?>
            <?php foreach ($applications as $app): ?>
                <tr>
                    <td><?= htmlspecialchars($app['aadhaar']) ?></td>
                    <td><?= htmlspecialchars($app['scholarship_name']) ?></td>
                    <td><?= htmlspecialchars($app['education_level']) ?></td>
                    <td><?= htmlspecialchars($app['percentage']) ?>%</td>
                    <td><?= htmlspecialchars($app['course']) ?></td>
                    <td><?= htmlspecialchars($app['institution']) ?></td>
                    <td><?= htmlspecialchars($app['applied_on']) ?></td>
                    <td class="status-<?= strtolower($app['status']) ?>"><?= ucfirst($app['status']) ?></td>
                    <td>
                        <?php if ($app['status'] == 'Pending'): ?>
                            <form method="POST">
                                <input type="hidden" name="application_id" value="<?= $app['id'] ?>">
                                <button type="submit" name="action" value="approved" class="approve">Approve</button>
                                <button type="submit" name="action" value="rejected" class="reject">Reject</button>
                            </form>
                        <?php else: ?>
                            <em>No actions</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="9">No applications found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<h2>Verify Documents</h2>
<table>
    <tr>
        <th>Scholarship</th>
        <th>Aadhaar</th>
        <th>Education</th>
        <th>Documents</th>
    </tr>
    <?php foreach ($documentApplications as $app): ?>
        <tr>
            <td><?= htmlspecialchars($app['scholarship_name']) ?></td>
            <td><?= htmlspecialchars($app['aadhaar']) ?></td>
            <td><?= htmlspecialchars($app['education_level']) ?></td>
            <td>
                <a class="view" href="<?= $app['aadhaar_card'] ?>" target="_blank">Aadhaar</a>
                <a class="view" href="<?= $app['marksheet'] ?>" target="_blank">Marksheet</a>
                <a class="view" href="<?= $app['income_certificate'] ?>" target="_blank">Income</a>
                <a class="view" href="<?= $app['caste_certificate'] ?>" target="_blank">Caste</a>
                <a class="view" href="<?= $app['bank_passbook'] ?>" target="_blank">Bank Passbook</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
