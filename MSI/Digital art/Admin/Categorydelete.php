<?php
include 'db.php'; // Include the database connection

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Get and sanitize the ID
    
    // Prepare SQL to select the image name based on the ID
    $stmt = $conn->prepare("SELECT category_image FROM category WHERE c_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a record was found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_name = $row['category_image'];
        
        // Delete the record from the database
        $stmt = $conn->prepare("DELETE FROM category WHERE c_id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Remove the image file from the server
            $target_file = "uploads/" . $image_name;
            if (file_exists($target_file)) {
                if (unlink($target_file)) {
                    header('Location: Category.php');
                } else {
                    echo "Error deleting the image file.";
                }
            } else {
                echo "Image file not found.";
            }
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "No record found with the provided ID.";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid request. 'id' parameter is missing or incorrect.";
}

// Close the database connection
$conn->close();
?>
