<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SMS</title>
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
            color: #fff;
        }

        .container {
            text-align: center;
            background: rgba(3, 2, 2, 0.6); /* Transparent background */
            padding: 50px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 36px;
            margin-bottom: 30px;
        }

        .btn-group {
            margin-bottom: 20px;
        }

        .button {
            padding: 15px 30px;
            background: linear-gradient(135deg, #007BFF, #0056b3);
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.2s ease;
            margin: 0 10px;
        }

        .button:hover {
            background: linear-gradient(135deg, #0056b3, #003d82);
            transform: scale(1.05);
        }

        .query-message {
            margin-top: 20px;
            font-size: 16px;
        }

        .query-message a {
            color: #00d9ff;
            text-decoration: none;
        }

        .query-message a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Welcome to Scholarship Management System</h1>
        <div class="btn-group">
            <a href="student_login.php" class="button">Student Login</a>
            <a href="admin_login.php" class="button">Admin Login</a>
        </div>

        <div class="query-message">
            Have any queries? <a href="contact_us.php">Contact Us</a>
        </div>
    </div>

</body>
</html>
