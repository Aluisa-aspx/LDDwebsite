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
    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $checkUserQuery = "SELECT * FROM ForumUsers WHERE ForumUsername = '$username'";
        $result = $mysqli->query($checkUserQuery);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['ForumPasswordHash'];

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['ForumUserId'] = $row['Id'];
                $_SESSION['ForumUsername'] = $username;

                header("Location: /Forum/");
                exit();
            } else {
                header("Location: /Forum/Login/?#ERROR1");
                exit();
            }
        } else {
            header("Location: /Forum/Login/?#ERROR2");
            exit();
        }
    } else {
        header("Location: /Forum/Login/?#ERROR3");
        exit();
    }
} else {
    header("Location: /Forum/Login/?#ERROR4");
    exit();
}
closeDBConnection();
?>
