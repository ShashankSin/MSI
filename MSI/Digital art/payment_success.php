<?php
session_start();
include('db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo "Invalid order ID.";
    exit();
}

// Fetch the cart summary and order details
$receipt_query = $conn->prepare("
    SELECT 
        p.product_name, 
        ci.quantity, 
        p.product_price, 
        (ci.quantity * p.product_price) AS total_item_price, 
        o.total_amount, 
        o.order_date, 
        o.status, 
        pm.pay_via, 
        pm.transaction_id, 
        pm.payment_date 
    FROM 
        cart_items ci
    JOIN 
        product p ON ci.product_id = p.p_id
    JOIN 
        cart_summary cs ON ci.cart_id = cs.cart_id
    JOIN 
        orders o ON cs.cart_id = o.cart_id
    JOIN 
        payment pm ON o.order_id = pm.order_id
    WHERE 
        o.order_id = ?
");
$receipt_query->bind_param("i", $order_id);
$receipt_query->execute();
$receipt_result = $receipt_query->get_result();

if ($receipt_result->num_rows == 0) {
    echo "No data found for the given order.";
    exit();
}

// Fetch data for the receipt
$receipt_data = [];
while ($row = $receipt_result->fetch_assoc()) {
    $receipt_data[] = $row;
}

if (empty($receipt_data)) {
    echo "Error: No receipt data found.";
    exit();
}

// Update the order status to 'delivered'
$update_status_query = $conn->prepare("UPDATE orders SET status = 'delivered' WHERE order_id = ?");
$update_status_query->bind_param("i", $order_id);
$update_status_query->execute();

// Get the order information (assuming all rows have the same order info)
$order_date = $receipt_data[0]['order_date'];
$total_amount = $receipt_data[0]['total_amount'];
$order_status = $receipt_data[0]['status'];
$pay_via = $receipt_data[0]['pay_via'];
$transaction_id = $receipt_data[0]['transaction_id'];
$payment_date = $receipt_data[0]['payment_date'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Payment Receipt</h2>

    <!-- Order Information -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Order Information</h5>
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>
            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order_date); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order_status); ?></p>
            <p><strong>Total Amount:</strong> Rs. <?php echo number_format($total_amount, 2); ?></p>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Payment Information</h5>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($pay_via); ?></p>
            <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($transaction_id); ?></p>
            <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($payment_date); ?></p>
        </div>
    </div>

    <!-- Cart Items -->
    <h5 class="mb-3">Items Purchased:</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($receipt_data as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td>Rs. <?php echo number_format($item['product_price'], 2); ?></td>
                <td>Rs. <?php echo number_format($item['total_item_price'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Total Amount -->
    <div class="text-end mb-4">
        <h4>Total Amount Paid: Rs. <?php echo number_format($total_amount, 2); ?></h4>
    </div>

    <!-- Back to Home Button -->
    <div class="d-flex justify-content-center">
        <a href="loggedin.php" class="btn btn-primary">Back to Home</a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
