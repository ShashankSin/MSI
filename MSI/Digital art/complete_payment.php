<?php
include('db.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch username from the user table
$user_query = $conn->prepare("SELECT user_name FROM user WHERE u_id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
    $username = $user['user_name'];
} else {
    echo "Error: User not found.";
    exit();
}

// Fetch the product_id and amount from the add_to_cart table for the logged-in user
$cart_query = $conn->prepare("SELECT product_id, quantity, price FROM add_to_cart WHERE user_id = ?");
$cart_query->bind_param("i", $user_id);
$cart_query->execute();
$cart_result = $cart_query->get_result();

if ($cart_result->num_rows > 0) {
    $cart = $cart_result->fetch_assoc();
    $product_id = $cart['product_id'];
    $quantity = $cart['quantity'];
    $amount = $cart['price'] * $quantity; // Calculate the total amount based on quantity
} else {
    echo "Error: Cart is empty or product not found.";
    exit();
}

// Check if the product exists in the product table before inserting into orders
$product_check_query = $conn->prepare("SELECT p_id FROM product WHERE p_id = ?");
$product_check_query->bind_param("i", $product_id);
$product_check_query->execute();
$product_check_result = $product_check_query->get_result();

if ($product_check_result->num_rows > 0) {
    // Product exists, proceed with inserting the order
    $order_status = 'pending';
    $order_date = date('Y-m-d H:i:s');

    $order_query = $conn->prepare("INSERT INTO orders (user_id, product_id, price, status, order_date) VALUES (?, ?, ?, ?, ?)");
    $order_query->bind_param("iisss", $user_id, $product_id, $amount, $order_status, $order_date);

    if ($order_query->execute()) {
        // Get the generated order_id for the new order
        $order_id = $conn->insert_id;

        // Now proceed with the payment details
        $pay_via = 'Khalti'; // This can be 'Credit Card', 'PayPal', etc.
        $transaction_id = 'TX12345678'; // Payment gateway transaction ID
        $Initrader = $username; // The username initiating the transaction
        // Get the current date and time
        $payment_date = date('Y-m-d H:i:s');

        // Insert the payment details into the payment table, including the payment date
        $payment_query = $conn->prepare("INSERT INTO payment (order_id, pay_via, transaction_id, amount, Initrader, payment_date) VALUES (?, ?, ?, ?, ?, ?)");
        $payment_query->bind_param("issdss", $order_id, $pay_via, $transaction_id, $amount, $Initrader, $payment_date);

        if ($payment_query->execute()) {
            // Update the order status to 'delivered' after successful payment
            $update_order_status_query = $conn->prepare("UPDATE orders SET status = 'delivered' WHERE order_id = ?");
            $update_order_status_query->bind_param("i", $order_id);
            $update_order_status_query->execute();

            // Clear the cart after payment is successful
            $clear_cart_query = $conn->prepare("DELETE FROM add_to_cart WHERE user_id = ?");
            $clear_cart_query->bind_param("i", $user_id);
            $clear_cart_query->execute();

            echo "Payment successful! Your cart has been cleared, order status updated to delivered, and payment details have been recorded.";
        } else {
            echo "Error processing payment: " . $conn->error;
        }
    } else {
        echo "Error creating order: " . $conn->error;
    }
} else {
    echo "Error: Product does not exist.";
}

?>
