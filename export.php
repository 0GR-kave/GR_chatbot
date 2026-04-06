<?php
$conn = new mysqli("localhost","root","","cohere");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=chat_history.xls");

echo "User ID\tUser Message\tBot Reply\n";

$result = $conn->query("SELECT * FROM chat_history");

while($row = $result->fetch_assoc()){
    echo $row['user_id']."\t".$row['user_msg']."\t".$row['bot_msg']."\n";
}
?>