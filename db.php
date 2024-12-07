<?php
$servername = 'localhost';
$name = 'root';
$Password = '';
$dbname = "terma";

$conn = new mysqli($servername, $name, $Password, $dbname);
// $conn->set_charset("utf-8");

if ($conn->connect_error) {
    die("error: " . $conn->connect_error);
}

echo "";
?>
