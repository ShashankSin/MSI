<?php
include 'db.php'; // Include the database connection

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Get and sanitize the ID
    
    // Fetch the existing data
    $stmt = $conn->prepare("SELECT * FROM category WHERE c_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $category_name = $row['category_name'];
        $category_image = $row['category_image'];
    } else {
        echo "No record found with the provided ID.";
        exit();
    }
} else {
    echo "Invalid request. 'id' parameter is missing.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'];
    
    // Handle file upload if a new image is provided
    if (!empty($_FILES['category_image']['name'])) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["category_image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $valid_extensions)) {
            if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $target_file)) {
                // Update record with new image
                $stmt = $conn->prepare("UPDATE category SET category_name = ?, category_image = ? WHERE c_id = ?");
                $stmt->bind_param("ssi", $category_name, $image_name, $id);
                if ($stmt->execute()) {
                    // Remove old image if it exists
                    $old_image_path = $target_dir . $category_image;
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                    header('Location: Category.php');
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo "Error uploading the image.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        // Update record without changing the image
        $stmt = $conn->prepare("UPDATE category SET category_name = ? WHERE c_id = ?");
        $stmt->bind_param("si", $category_name, $id);
        if ($stmt->execute()) {
            header('Location: Category.php');
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    $stmt->close();
}

$conn->close();
?>

<!-- 
-->

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Digital Art</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <div class="navigation">
        <ul>
            <li><a href="#"><span class="title">Digital Art</span></a></li>
            <li><a href="index.php"><span class="icon"><ion-icon name="home-outline"></ion-icon></span><span class="title">Dashboard</span></a></li>
            <li><a href="Category.php"><span class="icon"><ion-icon name="people-outline"></ion-icon></span><span class="title">Category</span></a></li>
            <li><a href="Product.php"><span class="icon"><ion-icon name="help-outline"></ion-icon></span><span class="title">Product</span></a></li>
            <li><a href="view_product.php"><span class="icon"><ion-icon name="help-outline"></ion-icon></span><span class="title">View Product</span></a></li>
            <li><a href="Sale.php"><span class="icon"><ion-icon name="settings-outline"></ion-icon></span><span class="title">Sales</span></a></li>
            <li><a href="Report.php"><span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span><span class="title">Report</span></a></li>
            <li><a href="User.php"><span class="icon"><ion-icon name="log-out-outline"></ion-icon></span><span class="title">User</span></a></li>
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

        
        <!-- Category Section -->
        <div class="container Category mt-5">
            <h2>Edit Category</h2>
            <form action="Categoryedit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="category_name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo htmlspecialchars($category_name); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="category_image" class="form-label">Category Image (Optional)</label>
                    <input type="file" class="form-control" id="category_image" name="category_image" accept="image/*">
                    <?php if ($category_image): ?>
                        <img src="uploads/<?php echo htmlspecialchars($category_image); ?>" alt="Current Image" style="width: 100px; height: auto; margin-top: 10px;">
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div> 
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js" nomodule></script>
</body>
</html>

