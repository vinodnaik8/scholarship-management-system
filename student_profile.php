<?php
session_start();
require 'db.php';

if (!isset($_SESSION['aadhaar'])) {
    header("Location: student_login.php");
    exit();
}

$aadhaar = $_SESSION['aadhaar'];

$student_query = "SELECT * FROM student_data WHERE aadhaar = '$aadhaar'";
$student_result = mysqli_query($conn, $student_query);
$student = mysqli_fetch_assoc($student_result);

$app_query = "SELECT * FROM applications WHERE aadhaar = '$aadhaar' ORDER BY applied_on DESC";
$app_result = mysqli_query($conn, $app_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-image: url('sms1.jpg');
            background-size:cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 40px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: rgba(243, 241, 241, 0.1);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h2, h3 {
            text-align: center;
            color: #007BFF;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        td, th {
            padding: 10px;
            border-bottom: 10px solid #ddd;
        }
        tr { background-color:rgba(255, 255, 255, 0.47); }
        .label { font-weight: bold; width: 30%; }
        .status-pending { color: orange; font-weight: bold; }
        .status-approved { color: green; font-weight: bold; }
        .status-rejected { color: red; font-weight: bold; }
        .buttons {
            text-align: center;
            margin-top: 30px;
        }
        .buttons a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background: #007BFF;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }
        .buttons a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Student Profile</h2>

    <table>
        <tr><td class="label">Aadhaar:</td><td><?= htmlspecialchars($student['aadhaar']) ?></td></tr>
        <tr><td class="label">Name:</td><td><?= htmlspecialchars($student['name']) ?></td></tr>
        <tr><td class="label">DOB:</td><td><?= htmlspecialchars($student['dob']) ?></td></tr>
        <tr><td class="label">Gender:</td><td><?= htmlspecialchars($student['gender']) ?></td></tr>
        <tr><td class="label">Email:</td><td><?= htmlspecialchars($student['email']) ?></td></tr>
        <tr><td class="label">Phone:</td><td><?= htmlspecialchars($student['phone']) ?></td></tr>
        <tr><td class="label">Address:</td><td><?= htmlspecialchars($student['address']) ?></td></tr>
        <tr><td class="label">Country:</td><td><?= htmlspecialchars($student['country']) ?></td></tr>
        <tr><td class="label">State:</td><td><?= htmlspecialchars($student['state']) ?></td></tr>
        <tr><td class="label">Bank Name:</td><td><?= htmlspecialchars($student['bank_name']) ?></td></tr>
        <tr><td class="label">Account Number:</td><td><?= htmlspecialchars($student['account_number']) ?></td></tr>
        <tr><td class="label">IFSC Code:</td><td><?= htmlspecialchars($student['ifsc_code']) ?></td></tr>
    </table>

    <h3>Application History</h3>
    <?php if (mysqli_num_rows($app_result) > 0): ?>
        <table>
            <tr>
                <th>Scholarship</th>
                <th>Education Level</th>
                <th>Applied On</th>
                <th>Status</th>
            </tr>
            <?php while ($app = mysqli_fetch_assoc($app_result)): 
                $statusClass = '';
                switch (strtolower($app['status'])) {
                    case 'pending': $statusClass = 'status-pending'; break;
                    case 'approved': $statusClass = 'status-approved'; break;
                    case 'rejected': $statusClass = 'status-rejected'; break;
                }
            ?>
                <tr>
                    <td><?= htmlspecialchars($app['scholarship_name']) ?></td>
                    <td><?= htmlspecialchars($app['education_level']) ?></td>
                    <td><?= htmlspecialchars($app['applied_on']) ?></td>
                    <td class="<?= $statusClass ?>"><?= ucfirst($app['status']) ?></td>
                </tr>

            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No application history found.</p>
    <?php endif; ?>


    <div class="buttons">
        <a href="scholarships.php">Apply Scholarship</a>
        <a href="view_status.php">View Status</a>
        <a href="student_logout.php">Logout</a>
    </div>
</div>
</body>
</html>
