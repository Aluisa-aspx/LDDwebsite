<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('db file');
session_start();

global $mysqli;
$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['password'], $_POST['confirm_password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $checkUsernameQuery = "SELECT * FROM ForumUsers WHERE ForumUsername = '$username'";
        $result = $mysqli->query($checkUsernameQuery);

        if ($result->num_rows > 0) {
            header("Location: /Forum/Login/New.php#ERROR3");
            exit();
        }

        if ($password === $confirmPassword) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = "INSERT INTO ForumUsers (ForumUsername, ForumPasswordHash, LastSeen, OnlineStatus)
                            VALUES ('$username', '$passwordHash', NOW(), false)";

            if ($mysqli->query($insertQuery)) {
                $userId = $mysqli->insert_id;
                $_SESSION['ForumUserId'] = $userId;
                $_SESSION['ForumUsername'] = $username;

                header("Location: /Forum/");
                exit();
            } else {
                $errorMessage = urlencode($mysqli->error);
                header("Location: /Forum/Login/New.php?error=$errorMessage");
                exit();
            }
        } else {
            header("Location: /Forum/Login/New.php#ERROR1");
            exit();
        }
    } else {
        header("Location: /Forum/Login/New.php#ERROR2");
        exit();
    }
} else {
    header("Location: /Forum/Login/New.php#ERROR3");
    exit();
}
closeDBConnection();
?>
