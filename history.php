<?php
session_start();

$conn = new mysqli("localhost","root","","cohere");

$user_id = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM chat_history WHERE user_id=$user_id");

while($row = $result->fetch_assoc()){
    echo "<p><b>You:</b> ".$row['user_msg']."</p>";
    echo "<p><b>Bot:</b> ".$row['bot_msg']."</p><hr>";
}
?>