<?php
session_start();
require 'db.php';

if (!isset($_SESSION['aadhaar'])) {
    header("Location: student_login.php");
    exit();
}

$query = "SELECT * FROM scholarships ORDER BY deadline ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Scholarships</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('sms1.jpg'); /* 🌟 Replace with your image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: rgba(255, 255, 255, 0.12);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            width: 10000px;
            height: 1000px;
        }
        h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 30px;
        }
        .scholarship {
            background:rgba(255, 255, 255, 0.67);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
        }
        .scholarship:hover {
            transform: translateY(-5px);
        }
        .scholarship h3 {
            margin-top: 0;
            color: #007BFF;
        }
        .scholarship p {
            margin: 8px 0;
            color: #333;
        }
        .btn {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .back-btn {
            background-color: #6c757d;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background-color: #5a6268;
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="student_profile.php" class="btn back-btn">⬅ Back to Dashboard</a>
        <h2>Available Scholarships</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="scholarship">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
                    <p><strong>Eligibility:</strong> <?= htmlspecialchars($row['eligibility']) ?></p>
                    <p><strong>Amount:</strong> ₹<?= htmlspecialchars($row['amount']) ?></p>
                    <p><strong>Deadline:</strong> <?= htmlspecialchars($row['deadline']) ?></p>
                    <a class="btn" href="apply_scholarship.php?scholarship_id=<?= $row['id'] ?>">Apply</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No scholarships available at the moment.</p>
        <?php endif; ?>
    </div>
</body>
</html>
