<?php 
include('db.php'); 
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user with prepared statements
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
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Your Cart</h2>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Product ID</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch items in cart
                if ($cart_result->num_rows > 0) {
                    while ($cart_item = $cart_result->fetch_assoc()) {
                        $item_total = $cart_item['price'] * $cart_item['quantity'];
                        $total_price += $item_total; // Calculate total price
                        echo "<tr>
                            <td>" . htmlspecialchars($cart_item['product_id']) . "</td>
                            <td><img src='Admin/productPics/" . htmlspecialchars($cart_item['image']) . "' alt='Product Image' style='width: 100px; height: auto;'></td>
                            <td>
                                <form method='POST' action='add_to_cart.php' style='display:inline;'>
                                    <input type='hidden' name='cart_id' value='" . htmlspecialchars($cart_item['cart_id']) . "'>
                                    <input type='number' name='quantity' value='" . htmlspecialchars($cart_item['quantity']) . "' min='1' max='" . htmlspecialchars($cart_item['stock']) . "' required>
                                    <button type='submit' name='update_quantity' class='btn btn-warning'>Update</button>
                                </form>
                            </td>
                            <td>$" . number_format($item_total, 2) . "</td>
                            <td>
                                <form method='POST' action='add_to_cart.php' style='display:inline;'>
                                    <input type='hidden' name='cart_id' value='" . htmlspecialchars($cart_item['cart_id']) . "'>
                                    <button type='submit' name='delete_from_cart' class='btn btn-danger'>Remove</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No items in the cart.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <!-- Display total amount -->
        <div class="mb-3">
            <strong>Total Amount: $<?php echo number_format($total_price, 2); ?></strong>
        </div>

        <form action="payment.php" method="GET">
            <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($total_price); ?>">
            <button type="submit" class="btn btn-success">Proceed to Payment</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
