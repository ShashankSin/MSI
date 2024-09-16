<?php
// Database connection
include 'db.php'; // Ensure the path is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['category_name']) && isset($_FILES['category_image']) && $_FILES['category_image']['error'] == UPLOAD_ERR_OK) {
        $category_name = htmlspecialchars($_POST['category_name']);
        
        // Handle file upload securely
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
        }

        $image_name = basename($_FILES["category_image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image file type
        $valid_extensions = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $valid_extensions)) {
            // Move uploaded file to the target directory
            if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $target_file)) {
                // Prepare SQL query
                if ($conn) { // Check if the connection is established
                    $stmt = $conn->prepare("INSERT INTO category (category_name, category_image) VALUES (?, ?)");
                    $stmt->bind_param("ss", $category_name, $image_name);

                    if ($stmt->execute()) {
                        header('Location: Category.php');
                    } else {
                        echo "Error: " . $conn->error;
                    }
                    $stmt->close();
                } else {
                    echo "Database connection failed.";
                }
            } else {
                echo "Error uploading image.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        echo "All fields are required or there was an error with the file upload.";
    }

    // Close the database connection
    if (isset($conn)) {
        $conn->close();
    }
}
?>
