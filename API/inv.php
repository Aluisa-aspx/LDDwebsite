<?php
session_start();
require_once('db file');
$username = $_GET['username'];
$sql = "SELECT item_id FROM UserItems WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $items = [];

    while ($row = $result->fetch_assoc()) {
        $item_id = $row['item_id'];
        $image_path = "/res/doges/$item_id" . "_compressed.png";

        $items[] = [
            'item_id' => $item_id,
            'image_path' => $image_path,
        ];
    }

    $responseData = [
        'username' => $username,
        'items' => $items,
        'total_items' => count($items),
        'success' => true,
    ];
} else {
    $responseData = [
        'success' => false,
        'error' => 'No items found in the inventory.',
    ];
}
header('Content-Type: application/json');
echo json_encode($responseData);
$conn->close();
?>