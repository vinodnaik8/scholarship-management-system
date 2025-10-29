<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aadhaar = mysqli_real_escape_string($conn, $_POST['aadhaar']);
    $password = $_POST['password'];

    $query = "SELECT * FROM student_data WHERE aadhaar = '$aadhaar' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);

        if (password_verify($password, $student['password'])) {
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['aadhaar'] = $student['aadhaar'];
            $_SESSION['name'] = $student['name'];

            header("Location: student_profile.php");
            exit();
        } else {
            $error = "Incorrect password.";
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
    <title>Student Login - SMS</title>
    <style>
        body {
            font-family: Arial;
            background-image:url('sms1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .success {
            color: green;
            text-align: center;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            color:rgb(14, 41, 176);
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<form method="POST" action="">
    <h2>Student Login</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['registered']) && $_GET['registered'] == 'success'): ?>
        <div class="success">Registration successful! Please log in.</div>
    <?php endif; ?>

    <label for="aadhaar">Aadhaar Number:</label>
    <input type="text" name="aadhaar" id="aadhaar" required pattern="\d{12}" maxlength="12" placeholder="12-digit Aadhaar">

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>

    <input type="submit" value="Login">

    <div class="link">
        <a href="student_register.php">New student? Register here</a>
    </div>
    <div class="link">
    <a href="forgot_password.php">Forgot Password?</a>
</div>
<div class="link">
    <a href="index.php">Go back</a>
</div>

</form>

</body>
</html>
