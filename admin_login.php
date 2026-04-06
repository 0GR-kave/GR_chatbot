<?php
session_start();

$conn = new mysqli("localhost","root","","cohere");

$email = $_POST['email'];
$password = $_POST['password'];

$result = $conn->query("SELECT * FROM users WHERE email='$email' AND role='admin'");
$user = $result->fetch_assoc();

if($user && password_verify($password, $user['password'])){
    $_SESSION['admin'] = true;
    header("Location: admin_dashboard.php");
} else {
   header("Refresh: 1; url=admin_login.html");
echo "wrong email or password";
   
}

?>