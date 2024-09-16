<?php
include '../db.php';

if(isset($_POST['p_id'])) {
    $id = $_POST['p_id'];

    $sql = "DELETE FROM Product WHERE p_id='$id'";
    if (mysqli_query($conn, $sql)) {
        echo "Product deleted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
