<?php
session_start();
include('db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_query = $conn->prepare("SELECT user_name, user_email, user_phone FROM user WHERE u_id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
    $name = $user['user_name'];
    $email = $user['user_email'];
    $phone = $user['user_phone'];
    $initrader = $user['user_name']; // Get the user name for initrader
} else {
    echo "Error: User not found.";
    exit();
}

// Fetch the cart details
$cart_query = $conn->prepare("SELECT product_id, quantity, price FROM add_to_cart WHERE user_id = ?");
$cart_query->bind_param("i", $user_id);
$cart_query->execute();
$cart_result = $cart_query->get_result();

if ($cart_result->num_rows > 0) {
    $cart = $cart_result->fetch_assoc();
    $product_id = $cart['product_id'];
    $quantity = $cart['quantity'];
    $amount = $cart['price'] * $quantity; // Total amount
} else {
    echo "Error: Cart is empty or product not found.";
    exit();
}

// Insert the order
$order_status = 'pending';
$order_date = date('Y-m-d H:i:s');

$order_query = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, price, status, order_date) VALUES (?, ?, ?, ?, ?, ?)");
$order_query->bind_param("iidsis", $user_id, $product_id, $quantity, $amount, $order_status, $order_date);

if ($order_query->execute()) {
    $order_id = $conn->insert_id; // Get the generated order_id

    // Check product stock before decrementing
    $product_stock_query = $conn->prepare("SELECT product_stock FROM product WHERE p_id = ?");
    $product_stock_query->bind_param("i", $product_id);
    $product_stock_query->execute();
    $product_stock_result = $product_stock_query->get_result();

    if ($product_stock_result->num_rows > 0) {
        $product_stock = $product_stock_result->fetch_assoc()['product_stock'];

        // Ensure there is enough stock available
        if ($product_stock >= $quantity) {
            // Decrement the stock
            $new_stock = $product_stock - $quantity;
            $update_stock_query = $conn->prepare("UPDATE product SET product_stock = ? WHERE p_id = ?");
            $update_stock_query->bind_param("ii", $new_stock, $product_id);
            $update_stock_query->execute();

            // Proceed with the Khalti payment integration
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $converted_payment = (float)$amount * 100; // Convert to paisa for Khalti

                // Check if the payment amount is within the allowed range
                $min_amount = 10 * 100; // Minimum amount in paisa
                $max_amount = 1000 * 100; // Maximum amount in paisa

                if ($converted_payment < $min_amount || $converted_payment > $max_amount) {
                    echo "Error: Payment amount must be between Rs 10 and Rs 1000.";
                    exit();
                }

                // Log amount values for debugging
                echo "Amount in Rs: " . ($amount) . "<br>";
                echo "Converted amount in paisa: " . $converted_payment . "<br>";

                // Check for errors before initiating payment
                $errors = [];
                if ($converted_payment <= 0) {
                    $errors[] = "Invalid payment amount.";
                }
                if (empty($name) || empty($email) || empty($phone)) {
                    $errors[] = "User details are incomplete.";
                }
                if (!preg_match("/^\d{10}$/", $phone)) {
                    $errors[] = "Invalid phone number.";
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Invalid email address.";
                }

                if (empty($errors)) {
                    // cURL request to Khalti
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => json_encode([
                            "return_url" => "http://localhost/payment_success.php",
                            "website_url" => "http://localhost",
                            "amount" => $converted_payment,
                            "purchase_order_id" => $order_id,
                            "purchase_order_name" => "Order Payment",
                            "customer_info" => [
                                "name" => $name,
                                "email" => $email,
                                "phone" => $phone
                            ]
                        ]),
                        CURLOPT_HTTPHEADER => [
                            'Authorization: Key e6acf73475c0480f93d9b6674b489c55',
                            'Content-Type: application/json',
                        ],
                    ]);

                    $response = curl_exec($curl);
                    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    curl_close($curl);

                    if ($http_code == 200) {
                        $response_data = json_decode($response, true);
                        $payment_url = $response_data['payment_url'];
                        $transaction_id = $response_data['transaction_id'] ?? 'demo_transaction_id'; // Fallback to demo if undefined

                        // Insert payment details into the payment table
                        $payment_date = date('Y-m-d H:i:s');
                        $pay_via = 'Khalti';
                        $insert_payment_query = $conn->prepare('INSERT INTO payment (order_id, pay_via, transaction_id, amount, initrader, payment_date) VALUES (?, ?, ?, ?, ?, ?)');
                        $insert_payment_query->bind_param('ississ', $order_id, $pay_via, $transaction_id, $converted_payment, $initrader, $payment_date);
                        $insert_payment_query->execute();
                        $insert_payment_query->close();

                        // Update order status to 'delivered'
                        $update_order_query = $conn->prepare("UPDATE orders SET status = 'delivered' WHERE order_id = ?");
                        $update_order_query->bind_param("i", $order_id);
                        $update_order_query->execute();

                        // Redirect to the Khalti payment URL
                        $_SESSION['payment_status'] = "Payment initiated successfully.";
                        header('Location: ' . $payment_url);
                        exit();
                    } else {
                        // Update order status to 'failed' if payment initiation fails
                        $update_order_query = $conn->prepare("UPDATE orders SET status = 'failed' WHERE order_id = ?");
                        $update_order_query->bind_param("i", $order_id);
                        $update_order_query->execute();

                        echo "Error: Payment initiation failed. HTTP Code: $http_code. Response: " . htmlspecialchars($response);
                    }
                } else {
                    $_SESSION['status'] = implode("<br>", $errors);
                    exit();
                }
            }
        } else {
            echo "Error: Not enough stock available.";
            exit();
        }
    } else {
        echo "Error: Product not found.";
        exit();
    }
} else {
    echo "Error creating order: " . $conn->error;
}
?>
