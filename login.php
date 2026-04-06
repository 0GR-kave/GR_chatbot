<?php
session_start();

$conn = new mysqli("localhost","root","","cohere");
$email = htmlspecialchars($_POST['email']);
// $email = $_POST['email'];
$password = $_POST['password'];

$result = $conn->query("SELECT * FROM users WHERE email='$email'");
$user = $result->fetch_assoc();

if($user && password_verify($password, $user['password'])){
    $_SESSION['user_id'] = $user['id'];
    header("Location: index.php");
} else {
    header("Refresh: 1; url=login.html");
echo "wrong email or password";
}
?>

