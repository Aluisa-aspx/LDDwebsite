<?php
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $imagePath = 'rootpath/res/doges/' . $id . '_compressed.png';
    
    if(file_exists($imagePath)) {
        $image = imagecreatefrompng($imagePath);
    } else {
        $imagePath = 'rootpath/res/doges/default.png';
        $image = imagecreatefrompng($imagePath);
    }

    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
} else {
    echo '<h2 style="color:red;font-family: Arial, Verdana, sans-serif;">Please provide the ID in parameter or insert "?id={id}" parameter</h2>';
}
?>
