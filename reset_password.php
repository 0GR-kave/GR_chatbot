<?php
$conn = new mysqli("localhost","root","","cohere");

if(!isset($_GET['email'])){
    echo "Invalid request";
    exit;
}

$email = $_GET['email'];

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $new_password = $_POST['password'];

    if(empty($new_password)){
        echo "Password cannot be empty";
        exit;
    }

    // Hash password
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password
    $stmt = $conn->prepare("UPDATE users SET password=?, otp=NULL WHERE email=?");
    $stmt->bind_param("ss", $hashed, $email);

    if($stmt->execute()){
        echo "Password updated successfully!";
        header("Location: login.html");
    } else {
        echo "Error updating password";
    }
}
?>
<html>
    <head>
        <style>
    body{
   display: flex;
    justify-content: center;
}
#f{
   display: flex;
              flex-direction: column;
              text-align: center;
              align-items: center;
              justify-content: center;
              background:rgb(109,147,170);
              width: 250px;
                height: 250px;
                margin-top: 150px;
                border-radius: 10PX;
                box-shadow: 10px 10px 5px 0px rgba(0, 0, 0, .8);
}
label{
    font-size:20px;
}
</style>
    </head>
    <body>
    <form id="f" method="post">
<label for="">New password</label><br>
<input type="password" name="password" required><br><br>
<input type="submit" value="Reset Password">
</form>        
    </body>
</html>
