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
    <title>Config</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
</head>
<body>
    <div id="Tablet">
        <div id="Header">
            <h2>
                DGS | Configuration
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
            if (!isset($_SESSION['username']) || !in_array($_SESSION['username'], ['Aluisa', 'Victor'])) {
                echo "Access denied. You do not have permission to access this page.";
                exit();} ?>
            <form action="save.php" method="post">
                <label for="item_id">Item ID:</label><br/>
<?php
include '/usr/home/NilB/domains/nilb.serv00.net/public_html/Get.php';

$itemsPerPage = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$allItemIDs = getUniqueItemIDs();
$totalPages = ceil(count($allItemIDs) / $itemsPerPage);
$startIndex = ($page - 1) * $itemsPerPage;
$itemIDs = array_slice($allItemIDs, $startIndex, $itemsPerPage);

foreach ($itemIDs as $id) {
    echo "<input type='radio' name='item_id' value='$id' required><i> Item $id<i/><br/>";
}

echo "<div style='width:100%;background-color:#ccc;padding:2px;'>";
for ($i = 1; $i <= $totalPages; $i++) {
    echo "<a href='?page=$i'>$i</a> ";
}
echo "</div>";
?>





                <label for="name">Item Name:</label>
                <input type="text" name="name" required /><br />
                <br />
                <label for="limited">Limited:</label>
                <input type="radio" name="limited" value="yes" /> Yes
                <input type="radio" name="limited" value="no" /> No<br />
                <br />
                <label for="stock">Stock:</label>
                <input type="number" name="stock" required /><br />
                <br />
                <label for="value">Item Value:</label>
                <input type="number" name="value" required /><br />
                <br />
                <label for="rarity">Rarity:</label>
                <select name="rarity" required>
                    <option value="Common: F">Common: F</option>
                    <option value="Uncommon: D">Uncommon: D</option>
                    <option value="Rare: C">Rare: C</option>
                    <option value="Super-Rare: B">Super-Rare: B</option>
                    <option value="Ultra-Rare: A">Ultra-Rare: A</option>
                    <option value="Lengendary: S">Lengendary: S</option>
                    <option value="Mithycal: X">Mithycal: X</option>
                    <option value="Special: E">Special: E</option>
                </select><br />
                <br />
                <input type="submit" value="Save" />
                <br />
            </form>
        </div>
    </div>
    <div id="Footer"></div>
</body>
</html>