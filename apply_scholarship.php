<?php
session_start();
require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (!isset($_SESSION['aadhaar'])) {
    header("Location: student_login.php");
    exit();
}

$aadhaar = $_SESSION['aadhaar'];

$student_query = mysqli_query($conn, "SELECT * FROM student_data WHERE aadhaar = '$aadhaar'");
$student = mysqli_fetch_assoc($student_query);

$scholarship_id = $_GET['scholarship_id'] ?? '';
$scholarship = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM scholarships WHERE id = '$scholarship_id'"));

if (!$scholarship) {
    echo "<script>alert('Invalid Scholarship ID'); window.location.href='student_profile.php';</script>";
    exit();
}

$existing = mysqli_query($conn, "SELECT * FROM applications WHERE aadhaar = '$aadhaar' AND scholarship_id = '$scholarship_id' AND status = 'Pending'");
if (mysqli_num_rows($existing) > 0) {
    echo "<script>alert('You have already applied for this scholarship and it is under review.'); window.location.href='student_profile.php';</script>";
    exit();
}

$scholarship_name = $scholarship['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $education_level = "UG"; // Only UG allowed, fixed
    $percentage = mysqli_real_escape_string($conn, $_POST['percentage']);
    $institution = mysqli_real_escape_string($conn, $_POST['institution']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);  // Now selecting course from dropdown
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $account_holder = mysqli_real_escape_string($conn, $_POST['account_holder']);
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
    $ifsc_code = mysqli_real_escape_string($conn, $_POST['ifsc_code']);

    $doc_fields = ['aadhaar_card', 'marksheet', 'income_certificate', 'caste_certificate', 'bank_passbook'];
    $uploaded_docs = [];
    $upload_dir = 'uploads/' . $aadhaar . '/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    foreach ($doc_fields as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] == 0) {
            if ($_FILES[$field]['size'] > 5000000) {
                echo "<script>alert('File size for $field is too large. Please upload a file smaller than 5MB.'); window.location.href='apply_scholarship.php';</script>";
                exit();
            }

            $file_name = basename($_FILES[$field]['name']);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($file_ext, ['pdf', 'jpg', 'jpeg', 'png'])) {
                echo "<script>alert('Invalid file type for $field. Please upload a PDF or image file.'); window.location.href='apply_scholarship.php';</script>";
                exit();
            }

            $target_path = $upload_dir . $field . "_" . time() . "_" . $file_name;
            if (!move_uploaded_file($_FILES[$field]['tmp_name'], $target_path)) {
                echo "<script>alert('Error uploading $field. Please try again.'); window.location.href='apply_scholarship.php';</script>";
                exit();
            }
            $uploaded_docs[$field] = $target_path;
        } else {
            $uploaded_docs[$field] = '';
        }
    }

    $stmt = $conn->prepare("INSERT INTO applications (
        aadhaar, email, scholarship_id, scholarship_name,
        education_level, percentage, institution, course, year, semester,
        bank_name, account_holder, account_number, ifsc_code,
        aadhaar_card, marksheet, income_certificate, caste_certificate, bank_passbook, status, applied_on
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, 'Pending', NOW())");

    $stmt->bind_param("sssssssssssssssssss", 
        $aadhaar, $student['email'], $scholarship_id, $scholarship_name, 
        $education_level, $percentage, $institution, $course, $year, $semester,
        $bank_name, $account_holder, $account_number, $ifsc_code, 
        $uploaded_docs['aadhaar_card'], $uploaded_docs['marksheet'], 
        $uploaded_docs['income_certificate'], $uploaded_docs['caste_certificate'], $uploaded_docs['bank_passbook']
    );

    if ($stmt->execute()) {
        $email = $student['email'];

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
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('vinodnaik00089@gmail.com', 'Scholarship Portal');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Scholarship Application Submitted';
            $mail->Body = "<p>Your application for <b>$scholarship_name</b> has been submitted on " . date("Y-m-d") . ".</p>";
            $mail->send();
        } catch (Exception $e) {
            echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
        }

        echo "<script>alert('Application submitted successfully!'); window.location.href='student_profile.php';</script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Scholarship</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 40px 20px;
        background-image: url('sms1.jpg'); /* Replace with your image filename */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }

    form {
        max-width: 600px;
        margin: auto;
        padding: 30px;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.2); /* Transparent white */
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px); /* Glass effect */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        color: #ffffff;
    }

    input, select, button {
        width: 100%;
        padding: 12px;
        margin-top: 12px;
        border: none;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.8);
        color: #333;
        font-size: 16px;
        outline: none;
    }

    label {
        margin-top: 20px;
        display: block;
        font-weight: bold;
        color: #ffffff;
        font-size: 18px;
    }

    button {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s, transform 0.3s;
    }

    button:hover {
        background: linear-gradient(135deg, #0056b3, #003d82);
        transform: scale(1.03);
    }
</style>

</head>
<body>
    <h2 style="text-align: center;">Apply for: <?= htmlspecialchars($scholarship_name) ?></h2>
    <form method="POST" enctype="multipart/form-data">

        <input type="hidden" name="education_level" value="UG">

        <label>Percentage (%)</label>
        <input type="number" name="percentage" min="0" max="100" step="0.01" required>

        <label>Institution Name</label>
        <input type="text" name="institution" required>

        <label>Course</label>
        <select name="course" required>
        <option value="">-- Select your course--</option>
            <option value="BSc Computer Science">BSc Computer Science</option>
            <option value="BCom">BCom</option>
            <option value="BA English">BA English</option>
            <option value="BTech">BTech</option>
            <option value="BBA">BBA</option>
            <option value="BSc Mathematics">BSc Mathematics</option>
            <option value="BSc Physics">BSc Physics</option>
            <option value="BCA">BCA</option>
        </select>

        <label>Year of Study</label>
            <select name="year" required >
            <option value="">-- Select current year --</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
            </select>
        </div>

        <label>Semester</label>
            <select name="semester" required >
            <option value="">-- Select current semester --</option>
                <option value="1st Semester">1st Semester</option>
                <option value="2nd Semester">2nd Semester</option>
                <option value="3rd Semester">3rd Semester</option>
                <option value="4th Semester">4th Semester</option>
                <option value="5th Semester">5th Semester</option>
                <option value="6th Semester">6th Semester</option>
            </select>
        </div>

        <hr>
        <label>Bank Name</label>
        <input type="text" name="bank_name" required>

        <label>Account Holder Name</label>
        <input type="text" name="account_holder" required>

        <label>Account Number</label>
        <input type="text" name="account_number" required>

        <label>IFSC Code</label>
        <input type="text" name="ifsc_code" pattern="^[A-Z]{4}0[A-Z0-9]{6}$" title="Enter valid IFSC (e.g., SBIN0001234)" required>

        <hr>
        <label>Upload Aadhaar Card (PDF/JPG/PNG)</label>
        <input type="file" name="aadhaar_card" accept=".pdf,.jpg,.jpeg,.png" required>

        <label>Upload Marksheet (PDF/JPG/PNG)</label>
        <input type="file" name="marksheet" accept=".pdf,.jpg,.jpeg,.png" required>

        <label>Upload Income Certificate (PDF/JPG/PNG)</label>
        <input type="file" name="income_certificate" accept=".pdf,.jpg,.jpeg,.png" required>

        <label>Upload Caste Certificate (PDF/JPG/PNG)</label>
        <input type="file" name="caste_certificate" accept=".pdf,.jpg,.jpeg,.png" required>

        <label>Upload Bank Passbook (PDF/JPG/PNG)</label>
        <input type="file" name="bank_passbook" accept=".pdf,.jpg,.jpeg,.png" required>

        <br>
        <button type="submit">Submit Application</button>
    </form>
</body>
</html>
