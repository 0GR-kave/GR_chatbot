<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.html");
    exit;
}

$conn = new mysqli("localhost","root","","cohere");

// Totals
$total_users = $conn->query("SELECT COUNT(*) as t FROM users")->fetch_assoc()['t'];
$total_chats = $conn->query("SELECT COUNT(*) as t FROM chat_history")->fetch_assoc()['t'];

// Chats per user
$res = $conn->query("
    SELECT users.name, COUNT(chat_history.id) as total 
    FROM chat_history 
    JOIN users ON users.id = chat_history.user_id
    GROUP BY users.name
");

$names = [];
$counts = [];

while($r = $res->fetch_assoc()){
    $names[] = $r['name'];
    $counts[] = $r['total'];
}

// Daily chats
$daily = $conn->query("
    SELECT DATE(created_at) as day, COUNT(*) as total
    FROM chat_history
    GROUP BY DATE(created_at)
");

$days = [];
$dayCounts = [];

while($d = $daily->fetch_assoc()){
    $days[] = $d['day'];
    $dayCounts[] = $d['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Advanced Admin Dashboard</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
    font-family: Arial;
    background:url(admin_d_b.jpg);
    background-repeat:no-repeat;
    background-size:cover;
    margin: 0;
}

.header {
    background: #2c3e50;
    color: white;
    padding: 15px;
    text-align: center;
}
#hd{
    display: flex;
    justify-content: space-between;
}
#hd a{
    text-decoration:none;
    background: #5f6469;
    padding: 5px;
    border-radius:10px;
    color:white;
    box-shadow: 0 0 3px #ccc;

}
#hd a:hover{
    background: white;
    color:#5f6469;
     transition-duration:500ms ;
}

.container {
    padding: 20px;
}

.cards {
    display: flex;
    justify-content: space-around;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    width: 200px;
    text-align: center;
    box-shadow: 0 0 10px #ccc;
}

.search {
    margin: 20px;
    text-align: center;
}
#searchUser{
    margin-bottom:10px;
}
input {
    padding: 8px;
    width: 200px;
}
#as{
    
}
table {
    width: 80%;
    margin: auto;
    background: white;
    border-collapse: collapse;
    border-radius:20px;
    border: none;
    box-shadow: 0 0 10px #313131;
}

td, th {
    padding: 10px;
    border: 1px solid #dad6d6;
}
#toggleBtn{
    margin-top:10px;
    padding: 5px;
    border-radius:8px;
    border-width:1px;
}

canvas {
    margin: 30px auto;
    display: block;
    
}

</style>

</head>

<body>

<div class="header">
    <h1>Admin Dashboard</h1>
    <div id="hd">
    <a href="export.php">Download Chat Data</a>
    <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

<div class="cards">
    <div class="card">
        <h3>Total Users</h3>
        <p><?php echo $total_users; ?></p>
    </div>

    <div class="card">
        <h3>Total Chats</h3>
        <p><?php echo $total_chats; ?></p>
    </div>
</div>


<center>
<h2>All Users</h2>
<input type="text" id="searchUser" placeholder="Search user..." onkeyup="searchUser()">
</center>
<table id="as" border="1" width="80%" align="center">
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Action</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM users");

while($row = $res->fetch_assoc()){
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['email']}</td>
        <td><a  href='delete_user.php?id={$row['id']}'>Delete</a></td>
    </tr>";
}
?>
</table>
<p id="noData" style="display:none; text-align:center;">No user found</p>





<center>
<h2>All Chats</h2></center>
<table id="chatTable" border="1" width="80%" align="center">
<tr>
<th>User ID</th>
<th>User Message</th>
<th>Bot Reply</th>
<th>Action</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM chat_history");

while($row = $res->fetch_assoc()){
    echo "<tr>
        <td>{$row['user_id']}</td>
        <td>{$row['user_msg']}</td>
        <td>{$row['bot_msg']}</td>
        <td><a href='delete_chat.php?id={$row['id']}'>Delete</a></td>
    </tr>";
}
?>
</table>
<center>
<button onclick="toggleChats()" id="toggleBtn">Show More</button>
</center>



<canvas id="barChart"></canvas>
<canvas id="pieChart"></canvas>
<canvas id="lineChart"></canvas>

<br>


</div>

<script>
let names = <?php echo json_encode($names); ?>;
let counts = <?php echo json_encode($counts); ?>;
let days = <?php echo json_encode($days); ?>;
let dayCounts = <?php echo json_encode($dayCounts); ?>;

// Bar chart
new Chart(document.getElementById("barChart"), {
    type: 'bar',
    data: {
        labels: names,
        datasets: [{ label: "Chats per User", data: counts }]
    }
});

// Pie chart
new Chart(document.getElementById("pieChart"), {
    type: 'pie',
    data: {
        labels: names,
        datasets: [{ data: counts }]
    }
});

// Line chart (daily activity)
new Chart(document.getElementById("lineChart"), {
    type: 'line',
    data: {
        labels: days,
        datasets: [{ label: "Daily Chats", data: dayCounts }]
    }
});

// Search function
function searchUser() {
    let input = document.getElementById("searchUser").value.toLowerCase();
    let rows = document.getElementById("as").rows;
    let found = false;

    for (let i = 1; i < rows.length; i++) {
        let name = rows[i].cells[1].innerText.toLowerCase();

        if (name.includes(input)) {
            rows[i].style.display = "";
            found = true;
        } else {
            rows[i].style.display = "none";
        }
    }

    document.getElementById("noData").style.display = found ? "none" : "block";
}

let expanded = false;

window.onload = function () {
    let rows = document.getElementById("chatTable").rows;

    for (let i = 3; i < rows.length; i++) {
        rows[i].style.display = "none";
    }
};

function toggleChats() {
    let rows = document.getElementById("chatTable").rows;

    if (!expanded) {
        // Show all
        for (let i = 1; i < rows.length; i++) {
            rows[i].style.display = "";
        }
        document.getElementById("toggleBtn").innerText = "Show Less";
        expanded = true;

    } else {
        // Show only first 2
        for (let i = 3; i < rows.length; i++) {
            rows[i].style.display = "none";
        }
        document.getElementById("toggleBtn").innerText = "Show More";
        expanded = false;
    }
}
</script>

</body>
</html>