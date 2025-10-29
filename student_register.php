<?php
session_start();
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'db.php';

$notification = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aadhaar = mysqli_real_escape_string($conn, $_POST['aadhaar']);

    $query = "SELECT * FROM student_data WHERE aadhaar = '$aadhaar'";
    $result = mysqli_query($conn, $query);
    $student = mysqli_fetch_assoc($result);

    if ($student) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['aadhaar'] = $aadhaar;

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'vinodnaik00089@gmail.com';
            $mail->Password   = 'yptkgnqksfclukut';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = 587;

            $mail->setFrom('vinodnaik00089@gmail.com', 'Scholarship Management');
            $mail->addAddress($student['email'], $student['name']);
            $mail->Subject = 'OTP for Registration';
            $mail->Body    = "Hello {$student['name']},\n\nYour OTP for registration is: $otp\n\nRegards,\nSMS Team";

            $mail->send();
            $_SESSION['otp_sent'] = true;
            header('Location: verify_otp.php');
            exit();
        } catch (Exception $e) {
            $notification = "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $notification = "Aadhaar number not found in the system.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('sms1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        form {
            background-color: rgba(245, 245, 245, 0.1);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        .notification {
            color: red;
            text-align: center;
            margin-top: -10px;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Register with Aadhaar</h2>
        <label for="aadhaar">Enter Aadhaar Number:</label>
        <input type="text" name="aadhaar" required pattern="\d{12}" maxlength="12" placeholder="12-digit Aadhaar">

        <input type="submit" value="Send OTP">
        <?php if ($notification): ?>
            <div class="notification"><?= htmlspecialchars($notification) ?></div>
        <?php endif; ?>
        <div class="link">
    <a href="index.php">Go back</a>
</div>
    </form>
</body>
</html>
