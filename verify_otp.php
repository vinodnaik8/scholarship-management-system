<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];
    if ($entered_otp == $_SESSION['otp']) {
        header('Location: set_password.php');
    } else {
        echo "<div class='error-message'>Invalid OTP. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-image: url('sms1.jpg'); /* your background image */
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.11); /* light frosted-glass effect */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            color: #007BFF;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            text-align: center;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-message {
            background: rgba(255, 0, 0, 0.1);
            color: red;
            padding: 10px;
            margin-top: 20px;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter OTP sent to your email</h2>
        <form method="POST">
            <label for="otp">OTP:</label>
            <input type="text" name="otp" required>
            <input type="submit" value="Verify OTP">
            <div class="link">
    <a href="student_register.php">Go back</a>
</div>
        </form>
    </div>
</body>
</html>
