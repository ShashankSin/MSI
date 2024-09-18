<?php 
include('db.php'); 
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit();
}

$user_id = $_SESSION['user_id'];

$cart_query = "SELECT * FROM add_to_cart WHERE user_id = $user_id";
$cart_result = $conn->query($cart_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
</head>
<body>
    <h1>Your Cart</h1>
    <div class="cart-list">
        <?php
        if ($cart_result->num_rows > 0) {
            while ($item = $cart_result->fetch_assoc()) {
                echo '
                <div class="cart-item">
                    <img src="Admin/productPics/' . htmlspecialchars($item["image"]) . '" alt="Product Image">
                    <h4>Product ID: ' . htmlspecialchars($item["product_id"]) . '</h4>
                    <p>Quantity: ' . htmlspecialchars($item["quantity"]) . '</p>
                    <p>Price: $' . htmlspecialchars($item["price"]) . '</p>
                    <p>Total: $' . ($item["price"] * $item["quantity"]) . '</p>
                </div>
                ';
            }
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
    </div>
</body>
</html>
