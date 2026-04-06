<?php

session_start();

if(!isset($_SESSION['user_id'])){
    echo "Please login first";
    exit;
}

$user_id = $_SESSION['user_id'];

// 🔹 Database connection (change DB name if needed)
$conn = new mysqli("localhost", "root", "", "cohere"); // or your DB name

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// 🔹 Cohere API Key
$api_key =getenv("API_KEY") ;

// 🔹 Get user input
$user_input = $_POST['message'];

// 🔹 Detect if user wants code
if (preg_match("/code|html|css|javascript|php|program/i", $user_input)) {
    $message = "Return ONLY code without explanation:\n" . $user_input;
} else {
    $message = $user_input;
}

// 🔹 Prepare API request
$data = [
    "model" => "command-a-03-2025",
    "messages" => [
        [
            "role" => "user",
            "content" => $message
        ]
    ]
];

// 🔹 Initialize cURL
$ch = curl_init("https://api.cohere.ai/v2/chat");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $api_key"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// 🔹 Execute request
$response = curl_exec($ch);

// 🔹 Error handling
if (curl_errno($ch)) {
    echo "Error: " . curl_error($ch);
    exit;
}

curl_close($ch);

// 🔹 Decode response
$result = json_decode($response, true);

// 🔹 Extract AI response
$bot_reply = "";

if (isset($result['message']['content'][0]['text'])) {
    $bot_reply = $result['message']['content'][0]['text'];
} elseif (isset($result['message'])) {
    $bot_reply = $result['message']; // error or plain message
} elseif (isset($result['text'])) {
    $bot_reply = $result['text'];
} else {
    $bot_reply = "No response";
}

// 🔹 Save chat history to DB
$stmt = $conn->prepare("INSERT INTO chat_history (user_id, user_msg, bot_msg) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $user_input, $bot_reply);

$stmt->execute();

// 🔹 Output as plain text (IMPORTANT)
header("Content-Type: text/plain");

echo $bot_reply;
?>