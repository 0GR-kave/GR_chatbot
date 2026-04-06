<?php
$conn = new mysqli("localhost","root","","cohere");

$email = $_GET['email'];

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $otp = $_POST['otp'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email' AND otp='$otp'");

    if($res->num_rows > 0){
        header("Location: reset_password.php?email=$email");
    } else {
        echo "Invalid OTP";
    }
}
?>
<html>
    <head><style>
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

</style></head>

<body>
<form id="f" method="post">
<label for="">Enter OTP</label><br>
<input type="text" name="otp"><br><br>
<input type="submit" value="Verify">
</form>
    
</body>
</html>