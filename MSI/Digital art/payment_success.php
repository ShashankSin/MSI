<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit();
}

// Fetch payment details using the order ID from the query parameter
$order_id = $_GET['order_id'] ?? null; // Retrieve order ID from the query string

if (!$order_id) {
    echo "Order ID is missing.";
    exit();
}

// Fetch payment details from the database
$payment_query = $conn->prepare("SELECT * FROM payment WHERE order_id = ?");
$payment_query->bind_param("i", $order_id);
$payment_query->execute();
$payment_result = $payment_query->get_result();

if ($payment_result->num_rows === 0) {
    echo "Payment details not found.";
    exit();
}

$payment = $payment_result->fetch_assoc();

// Update the order status to 'delivered'
$update_order_query = $conn->prepare("UPDATE orders SET status = 'delivered' WHERE order_id = ?");
$update_order_query->bind_param("i", $order_id);
$update_order_query->execute();

// Fetch order details for more information
$order_query = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$order_query->bind_param("i", $order_id);
$order_query->execute();
$order_result = $order_query->get_result();
$order = $order_result->fetch_assoc();

// Fetch product details for display
$product_query = $conn->prepare("SELECT product_name, product_price FROM product WHERE p_id = ?");
$product_query->bind_param("i", $order['product_id']);
$product_query->execute();
$product_result = $product_query->get_result();
$product = $product_result->fetch_assoc();

// Fetch user details
$user_query = $conn->prepare("SELECT user_name, user_email, user_phone FROM user WHERE u_id = ?");
$user_query->bind_param("i", $order['user_id']);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Payment Successful</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Bill Details</h5>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($payment['order_id']); ?></p>
                <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($payment['transaction_id']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment['pay_via']); ?></p>
                <p><strong>Amount Paid:</strong> ₹<?php echo htmlspecialchars($payment['amount'] / 100); ?></p>
                <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($payment['payment_date']); ?></p>
                
                <h5 class="mt-4">User Details</h5>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['user_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['user_email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['user_phone']); ?></p>
                
                <h5 class="mt-4">Product Details</h5>
                <p><strong>Product Name:</strong> <?php echo htmlspecialchars($product['product_name']); ?></p>
                <p><strong>Product Price:</strong> ₹<?php echo htmlspecialchars($product['product_price']); ?></p>
                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
                <p><strong>Total Amount:</strong> ₹<?php echo htmlspecialchars($order['price']); ?></p>
                
                <div class="text-center">
                    <a href="loggedin.php" class="btn btn-primary">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
