<?php
include 'db file';

function generateItemElement($item_id, $owner, $name, $limited, $stock, $value, $rarity, $image_path) {
    $itemElement = '<item>';
    $itemElement .= '<title>' . htmlspecialchars($item_id) . ' | ' . htmlspecialchars($owner) . '</title>';
    $itemElement .= '<description>' . htmlspecialchars($owner) . ' got an item ' . htmlspecialchars($name) . '</description>';
    $itemElement .= '<link>https://nilb.serv00.net/item/?item_id=' . htmlspecialchars($item_id) . '</link>';
  $itemElement .= '<img><source1>https://nilb.serv00.net/item/thumb/?item_id=' . htmlspecialchars($item_id) . '</source1>
    <source2>' . htmlspecialchars($image_path) . '</source2></img>';
    $itemElement .= '<pubDate>' . date('D, d M Y H:i:s O') . '</pubDate>';
    $itemElement .= '</item>';
    return $itemElement;
}

$sqlFirstForm = "SELECT * FROM UserItems ORDER BY item_id DESC LIMIT 10";
$resultFirstForm = $conn->query($sqlFirstForm);

$itemsFirstForm = '';
while ($rowFirstForm = $resultFirstForm->fetch_assoc()) {
    $itemsFirstForm .= generateItemElement(
        $rowFirstForm['item_id'],
        $rowFirstForm['username'],
        '',
        '',
        '',
        '',
        '',
        $rowFirstForm['image_path']
    );
}

$sqlSecondForm = "SELECT * FROM ItemTable ORDER BY created DESC LIMIT 10";
$resultSecondForm = $conn->query($sqlSecondForm);

$itemsSecondForm = '';
while ($rowSecondForm = $resultSecondForm->fetch_assoc()) {
    $itemsSecondForm .= generateItemElement(
        $rowSecondForm['item_id'],
       $rowSecondForm['name'],
        '',
        $rowSecondForm['limited'],
        $rowSecondForm['stock'],
        $rowSecondForm['value'],
        $rowSecondForm['rarity'],
        ''
    );
}

$conn->close();

$template = '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>RSS feed</title>
    <link>https://nilb.serv00.net/RSS/index.php</link>
    <description>Rich Site Summary</description>
    <language>en-us</language>
    {items}
  </channel>
</rss>';

$template = str_replace('{items}', $itemsFirstForm . $itemsSecondForm, $template);

header('Content-Type: application/xml; charset=utf-8');
echo $template;
?>
