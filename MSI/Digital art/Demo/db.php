<?php
$servername = "localhost";
$username = "root"; // replace with your DB username
$password = ""; // replace with your DB password
$dbname = "crud_app"; // replace with your DB name

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
