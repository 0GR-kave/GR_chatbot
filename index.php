<!DOCTYPE html>
<html>
<head>
<title>Cohere AI Chatbot</title>
<link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>">

</head>
<body>
<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.html");
    exit;
}
?>
<div class="con">
    
<Div class="con1">
<h1>Cohere AI Chatbot</h1>
<a href="logout.php">Logout</a>
</Div>

<div id="chatbox"></div>
<DIV class="con3" >
<button id="lh" onclick="loadHistory()">Load Previous Chats</button>
<input type="text" id="userInput" placeholder="Type your message...">
<button id="s" onclick="sendMessage()">Send</button>
</DIV>
<div class="con4">

</div>
<script src="script.js"></script>
</div>
</body>
</html>
