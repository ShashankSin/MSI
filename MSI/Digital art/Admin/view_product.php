<?php
include 'db.php'; // Include your database connection
?>

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

        <div class="product_display container-xxl mt-5">
        <?php 
            // Fetch data from the Product table
            $sql = "SELECT * FROM product";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<div>";
                echo "<h2>Products</h2>";
                echo "<table class='table'>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Details</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Image</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>";
                echo "<tbody>";

                while ($row = $result->fetch_assoc()) {
                    $image_path = 'productPics/' . htmlspecialchars($row["product_image"]);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["p_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["product_price"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["product_details"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["category_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["product_stock"]) . "</td>";
                    echo "<td><img src='" . $image_path . "' alt='" . htmlspecialchars($row["product_name"]) . "' style='width: 100px; height: auto;'></td>";
                    echo "<td><a href='Productedit.php?id=" . htmlspecialchars($row['p_id']) . "' class='btn btn-primary'>Edit</a></td>";
                    echo "<td><a href='Productdelete.php?id=" . htmlspecialchars($row['p_id']) . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this item?\");'>Delete</a></td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else {
                echo "0 results";
            }

            // Close the database connection
            $conn->close();
        ?>

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
<?php 
$conn->close();
?>
