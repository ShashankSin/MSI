<?php
include('db.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for confirmation or payment processing
$cart_query = $conn->prepare("SELECT * FROM add_to_cart WHERE user_id = ?");
$cart_query->bind_param("i", $user_id);
$cart_query->execute();
$cart_result = $cart_query->get_result();

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Payment Section</h2>

        <?php if ($cart_result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($cart_item = $cart_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cart_item['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($cart_item['quantity']); ?></td>
                            <td>$<?php echo htmlspecialchars($cart_item['price']); ?></td>
                            <td>$<?php echo htmlspecialchars($cart_item['price'] * $cart_item['quantity']); ?></td>
                        </tr>
                        <?php $total_price += $cart_item['price'] * $cart_item['quantity']; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h3>Total: $<?php echo $total_price; ?></h3>

            <!-- Mock Payment Integration or Proceed to Real Payment Gateway -->
            <form method="POST" action="complete_payment.php">
                <button type="submit" class="btn btn-primary">Complete Payment</button>
            </form>

        <?php else: ?>
            <p>No items found in your cart.</p>
        <?php endif; ?>
    </div>
</body>
</html>
