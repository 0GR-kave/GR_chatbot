<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.html");
    exit;
}

$conn = new mysqli("localhost","root","","cohere");

$result = $conn->query("SELECT * FROM chat_history");

while($row = $result->fetch_assoc()){
    echo "<b>User ID:</b> ".$row['user_id']."<br>";
    echo "<b>User:</b> ".$row['user_msg']."<br>";
    echo "<b>Bot:</b> ".$row['bot_msg']."<br>";
    echo "<a href='delete_chat.php?id=".$row['id']."'>Delete</a>";
    echo "<hr>";
}
?>