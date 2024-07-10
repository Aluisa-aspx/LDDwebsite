<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once('db file');
require_once('GET FILE');
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
if ($username !== 'Aluisa') {
    echo "Denied, only an Admin can view this.";
    exit();
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$itemsPerPage = 34;
$startItemId = ($page - 1) * $itemsPerPage + 1;
$endItemId = $startItemId + $itemsPerPage - 1;

function getAllItems($startItemId, $endItemId) {
    global $conn;

    $sql = "SELECT * FROM ItemTable WHERE item_id BETWEEN $startItemId AND $endItemId";
    $result = $conn->query($sql);

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $itemID = $row['item_id'];
        $owners = getItemOwners($itemID);
        $items[] = [
            'itemInfo' => $row,
            'owners' => $owners,
        ];
    }

    return $items;
}

function getItemOwners($itemID) {
    global $conn;

    $sql = "SELECT username FROM UserItems WHERE item_id = '$itemID'";
    $result = $conn->query($sql);

    $owners = [];
    while ($row = $result->fetch_assoc()) {
        $owners[] = $row['username'];
    }

    return $owners;
}

$allItems = getAllItems($startItemId, $endItemId);

$tableHTML = '<table border="1" width="900px" cellpadding="2" cellspacing="0" style="font-size: 12px; border-collapse: collapse; font-family: Arial, Verdana, sans-serif;">';
$tableHTML .= '<tr><th style="background:#ccc;border:inset 4px #eee;color:blue;" width="40"><b>Item ID</b></th><th width="30" style="background:#ccc;border:inset 4px #eee;color:blue;"><b>Item Name</b></th><th width="40" style="background:#ccc;border:inset 4px #eee;color:blue;"><b>Owners</b></th></tr>';

foreach ($allItems as $itemData) {
    $itemInfo = $itemData['itemInfo'];
    $owners = $itemData['owners'];

    $tableHTML .= '<tr>';
    $tableHTML .= '<td style="background:#ccc;border:inset 4px #eee; width="40">' . $itemInfo['item_id'] . '</td>';
    $tableHTML .= '<td style="background:#ccc;border:inset 4px #eee; width="30">' . $itemInfo['name'] . '</td>';
    $tableHTML .= '<td style="background:#ccc;border:inset 4px #eee; width="40">' . implode(', ', $owners) . '</td>';
    $tableHTML .= '</tr>';
}

$tableHTML .= '</table>';

$htmlFile = '/usr/home/NilB/domains/nilb.serv00.net/public_html/html/temp.html';
file_put_contents($htmlFile, $tableHTML);

$outputImage = '/usr/home/NilB/domains/nilb.serv00.net/public_html/html/temp.png';
exec("wkhtmltoimage --width 800 --height 800 $htmlFile $outputImage");

header("Content-Type: image/png");
readfile($outputImage);

unlink($htmlFile);
unlink($outputImage);

exit();
?>
