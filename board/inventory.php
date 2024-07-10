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
    <title>Inventory</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
</head>
<body>
    <div id="Tablet">
        <div id="Header">
            <h2>
                DGS | Inventory
                <span class="formatMenu">
<a href="/user.php?username=<?php echo $username; ?>">Profile</a><span> | </span>
<a href="/board/">Home</a><span> | </span>
<a href="/login/logout.php">Logout</a>
                </span>
            </h2>
        </div>
        <div class="div">
            <?php
session_start();
require_once('db file');

if (!isset($_SESSION['username'])) {
    header('Location: /index.php');
    exit();
}

$username = $_GET['username'];
$sql = "SELECT item_id FROM UserItems WHERE username = '$username'";
$result = $conn->query($sql);
$itemsPerPage = 20;

if ($result->num_rows > 0) {
    $totalPages = ceil($result->num_rows / $itemsPerPage);
    $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $startIndex = ($currentPage - 1) * $itemsPerPage;
    $sql = "SELECT item_id FROM UserItems WHERE username = '$username' LIMIT $startIndex, $itemsPerPage";
    $result = $conn->query($sql);

    echo "<h2>Inventory for $username</h2>";
    echo "<table cellpadding='5' id='InvT'>";

    $count = 0;

    while ($row = $result->fetch_assoc()) {
        if ($count % 5 == 0) {
            if ($count > 0) {
                echo "</tr>";
            }
            echo "<tr>";
        }

        $item_id = $row['item_id'];
        $image_path = "/res/doges/$item_id" . "_compressed.png";

        echo "<td>";
        echo "<a href=\"/item/?item_id=$item_id\">";
        echo "<div id='doge'><img src=\"$image_path\" alt=\"$item_id\" /></div>";
        echo "</a>";
        echo "</td>";

        $count++;
    }

    $remainingCells = 5 - ($count % 5);
    if ($remainingCells < 5) {
        for ($i = 0; $i < $remainingCells; $i++) {
            echo "<td></td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    echo "<div id='PageSelector' style='width:351.08px;background-color:#ccc;margin:0;padding:4px;'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?username=$username&page=$i'>$i</a>&nbsp;";
    }
    echo "</div>";
} else {
    echo "No items found in the inventory.";
}

$conn->close();
?>
        </div>
    </div>
    <div id="Footer"></div>
</body>
</html>