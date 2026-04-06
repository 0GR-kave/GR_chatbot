<?php
use PHPMailer\PHPMailer\PHPMailer;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$conn = new mysqli("localhost","root","","cohere");

$email = $_POST['email'];

// Check user exists
$res = $conn->query("SELECT * FROM users WHERE email='$email'");
if($res->num_rows == 0){
    echo "Email not found";
    exit;
}

// Generate OTP
$otp = rand(100000,999999);

// Save OTP
$conn->query("UPDATE users SET otp='$otp' WHERE email='$email'");

// Send Email
$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'grkavevendhan@gmail.com'; // YOUR EMAIL
$mail->Password = 'eyutjjftpcoobvuz';   // APP PASSWORD
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('grkavevendhan@gmail.com', 'AI Chatbot');
$mail->addAddress($email);

$mail->Subject = "OTP for Password Reset";
$mail->Body = "Your OTP is: $otp";

if($mail->send()){
  echo "<center>";
echo "<p style='color: green; font-weight: bold;'>OTP sent successfully!</p>";
echo "<a href='verify_otp.php?email=$email' style='color: blue; text-decoration: underline;'>Verify OTP</a>";
echo "</center>";
} else {
    echo "Failed to send OTP";
}
?>