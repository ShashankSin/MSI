<?php
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_details = $_POST['product_details'];
    $category_id = $_POST['category_id'];  // Using category ID
    $product_stock = $_POST['product_stock'];
    $product_image = $_FILES['product_image']['name'];
    
    // Directory to store the uploaded image
    $target_dir = "productPics/";
    $target_file = $target_dir . basename($product_image);

    // Fetch the category name based on the category ID
    $category_query = "SELECT category_name FROM Category WHERE c_id = '$category_id'";
    $category_result = $conn->query($category_query);
    $category_row = $category_result->fetch_assoc();
    $category_name = $category_row['category_name'];

    // Upload the image
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
        // Insert product data into the database
        $sql = "INSERT INTO Product (product_name, product_price, product_details, c_id, category_name, product_stock, product_image)
                VALUES ('$product_name', '$product_price', '$product_details', '$category_id', '$category_name', '$product_stock', '$product_image')";

        if ($conn->query($sql) === TRUE) {
            header('Location: Product.php');
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Close the database connection
$conn->close();
?>
