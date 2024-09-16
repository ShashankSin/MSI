<?php
include '../db.php';

if(isset($_POST['product_name'], $_POST['product_price'])) {
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];

    $sql = "INSERT INTO Product (product_name, product_price) VALUES ('$name', '$price')";
    if (mysqli_query($conn, $sql)) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
