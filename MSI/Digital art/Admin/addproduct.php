<?php
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_details = $_POST['product_details'];
    $category_id = $_POST['category_id'];  // Using category ID
    $product_stock = (int)$_POST['product_stock']; // Cast to integer if necessary
    $product_image = $_FILES['product_image']['name'];
    
    // Directory to store the uploaded image
    $target_dir = "productPics/";
    $target_file = $target_dir . basename($product_image);

    // Fetch the category name based on the category ID
    $category_query = "SELECT category_name FROM Category WHERE c_id = ?";
    $stmt = $conn->prepare($category_query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $category_result = $stmt->get_result();
    $category_row = $category_result->fetch_assoc();
    $category_name = $category_row['category_name'];

    // Upload the image
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
        // Prepare the insert statement
        $insert_query = "INSERT INTO product (product_name, product_price, product_details, c_id, category_name, product_stock, product_image)
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        // Ensure the types match the parameters:
        // product_name (string), product_price (double), product_details (string),
        // c_id (integer), category_name (string), product_stock (integer), product_image (string)
        $insert_stmt->bind_param("sdssisi", $product_name, $product_price, $product_details, $category_id, $category_name, $product_stock, $product_image);

        // Execute the insert statement
        if ($insert_stmt->execute()) {
            header('Location: Product.php');
        } else {
            echo "Error: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
