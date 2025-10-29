<?php
// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = 0;  // Set to SMTP::DEBUG_SERVER for detailed logs
    $mail->isSMTP();                            
    $mail->Host       = 'smtp.gmail.com';       
    $mail->SMTPAuth   = true;                  
    $mail->Username   = 'vinodnaik00089@gmail.com';  // Your Gmail
    $mail->Password   = 'yptkgnqksfclukut';         // Your App Password (no spaces!)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $mail->Port       = 587;

    // Sender and recipient
    $mail->setFrom('vinodnaik00089@gmail.com', 'Scholarship Management');
    $mail->addAddress('vv2941329@gmail.com', 'Student');  // Receiver

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from Scholarship Management';
    $mail->Body    = '<strong>This is a test email sent using PHPMailer with Gmail SMTP.</strong>';
    $mail->AltBody = 'This is a test email sent using PHPMailer with Gmail SMTP.';

    $mail->send();
    echo 'Message has been sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Error: {$mail->ErrorInfo}";
}
