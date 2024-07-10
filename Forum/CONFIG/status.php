<?php
session_start();
require_once('db file');

if (isset($_SESSION['ForumUsername'])) {
    $forumUsername = $_SESSION['ForumUsername'];
    $onlineStatus = 1; 
    updateUserStatus($forumUsername, $onlineStatus);
}

function updateUserStatus($username, $onlineStatus) {
    global $mysqli;

    if ($mysqli) {
        $username = $mysqli->real_escape_string($username);
        $updateQuery = "UPDATE ForumUsers SET OnlineStatus = '$onlineStatus' WHERE ForumUsername = '$username'";
        $mysqli->query($updateQuery);
    } else {
    }
}

echo json_encode(['status' => 'success']);
?>
