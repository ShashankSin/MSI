<?php 
include('db.php'); 
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit();
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id']; 

    // Fetch product details
    $product_query = "SELECT * FROM Product WHERE p_id = $product_id";
    $product_result = $conn->query($product_query);

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
        $image = $product['product_image'];
        $price = $product['product_price'];
        $stock = $product['product_stock'];

        // Check if there is enough stock
        if ($quantity > $stock) {
            echo "Not enough stock available.";
            exit();
        }

        // Check if the product is already in the cart
        $cart_query = "SELECT * FROM add_to_cart WHERE product_id = $product_id AND user_id = $user_id";
        $cart_result = $conn->query($cart_query);

        if ($cart_result->num_rows > 0) {
            $cart_item = $cart_result->fetch_assoc();
            $new_quantity = $cart_item['quantity'] + $quantity;

            if ($new_quantity > $stock) {
                echo "Not enough stock available.";
            } else {
                // Ensure the 'id' exists in the Add_to_cart table
                $cart_id = $cart_item['cart_id']; // Ensure the 'id' field is present in the Add_to_cart table schema
                $update_cart_query = "UPDATE add_to_cart SET quantity = $new_quantity WHERE cart_id = $cart_id";
                
                if ($conn->query($update_cart_query) === TRUE) {
                    echo "<script>alert('Product added Successfully'); window.location.href='cart.php';</script>";
                } else {
                    echo "Error updating cart: " . $conn->error;
                }
            }
        } else {
            $add_cart_query = "INSERT INTO add_to_cart (product_id, image, quantity, stock, price, user_id) 
                               VALUES ($product_id, '$image', $quantity, $stock, $price, $user_id)";
            if ($conn->query($add_cart_query) === TRUE) {
                echo "<script>alert('Product added Successfully'); window.location.href='loggedin.php';</script>";
            } else {
                echo "Error adding to cart: " . $conn->error;
            }
        }
    } else {
        echo "Product not found.";
    }
}
?>
