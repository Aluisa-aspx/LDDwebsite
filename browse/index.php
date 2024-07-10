<?php
session_start();
require_once('db file');

if (!isset($_SESSION['username'])) {
    header('Location: /go/index.php');
    exit();
}
$searchTerm = '';
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

$itemsPerPage = 7;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$searchCondition = !empty($searchTerm) ? " AND ItemTable.name LIKE '%$searchTerm%'" : '';

} else {
    $searchCondition = '';
}

$startIndex = ($currentPage - 1) * $itemsPerPage;

$sqlDistinctItems = "SELECT DISTINCT UserItems.item_id FROM UserItems
                     JOIN ItemTable ON UserItems.item_id = ItemTable.item_id
                     WHERE 1 $searchCondition";

$resultDistinctItems = $conn->query($sqlDistinctItems);
$distinctItemIDs = [];

while ($row = $resultDistinctItems->fetch_assoc()) {
    $distinctItemIDs[] = $row['item_id'];
}

shuffle($distinctItemIDs);
$paginatedItemIDs = array_slice($distinctItemIDs, $startIndex, $itemsPerPage);
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'random';
$sortOrder = ($sortOption == 'random') ? 'ORDER BY RAND()' : 'ORDER BY UserItems.item_id';

$sqlDistinctItems = "SELECT DISTINCT UserItems.item_id FROM UserItems
                     JOIN ItemTable ON UserItems.item_id = ItemTable.item_id
                     WHERE 1 $searchCondition $sortOrder";
 $paginationLinks = '';
$totalPages = ceil(count($distinctItemIDs) / $itemsPerPage);

if ($totalPages > 1) {
    for ($i = 1; $i <= $totalPages; $i++) {
        $paginationLinks .= '<a href="?page=' . $i . '&sort=' . $sortOption . '">' . $i . '</a>&nbsp;';
    }
}
$paginatedAndSortedItemIDs = [];

if (!empty($distinctItemIDs)) {
    if ($sortOption === 'random') {
        shuffle($distinctItemIDs);
    } else {
        sort($distinctItemIDs);
    }
    $paginatedAndSortedItemIDs = array_slice($distinctItemIDs, $startIndex, $itemsPerPage);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>Browse</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
    <script src="/filter.js" type="text/javascript"></script>
</head>
<body>
    <div id="Tablet">
        <div id="Header">
            <h2>
                DGS | Browsing...
                <span class="formatMenu">
                    <a href="/user.php?username=<?php echo $username; ?>">Profile</a><span> | </span>
                    <a href="/board/">Home</a><span> | </span>
                    <a href="/login/logout.php">Logout</a>
                </span>
            </h2>
        </div>
        <div class="div">
            <form method="GET" action="" style="text-align:center;">
                <label for="search">Search: </label>
                <input type="text" id="search" name="search" placeholder="Enter Item's name..." value="<?php echo htmlspecialchars($searchTerm); ?>" />
                <button type="submit" style="background-color:#eee;border: solid 1px #000; padding:2px 7px;cursor: pointer;">Submit</button>
<span style="float:right;">
 <label for="sort">Sort By:</label>
<select id="sort" name="sort" style="background-color:#ffea00;">
  <option style="background-color:#ffea00;font-weight:bold;" value="random" <?php echo ($_GET['sort'] == 'random') ? 'selected' : ''; ?>>Sort Random</option>
    <option style="background-color:#ffea00;font-weight:bold;" value="id" <?php echo ($_GET['sort'] == 'id') ? 'selected' : ''; ?>>Sort by ID</option>
</select> 
</span>
            </form>

<?php if (!empty($paginatedAndSortedItemIDs)) : ?>
                <table style="margin: 0 auto;">
 <?php foreach ($paginatedAndSortedItemIDs as $item_id) : ?>
                        <?php
                        $image_path = "/item/thumb/?id={$item_id}";
                        $itemInfoQuery = "SELECT name FROM ItemTable WHERE item_id = '$item_id'";
                        $itemInfoResult = $conn->query($itemInfoQuery);

                        if ($itemInfoResult->num_rows > 0) {
                            $itemInfoRow = $itemInfoResult->fetch_assoc();
                            $itemName = $itemInfoRow['name'];
                        } else {
                            $itemName = "Item #$item_id";
                        }
                        ?>
                        <tr>
                            <td style="text-align: center; width: 70px;">
                                <img src="<?php echo $image_path; ?>" style="width:50px;height:50px" />
                            </td>
                            <td style="width: 200px;">
                                <a href="/item/?item_id=<?php echo $item_id; ?>" title="<?php echo $itemName; ?>">
                                    <?php echo $itemName; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
<div id="PageSelector" style="background-color: #ccc; margin: 0; padding: 4px;">
    <?php
    echo '<span>Page ' . $currentPage . ' of ' . $totalPages . '</span>';
    if ($totalPages > 1) {
        echo '<span style="float:right;">Go to page: ';
        if ($currentPage > 1) {
            echo '<a href="?page=' . ($currentPage - 1) . '&sort=' . $sortOption . '">Previous</a>, ';}
        $ellipsisDisplayed = false;
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                echo '<strong>' . $i . '</strong>';
                if ($i < $totalPages && $i < $currentPage + 2) {
                    echo ' ,';}} elseif (
                $i == 1 ||
                $i == $totalPages ||
                ($i >= $currentPage - 2 && $i <= $currentPage + 2)) {
                if ($i != $currentPage - 1) {
                    echo '<a href="?page=' . $i . '&sort=' . $sortOption . '">' . $i . '</a> ,';}
                if ($i < $totalPages && $i < $currentPage + 2) {
                    echo '';}
                $ellipsisDisplayed = false;} elseif (!$ellipsisDisplayed) {
                echo ' ... ';
                $ellipsisDisplayed = true;}}
        if ($currentPage < $totalPages - 1) {
            echo ' <a href="?page=' . ($currentPage + 1) . '&sort=' . $sortOption . '">Next</a>';}
        echo '</span>';}
    ?>
</div>
        </div>
    </div>
    <div id="Footer"></div>
</body>
</html>
