<?php
session_start();
require_once('db file');

if (isset($_SESSION['ForumUsername'])) {
    $forumUsername = $_SESSION['ForumUsername'];
    $onlineStatus = true;
    updateUserStatus($forumUsername, $onlineStatus);
    setLastSeen($forumUsername);
}

function isLoggedIn() {
    return isset($_SESSION['ForumUsername']);
}

function getUsername() {
    return isset($_SESSION['ForumUsername']) ? $_SESSION['ForumUsername'] : null;
}

function updateUserStatus($username, $onlineStatus) {
    global $mysqli;

    if ($mysqli) {
        $username = $mysqli->real_escape_string($username);
        $updateQuery = "UPDATE ForumUsers SET OnlineStatus = '$onlineStatus' WHERE ForumUsername = '$username'";
        $mysqli->query($updateQuery);
    }
}

function setLastSeen($username) {
    global $mysqli;

    if ($mysqli) {
        $username = $mysqli->real_escape_string($username);
        $updateQuery = "UPDATE ForumUsers SET LastSeen = NOW() WHERE ForumUsername = '$username'";
        $mysqli->query($updateQuery);
    }
}
?>
