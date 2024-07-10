<?php
session_start();
require_once('CONFIG > User.php');
if (isset($_SESSION['ForumUsername'])) {
    $forumUsername = $_SESSION['ForumUsername'];
    updateUserStatus($forumUsername, false);
    session_destroy();
}
header("Location: /Forum/");
exit();
?>
