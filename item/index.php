<?php
session_start();
require_once('db file');
if (!isset($_SESSION['username'])) {
    header('Location: /go/index.php');
    exit();
}
$username = $_SESSION['username'];
 $allowedUsernames = ['Aluisa', 'Victor'];
$isAllowedUser = in_array($username, $allowedUsernames);
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $coins = $row['coins'];
} else {
    echo "User not found";
    exit();
}
  function deleteItem($itemID)
{
    global $conn;
    $deleteUserItems = "DELETE FROM UserItems WHERE item_id = '$itemID'";
    $conn->query($deleteUserItems);
    $deleteItemTable = "DELETE FROM ItemTable WHERE item_id = '$itemID'";
    $conn->query($deleteItemTable);
    $imagePath = $_SERVER['DOCUMENT_ROOT'] . "/usr/home/NilB/domains/nilb.serv00.net/public_html/res/doges/{$itemID}_compressed.png";
if (file_exists($imagePath)) {
    unlink($imagePath);
}
}

$itemID = isset($_GET['item_id']) ? $_GET['item_id'] : 1;
if (isset($_POST['delete']) && $_POST['delete'] == 1 && $isAllowedUser) {
    deleteItem($itemID);
    header("Location: /board/index.php"); 
    exit();
}
function getItemInfo($itemID)
{
    global $conn;

    $sql = "SELECT *, DATE_FORMAT(created, '%Y-%m-%d %H:%i:%s') as formatted_created FROM ItemTable WHERE item_id = '$itemID'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}
function getItemOwners($itemID)
{
    global $conn;

    $sql = "SELECT username FROM UserItems WHERE item_id = '$itemID'";
    $result = $conn->query($sql);

    $owners = [];
    while ($row = $result->fetch_assoc()) {
        $owners[] = $row['username'];
    }

    return $owners;
}
$itemID = isset($_GET['item_id']) ? $_GET['item_id'] : 1;

$itemInfo = getItemInfo($itemID);

if ($itemInfo) {
    $itemName = $itemInfo['name'];
    $limited = ($itemInfo['limited'] == 1) ? 'Yes' : 'No';
    $stock = $itemInfo['stock'];
    $value = $itemInfo['value'];
    $rarity = $itemInfo['rarity'];
  $created = $itemInfo['created'];
  $created = $itemInfo['created'];
$formattedCreated = date('D M j, g:i A', strtotime($created));
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="/all.css" type="text/css" />
        <title>Item</title>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
        <script src="/res/js/load.js" type="text/javascript"></script>
      <style>#Own td:hover {text-decoration:underline;cursor:default;}
      .item_name {font-size: 18px;display:inline-block; max-width: 193.33px;text-overflow: ellipsis;
    white-space: nowrap;font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;cursor:help;
    -moz-white-space: nowrap;
    overflow: hidden; vertical-align: middle;}</style>
    </head>
    <body>
        <div id="Tablet">
            <div id="Header">
                <h2>
                    DGS | <span class="item_name" title="<?php echo $itemName; ?>"><?php echo $itemName; ?></span>
                    <span class="formatMenu">
                        <a href="/user.php?username=<?php echo $username; ?>">Profile</a><span> | </span>
                        <a href="/board/">Home</a><span> | </span>
                        <a href="/login/logout.php">Logout</a>
                    </span>
                </h2>
            </div>
            <div class="div">
                <div style="text-align:center; width:100%;">
                    <img src="/item/thumb/?id=<?php echo $itemID; ?>" style="height:80px; width:80px; border: solid 2px steelblue;" alt="<?php echo $itemName; ?>" />
                </div>
                <table cellpadding="8">
                    <tr>
                        <td><b>Item:</b> <?php echo $itemName; ?></td>
                        <td><b>Item ID:</b> <?php echo $itemID; ?></td>
                        <td><b>Limited:</b> <?php echo $limited; ?></td>
                    </tr>
                    <tr>
                        <td><b>Stock:</b> <?php echo ($stock == 0) ? 'Unlimited' : $stock; ?></td>
<td><b>Value:</b>  <?php echo ($value == 0) ? 'None' : number_format($value, 0); ?></td>
                        <td><b>Rarity:</b> <?php echo $rarity; ?></td>
                    </tr>
                   <tr>
                     <td colspan="3" style="text-align:center;"><b>Created on:</b> <?php echo $formattedCreated; ?></td>
                     </tr>
                </table>
                <h3>Owners of this item:</h3>
                <table style="border:solid 1px #000;border-collapse:collapse;width:100px;font-weight:bold;font-family: Arial, Verdana, sans-serif;
              background-color:steelblue;color:#fff;" cellpadding="8" id="Own">
                    <?php
    $owners = getItemOwners($itemID);
    foreach ($owners as $owner) {
        if ($owner !== 'username to exclude here') {
            echo "<tr><td title='$owner'>$owner</td></tr>";
        }
    }
    ?>
                </table>
              
<?php if ($isAllowedUser) { ?>
  <div style="text-align:center;margin:10px 0">
                        <form method="post">
                          <h3>Delete item from database, image cannot be deleted.</h3>
                            <input type="hidden" name="delete" value="1">
                            <input type="submit" value="Delete Item">
                        </form>
                    </div>
<?php } ?>
            </div>
        </div>
        <div id="Footer"></div>

</body>
</html>
<?php } ?>