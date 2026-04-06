<?php
$conn = new mysqli("localhost","root","","cohere");

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
if(empty($email) || empty($password) || empty($name)){
    echo "All fields required";
    exit;
}
else{
$conn->query("INSERT INTO users(name,email,password) VALUES('$name','$email','$password')");

echo "Registered successfully";
header("Location: login.html");
}

?>
