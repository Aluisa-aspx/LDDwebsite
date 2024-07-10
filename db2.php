<?php
$servername = "server";
$username = "usr";
$password = "password";
$dbname = "dbname";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
