<?php
// backend.php

// Database connection settings (replace with your own)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digitalart";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get product counts per category
$sql = "SELECT category.category_name, COUNT(product.p_id) AS product_count
        FROM category
        LEFT JOIN product ON category.c_id = product.c_id
        GROUP BY category.category_name";

$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    // Fetch data and format it for JSON output
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return data as JSON
echo json_encode($data);

// Close connection
$conn->close();
?>
