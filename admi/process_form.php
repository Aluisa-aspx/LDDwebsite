<?php
session_start();
require_once('db file');

if (!isset($_SESSION['username'])) {
    header('Location: /board/index.php');
    exit();
}

$allowedUsernames = ['Aluisa', 'Victor'];
$loggedInUsername = $_SESSION['username'];

if (!in_array($loggedInUsername, $allowedUsernames)) {
    echo "Access denied. You do not have permission to access this page.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $item_id = $_POST['item_id'];
    $checkExistingItemQuery = "SELECT * FROM UserItems WHERE item_id = '$item_id'";
    $result = $conn->query($checkExistingItemQuery);

    if ($result->num_rows > 0) {
        $existingItem = $result->fetch_assoc();
        if (!empty($existingItem['image_path'])) {
            $updateExistingItemQuery = "UPDATE UserItems SET image_path = '-' WHERE item_id = '$item_id'";
            $conn->query($updateExistingItemQuery);
        }
    }

    $targetDirectory = '/usr/home/NilB/domains/nilb.serv00.net/public_html/res/doges/';
    $targetFile = $targetDirectory . $item_id . '.' . strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES['image']['tmp_name']);
    
    if ($check === false) {
        echo "file is not an image.";
        $uploadOk = 0;
    }

    if (file_exists($targetFile)) {
        echo "sry, file already exists.";
        $uploadOk = 0;
    }

    if ($_FILES['image']['size'] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, PNG, BMP, and GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk) {
        $uploadedFileName = $_FILES['image']['name'];
        $uploadedFilePath = $targetDirectory . $uploadedFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFilePath)) {
            $compressedImage = compressAndResizeImage($uploadedFilePath, $item_id, $imageFileType, $targetDirectory, 70, 70);

            unlink($uploadedFilePath);

            $sqlInsertItem = "INSERT INTO UserItems (username, item_id, image_path) VALUES ('$username', '$item_id', '$compressedImage')";

            if ($conn->query($sqlInsertItem) === TRUE) {
                echo "Item added successfully";
            } else {
                echo "<b>Error adding item:</b> " . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading thy file.";
        }
    }
}

$conn->close();

function compressAndResizeImage($targetFile, $itemId, $imageFileType, $targetDirectory, $width, $height) {
    list($originalWidth, $originalHeight) = getimagesize($targetFile);

    $newImage = imagecreatetruecolor($width, $height);

    if ($imageFileType == 'jpg' || $imageFileType == 'jpeg') {
        $originalImage = imagecreatefromjpeg($targetFile);
    } elseif ($imageFileType == 'png') {
        $originalImage = imagecreatefrompng($targetFile);
    } elseif ($imageFileType == 'gif') {
        $originalImage = imagecreatefromgif($targetFile);
    } elseif ($imageFileType == 'bmp') {
        $originalImage = imagecreatefromwbmp($targetFile);
    } else {
        echo "Unsupported image format.";
        exit();
    }

    imagecopyresampled($newImage, $originalImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

    $compressedImage = $targetDirectory . $itemId . '_compressed.' . $imageFileType;

    if ($imageFileType == 'jpg' || $imageFileType == 'jpeg') {
        imagejpeg($newImage, $compressedImage);
    } elseif ($imageFileType == 'png') {
        imagepng($newImage, $compressedImage);
    } elseif ($imageFileType == 'gif') {
        imagegif($newImage, $compressedImage);
    } elseif ($imageFileType == 'bmp') {
        imagewbmp($newImage, $compressedImage);
    }

    imagedestroy($newImage);
    imagedestroy($originalImage);

    return $compressedImage;
}
?>
