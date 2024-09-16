<?php
include '../db.php';

if(isset($_POST['p_id'], $_POST['product_name'], $_POST['product_price'])) {
    $id = $_POST['p_id'];
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];

    $sql = "UPDATE Product SET product_name='$name', product_price='$price' WHERE p_id='$id'";
    if (mysqli_query($conn, $sql)) {
        echo "Product updated successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
