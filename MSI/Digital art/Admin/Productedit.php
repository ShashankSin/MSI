<?php
include 'db.php'; // Include your database connection

// Check if the product ID is set in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch the current product data
    $query = "SELECT * FROM product WHERE p_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "<script>alert('Database error: " . $conn->error . "');</script>";
        exit();
    }

    $stmt->bind_param("i", $product_id);
    if (!$stmt->execute()) {
        echo "<script>alert('Execute failed: " . $stmt->error . "');</script>";
        exit();
    }

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "<script>alert('Product not found.'); window.location.href='view_product.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid product ID.'); window.location.href='view_product.php';</script>";
    exit();
}

// Handle form submission to update the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_details = $_POST['product_details'];
    $category_id = $_POST['category_id'];
    $product_stock = $_POST['product_stock'];
    $product_image = $_FILES['product_image']['name'];

    // Validate product price and stock
    if (!is_numeric($product_price) || !is_numeric($product_stock)) {
        echo "<script>alert('Please enter valid numbers for price and stock.');</script>";
        exit();
    }

    // Handle image upload if a new image is uploaded
    if ($product_image) {
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = pathinfo($product_image, PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            echo "<script>alert('Invalid image type. Only JPEG and PNG are allowed.');</script>";
            $product_image = $product['product_image']; // Keep the old image
        } else {
            $target_dir = "productPics/";
            $target_file = $target_dir . basename($product_image);

            // Delete old image if a new one is uploaded
            $old_image = $product['product_image'];
            if ($old_image && file_exists($target_dir . $old_image)) {
                if (!unlink($target_dir . $old_image)) {
                    echo "<script>alert('Error deleting old image.');</script>";
                }
            }

            // Upload the new image
            if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
                $product_image = $product['product_image']; // Keep the old image if upload fails
            }
        }
    } else {
        $product_image = $product['product_image']; // Keep the old image if no new one is uploaded
    }

    // Fetch the category name based on category ID
    $category_query = "SELECT category_name FROM category WHERE c_id = ?";
    $category_stmt = $conn->prepare($category_query);
    if (!$category_stmt) {
        echo "<script>alert('Database error: " . $conn->error . "');</script>";
        exit();
    }
    $category_stmt->bind_param("i", $category_id);
    if (!$category_stmt->execute()) {
        echo "<script>alert('Execute failed: " . $category_stmt->error . "');</script>";
        exit();
    }

    $category_result = $category_stmt->get_result();
    $category_row = $category_result->fetch_assoc();
    $category_name = $category_row['category_name'];

    // Update the product data in the database
    $update_query = "UPDATE product SET product_name = ?, product_price = ?, product_details = ?, c_id = ?, category_name = ?, product_stock = ?, product_image = ? WHERE p_id = ?";
    $update_stmt = $conn->prepare($update_query);
    if (!$update_stmt) {
        echo "<script>alert('Database error: " . $conn->error . "');</script>";
        exit();
    }

    $update_stmt->bind_param("ssssissi", $product_name, $product_price, $product_details, $category_id, $category_name, $product_stock, $product_image, $product_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Product updated successfully.'); window.location.href='view_product.php';</script>";
    } else {
        echo "<script>alert('Error: " . $update_stmt->error . "');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard | Digital Art</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div>
        <!-- Navigation -->
        <div class="navigation">
            <ul>
                <li><a href="#"><span class="title">Digital Art</span></a></li>
                <li><a href="index.php"><span class="icon"><ion-icon name="home-outline"></ion-icon></span><span class="title">Dashboard</span></a></li>
                <li><a href="Category.php"><span class="icon"><ion-icon name="people-outline"></ion-icon></span><span class="title">Category</span></a></li>
                <li><a href="Product.php"><span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span><span class="title">Vendor</span></a></li>
                <li><a href="#"><span class="icon"><ion-icon name="help-outline"></ion-icon></span><span class="title">Product</span></a></li>
                <li><a href="#"><span class="icon"><ion-icon name="help-outline"></ion-icon></span><span class="title">View Product</span></a></li>
                <li><a href="#"><span class="icon"><ion-icon name="settings-outline"></ion-icon></span><span class="title">Sales</span></a></li>
                <li><a href="#"><span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span><span class="title">Report</span></a></li>
                <li><a href="#"><span class="icon"><ion-icon name="log-out-outline"></ion-icon></span><span class="title">User</span></a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <div class="search">
                    <label>
                        <input type="text" placeholder="Search here">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div>
                <div class="user">
                    <img src="assets/imgs/customer01.jpg" alt="">
                </div>
            </div>

            <div class="container mt-5 Category">
                <h2>Edit Product</h2>
                <form action="Productedit.php?id=<?php echo htmlspecialchars($product_id); ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="product_price" class="form-label">Product Price</label>
                        <input type="number" class="form-control" id="product_price" name="product_price" value="<?php echo htmlspecialchars($product['product_price']); ?>" required min="0" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="product_details" class="form-label">Product Details</label>
                        <textarea class="form-control" id="product_details" name="product_details" required><?php echo htmlspecialchars($product['product_details']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select a category</option>
                            <?php
                            // Fetch categories for the dropdown
                            $category_query = "SELECT c_id, category_name FROM category";
                            $category_result = $conn->query($category_query);

                            // Check if the query was successful
                            if ($category_result) {
                                // Loop through the categories and create options
                                while ($category = $category_result->fetch_assoc()) {
                                    $selected = ($category['c_id'] == $product['c_id']) ? 'selected' : '';
                                    echo "<option value='{$category['c_id']}' $selected>{$category['category_name']}</option>";
                                }
                            } else {
                                echo "<option value=''>Error fetching categories</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="product_stock" class="form-label">Product Stock</label>
                        <input type="number" class="form-control" id="product_stock" name="product_stock" value="<?php echo htmlspecialchars($product['product_stock']); ?>" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="product_image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="product_image" name="product_image">
                        <img src="productPics/<?php echo htmlspecialchars($product['product_image']); ?>" alt="Product Image" class="img-thumbnail mt-2" width="150">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Ionicons -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
