<?php
session_start();
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $eligibility = $_POST['eligibility'];
    $percentage = $_POST['percentage'];
    $amount = $_POST['amount'];
    $deadline = $_POST['deadline'];

    $stmt = $conn->prepare("INSERT INTO scholarships (name, description, eligibility, percentage, amount, deadline, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssids", $name, $description, $eligibility, $percentage, $amount, $deadline);

    if ($stmt->execute()) {
        $message = "Scholarship added successfully!";
    } else {
        $message = "Error adding scholarship: " . $stmt->error;
    }
}

$scholarships = $conn->query("SELECT * FROM scholarships ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Scholarships</title>
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
        .container {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background: rgba(255, 255, 255, 0.8); /* Transparent background */
            padding: 30px;
            border-radius: 10px;
            width: 100%;
        }
        a.back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        h2 {
            color: #007bff;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .message {
            color: green;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            background: #007bff;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">

    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

    <h2>Add New Scholarship</h2>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Scholarship Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <textarea name="eligibility" placeholder="Eligibility Criteria" required></textarea>
        <input type="number" step="0.01" name="percentage" placeholder="Minimum Percentage Required" required>
        <input type="number" name="amount" placeholder="Scholarship Amount" required>
        <input type="date" name="deadline" required>
        <button type="submit">Add Scholarship</button>
    </form>

    <h2>Existing Scholarships</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Eligibility</th>
            <th>Percentage</th>
            <th>Amount</th>
            <th>Deadline</th>
            <th>Created At</th>
        </tr>
        <?php while ($row = $scholarships->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['eligibility']) ?></td>
                <td><?= $row['percentage'] ?>%</td>
                <td>₹<?= $row['amount'] ?></td>
                <td><?= $row['deadline'] ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
