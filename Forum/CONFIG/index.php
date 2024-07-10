<?php
$servername = "server";
$username = "usr";
$password = "password";
$dbname = "dbname";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
     $conn->set_charset("utf8mb4");
}
global $mysqli;
$mysqli = $conn;
?>
