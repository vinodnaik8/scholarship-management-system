<?php
session_start();
require 'db.php';

if (!isset($_SESSION['aadhaar'])) {
    header("Location: student_login.php");
    exit();
}

$aadhaar = $_SESSION['aadhaar'];
$query = "SELECT * FROM applications WHERE aadhaar = '$aadhaar' ORDER BY applied_on DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Application Status</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-image: url('sms1.jpg');
            background-size:cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 40px;
        }
        .status-container {
            background-color: rgba(245, 245, 245, 0.66);
            max-width: 900px;
            margin: auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-rejected {
            color: red;
            font-weight: bold;
        }
        .dashboard-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            color: #007BFF;
            text-decoration: none;
        }
        .dashboard-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="status-container">
    <h2>Application Status</h2>

    <table>
        <tr>
            <th>Scholarship</th>
            <th>Education Level</th>
            <th>Date Applied</th>
            <th>Status</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $status_class = '';
                switch (strtolower($row['status'])) {
                    case 'pending':
                        $status_class = 'status-pending';
                        break;
                    case 'approved':
                        $status_class = 'status-approved';
                        break;
                    case 'rejected':
                        $status_class = 'status-rejected';
                        break;
                }
                echo "<tr>
                        <td>{$row['scholarship_name']}</td>
                        <td>{$row['education_level']}</td>
                        <td>{$row['applied_on']}</td>
                        <td class='$status_class'>" . ucfirst($row['status']) . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No applications found.</td></tr>";
        }
        ?>
    </table>

    <a href="student_profile.php" class="dashboard-link">Go to Dashboard</a>
</div>

</body>
</html>
