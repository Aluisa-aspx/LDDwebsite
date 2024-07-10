<?php
session_start();
require_once('db file');
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
    <title>Logout...</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
</head>
<body>
    <div id="Tablet">
        <div id="Header">
            <h2>
                DGS | Log out of your account
                <span class="formatMenu">
                    <a href="/user.php?username=<?php echo $username; ?>">Profile</a><span> | </span>
                    <a href="/board/">Home</a><span> | </span>
                    <a href="/login/logout.php">Logout</a>
                </span>
            </h2>
        </div>
<h2>
Are you sure you want to <a href="process.php">LOGOUT</a>?
</h2>
<p><a href="process.php">Yes!</a></p>
<p><a href="/board/">No.</a></p>
    </div>
    <div id="Footer"></div>
</body>
</html>