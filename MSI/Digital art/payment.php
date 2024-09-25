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
} else {
    echo "Error: User not found.";
    exit();
}

// Join add_to_cart and product tables to fetch cart details
$cart_query = $conn->prepare("
    SELECT a.product_id, a.quantity, p.product_price
    FROM add_to_cart a
    JOIN product p ON a.product_id = p.p_id
    WHERE a.user_id = ?
");
$cart_query->bind_param("i", $user_id);
$cart_query->execute();
$cart_result = $cart_query->get_result();

$total_amount = 0;

if ($cart_result->num_rows > 0) {
    // Loop through each item in the cart to calculate the total amount
    while ($cart_item = $cart_result->fetch_assoc()) {
        $total_amount += $cart_item['product_price'] * $cart_item['quantity'];
    }

    // Create a new order
    $order_status = 'pending';
    $order_date = date('Y-m-d H:i:s');

    // Insert into the orders table
    // Initially set cart_id as NULL here; it will be updated later after cart_summary insertion
    $order_query = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_date, status, cart_id) VALUES (?, ?, ?, ?, NULL)");
    $order_query->bind_param("idss", $user_id, $total_amount, $order_date, $order_status);

    if (!$order_query->execute()) {
        echo "Error inserting order: " . $order_query->error;
        exit();
    }

    // Fetch the last inserted order_id (which is temporary)
    $order_id = $conn->insert_id;

    // Insert into cart_summary table and get the cart_id
    $cart_summary_query = $conn->prepare("INSERT INTO cart_summary (user_id, created_on) VALUES (?, ?)");
    $created_on = date('Y-m-d H:i:s');
    $cart_summary_query->bind_param("is", $user_id, $created_on);
    $cart_summary_query->execute();
    
    // Get the last inserted cart_summary ID which will be used as cart_id
    $cart_id = $conn->insert_id;

    // Update the orders table with the cart_id
    $update_order_query = $conn->prepare("UPDATE orders SET cart_id = ? WHERE order_id = ?");
    $update_order_query->bind_param("ii", $cart_id, $order_id);
    $update_order_query->execute();

    // Loop through each item to insert into cart_items and update stock
    $cart_query->execute(); // Execute again to fetch results
    $cart_result = $cart_query->get_result(); // Re-fetch the result set

    while ($cart_item = $cart_result->fetch_assoc()) {
        $product_id = $cart_item['product_id'];
        $quantity = $cart_item['quantity'];
        $added_on = date('Y-m-d H:i:s'); // Get current timestamp for added_on

        // Insert into cart_items table
        $cart_items_query = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, added_on) VALUES (?, ?, ?, ?)");
        $cart_items_query->bind_param("iiis", $cart_id, $product_id, $quantity, $added_on);
        
        if (!$cart_items_query->execute()) {
            echo "Error inserting cart item: " . $cart_items_query->error;
            exit();
        }

        // Update product stock
        $product_stock_query = $conn->prepare("SELECT product_stock FROM product WHERE p_id = ?");
        $product_stock_query->bind_param("i", $product_id);
        $product_stock_query->execute();
        $product_stock_result = $product_stock_query->get_result();

        if ($product_stock_result->num_rows > 0) {
            $product_stock = $product_stock_result->fetch_assoc()['product_stock'];

            if ($product_stock >= $quantity) {
                $new_stock = $product_stock - $quantity;
                $update_stock_query = $conn->prepare("UPDATE product SET product_stock = ? WHERE p_id = ?");
                $update_stock_query->bind_param("ii", $new_stock, $product_id);
                $update_stock_query->execute();
            } else {
                echo "Error: Insufficient stock for product ID: $product_id.";
                exit();
            }
        } else {
            echo "Error: Product not found for ID: $product_id.";
            exit();
        }
    }

    // Delete items from the add_to_cart table
    $delete_cart_query = $conn->prepare("DELETE FROM add_to_cart WHERE user_id = ?");
    $delete_cart_query->bind_param("i", $user_id);
    $delete_cart_query->execute();

    // Proceed with the Khalti payment integration
    $converted_payment = (float)$total_amount * 100; // Convert to paisa
    $min_amount = 10 * 100; // Minimum payment in paisa
    $max_amount = 1000 * 100; // Maximum payment in paisa

    if ($converted_payment < $min_amount || $converted_payment > $max_amount) {
        echo "Error: Payment amount must be between Rs 10 and Rs 1000.";
        exit();
    }

    // Validate user details
    $errors = [];
    if ($converted_payment <= 0) {
        $errors[] = "Invalid payment amount.";
    }
    if (empty($name) || empty($email) || empty($phone)) {
        $errors[] = "Incomplete user details.";
    }
    if (!preg_match("/^\d{10}$/", $phone)) {
        $errors[] = "Invalid phone number.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (empty($errors)) {
        // Initialize Khalti payment
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                "return_url" => "http://localhost/College%20projects/MSI/Digital%20art/payment_success.php?order_id=" . $order_id,
                "website_url" => "http://localhost",
                "amount" => $converted_payment, // Use converted payment for Khalti
                "purchase_order_id" => $order_id,
                "purchase_order_name" => "Order Payment",
                "customer_info" => [
                    "name" => $name,
                    "email" => $email,
                    "phone" => $phone
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                'Authorization: Key e6acf73475c0480f93d9b6674b489c55', // Replace with your actual Khalti secret key
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_code == 200) {
            $response_data = json_decode($response, true);
            $payment_url = $response_data['payment_url'] ?? null;
            $transaction_id = $response_data['transaction_id'] ?? 'EXAMPLE_TRANSACTION_ID'; // Example transaction ID

            // Insert payment details into the payment table
            $payment_date = date('Y-m-d H:i:s');
            $pay_via = 'Khalti';
            $insert_payment_query = $conn->prepare('INSERT INTO payment (order_id, pay_via, transaction_id, amount, payment_date, initrader) VALUES (?, ?, ?, ?, ?, ?)');
            $insert_payment_query->bind_param('ississ', $order_id, $pay_via, $transaction_id, $total_amount, $payment_date, $name); // Use total_amount directly
            $insert_payment_query->execute();

            // Redirect to the Khalti payment URL
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
        echo implode("<br>", $errors);
        exit();
    }
} else {
    echo "Error: Cart is empty.";
}
?>
