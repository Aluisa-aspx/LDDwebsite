<?php
session_start();
require_once('db.php directory here');
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}
$username = $_SESSION['username'];

$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $coins = $row['coins'];
} else {
    echo "User not found";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>User profile</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
</head>
<body>
    <div id="Tablet">
        <div id="Header">
<h2>DGS | User
    <span class="formatMenu">
        <a href="/user.php?username=<?php echo $username; ?>">Profile</a><span> | </span>
        <a href="/board/">Home</a><span> | </span>
        <a href="/login/logout.php">Logout</a>
    </span></h2>
        </div>
<p style="text-align:center;margin:4px 0;">
Welcome to <span><?php echo $username; ?></span>'s profile:
</p>
<p style="margin:4px;">Username: <b><?php echo $username; ?></b></p>
        <p style="margin:4px;">Dogecoins: <b><?php echo $coins; ?></b></p>
       <p> <a href="/board/inventory.php?username=<?php echo $username; ?>">Inventory...</a></a>
    </div>
    <div id="Footer"></div>
</body>
</html>