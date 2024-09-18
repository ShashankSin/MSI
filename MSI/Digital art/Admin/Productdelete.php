<?php
include 'db.php'; // Include your database connection

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Check if the product has associated orders
    $order_check_query = "SELECT COUNT(*) AS order_count FROM orders WHERE product_id = ?";
    $order_check_stmt = $conn->prepare($order_check_query);
    $order_check_stmt->bind_param("i", $product_id);
    $order_check_stmt->execute();
    $order_result = $order_check_stmt->get_result();
    $order_row = $order_result->fetch_assoc();

    // Fetch the product image path to delete the image from the server
    $query = "SELECT product_image FROM product WHERE p_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $product_image = $row['product_image'];

    if ($product_image) {
        $image_path = 'productPics/' . $product_image;

        // Delete the image file if it exists
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Delete the product record from the database
    $delete_query = "DELETE FROM product WHERE p_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully.'); window.location.href='view_product.php';</script>";
    } else {
        echo "<script>alert('Error deleting product.'); window.location.href='view_product.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid product ID.'); window.location.href='view_product.php';</script>";
}

$conn->close();
?>
