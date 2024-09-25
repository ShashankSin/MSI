<?php
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug form inputs
    var_dump($_POST);
    var_dump($_FILES);

    // Get the form data
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_details = $_POST['product_details'];
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : null;  // Using category ID
    $product_stock = (int)$_POST['product_stock']; // Cast to integer if necessary
    $product_image = isset($_FILES['product_image']) ? $_FILES['product_image']['name'] : null;

    // Check for errors in form data
    if (!$category_id) {
        echo "Error: Category ID is not set.";
        exit();
    }

    if (!$product_image) {
        echo "Error: No image uploaded.";
        exit();
    }

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

    if (!$category_row) {
        echo "Error: Category not found for the given category ID.";
        exit();
    }

    $category_name = $category_row['category_name'];

    // Upload the image
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
        // Prepare the insert statement
        $insert_query = "INSERT INTO product (product_name, product_price, product_details, c_id, category_name, product_stock, product_image)
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("sdsssss", $product_name, $product_price, $product_details, $category_id, $category_name, $product_stock, $product_image);

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
