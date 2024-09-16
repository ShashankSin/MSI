<?php 

$servername = "localhost"; 
$username = "root";
$password = "";
$database = "digitalart";


$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $con->connect_error);
}


?>