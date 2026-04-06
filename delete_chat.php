<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.html");
    exit;
}

$conn = new mysqli("localhost","root","","cohere");

$id = $_GET['id'];

$conn->query("DELETE FROM chat_history WHERE id=$id");

header("Location: view_chats.php");
?>