<?php
session_start();
require_once('db file');
if (!isset($_SESSION['username'])) {
    header('Location: /go/index.php');
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>Create</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
</head>
<body>
    <div id="Tablet">
        <div id="Header">
            <h2>
DGS | Create
<span class="formatMenu">
<a href="/user.php?username=<?php echo $username; ?>">Profile</a><span> | </span>
<a href="/board/">Home</a><span> | </span>
<a href="/login/logout.php">Logout</a>
</span>
            </h2>
        </div>
<form action="process_form.php" method="post" style="margin:8px;"  enctype="multipart/form-data">
    <label for="username"><b>Choose a username:</b></label>
 <?php
require_once('db file');

$sql = "SELECT username FROM users";
$result = $conn->query($sql);
if ($result === FALSE) {
    echo "Error: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $username = $row['username'];
            echo '<input type="radio" name="username" value="' . $username . '" required />' . $username . '<br/>';
        }
    } else {
        echo "No users found";
    }
}
$conn->close();
?>
<br/>
<br/>
    <label for="item_id">Enter Item ID:</label>
    <input type="text" id="item_id" name="item_id" required />
<br/><br/>
    <label for="image">Upload Icon (70x70 pixels):</label>
    <input type="file" name="image" accept="image/png, image/jpg, image/bmp, image/jpeg" />
<br/><br/>

    <button type="submit">Add Item to database</button>
</form>
    </div>
    <div id="Footer"></div>
</body>
</html>