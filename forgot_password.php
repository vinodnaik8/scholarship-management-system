<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php'; // your database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aadhaar = mysqli_real_escape_string($conn, $_POST['aadhaar']);

    // Check if student exists
    $query = "SELECT * FROM student_data WHERE aadhaar = '$aadhaar' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);

        // Generate OTP
        $otp = rand(100000, 999999); 
        $otp_expiration = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        // Store OTP in database
        $stmt = $conn->prepare("UPDATE student_data SET otp = ?, otp_expires_at = ? WHERE aadhaar = ?");
        $stmt->bind_param("sss", $otp, $otp_expiration, $aadhaar);

        if ($stmt->execute()) {
            // Send OTP Email
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
                $mail->Username = 'vinodnaik00089@gmail.com'; // Your Gmail account
                $mail->Password = 'yptkgnqksfclukut'; // Your app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // StartTLS encryption
                $mail->Port = 587; // Port for TLS


                $mail->setFrom('vinodnaik00089@gmail.com', 'Scholarship System');
                $mail->addAddress($student['email']);

                $mail->isHTML(true);
                $mail->Subject = 'OTP for Password Reset';
                $mail->Body = "
                    Dear {$student['name']},<br><br>
                    Your OTP for password reset is: <strong>$otp</strong><br><br>
                    This OTP will expire in 15 minutes.<br><br>
                    If you did not request this, please ignore this email.
                ";

                $mail->send();

                // Save Aadhaar temporarily in session
                $_SESSION['aadhaar_for_otp'] = $aadhaar;

                // Redirect to OTP entry page
                header('Location: resetpassword_otp.php');
                exit;
            } catch (Exception $e) {
                $error = "OTP Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $error = "Failed to save OTP. Please try again.";
        }
    } else {
        $error = "No student found with this Aadhaar number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - SMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('sms1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            padding: 50px;
        }
        form {
            background: rgba(255, 255, 255, 0.15);
            padding: 30px;
            max-width: 400px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .link {
            text-align: center;
            margin-top: 10px;
        }
        .link a {
            color:rgb(13, 13, 156);
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<form method="POST" action="">
    <h2>Forgot Password</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <label for="aadhaar">Aadhaar Number:</label>
    <input type="text" name="aadhaar" id="aadhaar" required maxlength="12" pattern="\d{12}" placeholder="Enter your Aadhaar Number">

    <input type="submit" value="Send OTP">

    <div class="link">
        <a href="student_login.php">Back to login</a>
    </div>
</form>

</body>
</html>
