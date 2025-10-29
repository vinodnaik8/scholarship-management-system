<?php
session_start();

if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit();
}

include('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('sms1.jpg'); /* <-- Replace with your image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #ffffff;
        }

        /* Header Styles */
        .header {
            background: rgba(0, 0, 0, 0.6);
            color: #ffffff;
            padding: 30px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.4);
            backdrop-filter: blur(8px);
        }

        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: bold;
        }

        /* Container Styles */
        .container {
            max-width: 1100px;
            margin: 60px auto;
            padding: 30px;
            background: rgba(17, 10, 10, 0.15); /* Light transparent white */
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px); /* Glass effect */
            border: 1px solid rgba(255,255,255,0.3);
        }

        /* Card Styles */
        .card {
            background: rgba(255, 255, 255, 0.2); /* Slightly more transparent */
            padding: 40px;
            margin-bottom: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
            text-align: center;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .card h2 {
            margin-top: 0;
            font-size: 28px;
            color:rgb(5, 4, 4);
            font-weight: bold;
        }

        .card p {
            font-size: 18px;
            margin-top: 10px;
            color:rgb(13, 9, 9);
        }

        /* Button Styles */
        .btn-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 15px 30px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
        }

        .btn:hover {
            padding: 15px 30px;
            background: linear-gradient(135deg, #0072ff, #0056b3);
            transform: scale(1.05);
        }
        /* Logout Section Styles */
        .logout {
            margin-top: 40px;
            text-align: center;
        }

        .logout a {
            color: #ff4d4d;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .logout a:hover {
            color: #ff1a1a;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</h1>
</div>

<div class="container">
    <div class="card">
        <h2>Admin Dashboard</h2>
        <p>Select an action below to manage the scholarship system:</p>
        <div class="btn-group">
            <a href="view_applications.php" class="btn">View Applications</a>
            <a href="manage_scholarships.php" class="btn">Manage Scholarships</a>
        </div>
    </div>

    <div class="logout">
        <a href="admin_logout.php">Logout</a>
    </div>
</div>

</body>
</html>
