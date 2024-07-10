<?php
function getUniqueItemIDs()
{
    $servername = "server";
    $username = "usr";
    $password = "password";
    $dbname = "dbname";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT DISTINCT item_id FROM UserItems";

    $result = $conn->query($sql);

    $itemIDs = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $itemIDs[] = $row['item_id'];
        }
    }
    $conn->close();

    return $itemIDs;
}

function organizeItemsIntoPages($items, $itemsPerPage = 5)
{
    $pages = [];
    $currentPage = [];

    foreach ($items as $item) {
        $currentPage[] = $item;

        if (count($currentPage) == $itemsPerPage) {
            $pages[] = $currentPage;
            $currentPage = [];
        }
    }

    if (!empty($currentPage)) {
        $pages[] = $currentPage;
    }

    return $pages;
}
?>
