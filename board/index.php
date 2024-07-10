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

$itemsPerPage = 4;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$startIndex = ($currentPage - 1) * $itemsPerPage;
$sqlDistinctItems = "SELECT DISTINCT item_id FROM UserItems";
$resultDistinctItems = $conn->query($sqlDistinctItems);
$distinctItemIDs = [];

while ($row = $resultDistinctItems->fetch_assoc()) {
    $distinctItemIDs[] = $row['item_id'];
}
shuffle($distinctItemIDs);
$paginatedItemIDs = array_slice($distinctItemIDs, $startIndex, $itemsPerPage);
  $sqlLatestItem = "SELECT item_id FROM ItemTable ORDER BY item_id DESC LIMIT 1";
$resultLatestItem = $conn->query($sqlLatestItem);

if ($resultLatestItem->num_rows > 0) {
    $latestItemRow = $resultLatestItem->fetch_assoc();
    $latestItemID = $latestItemRow['item_id'];
} else {
    $latestItemID = 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>Dashboard</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
  
  <style>
.firstTD {font-family:'Comic Sans MS';background-color:#ccc;vertical-align:middle;
              width:70px;text-align:center;border-right:solid 1px #000;font-weight:bold;}
    </style>
</head>
<body>
    <div id="Tablet">
        <div id="Header">
<h2>DGS | Dasboard
    <span class="formatMenu">
        <a href="/user.php?username=<?php echo $username; ?>">Profile</a><span> | </span>
        <a href="/board/">Home</a><span> | </span>
        <a href="/login/logout.php">Logout</a>
    </span>            
</h2>
        </div>
<div class="div">
<p style="margin:2px 0;color:blue;">Greetings, <abbr title="This is your username!" style="cursor:help;"><?php echo $username; ?></abbr>...</p>
<p style="margin:2px 0"><b>Welcome to your DGS dashboard, here you can set things up!</b></p>
<table cellspacing="20" cellpadding="3">
<tr>
 <td colspan="3" style="text-align:center;">
<b>BROWSE:</b>
 </td>
</tr>
<tr>
<td>
    <a href="https://discord.com" target="_blank">Access Discord</a>
</td>
<td>
    <a href="/board/inventory.php?username=<?php echo $username; ?>">My invetory</a>
</td>
<td>
    <a href="/admi/create.php">Create/Assign (admin)</a>
</td>
</tr>
<tr>
 <td>
<a href="/admi/config.php">Config (admin)</a>
 </td>
 <td>
<a href="/browse/">Browse Doges</a>
 </td>
 <td>
<a href="/login/logout.php">Logout</a>
 </td>
</tr>
<tr>
 <td>
<a href="/item/?item_id=<?php echo $latestItemID; ?>">Latest Item</a>
 </td>
  <td>
  <a href="/APP/">App</a>
 </td>
    <td>
  <a href="/RSS/">RSS feed</a><a href="/RSS/"><img border="0" style="padding:0 0 0 5px;" src="rss.png" alt="rss icon" /></a>
 </td>
</tr>
<tr>
  <td>
 <a href="/Forum/">Forum</a>
   </td>
  <td>
<a href="https://nueva.serv00.net/dgs.php?id=wiki:welcome" target="_blank">Wiki</a>
   </td>
</tr>
</table>
 <?php
            echo '<table style="margin: 0 auto; border: solid 1px #000;border-collapse:collapse;" ><tr><td class="firstTD">Cool DGS Doges!</td>';
            foreach ($paginatedItemIDs as $item_id) {
                $image_path = "/item/thumb/?id={$item_id}";

                echo '<td style="padding: 4px 15px;">';
                echo '<a href="/item/?item_id=' . $item_id . '" title="';

                $itemInfoQuery = "SELECT name FROM ItemTable WHERE item_id = '$item_id'";
                $itemInfoResult = $conn->query($itemInfoQuery);

                if ($itemInfoResult->num_rows > 0) {
                    $itemInfoRow = $itemInfoResult->fetch_assoc();
                    $itemName = $itemInfoRow['name'];
                } else {
                    $itemName = "Item #$item_id";
                }

                echo $itemName . '">';
                echo '<img src="' . $image_path . '" alt="' . $itemName . '" style="border-radius:5px;" />';
                echo '</a>';
                echo '</td>';
            }

            echo '</tr></table>';
            ?>
<h2>
See your stats at your profile, access it by clicking the profile button!
</h2>
<h4 style="font-size:9px">
Remember to check your stuff here
</h4>
</div>
    </div>
    <div id="Footer"></div>
</body>
</html>

<?php
$conn->close();
?>