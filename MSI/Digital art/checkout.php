<?php 
include('db.php'); 
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$cart_query = "SELECT * FROM Add_to_cart WHERE user_id = $user_id";
$cart_result = $conn->query($cart_query);

if ($cart_result->num_rows > 0) {
    // Process each cart item (deduct stock, create order record, etc.)
    while($cart_item = $cart_result->fetch_assoc()) {
        $product_id = $cart_item['product_id'];
        $quantity = $cart_item['quantity'];

        // Fetch current stock
        $stock_query = "SELECT product_stock FROM Product WHERE p_id = $product_id";
        $stock_result = $conn->query($stock_query);
        $product = $stock_result->fetch_assoc();
        $current_stock = $product['product_stock'];

        // Check if there is enough stock
        if ($quantity <= $current_stock) {
            // Deduct stock
            $new_stock = $current_stock - $quantity;
            $update_stock_query = "UPDATE Product SET product_stock = $new_stock WHERE p_id = $product_id";
            $conn->query($update_stock_query);

            // Create order record
            $order_query = "INSERT INTO Orders (user_id, product_id, quantity, price) 
                            VALUES ($user_id, $product_id, $quantity, " . $cart_item['price'] * $quantity . ")";
            $conn->query($order_query);
        } else {
            echo "Not enough stock for product ID: $product_id. Checkout failed for this product.";
            continue;
        }
    }

    // Clear the cart by deleting items from the Add_to_cart table
    $clear_cart_query = "DELETE FROM Add_to_cart WHERE user_id = $user_id";
    if ($conn->query($clear_cart_query) === TRUE) {
        echo "Checkout successful and cart cleared!";
    } else {
        echo "Checkout successful, but failed to clear cart: " . $conn->error;
    }
} else {
    echo "Your cart is empty.";
}
?>
