<?php
include 'db file';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $name = $_POST['name'];
    $limited = ($_POST['limited'] === 'yes') ? 'TRUE' : 'FALSE';
    $stock = $_POST['stock'];
    $value = $_POST['value'];
    $rarity = $_POST['rarity'];

    $sql = "INSERT INTO ItemTable (item_id, name, limited, stock, value, rarity, created) 
            VALUES ('$item_id', '$name', $limited, '$stock', '$value', '$rarity', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "Item added successfully";
    } else {
        echo "Error adding item: " . $conn->error;
    }
}

$conn->close();
?>
