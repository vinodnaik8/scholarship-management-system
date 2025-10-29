<?php
session_start();
require 'db.php';

$error = '';
$success = '';

// 🔥 Correct session check
if (!isset($_SESSION['verified_aadhaar'])) {
    header('Location: forgot_password.php'); 
    exit;
}

$aadhaar = $_SESSION['verified_aadhaar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($new_password) || empty($confirm_password)) {
        $error = "Both fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Hash and Update password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE student_data SET password = ?, otp = NULL, otp_expires_at = NULL WHERE aadhaar = ?");
        $stmt->bind_param("ss", $hashed_password, $aadhaar);
        
        if ($stmt->execute()) {
            // Clear session
            unset($_SESSION['verified_aadhaar']);
            $success = "Your password has been reset successfully. <a href='student_login.php'>Click here to Login</a>.";
        } else {
            $error = "Failed to update password. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - SMS</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-image: url('sms1.jpg'); /* Replace with your image */
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
        background: rgba(255, 255, 255, 0.15); /* Transparent background */
        padding: 30px;
        max-width: 400px;
        margin: auto;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        color: black;
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

    input[type="password"],
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 1rem;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }
</style>

</head>
<body>

<form method="POST" action="">
    <h2>Reset Password</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <?php if (empty($success)): ?> <!-- Only show form if not success -->
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <input type="submit" value="Reset Password">
    <?php endif; ?>

</form>

</body>
</html>
