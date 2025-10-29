<?php
// contact_us.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Scholarship Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url('sms1.jpg'); /* Replace with your image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .contact-container {
            background: rgba(255, 255, 255, 0.15); /* Transparent white */
            backdrop-filter: blur(10px); /* Glassmorphism effect */
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 90%;
            text-align: center;
            color: #fff;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color:rgb(13, 11, 11);
        }

        p {
            font-size: 18px;
            margin-bottom: 12px;
            color:rgb(7, 6, 6);
        }

        .back-btn {
            margin-top: 25px;
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #007BFF, #0056b3);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s, transform 0.3s;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #0056b3, #003d82);
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="contact-container">
    <h1>Contact Us</h1>
    <p><strong>Email:</strong> scholarshipmanagement@gmail.com</p>
    <p><strong>Phone:</strong> +91 98765 43210</p>
    <p><strong>Address:</strong> 123 Scholarship Lane, Education City, India</p>

    <a href="index.php" class="back-btn">Back to Home</a>
</div>

</body>
</html>
