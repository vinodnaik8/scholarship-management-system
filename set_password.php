<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password == $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $aadhaar = $_SESSION['aadhaar'];
        $query = "UPDATE student_data SET password = '$hashed_password' WHERE aadhaar = '$aadhaar'";
        if (mysqli_query($conn, $query)) {
            $_SESSION['registration_success'] = true;
            header('Location: registration_success.php');
        } else {
            echo "Error saving password.";
        }
    } else {
        echo "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('sms1.jpg'); /* 🌟 Replace with your image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            justify-content: center;
            align-items: center;
            display: flex;
            height: 100vh;
            margin: 0;
            padding: 40px;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.12);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
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
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="password"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Set Your Password</h2>
        <form method="POST">
            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" required>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
