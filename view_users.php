<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.html");
    exit;
}

$conn = new mysqli("localhost","root","","cohere");

$result = $conn->query("SELECT * FROM users");

while($row = $result->fetch_assoc()){
    echo "ID: ".$row['id']."<br>";
    echo "Name: ".$row['name']."<br>";
    echo "Email: ".$row['email']."<br>";
    echo "<a href='delete_user.php?id=".$row['id']."'>Delete</a>";
    echo "<hr>";
}
?>