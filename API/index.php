<?php
require_once('db file');

function getItemInfo($itemID){
global $conn;
    $sql = "SELECT *, DATE_FORMAT(created, '%Y-%m-%d %H:%i:%s') as formatted_created FROM ItemTable WHERE item_id = '$itemID'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        return $result->fetch_assoc();
    } else {return false;}}
function getItemOwners($itemID){
    global $conn;
    $sql = "SELECT username FROM UserItems WHERE item_id = '$itemID'";
    $result = $conn->query($sql);
    $owners = [];
    while ($row = $result->fetch_assoc()) {
        $owners[] = $row['username'];}
return $owners;}
$itemID = isset($_GET['item_id']) ? $_GET['item_id'] : 1;
$itemInfo = getItemInfo($itemID);
$owners = getItemOwners($itemID);
$itemValue = number_format($itemInfo['value'], 0, '', '');
$imagePath = "/usr/home/NilB/domains/nilb.serv00.net/public_html/item/thumb/?id={$itemID}";
$responseData = [
    'info' => [
        'itemName' => $itemInfo['name'],
        'itemID' => $itemID,
        'limited' => $itemInfo['limited'] == 1 ? 'Yes' : 'No',
        'stock' => $itemInfo['stock'] == 0 ? 'Unlimited' : $itemInfo['stock'],
        'value' => $itemValue,
        'rarity' => $itemInfo['rarity'],
        'created' => $itemInfo['created'],
        'image_path' => $imagePath, 
    ],
    'owners' => $owners,
    'success' => true,
];
header('Content-Type: application/json');
echo json_encode($responseData);
?>
