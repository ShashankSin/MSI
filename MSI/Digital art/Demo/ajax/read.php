<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db.php';

$sql = "SELECT * FROM Product";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["error" => "Database query failed: " . mysqli_error($conn)]);
    exit;
}

$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($products);
?>
