<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';

$error = '';
$success = '';
$show_reset_button = false;

// 🔥 Correct redirection
if (!isset($_SESSION['aadhaar_for_otp'])) {
    header('Location: forgot_password.php'); // ✅ back to forgot if session not set
    exit;
}

$aadhaar = $_SESSION['aadhaar_for_otp'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = mysqli_real_escape_string($conn, $_POST['otp']);

    $query = "SELECT otp, otp_expires_at FROM student_data WHERE aadhaar = '$aadhaar' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);

        $db_otp = $student['otp'];
        $db_otp_expiry = $student['otp_expires_at'];

        $current_time = date("Y-m-d H:i:s");

        // 🔥 Correct OTP checking
        if ($db_otp == $entered_otp && $current_time <= $db_otp_expiry) {
            // OTP Correct

            // Clear OTP after use
            $stmt = $conn->prepare("UPDATE student_data SET otp = NULL, otp_expires_at = NULL WHERE aadhaar = ?");
            $stmt->bind_param("s", $aadhaar);
            $stmt->execute();

            $_SESSION['verified_aadhaar'] = $aadhaar; // ✅ mark verified
            unset($_SESSION['aadhaar_for_otp']); // ✅ clear temporary session

            $success = "OTP Verified Successfully!";
            $show_reset_button = true;
        } else {
            $error = "Invalid OTP or OTP expired.";
        }
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP - SMS</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-image: url('sms1.jpg'); 
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    form {
        background: rgba(255, 255, 255, 0.13); 
        padding: 30px;
        max-width: 400px;
        margin: auto;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        width: 100%;
    }

    h2 {
        text-align: center;
        color: #333;
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

    input[type="text"],
    input[type="submit"],
    a.button {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        text-align: center;
        text-decoration: none;
        border-radius: 6px;
        font-size: 1rem;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: white;
        cursor: pointer;
        border: 1px solid #007bff;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    a.button {
        background: #28a745;
        color: white;
        display: block;
        border-radius: 6px;
        text-align: center;
    }

    a.button:hover {
        background: #218838;
    }
</style>

</head>
<body>

<form method="POST" action="">
    <h2>Verify OTP</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!$show_reset_button): ?>
        <label for="otp">Enter OTP:</label>
        <input type="text" name="otp" id="otp" required maxlength="6" pattern="\d{6}" placeholder="Enter 6-digit OTP">

        <input type="submit" value="Verify OTP">
    <?php endif; ?>

    <?php if ($show_reset_button): ?>
        <a href="reset_password.php" class="button">Reset Your Password</a>
    <?php endif; ?>

</form>

</body>
</html>
