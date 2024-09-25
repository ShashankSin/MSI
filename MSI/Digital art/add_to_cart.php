<?php 
include('db.php'); 
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in first.'); window.location.href='login.php';</script>";
    exit();
}

// Handle add to cart
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
            echo "<script>alert('Not enough stock available.');</script>";
            exit();
        }

        // Calculate total price for the current product
        $total_price = $price * $quantity;

        // Check if the product is already in the cart
        $cart_query = "SELECT * FROM add_to_cart WHERE product_id = $product_id AND user_id = $user_id";
        $cart_result = $conn->query($cart_query);

        if ($cart_result->num_rows > 0) {
            $cart_item = $cart_result->fetch_assoc();
            $new_quantity = $cart_item['quantity'] + $quantity;

            if ($new_quantity > $stock) {
                echo "<script>alert('Not enough stock available.');</script>";
            } else {
                // Update cart with new quantity and total price
                $cart_id = $cart_item['cart_id'];
                $update_cart_query = "UPDATE add_to_cart SET quantity = $new_quantity, price = $total_price WHERE cart_id = $cart_id";
                
                if ($conn->query($update_cart_query) === TRUE) {
                    echo "<script>alert('Product updated successfully'); window.location.href='cart.php';</script>";
                } else {
                    echo "<script>alert('Error updating cart: " . $conn->error . "');</script>";
                }
            }
        } else {
            // Insert new product into cart with total price
            $add_cart_query = "INSERT INTO add_to_cart (product_id, image, quantity, stock, price, user_id) 
                               VALUES ($product_id, '$image', $quantity, $stock, $total_price, $user_id)";
            if ($conn->query($add_cart_query) === TRUE) {
                echo "<script>alert('Product added successfully'); window.location.href='loggedin.php';</script>";
            } else {
                echo "<script>alert('Error adding to cart: " . $conn->error . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Product not found.');</script>";
    }
}

// Handle update quantity in cart
if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $new_quantity = $_POST['quantity']; // Get the new quantity
    $user_id = $_SESSION['user_id'];

    // Fetch the product to check stock
    $cart_query = "SELECT product_id FROM add_to_cart WHERE cart_id = $cart_id AND user_id = $user_id";
    $cart_result = $conn->query($cart_query);

    if ($cart_result->num_rows > 0) {
        $cart_item = $cart_result->fetch_assoc();
        $product_id = $cart_item['product_id'];

        // Fetch product details to check stock
        $product_query = "SELECT product_stock, product_price FROM Product WHERE p_id = $product_id";
        $product_result = $conn->query($product_query);

        if ($product_result->num_rows > 0) {
            $product = $product_result->fetch_assoc();
            $stock = $product['product_stock'];

            if ($new_quantity > $stock) {
                echo "<script>alert('Not enough stock available.');</script>";
            } else {
                // Calculate new total price
                $new_total_price = $product['product_price'] * $new_quantity;
                // Update the quantity and price in the cart
                $update_cart_query = "UPDATE add_to_cart SET quantity = $new_quantity, price = $new_total_price WHERE cart_id = $cart_id";

                if ($conn->query($update_cart_query) === TRUE) {
                    echo "<script>alert('Quantity updated successfully'); window.location.href='cart.php';</script>";
                } else {
                    echo "<script>alert('Error updating quantity: " . $conn->error . "');</script>";
                }
            }
        }
    }
}

// Handle delete from cart
if (isset($_POST['delete_from_cart'])) {
    $cart_id = $_POST['cart_id']; // Get the cart_id to delete

    // Delete the product from the cart
    $delete_cart_query = "DELETE FROM add_to_cart WHERE cart_id = $cart_id AND user_id = " . $_SESSION['user_id'];
    
    if ($conn->query($delete_cart_query) === TRUE) {
        echo "<script>alert('Product removed from cart successfully'); window.location.href='cart.php';</script>";
    } else {
        echo "<script>alert('Error removing from cart: " . $conn->error . "');</script>";
    }
}
?>
